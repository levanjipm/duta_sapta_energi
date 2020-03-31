<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-bar-chart'></i></a> /<a href='<?= site_url('Invoice') ?>'>Invoice </a> /Create invoice</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Customer</label>
		<p style='font-family:museo'><?= $details[0]->customer_name ?></p>
		<p style='font-family:museo'><?= $details[0]->address ?></p>
		<p style='font-family:museo'><?= $details[0]->city ?></p>
	
		<label>Sales order</label>
		<p style='font-family:museo'><?= $details[0]->so_name ?></p>
	
		<label>Sales</label>
<?php
	if(!empty($details[0]->seller)){
?>
		<p style='font-family:museo'><?= $details[0]->seller ?></p>
<?php
	} else {
?>
		<p style='font-family:museo'><i>No seller</i></p>
<?php
	}
?>
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
				<th>Net price</th>
				<th>Total price</th>
			</tr>
<?php
	$total_invoice		= 0;
	foreach($details as $detail){
		$reference		= $detail->reference;
		$name			= $detail->name;
		$quantity		= $detail->quantity;
		$price_list		= $detail->price_list;
		$discount		= $detail->discount;
		$net_price		= $price_list * (100 - $discount) / 100;
		$total_price	= $net_price * $quantity;
?>
			<tr>
				<td><?= $reference ?></td>
				<td><?= $name ?></td>
				<td><?= number_format($quantity) ?></td>
				<td>Rp. <?= number_format($net_price,2) ?></td>
				<td>Rp. <?= number_format($total_price,2) ?></td>
			</tr>
<?php
		$total_invoice += $total_price;
	}
?>
			<tr>
				<td colspan='3'></td>
				<td>Total</td>
				<td>Rp. <?= number_format($total_invoice,2) ?></td>
			</tr>
		</table>

		<form action='<?= site_url('Invoice/print_retail') ?>' method='POST'>
			<input type='hidden' value='<?= $details[0]->id ?>' name='id'>
			<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>