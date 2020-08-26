<?php
	$customerName = $customer->name;
	$customerAddress = $customer->address;
	$customerCity = $customer->city;
?>
<head>
	<title><?= $customerName ?> Receivable</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> /Receivable</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Customer</label>
		<p><?= $customerName ?></p>
		<p><?= $customerCity ?></p>

		<label>Receivable</label>
		<table class='table table-bordered'>
			<tr>
				<th>Date</th>
				<th>Name</th>
				<th>Debit</th>
				<th>Credit</th>
				<th>Balance</th>
			</tr>
			<tbody id='receivableTableContent'></tbody>
		</table>
	</div>
</div>
<script>
	$.ajax({
		url:"<?= site_url('Receivable/getCompleteReceivableByCustomerId') ?>",
		data:{
			id: '<?= $customer->id ?>'
		},
		success:function(response){
			console.log(response);
		}
	})
</script>
