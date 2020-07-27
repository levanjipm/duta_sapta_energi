<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> / Bank/ Assign bank</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Transaction</label>
		<select class='form-control' id='transaction'>
			<option value='1'>Credit</option>
			<option value='2'>Debit</option>
		</select>
		
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
		<br>
		<div id='transactionTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Value</th>
					<th>Opponent</th>
					<th>Action</th>
				</tr>
				<tbody id='transactionTableContent'></tbody>
			</table>
			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>

		<p id='transactionTableText'>There is no unassigned bank data.</p>
	</div>
</div>
<form action='<?= site_url('Bank/assign_do') ?>' method='POST' id='assign_bank_form'>
	<input type='hidden' id='transaction_id' name='id'>
</form>
<script>
	refresh_view();
	
	$('#account').change(function(){
		refresh_view(1);
	});
	
	$('#page').change(function(){
		refresh_view();
	});
	
	$('#transaction').change(function(){
		refresh_view(1);
	});
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Bank/view_unassigned_data/accounting') ?>',
			data:{
				type:$('#transaction').val(),
				page:page,
				account:$('#account').val()
			},
			type:'GET',
			success:function(response){
				var pages 			= response.pages;
				var bank_data		= response.banks;

				var transactionCount = 0;
				$('#transactionTableContent').html('');
				$('#page').html('');
				$.each(bank_data, function(index, data){
					var id		= data.id;
					var date	= data.date;
					var value	= data.value;
					var name	= data.name;
					$('#transactionTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>" + name + "</td><td><button type='button' class='button button_default_dark' onclick='assign_bank_data(" + id + ")'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
					transactionCount++;
				});

				if(transactionCount > 0){
					$('#transactionTable').show();
					$('#transactionTableContent').hide();
				} else {
					$('#transactionTable').hide();
					$('#transactionTableContent').show();
				}
				
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				};
			}
		});
	}
	
	function assign_bank_data(n){
		$('#transaction_id').val(n);
		$('#assign_bank_form').submit();
	}
</script>