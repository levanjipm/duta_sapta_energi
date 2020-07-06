<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-briefcase'></i></a> <a href='<?= site_url('Bank') ?>'>Bank</a> / Mutation</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Account</label>
		<select class='form-control' id='account'>
<?php
	foreach($accounts as $account){
?>
			<option value='<?= $account->id ?>'><?= $account->number ?> - <?= $account->name ?> ( <?= $account->bank ?> )</option>
<?php
	}
?>
		</select>
		<form id='validation_form'>
			<label>Date start</label>
			<input type='date' class='form-control' value='<?= date('Y-m-d') ?>' id='date_1'>
			
			<label>End date</label>
			<input type='date' class='form-control' value='<?= date('Y-m-d') ?>' id='date_2'>
		</form>
		<br>
		<button type='button' class='button button_default_dark' id='search_transaction_button'><i class='fa fa-search'></i></button>
		<br><br>
		<table class='table table-bordered'>
			<tr>
				<th>Date</th>
				<th>Opponent</th>
				<th>Debit</th>
				<th>Credit</th>
				<th>Balance</th>
			</tr>
			<tbody id='mutation_table'></tbody>
		</table>
		<select class='form-control' id='page' style='width:100px'>
			<option value='1'>1</option>
		</select>
	</div>
</div>
<script>
	refresh_view();
	
	$('#page').change(function(){
		refresh_view();
	});
	
	$('#search_transaction_button').click(function(){
		$('#validation_form').validate();
		
		if($("#validation_form").valid()){
			refresh_view(1);
		}
	});
	
	$('#date_1').change(function(){
		var date_val	= $('#date_1').val();
		$('#date_2').attr('min', date_val);
	});
	
	$('#date_2').change(function(){
		var date_val	= $('#date_1').val();
		$('#date_1').attr('max', date_val);
	});
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Bank/view_mutation') ?>',
			data:{
				page:page,
				account:$('#account').val(),
				start:$('#date_1').val(),
				end:$('#date_2').val()
			},
			success:function(response){
				var pages			= response.pages;
				var page			= page;
				$('#page').html('');
				$('#mutation_table').html('');
				
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				};
				
				var final_balance = 0;
				var balance_array	= response.balance;
				$.each(balance_array, function(index, balance){
					if(balance.transaction == 1){
						final_balance += parseFloat(balance.value);
					} else {
						final_balance	-= parseFloat(balance.value);
					}
				});

				current_balance		= final_balance;
				
				var transactions = response.mutations;
				$.each(transactions, function(index, transaction){
					var date		= transaction.date;
					var id			= transaction.id;
					var value		= transaction.value;
					var type		= transaction.transaction;
					var name		= transaction.name;
					
					if(type == 1){
						current_balance	+= parseFloat(value);
						$('#mutation_table').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Rp. " + numeral(0).format('0,0.00') + "</td><td>Rp. " + numeral( ).format('0,0.00') + "</td></tr>");
					} else {
						current_balance -= parseFloat(value);
						$('#mutation_table').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Rp. " + numeral(0).format('0,0.00') + "</td><td>Rp. " + numeral(current_balance).format('0,0.00') + "</td></tr>");
					}						
				});
			}
		});
	}
</script>