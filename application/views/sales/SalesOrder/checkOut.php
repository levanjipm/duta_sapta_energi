<head>
	<title>Submission success - <?= $general->name ?></title>
	<style>
		.logo_wrapper{
			position:relative; 
			height:200px;
		}

		.circle_wrapper {
			opacity: 1;
			transform: scale(0);
			color: white;
			border-radius:50%;
			border:2px solid #fff;
			-webkit-text-stroke: 0;
			animation: background .3s cubic-bezier(1.000, 0.008, 0.565, 1.650) .1s 1 forwards;
			width:200px;
			height:200px;
			position:absolute;
			text-align:center;
		}

		.check{
			position:relative;
			top:5%;
			opacity:0;
			color:white;
			font-size:10rem;
			animation: icon .5s cubic-bezier(0.895, 0.030, 0.685, 0.220) forwards;
		}

		@keyframes background {
			from {
				border:2px solid white;
				opacity: 0;
				transform: scale(0.3);
				background-color:transparent;
			}
			to {
				border:2px solid transparent;
				opacity: 1;
				transform: scale(1);
				background-color:#00d478;
			}
		}

		@keyframes icon {
			from {
				opacity: 0;
				transform: scale(0.3);
			}
			to {
				opacity: 1;
				transform: scale(1)
			}
		}

		#copy_text_span{
			cursor:pointer;
		}
			
	</style>
</head>
<div class='dashboard'>
	<br>
	<div class='dashboard_in'>
		<div class='logo_wrapper'>
			<div class='circle_wrapper'>
				<div class='check'><i class='fa fa-check'></i></div>
			</div>
		</div>
		<br>
		<div class='row'>
			<div class='col-sm-6 col-xs-12'>
				<label>Customer</label>
<?php
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
	
	if($customer_number != NULL || $customer_number == "000" || $customer_number == ""){
		$complete_address	.= ' No. ' . $customer_number;
	}
	
	if($customer_block != NULL || $customer_block == "000" || $customer_block == ""){
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
	
	if($general->seller == null){
		$sales		= "<i>Not assigned</i>";
	} else {
		$sales		= $general->seller;
	}
?>
				<p style='font-family:museo'><?= $customer_name ?></p>
				<p style='font-family:museo'><?= $complete_address ?></p>
				<p style='font-family:museo'><?= $customer_city ?></p>
				<input type='text' id='salesOrderNameInput' value='<?= $general->name ?>' style='opacity:0'>
			</div>
			<div class='col-sm-6 col-xs-12'>
				<label>Sales order name</label>
				<p style='font-family:museo'><?= $general->name ?> <button class='button button_transparent' style='display:inline-block' onclick='copySalesOrderName()'><i class='fa fa-copy'></i></button></p>
				
				<label>Payment</label>
				<p style='font-family:museo'><?= number_format($general->payment, 0) ?></p>

				<label>Sales</label>
				<p style='font-family:museo'><?= $sales ?></p>
			</div>
		</div>
		<div class='row'>
			<div class='col-sm-12'>
				<div class='table-responsive-lg'>
					<table class='table table-bordered'>
						<tr>
							<th>Reference</th>
							<th>Description</th>
							<th>Price list</th>
							<th>Discount</th>
							<th>Quantity</th>
							<th>Net unit price</th>
							<th>Price</th>
						</tr>
<?php
	$sales_order_value		= 0;
	foreach($details as $detail){
		$unit_price = $detail->price_list * (100 - $detail->discount) / 100;
		$price		= $unit_price * $detail->quantity;
		$sales_order_value	+= $price;
?>
						<tr>
							<td><?= $detail->reference ?></td>
							<td><?= $detail->name ?></td>
							<td>Rp. <?= number_format($detail->price_list,2) ?></td>
							<td><?= number_format($detail->discount,2) ?> %</td>
							<td><?= number_format($detail->quantity) ?></td>
							<td>Rp. <?= number_format($unit_price,2) ?></td>
							<td>Rp. <?= number_format($price,2) ?></td>
						</tr>
<?php
	}
?>
						<tr>
							<td colspan='3'></td>
							<td colspan='2'>Total</td>
							<td colspan='2'>Rp. <?= number_format($sales_order_value,2) ?></td>
						</tr>
					</table>
				</div>
				<label>Note</label>
				<p><?= ($general->note == null || $general->note == "") ? "<i>Not available</i>" : $general->note ?></p>
				<div class='notificationText success' id='successCopyNotification'><p>Sales order number has been copied.</p></div>
		
				<a href='<?= site_url('Sales_order/createDashboard') ?>'><button class='button button_default_dark'><i class='fa fa-long-arrow-left'></i></button></a>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		var window_width	= $(document).width() - 200;
		var difference		= window_width * 0.5 - 200;		
		$('.logo_wrapper').css('margin-left', difference, 'important');
	});

	$(window).resize(function(){
		var window_width	= $(document).width() - 200;
		var difference		= window_width * 0.5 - 200;		
		$('.logo_wrapper').css('margin-left', difference, 'important');
	})

	function copySalesOrderName(){
		var copyText = document.getElementById("salesOrderNameInput");
		copyText.select();
		copyText.setSelectionRange(0, 99999);
		document.execCommand("copy");

		$('#successCopyNotification').fadeIn(250);
		setTimeout(function(){
			$('#successCopyNotification').fadeOut(250);
		}, 1000);
	}
</script>
