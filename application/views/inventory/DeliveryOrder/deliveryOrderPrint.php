<head>
	<title><?= $general->name . ' ' . $customer->name ?></title>
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
			font-size:10pt;
		}
		
		@page {
		size: 21.59cm 13.97cm;
		}
	}
	</style>
</head>
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
		<div class='row' style='padding:15px'>
			<div class='col-xs-5'>
				<div style='display:inline-block'>
					<p style='margin-top:0'><b>PT Duta Sapta Energi</b></p>
					<p style='margin:0'>Jalan Babakan Hantap no. 23</p>
					<p style='margin:0'>Bandung, 40281</p>
					<p style='margin:0'><b>Ph. </b>(022) 7102225</p>
					<p style='margin:0'><b>Email </b>marketing@dutasaptaenergi.com</p>
				</div>
				<table style='width:100%'>
					<tr>
						<td>SO NO. </td>
						<td><?= $general->sales_order_name ?></td>
					</tr>
					<tr>
						<td>Tanggal Pengiriman</td>
						<td><?= date('d M Y', strtotime($general->date)) ?></td>
					</tr>
				</table>
			</div>
			<div class='col-xs-3'>
				<h3 style='margin:0;text-align:center;letter-spacing:0.5pt'>SURAT JALAN</h3>
				<p style='margin:0;text-align:center'><?= $general->name ?></p>
			</div>
			<div class='col-xs-4'>
				<div style='line-height:1;text-align:left'>
					<p>Bandung, <?= date('d M Y',strtotime($general->date));?></p>
					<p>Kepada Yth.</p>
					<p><b><?= $customer->name ?></b></p>
					<p><?= $complete_address ?></p>
					<p><?= $customer->city ?></p>
				</div>
			</div>
		</div>
		<div class='row'>
			<div class='col-xs-12'>
				<br>
				<table class="table">
					<tr style='border-top:2px solid black;border-bottom:2px solid black'>
						<th>Referensi</th>
						<th>Nama barang</th>
						<th>Jumlah</th>
					</tr>
<?php
	foreach($items as $item){
?>
					<tr>
						<td><?= $item->reference ?></td>
						<td><?= $item->name ?></td>
						<td><?= number_format($item->quantity) ?></td>
					</tr>
<?php
	}
?>
				</table>
			</div>
		</div>
		<br><br>
		<div class='row'>
			<div class='col-xs-12'>
				<table style='width:100%;text-align:center'>
					<tr>
						<td style='width:33%;padding:5px 60px'>
							<p>Penerima</p>
							<br><br><br>
							<hr style='border-top:2px solid black'>
						</td>
						<td style='width:33%;padding:5px 60px'>
							<p>Pengirim</p>
							<br><br><br>
							<hr style='border-top:2px solid black'>
						</td>
						<td style='width:33%;padding:5px 60px'>
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
