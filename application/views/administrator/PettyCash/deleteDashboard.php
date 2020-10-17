<head>
	<title>Petty Cash - Delete</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Administrators') ?>' title='Administrators'><i class='fa fa-briefcase'></i></a> / Petty Cash / Delete</p>
	</div>
	<br>
	<div class='dashboard_in'>
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
		<div id='pettyCashTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Value</th>
					<th>Information</th>
					<th>Action</th>
				</tr>
				<tbody id='pettyCashTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='pettyCashTableText'>There is no petty cash data found.</p>
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
	var transactionSelected;
	$('#month').change(function(){
		refreshView(1);
	});

	$('#year').change(function(){
		refreshView(1);
	});

	$('#page').change(function(){
		refreshView();
	});

	$(document).ready(function(){
		refreshView(1);
	})

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Petty_cash/getMutation') ?>",
			data:{
				month: $('#month').val(),
				year: $('#year').val(),
				page: page
			},
			success:function(response){
				var items		= response.transactions;
				var itemCount = 0;
				$('#pettyCashTableContent').html("");
				$.each(items, function(index, item){
					var date		= item.date;
					var value		= item.value;
					var information	= item.information;
					var transaction	= item.transaction;
					var id			= item.id;
					var description	= item.expense_description;
					var name		= item.expense_name;

					if(transaction == 1){
						$('#pettyCashTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td><label>Expense class</label><p>" + name + "</p><p>" + description + "</p><label>Information</label><p>" + information + "</p></td><td><button class='button button_danger_dark' onclick='confirmDeleteTransaction(" + id + ")'><i class='fa fa-trash'></i></button></td></tr>");
						itemCount++;
					}					
				});

				if(itemCount > 0){
					$('#pettyCashTable').show();
					$('#pettyCashTableText').hide();
				} else {
					$('#pettyCashTable').hide();
					$('#pettyCashTableText').show();
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
				url:"<?= site_url('Petty_cash/deleteById') ?>",
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
