<head>
	<title><?= $general->name . ' ' . $customer->name ?></title>
	<style>
	.table_footer{
		width:100%;
		text-align:center;
		border-collapse: collapse;
	}

	.table_footer, th, td {
		border:1px solid black!important;
		padding:5px;
	}
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
			font-size:9pt;
		}
		
		@page {
			size: 21.59cm 13.97cm portrait;
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
		<div class='row'>
			<div class='col-xs-3'>
				<img src='<?= base_url('assets/Logo.png') ?>' style='width:20%'></img>
				<p style='margin-top:4px'>PT Duta Sapta Energi - BDG</p>
				<label>Sales order number</label>
				<p><?= $general->sales_order_name  ?></p>
			</div>
			<div class='col-xs-5' style='text-align:center'>
				<h1 style='font-family:bebasneue;letter-spacing:4px;margin-top:0;margin-bottom:0'>SURAT JALAN</h1>
				<hr style='border-top:2px solid #424242;margin:0;'>
				<p style="margin-bottom:0"><?= $general->name ?></p>
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

	if(count($items) < 5){
		for($i = count($items); $i <5; $i++){
?>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
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
						<td style='width:20%'><p>Pengirim</p><br><br><br><br></td>
						<td style='width:20%'><p>Hormat Kami</p><br><br><br><br></td>
						<td style='width:40%'>
							<p style="margin-bottom:0;text-align:left">Barang yang sudah dibeli tidak dapat dikembalikan. Harap periksa barang pada saat penerimaan.</p>
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
