<head>
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
		size: 29.7cm, 21cm;
		}
	}
	</style>
	<?php
		$complete_address		= '';
		$complete_address		.= $supplier->address;
		$supplier_number		= $supplier->number;
		$supplier_block			= $supplier->block;
		$supplier_rt			= $supplier->rt;
		$supplier_rw			= $supplier->rw;
		$supplier_postal_code	= $supplier->postal_code;
		
		$created_by				= $general->created_by;
		$confirmed_by			= $general->confirmed_by;
		
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
			$request				= '--';
		}
	?>
	<title><?= $purchase_order_name . ' ' . $supplier->name ?></title>
</head>
<div class='row' style='margin:0'>	
	<div class='col-sm-10 col-sm-offset-1' style='background-color:white;padding:20px' id='printable'>
		<div class='row'>
			<div class='col-xs-2 col-xs-offset-5'><img src='<?= base_url('assets/Logo_dark.png') ?>' style='width:100%'></img></div>
		</div>
		<hr style='border-bottom:2px solid #e2bc53;margin-bottom:1px'>
		<hr style='border-bottom:5px solid #2b2f38;margin-top:1px'>
		<br><br>
		<table class='table table-bordered'>
			<tr>
				<td style='width:33%'><strong>Supplier</strong></td>
				<td style='width:33%'><strong>Delivery</strong></td>
				<td style='width:33%'><strong>Purchase order</strong></td>
			</tr>
			<tr>
				<td>
					<p><?= $supplier->name ?></p>
					<p><?= $complete_address ?></p>
					<p><?= $supplier->city ?></p>
					<p><?= $supplier->phone_number ?></p>
					<p><?= $supplier->npwp ?></p>
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

					<p><strong>Payment</strong></p>
					<p><?= $general->payment ?> days</p>
					<p>
				</td>
			</tr>
		</table>
		<br>
		<p>Please supply or manufacture the following items in accordance with the terms and conditions or purchase order attached.</p>
		
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
		
		<table style='width:100%'>
			<tr style='border:1px solid #ddd'>
				<td style='padding:20px;width:40%;border-right:1px solid #ddd' valign='top'>
					<label>Note</label>
					<p><?= $general->note ?></p>
				</td>
				<td style='padding:20px;border-right:1px solid #ddd;width:20%' valign='top'>
					<label>Supplier</label>
					<br><br><br><br><br>
					<hr style='border-top:1px solid black'>
					<p><?= $supplier->name ?></p>
				</td>
				<td style='padding:20px;border-right:1px solid #ddd;width:20%' valign='top'>
					<label>Created by</label>
					<br><br><br><br><br>
					<hr style='border-top:1px solid black'>
					<p><?= $general->created_by ?></p>
				</td>
				<td style='padding:20px;border-right:1px solid #ddd;width:20%' valign='top'>
					<label>Confirmed by</label>
					<br><br><br><br><br>
					<hr style='border-top:1px solid black'>
					<p><?= $general->confirmed_by ?></p>
				</td>
			</tr>
		</table>
	</div>
</div>
<br>
<div class='row' style='margin:0'>
	<div class='col-xs-12' style='text-align:center'>
		<button type='button' class='button button_default_light' onclick='print_purchase_order()' id='print_button'><i class='fa fa-print'></i></button>
		<button type='button' class='button button_success_dark' onclick='window.location.href="<?= site_url('Purchase_order/confirmDashboard') ?>"' id='back_button' style='display:none'><i class='fa fa-long-arrow-left'></i></button>
	</div>
</div>
<script>
	function print_purchase_order(){
		window.print();
		$('#print_button').hide();
		$('#back_button').show();
	}
</script>