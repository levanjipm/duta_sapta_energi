<?php
	$complete_address		= '';
	$customer_name			= $customer->name;
	$complete_address		.= $customer->address;
	$customer_city			= $customer->city;
	$customer_number		= $customer->number;
	$customer_rt			= $customer->rt;
	$customer_rw			= $customer->rw;
	$customer_postal		= $customer->postal_code;
	$customer_block			= $customer->block;
		
	if($customer_number != null){
		$complete_address	.= ' No. ' . $customer_number;
	}
					
	if($customer_block != null && $customer_block != "000"){
		$complete_address	.= ' Blok ' . $customer_block;
	}
				
	if($customer_rt != '000'){
		$complete_address	.= ' RT ' . $customer_rt;
	}
					
	if($customer_rw != '000' && $customer_rt != '000'){
		$complete_address	.= ' /RW ' . $customer_rw;
	}
					
	if($customer_postal != null){
		$complete_address	.= ', ' . $customer_postal;
	}
?>
<head>
	<title><?= $customer->name ?> Detail</title>
	<style>
		.progressBarWrapper{
			width:100%;
			height:30px;
			background-color:white;
			border-radius:10px;
			padding:5px;
			position:relative;
		}

		.progressBar{
			width:0;
			height:20px;
			background-color:#01bb00;
			position:relative;
		}

		.progressBarWrapper p{
			font-family:museo;
			color:black;
			font-weight:"bold";
			z-index:50;
			position:absolute;
			right:5px;
		}
	</style>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Customer') ?>'>Customer</a> / <?= $customer->name ?></p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='row'>
			<div class='col-md-4 col-sm-12 col-xs-12'>
				<label>General data</label>
				<p><?= $customer_name ?></p>
				<p><?= $complete_address ?>, <?= $customer_city ?></p>
				<p><?= $area->name ?></p>
				<p><?= $customer->npwp ?></p>
				<label>Location</label>
				<p>Latitude: <?= ($customer->latitude == null || $customer->latitude == "")? "<i>Not available</i>" : number_format($customer->latitude, 8) ?></p>
				<p>Longitude: <?= ($customer->longitude == null || $customer->longitude == "")? "<i>Not available</i>" : number_format($customer->longitude, 8) ?></p>
				<?php if($customer->latitude != "" && $customer->latitude != NULL && $customer->longitude != "" && $customer->longitude != null){ ?><a href='https://maps.google.com/?q=<?= $customer->latitude ?>,<?= $customer->longitude ?>' target='_blank'>View on Maps</a><br><br><?php } ?>

				<label>Contact</label>
				<p><?= $customer->pic_name ?></p>
				<p><?= $customer->phone_number ?></p>

				<label>Plafond</label>
				<p>Rp. <?= number_format($customer->plafond, 2) ?></p>

				<label>Term of payment</label>
				<p><?= number_format($customer->term_of_payment) ?></p>

				<label>Unique ID</label>
				<p><?= $customer->uid ?></p>
			</div>
			<div class='col-md-8 col-sm-12 col-xs-12'>
				<button class='button button_mini_tab' id='salesOrderButton'>Pending Sales Orders</button>
				<button class='button button_mini_tab' id='receivableButton'>Receivable</button>
				<br><br>
				<div id='pendingSalesOrderTable'>
					<?php if(count($salesOrders) > 0){ ?>
					<table class='table table-bordered'>
						<tr>
							<th>Date</th>
							<th>Name</th>
							<th>Progress</th>
							<th>Action</th>
						</tr>
						<tbody id='pendingSalesOrderTableContent'>
						<?php foreach($salesOrders as $salesOrder){ ?>
							<tr>
								<td><?= date('d M Y', strtotime($salesOrder->date)) ?></td>
								<td><?= $salesOrder->name ?></td>
								<td><div class='progressBarWrapper'>
									<p><?= $salesOrder->sent * 100 / $salesOrder->quantity ?>%</p>
									<div class='progressBar' id='progressBar-<?= $salesOrder->id ?>'></div></div></td>
								<script>
									$(document).ready(function(){
										$("#progressBar-<?= $salesOrder->id ?>").animate({
											width: "<?= max(5, $salesOrder->sent * 100 / $salesOrder->quantity) ?>%"
										},1000);
									})
								</script>
								<td><button class='button button_default_dark' onclick='viewSalesOrder(<?= $salesOrder->id ?>)'><i class='fa fa-eye'></i></button></td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
					<?php } ?>
			</div>
		</div>
	</div>
</div>
