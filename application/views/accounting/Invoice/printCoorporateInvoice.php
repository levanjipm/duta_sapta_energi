<?php
	$delivery_order_name		= substr($deliveryOrder->name,7);
	$delivery_order_date		= $deliveryOrder->date;
	$sales_order_name			= $deliveryOrder->sales_order_name;
	
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
	
	if($customer_block != NULL && $customer_block != "000" & $customer_block != ""){
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

	$complete_address .= " , Kel. " . $customer->kelurahan;
	$complete_address .= " , Kec. " . $customer->kecamatan;
?>
<head>
	<title>INV.DSE<?= $delivery_order_name ?> <?= $customer_name ?> <?= $customer_city ?></title>
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
				size: A4 portrait;

			}
		}

		.table_footer{
			width:100%;
			text-align:center;
			border-collapse: collapse;
		}

		.table_footer, th, td {
			border:1px solid black!important;
			padding:5px;
		}
	</style>
</head>
<div class='dashboard'>
	<div class='row' style='margin:0;margin-top:20px'>	
		<div class='col-sm-10 col-sm-offset-1' style='background-color:white;padding:20px' id='printable'>
			<div class='row'>
				<div class='col-xs-4 col-xs-offset-4' style='text-align:center'>
					<img src='<?= base_url('assets/logo_dark.png') ?>' style='width:50%'></img>
				</div>
				<div class='col-xs-12'>
					<hr style='border-bottom:2px solid #e2bc53;margin-bottom:1px'>
					<hr style='border-bottom:4px solid #2b2f38;margin-top:1px'>
				</div>
			</div>
			<div class='row'>
				<div class='col-xs-4'>
					<label>Invoice number</label>
					<p>INV.DSE<?= $delivery_order_name ?></p>

					<label>Sales Order</label>
					<p><?= $salesOrder->name ?></p>
					<p><i>Sales</i> : <?= ($salesOrder->seller == NULL || $salesOrder->seller == "") ? "<i>Not available</i>" : $salesOrder->seller ?></p>
				</div>
				<div class='col-xs-8' style='text-align:right'>
					<p>Bandung, <?= date('d M Y',strtotime($delivery_order_date)) ?></p>
					<label>Kepada Yth.</label>
					<p><?= $customer_name ?></p>
					<p><?= $complete_address ?></p>
					<p><?= $customer_city ?></p>
				</div>
			</div>
			<br><br>
			<div class='row'>
				<div class='col-xs-12'>
					<table class='table_footer' style='text-align:left'>
						<tr>
							<th>Referensi</th>
							<th>Nama barang</th>
							<th>Harga</th>
							<th>Diskon</th>
							<th>Harga satuan</th>
							<th>Jumlah</th>
							<th>Total</th>
						</tr>
<?php
	$invoice_value		= 0;
	$invoiceDiscount	= $invoice->discount;
	$invoiceDelivery	= $invoice->delivery;

	$rowCount = 0;
	foreach($items as $detail){
		$reference		= $detail->reference;
		$name			= $detail->name;
		$quantity		= $detail->quantity;
		$discount		= $detail->discount;
		$price_list		= $detail->price_list;
		$unit_price		= $price_list * (100 - $discount) / 100;
		$item_value		= $unit_price * $quantity;
		$invoice_value	+= $item_value;
		$rowCount++;
?>
						<tr>
							<td><?= $reference ?></td>
							<td><?= $name ?></td>
							<td>Rp. <?= number_format($price_list,2) ?></td>
							<td><?= number_format($discount,2) ?>%</td>
							<td>Rp. <?= number_format($unit_price,2) ?></td>
							<td><?= number_format($quantity) ?></td>
							<td>Rp. <?= number_format($item_value,2) ?></td>
						</tr>
<?php
	}

	if($rowCount < 8){
		for($i = 0; $i < (8 - $rowCount); $i++){
?>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
<?php
		}
	}
?>
						<tr>
							<td colspan='4'><label>Terbilang</label><p id='value_say'></p></td>
							<td colspan='2'>
								<p>Total</p>
								<p>Potongan Harga</p>
								<p>Ongkos Pengiriman</p>
								<p>Grand total</p>
							</td>
							<td>
								<p>Rp. <?= number_format($invoice_value,2) ?></p>
								<p>Rp. <?= number_format($invoiceDiscount,2) ?></p>
								<p>Rp. <?= number_format($invoiceDelivery,2) ?></p>
								<p>Rp. <?= number_format($invoice_value - $invoiceDiscount + $invoiceDelivery,2) ?></p>
							</td>
						</tr>
						<tr>
							<td colspan='7'>Pembayaran dengan menggunakan cek atau giro dianggap sah setelah berhasil dicairkan.</td>
						</tr>
					</table>
				</div>
			</div>
			<br><br>
			<div class='row'>
				<div class='col-xs-3 col-xs-offset-9' style='text-align:center'>
					<p>Hormat kami,</p>
					<br><br><br><br>
					<hr style='border-bottom:1px solid black;width:75%'>
				</div>
			</div>
		</div>
	</div>
	<br>
	<div class='row' style='margin:0'>
		<div class='col-xs-12' style='text-align:center'>
			<a href='<?= site_url('Invoice') ?>'><button type='button' class='button button_success_dark' style='display:none' id='back_button'><i class='fa fa-long-arrow-left'></i></button></a>
			<button type='button' class='button button_default_light' onclick="window.print();$('#back_button').show();$('#print_button').hide();" id='print_button'><i class='fa fa-print'></i></button>
		</div>
	</div>
</div>
<script>
	get_say();
	
	function get_say(){
		$.ajax({
			url:'<?= site_url('Invoice/convertNumberToWords') ?>',
			data:{
				value:<?= $invoice_value ?>
			},
			success:function(response){
				$('#value_say').html(response + " Rupiah.");
			}
		});
	}
</script>
