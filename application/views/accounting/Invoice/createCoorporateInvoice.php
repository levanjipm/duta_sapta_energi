<?php
	$complete_address		= '';
	$customer_name			= $customer->name;
	$complete_address		= $customer->address;
	$customer_city			= $customer->city;
	$customer_number		= $customer->number;
	$customer_rt			= $customer->rt;
	$customer_rw			= $customer->rw;
	$customer_postal		= $customer->postal_code;
	$customer_block			= $customer->block;
	$customer_id			= $customer->id;

	if($customer_number != NULL){
		$complete_address	.= ' No. ' . $customer_number;
	}
	
	if($customer_block != NULL){
		$complete_address	.= ' Blok ' . $customer_block;
	}
	
	if($customer_rt != '000'){
		$complete_address	.= ' RT ' . $customer_rt;
	}
	
	if($customer_rw != '000' && $customer_rt != '000'){
		$complete_address	.= ' /RW ' . $customer_rw;
	}
	
	if($customer_postal != NULL){
		$complete_address	.= ', ' . $customer_postal;
	}
?>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-bar-chart'></i></a> /<a href='<?= site_url('Invoice') ?>'>Invoice </a> /Create invoice</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Customer</label>
		<p style='font-family:museo'><?= $customer_name ?></p>
		<p style='font-family:museo'><?= $complete_address ?></p>
		<p style='font-family:museo'><?= $customer_city ?></p>
	
		<label>Sales order</label>
		<p style='font-family:museo'><?= $deliveryOrder->sales_order_name ?></p>
	
		<label>Sales</label>
<?php if(!empty($deliveryOrder->seller)){ ?>
		<p style='font-family:museo'><?= $deliveryOrder->seller ?></p>
<?php } else { ?>
		<p style='font-family:museo'><i>No seller</i></p>
<?php } ?>

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

		<form action='<?= site_url('Invoice/printRetailInvoice') ?>' method='POST'>
			<input type='hidden' value='<?= $deliveryOrder->id ?>' name='id'>
			<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>
