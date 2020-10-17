<head>
	<title>Bank - Delete</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Administrators') ?>' title='Administrators'><i class='fa fa-briefcase'></i></a> Bank / Delete</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Account</label>
		<button type='button' class='form-control' id='accountButton' style='text-align:left'></button>

		<label>Period</label>
		<div class='input_group'>
			<select class='form-control' id='month'>
<?php for($i = 1; $i <= 12; $i++){ ?>
				<option value='<?= $i ?>' <?= ($i == date('m'))? "selected" : "" ?>><?= date('F', mktime(0,0,0,$i, 1, date("Y"))) ?></option>
<?php } ?>
			</select>
			<select class='form-control' id='year'>
<?php for($i = 2020; $i <= date('Y'); $i++){ ?>
				<option value='<?= $i ?>' <?= ($i == date('Y'))? "selected" : "" ?>><?= $i ?></option>
<?php } ?>
			</select>
		</div>
		<br>
		<div id='bankDataTable' style='display:none'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Opponent</th>
					<th>Value</th>
					<th>Action</th>
				</tr>
				<tbody id='bankDataTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='bankDataTableText'>There is no data found.</p>
	</div>
</div>

<div class='alert_wrapper' id='accountWrapper'>
	<div class='alert_box_full'>
		<h3 style='font-family:bebasneue'>Choose an account</h3>
		<button type='button' class='button alert_full_close_button' title='Close select account session'>&times;</button>
		<br>
		<div class='row'>
			<div class='col-xs-12'>
				<input type='text' class='form-control' id='searchAccountBar'>
				<br>
				<div id='accountTable'>
					<table class='table table-bordered'>
						<tr>
							<th>Number</th>
							<th>Name</th>
							<th>Bank</th>
							<th>Action</th>
						</tr>
						<tbody id='accountTableContent'></tbody>
					</table>
					<select class='form-control' id='accountPage' style='width:100px'>
						<option value='1'>1</option>
					</select>
				</div>
				<p id='accountTableText'>There is no account found.</p>
			</div>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='deleteWrapper'>
	<div class='alert_box_confirm_wrapper'>
		<div class='alert_box_confirm_icon'><i class='fa fa-trash'></i></div>
		<div class='alert_box_confirm'>
			<input type='hidden' id='delete_customer_id'>
			<h3>Delete confirmation</h3>
			
			<p>You are about to delete this data.</p>
			<p>Are you sure?</p>
			<button class='button button_default_dark' onclick="$('#deleteWrapper').fadeOut()">Cancel</button>
			<button class='button button_danger_dark' onclick='deleteTransaction()'>Delete</button>
			
			<br><br>
			
			<p style='font-family:museo;background-color:#f63e21;width:100%;padding:5px;color:white;position:relative;bottom:0;left:0;opacity:0' id='errorDeleteTransaction'>Deletation failed.</p>
		</div>
	</div>
</div>

<script>
	var accountSelected;
	var transactionSelected = null;

	$('#accountButton').click(function(){
		$('#searchAccountBar').val("");
		refreshAccount(1);
		$('#accountWrapper').fadeIn(250);
	});

	function refreshAccount(page = $('#accountPage').val()){
		$.ajax({
			url:'<?= site_url('Bank_account/getItems') ?>',
			data:{
				page: page,
				term: $('#searchAccountBar').val()
			},
			success:function(response){
				$('#accountTableContent').html("");
				var accountCount = 0;
				var items = response.items;
				$.each(items, function(index, item){
					var number 		= item.number;
					var name		= item.name;
					var id			= item.id;
					var bank		= item.bank;
					var branch		= item.branch;

					$('#accountTableContent').append("<tr><td>" + number + "</td><td>" + name + "</td><td><label>" + bank + "</label><p>" + branch + "</p></td><td><button class='button button_default_dark' onclick='selectAccount(" + id + ")'><i class='fa fa-long-arrow-right'></i></button></td></tr>");

					accountCount++;
				})
				var pages = response.pages;
				$('#accountPage').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#accountPage').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#accountPage').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
				
				if(accountCount > 0){
					$('#accountTable').show();
					$('#accountTableText').hide();
				} else {
					$('#accountTable').hide();
					$('#accountTableText').show();
				}
			}
		})
	}

	function selectAccount(n){
		$.ajax({
			url:"<?= site_url('Bank_account/getById') ?>",
			data:{
				id: n
			},
			success:function(response){
				var name = response.name;
				var number = response.number;
				$('#accountButton').text(name + " - " + number);
				accountSelected = n;

				$('#accountWrapper').fadeOut(250);
				refreshView(1);
			}
		})
	}

	$('#month').change(function(){
		refreshView(1);
	});

	$('#year').change(function(){
		refreshView(1);
	});

	$('#page').change(function(){
		refreshView();
	})

	function refreshView(page = $('#page').val()){
		var date		= new Date($('#year').val() + "-" + $('#month').val().toString().padStart(2, "0") + "-01");
		var sof			= $('#year').val() + "-" + ($('#month').val()).toString().padStart(2,"0") + "-01";
		var dateParam	= endOfMonth(date);
		var eof			= dateParam.getFullYear() + "-" + (parseInt(dateParam.getMonth()) + 1).toString().padStart(2,"0") + "-" + dateParam.getDate();
		if(accountSelected != null){
			$.ajax({
				url:"<?= site_url('Bank/getMutation') ?>",
				data:{
					page: page,
					account: accountSelected,
					start: sof,
					end: eof,
				},
				success:function(response){
					var mutations		= response.mutations;
					$('#bankDataTableContent').html("");
					var itemCount		= 0;
					$.each(mutations, function(index, item){
						var date		= item.date;
						var name		= item.name;
						var value		= item.value;
						var transaction	= (item.transaction == 1) ? "CR" : "DB";
						var is_delete	= item.is_delete;
						var is_done		= item.is_done;
						var id			= item.id;

						if(is_done == 0 && is_delete == 0){
							$('#bankDataTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td>Rp. " + numeral(value).format('0,0.00') + " " + transaction + "</td><td><button class='button button_danger_dark' onclick='confirmDeleteTransaction(" + id + ")'><i class='fa fa-trash'></i></button></td></tr>");
							itemCount++;
						} else {
							$('#bankDataTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td>Rp. " + numeral(value).format('0,0.00') + " " + transaction + "</td></tr>");
							itemCount++;
						}
					});

					if(itemCount > 0){
						$('#bankDataTable').show();
						$('#bankDataTableText').hide();
					} else {
						$('#bankDataTable').hide();
						$('#bankDataTableText').show();
					}
					
					var pages			= response.pages;
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
	}

	$('#accountWrapper .alert_full_close_button').click(function(){
		$(this).parent().parent().fadeOut(250);
	});
	
	function endOfMonth(date){
		return new Date(date.getFullYear(), date.getMonth() + 1, 0);
	}

	function confirmDeleteTransaction(n){
		transactionSelected = n;
		$('#deleteWrapper').fadeIn(250);
	}

	function deleteTransaction(){
		if(transactionSelected != null){
			$.ajax({
				url:"<?= site_url('Bank/deleteById') ?>",
				data:{
					id: transactionSelected
				},
				type:"POST",
				beforeSend:function(){
					$('button').attr('disabled', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					refreshView();
					if(response == 1){
						$('#deleteWrapper').fadeOut(250);
						transactionSelected = null;
					} else {
						$('#errorDeleteTransaction').fadeTo(250, 1);
						setTimeout(function(){
							$('#errorDeleteTransaction').fadeTo(250, 0);
						}, 1000);
					}
				}
			})
		}
	}
</script>
