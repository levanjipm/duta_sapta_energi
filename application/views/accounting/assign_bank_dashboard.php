<div class='dashboard'>
	<h2 style='font-family:bebasneue'>Assign bank</h2>
	<hr>
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
	<table class='table table-bordered'>
		<tr>
			<th>Date</th>
			<th>Value</th>
			<th>Opponent</th>
			<th>Action</th>
		</tr>
		<tbody id='bank_table'></tbody>
	</table>
	<select class='form-control' id='page' style='width:100px'>
		<option value='1'>1</option>
	</select>
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
			url:'<?= site_url('Bank/view_unassigned_data') ?>',
			data:{
				type:$('#transaction').val(),
				page:page,
				account:$('#account').val()
			},
			type:'GET',
			success:function(response){
				var current_page	= $('#page').val();
				var pages 			= response.pages;
				var bank_data		= response.banks;
				$('#bank_table').html('');
				$('#page').html('');
				$.each(bank_data, function(index, data){
					var id		= data.id;
					var date	= data.date;
					var value	= data.value;
					var name	= data.name;
					$('#bank_table').append("<tr><td>" + date + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>" + name + "</td><td><button type='button' class='button button_default_light' onclick='assign_bank_data(" + id + ")'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
				});
				
				for(i = 1; i <= pages; i++){
					if(i == current_page){
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