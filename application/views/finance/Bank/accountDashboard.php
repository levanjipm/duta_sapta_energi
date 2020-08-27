<head>
	<title>Bank accounts</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-briefcase'></i></a> /Bank/ Account</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<input type='text' class='form-control' id='search_bar'>
			<div class='input_group_append'>
				<button type='button' class='button button_default_dark' id='addBankAccountButton'>Create new</button>
			</div>
		</div>
		<br>
		<div id='accountTable'>
			<table class='table table-bordered'>	
				<tr>
					<th>Account number</th>
					<th>Account name</th>
					<th>Bank information</th>
					<th>Action</th>
				</tr>
				<tbody id='accountTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='accountTableText'>There is no bank account found.</p>
	</div>
</div>

<div class='alert_wrapper' id='addBankAccountWrapper'>
	<button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<form id='addBankAccountForm'>
			<h3 style='font-family:bebasneue'>Add bank account</h3>
			<hr>
			<label>Name</label>
			<input type='text' class='form-control' name='name' required>
			
			<label>Number</label>
			<input type='text' class='form-control' name='number' required>
			
			<label>Bank</label>
			<input type='text' class='form-control' name='bank' required>
			
			<label>Branch</label>
			<input type='text' class='form-control' name='branch' required>
			<br>
			<button type='button' id='createBankAccountButton' class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
			<br>
			<div class='notificationText danger' id='failedCreateAccount'><p>Failed to insert data.</p></div>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='delete_account_wrapper'>
	<div class='alert_box_confirm_wrapper'>
		<div class='alert_box_confirm_icon'><i class='fa fa-trash'></i></div>
		<div class='alert_box_confirm'>
			<input type='hidden' id='delete_account_id'>
			<h3>Delete confirmation</h3>
			
			<p>You are about to delete this data.</p>
			<p>Are you sure?</p>
			<button class='button button_default_dark' onclick="$('#delete_account_wrapper').fadeOut()">Cancel</button>
			<button class='button button_danger_dark' onclick='delete_account()'>Delete</button>
			
			<br><br>
			
			<p style='font-family:museo;background-color:#f63e21;width:100%;padding:5px;color:white;position:relative;bottom:0;left:0;opacity:0' id='error_delete_account'>Deletation failed.</p>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='editBankAccountWrapper'>
<button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<form id='editBankAccountForm'>
			<h3 style='font-family:bebasneue'>Update bank account</h3>
			<hr>
			<input type='hidden' id='editId' name='id'>
			<label>Name</label>
			<input type='text' class='form-control' id='editName' name='name' required>
			
			<label>Number</label>
			<input type='text' class='form-control' id='editNumber' name='number' required>
			
			<label>Bank</label>
			<input type='text' class='form-control' id='editBank' name='bank' required>
			
			<label>Branch</label>
			<input type='text' class='form-control' id='editBranch' name='branch' required>
			<br>
			<button type='button' id='updateBankAccountButton' class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
			<br>
			<div class='notificationText danger' id='failedUpdateAccount'><p>Failed to update data.</p></div>
		</form>
	</div>
</div>
<script>
	$('#add_bank_acccount_form').validate();
	$('#editBankAccountForm').validate();

	$(document).ready(function(){
		refreshView();
	});

	$('#search_bar').change(function(){
		refreshView(1);
	})

	$('#page').change(function(){
		refreshView();
	})

	$('#addBankAccountButton').click(function(){
		$('#addBankAccountWrapper').fadeIn(300, function(){
			$('#addBankAccountWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	});

	function viewAccount(n){
		$.ajax({
			url:'<?= site_url('Bank_account/getById') ?>',
			data:{
				id:n
			},
			success:function(response){
				var name = response.name;
				var bank = response.bank;
				var number = response.number;
				var branch = response.branch;

				$('#editName').val(name);
				$('#editNumber').val(number);
				$('#editBank').val(bank);
				$('#editBranch').val(branch);

				$('#editId').val(n);

				$('#editBankAccountWrapper').fadeIn(300, function(){
					$('#editBankAccountWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	$('#updateBankAccountButton').click(function(){
		if($('#editBankAccountForm').valid()){
			$.ajax({
				url:"<?= site_url('Bank_account/updateById') ?>",
				data:$('#editBankAccountForm').serialize(),
				type:'POST',
				beforeSend:function(){
					$('button').attr('disabled', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					refreshView();
					if(response == 1){
						$('#editBankAccountWrapper .slide_alert_close_button').click();
					} else {
						$('#failedUpdateAccount').fadeIn(250);
						setTimeout(function(){
							$("#failedUpdateAccount").fadeOut(250);
						}, 1000)
					}
				}
			})
		}
	})

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Bank_account/getItems') ?>",
			data:{
				page:page,
				term: $('#search_bar').val()
			},
			success:function(response){
				accountCount = 0;
				$('#accountTableContent').html("");
				var items = response.items;
				$.each(items, function(index, item){
					var name = item.name;
					var number = item.number;
					var bank = item.bank;
					var branch = item.branch;
					var id =item.id;
					$("#accountTableContent").append("<tr><td>" + number + "</td><td>" + name + "</td><td><label>" + bank + "</label><p>" + branch + "</p></td><td><button class='button button_success_dark' onclick='viewAccount(" + id + ")'><i class='fa fa-pencil'></i></button> <button class='button button_danger_dark' onclick='confirm_delete(" + id + ")'><i class='fa fa-trash'></i></button></td></tr>");
					accountCount++;
				});
				if(accountCount > 0){
					$('#accountTable').show();
					$('#accountTableText').hide();
				} else {
					$('#accountTable').hide();
					$('#accountTableText').show();
				}

				var pages = response.pages;
				$('#page').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>")
					}
				}
			}
		})
	}

	$('#createBankAccountButton').click(function(){
		if($('#addBankAccountForm').valid()){
			$.ajax({
				url:'<?= site_url('Bank_account/insertItem') ?>',
				data:$('#addBankAccountForm').serialize(),
				type:'POST',
				beforeSend:function(){
					$('button').attr("disabled", true);
				},
				success:function(response){
					$("button").attr('disabled', false);
					refreshView();
					if(response == 1){
						$('#addBankAccountForm').trigger("reset");
						$('#addBankAccountWrapper .slide_alert_close_button').click();
					} else {
						$('#failedCreateAccount').fadeIn(250);
						setTimeout(function(){
							$('#failedCreateAccount').fadeOut(250);
						}, 1000);
					}
				}
			})
		}
	})
	
	function confirm_delete(n){
		$('#delete_account_id').val(n);
		$('#delete_account_wrapper').fadeIn();
	}
	
	function delete_account(){
		$.ajax({
			url:'<?= site_url('Bank_account/deleteById') ?>',
			data:{
				id: $('#delete_account_id').val()
			},
			type:"POST",
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				if(response == 1){
					window.location.reload();
				} else {
					$('#error_delete_account').fadeIn(250);
					setTimeout(function(){
						$('#error_delete_account').fadeOut(250);
					}, 1000);
				}
			}
		});
	}
	
	
</script>
