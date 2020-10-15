<?php
	$quotationName			= $general->name;
	$quotationDate			= $general->date;

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
	
	if($customer_block != NULL && $customer_block != "000" && $customer_block != ""){
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
	<title>Print Quotation <?= $quotationName ?></title>
</head>
<div class='dashboard' style='padding-top:90px'>
	<div class='row' style='margin:0'>	
		<div class='col-sm-10 col-sm-offset-1' style='background-color:white;padding:20px' id='printable'>
			<div class='row'>
				<div class='col-xs-4 col-xs-offset-4'>
					<img src='<?= base_url('assets/Logo_dark.png') ?>' style='width:40%;margin-left:30%'></img>
				</div>
				<div class='col-xs-12'>
					<hr style='border-top:4px solid #424242;margin:0;'>
					<hr style='border-top:2px solid #E19B3C;margin:0;'>
				</div>
			</div>
			<div class='row'>
				<div class='col-xs-12'>
					<h1 style='font-family:bebasneue;letter-spacing:4px;margin-top:0;text-align:center'>Quotation</h1>
					<p style='text-align:center'><?= $quotationName ?></p>
					<br>
					<p style='text-align:right'>Bandung, <?= date('d M Y', strtotime($quotationDate)) ?></p>
				</div>
				<div class='col-xs-6'>
					<label>Kepada Yth.</label>
					<p><?= $customer_name ?></p>
					<p><?= $complete_address ?></p>
					<p><?= $customer_city ?></p>
						
				</div>
				<div class='col-xs-6' style='text-align:right'>
					
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
	foreach($items as $detail){
		$reference		= $detail->reference;
		$name			= $detail->name;
		$quantity		= $detail->quantity;
		$discount		= $detail->discount;
		$price_list		= $detail->price_list;
		$unit_price		= $price_list * (100 - $discount) / 100;
		$item_value		= $unit_price * $quantity;
		$invoice_value	+= $item_value;
		if($general->taxing == 0){
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
		} else {
?>
						<tr>
							<td><?= $reference ?></td>
							<td><?= $name ?></td>
							<td>Rp. <?= number_format($price_list / 1.1,2) ?></td>
							<td><?= number_format($discount,2) ?>%</td>
							<td>Rp. <?= number_format($unit_price / 1.1,2) ?></td>
							<td><?= number_format($quantity) ?></td>
							<td>Rp. <?= number_format($item_value / 1.1,2) ?></td>
						</tr>
<?php
		}
	}

	if($general->taxing == 0){
?>
						<tr>
							<td colspan='4'><label>Terbilang</label><p id='value_say'></p></td>
							<td colspan='2'>Total</td>
							<td>Rp. <?= number_format($invoice_value,2) ?></td>
						</tr>
<?php
	} else {
?>
						<tr>
							<td colspan='4' rowspan='3'><label>Terbilang</label><p id='value_say'></p></td>
							<td colspan='2'>Sub Total</td>
							<td>Rp. <?= number_format($invoice_value / 1.1,2) ?></td>
						</tr>
						<tr>
							<td colspan='2'>PPn</td>
							<td>Rp. <?= number_format($invoice_value - $invoice_value / 1.1,2) ?></td>
						</tr>
						<tr>
							<td colspan='2'>Total</td>
							<td>Rp. <?= number_format($invoice_value,2) ?></td>
						</tr>
<?php
	}
?>
						
						<tr>
							<td colspan='7'>
								<label>Keterangan</label>
								<p><?= ($general->note == "")? "<i>Tidak tersedia.</i>": $general->note ?></p>
								<p>Pembayaran dengan menggunakan cek atau giro dianggap sah setelah berhasil dicairkan.</p>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<br><br>
			<div class='row'>
				<div class='col-xs-3' style='text-align:center'>
					<p>Menyetujui</p>
					<br><br><br>
					<p>___________________</p>
					<p><?= $customer->name ?></p>
				</div>
				<div class='col-xs-3 col-xs-offset-6' style='text-align:center'>
					<p>Hormat kami,</p>
					<br><br><br>
					<p>___________________</p>
					<p>Duta Sapta Energi</p>
				</div>
			</div>
		</div>
	</div>
	<br>
	<div class='row' style='margin:0'>
		<div class='col-xs-12' style='text-align:center'>
			<a href='<?= site_url('Quotation/confirmDashboard') ?>'><button type='button' class='button button_success_dark' style='display:none' id='back_button'><i class='fa fa-long-arrow-left'></i></button></a>
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
