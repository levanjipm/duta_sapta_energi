<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-briefcase'></i></a> /Petty cash/ Mutatation</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Month</label>
		<select class='form-control' id='month'>
<?php
	for($i = 1; $i <= 12; $i++){
		if($i == date('m')){
			$selected = 'selected';
		} else {
			$selected = '';
		}
?>
			<option value='<?= $i ?>' <?= $selected ?>><?= date('F', mktime(0,0,0,$i, 1)) ?></option>
<?php
	}
?>
		</select>
		<label>Year</label>
		<select class='form-control' id='year'>
<?php
		for($i = '2020'; $i <= date('Y'); $i++){
			if($i == date('Y')){
				$selected = 'selected';
			} else {
				$selected = '';
			}
?>
			<option value='<?= $i ?>' <?= $selected ?>><?= $i ?></option>
<?php
		}
?>
		</select><br>
		
		<button type='button' class='button button_default_dark' id='refresh_button'><i class='fa fa-search'></i></button><br><br>
		
		<table class='table table-bordered'>
			<tr>
				<th>Date</th>
				<th>Debit</th>
				<th>Credit</th>
				<th>Information</th>
				<th>Balance</th>
			</tr>
			<tbody id='petty_table'></tbody>
		</table>
		
		<select class='form-control' id='page' style='width:100px'>
			<option value='1'>1</option>
		</select>
	</div>
</div>
<script>
	$('#page').change(function(){
		var month		= $('#month').val();
		var year		= $('#year').val();
		refresh_view(month, year);
	});

	$('#refresh_button').click(function(){
		var month		= $('#month').val();
		var year		= $('#year').val();
		var page		= 1;
		refresh_view(month, year, page);
	});
	
	refresh_view(<?= date('m') ?>, <?= date('Y') ?>);
	
	function refresh_view(month, year, page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Petty_cash/view_mutation') ?>',
			data:{
				month:month,
				year:year,
				page:page
			},
			success:function(response){
				$('#petty_table').html('');
				var balance			= parseFloat(response.balance);
				var transactions 	= response.transactions;
				var pages			= response.pages;
				$.each(transactions, function(index, transaction){
					var date		= transaction.date;
					var value		= parseFloat(transaction.value);
					var type		= transaction.transaction;
					var information	= transaction.information;
					var expense_class	= transaction.expense_class;
					
					if(type == 1){
						balance		-= value;
						$('#petty_table').append("<tr><td>" + my_date_format(date) + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Rp. " + numeral(0).format('0,0.00') + "</td><td>" + information + "</td><td>Rp. " + numeral(balance).format('0,0.00') + "</td></tr>");
					} else if(type == 2){
						balance		+= value;
						$('#petty_table').append("<tr><td>" + date + "</td><td>Rp. " + numeral(0).format('0,0.00') + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Income</td><td>Rp. " + numeral(balance).format('0,0.00') + "</td></tr>");
					}
				});
				
				var page		= $('#page').val();
				$('#page').html('');
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		});
	}
</script>