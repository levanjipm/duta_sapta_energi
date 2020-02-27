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
<div class='row' style='margin:0'>	
	<div class='col-sm-10 col-sm-offset-1' style='background-color:white;padding:20px' id='printable'>
		<div class='row'>
			<div class='col-xs-2'><img src='<?= base_url('assets/Logo.png') ?>' style='width:100%'></img></div>
			<div class='col-xs-5'>
				<h3 style='margin-top:0'><b>Duta Sapta Energi</b></h3>
				<p>Jalan Babakan Hantap no. 23</p>
				<p>Bandung, 40281</p>
				<p><b>Ph. </b>(022) 7102225</p>
				<p><b>Email </b>marketing@dutasaptaenergi.com</p>
			</div>
			<div class='col-xs-4 col-xs-offset-1'>
				<p><b>Tanggal: </b><?= date('d M Y',strtotime($datas->date));?></p>
				<div style='line-height:1'>
					<p>Kepada Yth.</p>
					<p><b><?= $datas->customer_name ?></b></p>
					<p><?= $datas->address ?></p>
					<p><?= $datas->city ?></p>
				</div>
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