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
	$complete_address		= $customer->address;
	$complete_address		.= ' No. ' . $customer->number;
	
	if($customer->block != '' && $customer->block != '000'){
		$complete_address	.= ' Block ' . $customer->block;
	}
	
	if($customer->rt != '' && $customer->rt != '000'){
		$complete_address	.= ' RT ' . $customer->rt;
	}
	
	if($customer->rw != '' && $customer->rw != '000'){
		$complete_address	.= ' RW ' . $customer->rw;
	}
?>
<div class='row' style='margin:0'>	
	<div class='col-sm-10 col-sm-offset-1' style='background-color:white;padding:20px' id='printable'>
		<div class='row' style='border-bottom:2px solid black;padding:15px'>
			<div class='col-xs-1'><img src='<?= base_url('assets/Logo.png') ?>' style='width:90%'></img></div>
			<div class='col-xs-5' style='padding:0'>
				<h3 style='margin-top:0'><b>PT Duta Sapta Energi</b></h3>
				<p style='margin:0'>Jalan Babakan Hantap no. 23</p>
				<p style='margin:0'>Bandung, 40281</p>
				<p style='margin:0'><b>Ph. </b>(022) 7102225</p>
				<p style='margin:0'><b>Email </b>marketing@dutasaptaenergi.com</p>
			</div>
			<div class='col-xs-4 col-xs-offset-2'>
				<div style='line-height:1;text-align:left'>
					<p>Bandung, <?= date('d M Y',strtotime($general->date));?></p>
					<p>Kepada Yth.</p>
					<p><b><?= $customer->name ?></b></p>
					<p><?= $complete_address ?></p>
					<p><?= $customer->city ?></p>
				</div>
			</div>
		</div>
		<br><br>
		<div class='row'>
			<div class='col-xs-12'>
				<p style='margin:5px'><strong>Nomor Surat Jalan : </strong><?= $general->name ?></p>
				<p style='margin:5px'><strong>Nomor Order Penjualan : </strong><?= $general->sales_order_name ?></p>
				<br>
				<table class='table'>
					<tr>
						<th>Referensi</th>
						<th>Nama barang</th>
						<th>Jumlah</th>
					</tr>
<?php
	$total_quantity = 0;
	foreach($items as $item){
?>
					<tr>
						<td><?= $item->reference ?></td>
						<td><?= $item->name ?></td>
						<td><?= number_format($item->quantity) ?></td>
					</tr>
<?php
		$total_quantity += $item->quantity;
	}
?>
					<tr>
						<td colspan='3'><p>Jumlah barang : <?= number_format($total_quantity) ?></td>
					</tr>
				</table>
			</div>
		</div>
		<br><br>
		<div class='row'>
			<div class='col-xs-12'>
				<table style='width:100%;text-align:center;border:1px solid #ccc'>
					<tr>
						<td style='width:33%;padding:5px 60px;border:1px solid #ccc'>
							<p>Penerima</p>
							<br><br><br>
							<hr style='border-top:2px solid black'>
						</td>
						<td style='width:33%;padding:5px 60px;border:1px solid #ccc'>
							<p>Pengirim</p>
							<br><br><br>
							<hr style='border-top:2px solid black'>
						</td>
						<td style='width:33%;padding:5px 60px;border:1px solid #ccc'>
							<p>Hormat kami</p>
							<br><br><br>
							<hr style='border-top:2px solid black'>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
<br>
<div class='row' style='margin:0'>
	<div class='col-xs-12' style='text-align:center'>
		<button type='button' class='button button_default_light' onclick='window.print(); $(this).remove();$("#buttonBack").show();'><i class='fa fa-print'></i></button>
		<a type='button' class='button button_success_dark' href='<?= site_url('Delivery_order/createDashboard') ?>' style='display:none' id='buttonBack'><i class='fa fa-long-arrow-left'></i></a>
	</div>
</div>