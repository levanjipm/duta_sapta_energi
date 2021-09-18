<?php
	$delivery_order_name		= substr($deliveryOrder->name,7);
	$delivery_order_date		= $deliveryOrder->date;
	$sales_order_name			= $deliveryOrder->sales_order_name;
	$seller						= $deliveryOrder->seller;
	$payment					= $deliveryOrder->payment;
	$overdueDate				= strtotime("+" . $payment . " days", strtotime($delivery_order_date));
	
	$invoiceDiscount					= $invoice->discount;
	$invoiceDelivery					= $invoice->delivery;
	if(empty($seller)){
		$seller			= "<i>-</i>";
	};
	
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
	
	if($customer_block != NULL && $customer_block != "000"){
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
<title><?= $invoice->name . " - " . $customer_name ?></title>
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
	  size: 9.5in 11in portrait;
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
<div class='row' style='margin:0'>	
	<div class='col-sm-10 col-sm-offset-1' style='background-color:white;padding:20px' id='printable'>
		<div class='row'>
			<div class='col-xs-3'>
				<img src='<?= base_url('assets/Logo.png') ?>' style='width:20%'></img>
				<p style='margin-top:4px'>PT Duta Sapta Energi - BDG</p>
			</div>
			<div class='col-xs-5' style='text-align:center'>
				<h1 style='font-family:bebasneue;letter-spacing:4px;margin-top:0;margin-bottom:0'>Invoice</h1>
				<hr style='border-top:2px solid #424242;margin:0;'>
				<p style="margin-bottom:0">INV.DSE<?= $delivery_order_name ?></p>
			</div>
		</div>
		<div class='row'>
			<div class='col-xs-4'>
				<label style="margin-bottom:0">Konsumen</label>
				<p style="margin-bottom:0"><?= $customer_name ?></p>
				<p style="margin-bottom:0"><?= $complete_address ?></p>
				<p style="margin-bottom:0"><?= $customer_city ?></p>
			</div>
			<div class='col-xs-4'>
				<label style="margin-bottom:0">Tanggal</label>
				<p style="margin-bottom:0"><?= date('d M Y', strtotime($delivery_order_date)) ?> ( JT : <?= date("d M Y", $overdueDate) ?> )</p>
			</div>
			<div class='col-xs-4'>
				<label style="margin-bottom:0">Sales order</label>
				<p style="margin-bottom:0"><?= $sales_order_name ?></p>
				
				<label style="margin-bottom:0">Sales</label>
				<p style="margin-bottom:0"><?= $seller ?></p>
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
	foreach($details as $detail){
		$reference		= $detail->reference;
		$name			= $detail->name;
		$quantity		= $detail->quantity;
		$discount		= $detail->discount;
		$price_list		= $detail->price_list;
		$unit_price		= $price_list * (100 - $discount) / 100;
		$item_value		= $unit_price * $quantity;
		$invoice_value	+= $item_value;
?>
					<tr>
						<td><?= $reference ?></td>
						<td><?= $name ?></td>
						<td>Rp.&nbsp;<?= number_format($price_list,2) ?></td>
						<td><?= number_format($discount,2) ?>%</td>
						<td>Rp.&nbsp;<?= number_format($unit_price,2) ?></td>
						<td><?= number_format($quantity) ?></td>
						<td>Rp.&nbsp;<?= number_format($item_value,2) ?></td>
					</tr>
<?php
	}

	if(count($details) < 5){
		for($i = 1; $i <= (5 - count($details)); $i++){
?>
			<tr>
				<td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td>
			</tr>
<?php 
		}
	}
?>
				</table>
			</div>
		</div>
		<div class='row'>
			<div class='col-xs-12'>
				<table class='table_footer'>
					<tr>
						<td style='width:20%'><p>Penerima</p><br><br><br><br></td>
						<td style='width:10%'><p>Pengirim</p><br><br><br><br></td>
						<td style='width:35%'>
							<p style="margin-bottom:0;text-align:left">Barang yang sudah dibeli tidak dapat dikembalikan. Harap periksa barang pada saat penerimaan.</p>
							<p style="margin-bottom:0;text-align:left"><strong>Terbilang</strong> <span id='value_say'></span></p>
						</td>
						<td colspan='2'>
							<p style="margin-bottom:0;text-align:left">Total</p>
							<p style="margin-bottom:0;text-align:left">Potongan&nbsp;Harga</p>
							<p style="margin-bottom:0;text-align:left">Ongkos&nbsp;Pengiriman</p>
							<p style="margin-bottom:0;text-align:left"><strong>Grand&nbsp;total</strong></p>
						</td>
						<td>
							<p style="margin-bottom:0;text-align:left">Rp.&nbsp;<?= number_format($invoice_value,2) ?></p>
							<p style="margin-bottom:0;text-align:left">Rp.&nbsp;<?= number_format($invoiceDiscount,2) ?></p>
							<p style="margin-bottom:0;text-align:left">Rp.&nbsp; <?= number_format($invoiceDelivery,2) ?></p>
							<p style="margin-bottom:0;text-align:left"><strong>Rp.&nbsp;<?= number_format($invoice_value - $invoiceDiscount + $invoiceDelivery,2) ?></strong></p>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class='row mt-3'>
			<div class='col-12'>
				<p class='mb-0'>Mohon pembayaran dilakukan ke rekening berikut ini:</p>
				<ul style='padding-inline-start:20px'>
					<?php foreach($banks as $bank){ ?>
						<li>
							<p class='mb-0'><?= $bank->bank . ' cabang ' . $bank->branch ?></p>
							<p class='mb-0'>Atas nama <?= $bank->name ?></p>
							<p class='mb-0'><strong><?= $bank->number ?></strong></p>
						</li>
					<?php } ?>
				</ul>
				<p>Pembayaran melalui cek atau giro dianggap sah setelah berhasil diuangkan.</p>
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
