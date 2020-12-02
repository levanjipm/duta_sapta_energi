<head>
	<title>Check Value</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Sales'><i class='fa fa-bar-chart'></i></a> /Check Value</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Unit price</th>
				<th>Quantity</th>
				<th>Price</th>
			</tr>
			<?php $totalValue = 0; foreach($values as $value){ $totalValue += $value['value'] * $value['quantity'];?>
			<tr>
				<td><?= $value['reference'] ?></td>
				<td><?= $value['name'] ?></td>
				<td>Rp.<?= number_format($value['value'],2) ?></td>
				<td><?= number_format($value['quantity']) ?></td>
				<td>Rp.<?= number_format($value['value'] * $value['quantity'],2) ?></td>
			<?php } ?>
			<tr>
				<td colspan='3'></td>
				<td>Total</td>
				<td>Rp. <?= number_format($totalValue, 2) ?></td>
			</tr>
		</table>
	</div>
</div>