<style>
@media print {
	body * {
		visibility: hidden;
	}
	
	#printable, #printable *{
		visibility:visible!important;
	}
	
	#printable{
		position: absolute;
		left: 0;
		top: 0;
	}
	
	@page {
	  size: 21.59cm 13.97cm;
	}
}
</style>
<?php
	$complete_address		= '';
	$complete_address		.= $general->supplier_address;
	$supplier_number		= $general->number;
	$supplier_block			= $general->block;
	$supplier_rt			= $general->rt;
	$supplier_rw			= $general->rw;
	$supplier_postal_code	= $general->postal_code;
	
	print_r($general);
	
	$complete_address		.= 'No. ' . $supplier_number;
	
	if($supplier_block		== '' && $supplier_block == '000'){
		$complete_address	.= 'Block ' . $supplier_block;
	};
	
	if($supplier_rt != '' && $supplier_rt != '000'){
		$complete_address	.= 'RT ' . $supplier_rt . '/ RW ' . $supplier_rw;
	}
	
	if($supplier_postal_code != ''){
		$complete_address	.= ', ' . $supplier_postal_code;
	}
	
	$delivery_address			= $general->dropship_address;
	$delivery_city				= $general->dropship_city;
	$delivery_contact_person	= $general->dropship_contact_person;
	$delivery_contact			= $general->dropship_contact;
	
	if($delivery_address ==  null){
		$delivery_address		= "<strong>PT Duta Sapta Energi</strong>";
		$delivery_city			= "Jalan Babakan Hantap no. 23";
		$delivery_contact_person	= 'Bandung';
		$delivery_contact			= '';
	}
	
	$purchase_order_name		= $general->name;
	$purchase_order_date		= $general->date;
	
	$send_date					= $general->date_send_request;
	$status						= $general->status;
	if($send_date != null){
		$request				= $send_date;
	} else if($send_date == null && $status != null){
		$request				= $status;
	} else {
		$request				= '';
	}
?>
<div class='row' style='margin:0'>	
	<div class='col-sm-10 col-sm-offset-1' style='background-color:white;padding:20px' id='printable'>
		<div class='row'>
			<div class='col-xs-10 col-xs-offset-1'><img src='<?= base_url('assets/Logo.png') ?>' style='width:100%'></img></div>
		</div>
		<br><br>
		<table class='table table-bordered'>
			<tr>
				<td style='width:33%'><strong>Supplier</strong></td>
				<td style='width:33%'><strong>Delivery</strong></td>
				<td style='width:33%'><strong>Purchase order</strong></td>
			</tr>
			<tr>
				<td>
					<p><?= $general->supplier_name ?></p>
					<p><?= $complete_address ?></p>
					<p><?= $general->supplier_city ?></p>
					<p><?= $general->phone_number ?></p>
					<p><?= $general->npwp ?></p>
				</td>
				<td>
					<p><?= $delivery_address ?></p>
					<p><?= $delivery_city ?></p>
					<p><?= $delivery_contact_person ?></p>
					<p><?= $delivery_contact ?></p>
				</td>
				<td>
					<p><strong><?= $purchase_order_name ?></strong></p>
					<p><?= date('d M Y',strtotime($purchase_order_date)) ?></p>
					<p><?= $general->promo_code ?></p>
					<p><?= $request ?></p>
					<p>
				</td>
			</tr>
		</table>
		<br>
		<p>Please supply or manufacture the following items:</p>
		
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Price list</th>
				<th>Discount</th>
				<th>Net price</th>
				<th>Quantity</th>
				<th>Total price</th>
			</tr>
<?php
	$purchase_order_value		= 0;
	foreach($detail as $value){
		$reference		= $value->reference;
		$name			= $value->name;
		$price_list		= $value->price_list;
		$net_price		= $value->net_price;
		$discount		= 100 * (1 - $net_price / $price_list);
		$quantity		= $value->quantity;
		$total_price	= $net_price * $quantity;
		
		$purchase_order_value	+= $total_price;
?>
			<tr>
				<td><?= $reference ?></td>
				<td><?= $name ?></td>
				<td>Rp.<?= number_format($price_list * 10 / 11,2) ?></td>
				<td><?= number_format($discount,2) ?>%</td>
				<td>Rp.<?= number_format($net_price * 10 / 11,2) ?></td>
				<td><?= number_format($quantity) ?></td>
				<td>Rp.<?= number_format($total_price * 10 / 11,2) ?></td>
			</tr>
<?php
	}
?>
			<tr>
				<td colspan='4'></td>
				<td colspan='2'>Total</td>
				<td>Rp.<?= number_format($purchase_order_value * 10 / 11,2) ?></td>
			</tr>
			<tr>
				<td colspan='4'></td>
				<td colspan='2'>Tax</td>
				<td>Rp.<?= number_format($purchase_order_value / 11,2) ?></td>
			</tr>
			<tr>
				<td colspan='4'></td>
				<td colspan='2'>Grand total</td>
				<td>Rp.<?= number_format($purchase_order_value,2) ?></td>
			</tr>
		</table>
	</div>
</div>
<br>
<div class='row' style='margin:0'>
	<div class='col-xs-12' style='text-align:center'>
		<button type='button' class='button button_default_light' onclick='window.print()'><i class='fa fa-print'></i></button>
	</div>
</div>