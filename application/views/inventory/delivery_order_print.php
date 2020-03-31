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
	$complete_address		= $general->address;
	$complete_address		.= ' No. ' . $general->number;
	
	if($general->block != '' && $general->block != '000'){
		$complete_address	.= ' Block ' . $general->block;
	}
	
	if($general->rt != '' && $general->rt != '000'){
		$complete_address	.= ' RT ' . $general->rt;
	}
	
	if($general->rw != '' && $general->rw != '000'){
		$complete_address	.= ' RW ' . $general->rw;
	}
?>
<div class='row' style='margin:0'>	
	<div class='col-sm-10 col-sm-offset-1' style='background-color:white;padding:20px' id='printable'>
		<div class='row' style='border-bottom:2px solid black;padding:15px'>
			<div class='col-xs-2'><img src='<?= base_url('assets/Logo.png') ?>' style='width:90%'></img></div>
			<div class='col-xs-4'>
				<h3 style='margin-top:0'><b>PT Duta Sapta Energi</b></h3>
				<p style='margin:0'>Jalan Babakan Hantap no. 23</p>
				<p style='margin:0'>Bandung, 40281</p>
				<p style='margin:0'><b>Ph. </b>(022) 7102225</p>
				<p style='margin:0'><b>Email </b>marketing@dutasaptaenergi.com</p>
			</div>
			<div class='col-xs-6'>
				<h3 style='text-align:right'>SURAT JALAN</h3>
			</div>
		</div>
		<div class='row'>
			<div class='col-xs-6'>
				<div style='line-height:1'>
					<p>Kepada Yth.</p>
					<p><b><?= $general->customer_name ?></b></p>
					<p><?= $complete_address ?></p>
					<p><?= $general->city ?></p>
				</div>
			</div>
			<div class='col-xs-6' style='text-align:right'>
				<p style='margin:0'>Bandung, <?= date('d M Y',strtotime($general->date));?></p>
				<p style='margin:0'><strong>Nomor Surat Jalan : </strong><?= $general->name ?></p>
				<p style='margin:0'><strong>Nomor Order Penjualan : </strong><?= $general->sales_order_name ?></p>
			</div>
		</div>
		<br><br>
		<div class='row'>
			<div class='col-xs-12'>
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
				<table style='width:100%;text-align:center'>
					<tr>
						<td style='width:33%;padding:0px 60px'>
							<p>Penerima</p>
							<br><br><br><br>
							<hr style='border-top:2px solid black'>
						</td>
						<td style='width:33%;padding:0px 60px'>
							<p>Pengirim</p>
							<br><br><br><br>
							<hr style='border-top:2px solid black'>
						</td>
						<td style='width:33%;padding:0px 60px'>
							<p>Hormat kami</p>
							<br><br><br><br>
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
		<button type='button' class='button button_default_light' onclick='window.print()'><i class='fa fa-print'></i></button>
	</div>
</div>