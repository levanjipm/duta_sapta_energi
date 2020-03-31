<?php
	$delivery_order_name		= substr($details[0]->do_name,7);
	$delivery_order_date		= $details[0]->date;
	$sales_order_name			= $details[0]->so_name;
	$seller						= $details[0]->seller;
	if(empty($seller)){
		$seller			= "<i>Not assigned</i>";
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
				<h1 style='font-family:bebasneue;letter-spacing:4px;margin-top:0'>Invoice</h1>
				<hr style='border-top:2px solid #424242;margin:0;'>
			</div>
		</div>
		<div class='row'>
			<div class='col-xs-4'>
				<label>Customer</label>
				<p><?= $customer_name ?></p>
				<p><?= $complete_address ?></p>
				<p><?= $customer_city ?></p>
			</div>
			<div class='col-xs-4'>
				<label>Invoice number</label>
				<p>INV.DSE<?= $delivery_order_name ?></p>
				
				<label>Date</label>
				<p><?= $delivery_order_date ?></p>
			</div>
			<div class='col-xs-4'>
				<label>Sales order</label>
				<p><?= $sales_order_name ?></p>
				
				<label>Seller</label>
				<p><?= $seller ?></p>
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
						<td>Rp. <?= number_format($price_list,2) ?></td>
						<td><?= number_format($discount,2) ?>%</td>
						<td>Rp. <?= number_format($unit_price,2) ?></td>
						<td><?= number_format($quantity) ?></td>
						<td>Rp. <?= number_format($item_value,2) ?></td>
					</tr>
<?php
	}
?>
					<tr>
						<td colspan='4'><p>Barang yang sudah dibeli tidak dapat dikembalikan. Harap periksa barang pada saat penerimaan.</p><label>Terbilang</label><p id='value_say'></p></td>
						<td colspan='2'>Total</td>
						<td>Rp. <?= number_format($invoice_value,2) ?></td>
					</tr>
					<tr>
						<td colspan='7'>Pembayaran dengan menggunakan cek atau giro dianggap sah setelah berhasil dicairkan.</td>
					</tr>
				</table>
			</div>
		</div>
		<br><br>
		<div class='row'>
			<div class='col-xs-12'>
				<table class='table_footer'>
					<tr>
						<td style='width:30%'><p>Penerima</p><br><br><br><br></td>
						<td style='width:30%'><p>Pengirim</p><br><br><br><br></td>
						<td style='width:30%'><p>Hormat kami</p><br><br><br><br></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
<br>
<div class='row' style='margin:0'>
	<div class='col-xs-12' style='text-align:center'>
		<a href='<?= site_url('Accounting') ?>'><button type='button' class='button button_success_dark' style='display:none' id='back_button'><i class='fa fa-long-arrow-left'></i></button></a>
		<button type='button' class='button button_default_dark' onclick="window.print();$('#back_button').show();$('#print_button').hide();" id='print_button'><i class='fa fa-print'></i></button>
	</div>
</div>
<script>
	get_say();
	
	function get_say(){
		$.ajax({
			url:'<?= site_url('Invoice/convert_number') ?>',
			data:{
				value:<?= $invoice_value ?>
			},
			success:function(response){
				$('#value_say').html(response + " Rupiah.");
			}
		});
	}
</script>