<head>
	<title>Billing - Print</title>
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
		size: 29.7cm, 21cm;
		}
	}
	</style>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-briefcase'></i></a> / Print Billing</p>
	</div>
	<br>
	<div class='dashboard_in' id='printable'>
		<label>Date</label>
		<p><?= $billing->name ?></p>
		<p><?= date('d M Y', strtotime($billing->date)) ?></p>

		<label>Collector</label>
		<p><?= $billing->billed_by ?></p>

		<table class='table table-bordered'>
			<tr>
				<th>Customer</th>
				<th>Invoice</th>
				<th>Value</th>
				<th style='width:30%'>Result</th>
			</tr>
<?php
	$customerArray = array();
	foreach($items as $item){
		$customerId				= $item->customerId;
		$complete_address		= '';
		$customer_name			= $item->customerName;
		$complete_address		= $item->address;
		$customer_city			= $item->city;
		$customer_number		= $item->number;
		$customer_rt			= $item->rt;
		$customer_rw			= $item->rw;
		$customer_postal		= $item->postal_code;
		$customer_block			= $item->block;
	
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

		if(!in_array($item->customerId, $customerArray)){
			$customerArray[] = $item->customerId;
?>
			<tr>
				<td>
					<label><?= $customer_name ?></label>
					<p><?= $complete_address ?></p>
				</td>
				<td>
					<label><?= $item->name ?></label>
					<p><?= date('d M Y',strtotime($item->date)) ?></p>
				</td>
				<td>Rp. <?= number_format($item->value - $item->paid,2) ?></td>
				<td></td>
			</tr>
<?php
		} else {
?>
			<tr>
				<td></td>
				<td>
					<label><?= $item->name ?></label>
					<p><?= date('d M Y',strtotime($item->date)) ?></p>
				</td>
				<td>Rp. <?= number_format($item->value - $item->paid,2) ?></td>
				<td></td>
			</tr>
<?php
		}
	}
?>
		</table>
	</div>
	<br>
	<div class='row' style='margin:0'>
		<div class='col-xs-12' style='text-align:center'>
			<button type='button' class='button button_default_light' onclick='print_purchase_order()' id='print_button'><i class='fa fa-print'></i></button>
			<button type='button' class='button button_success_dark' onclick='window.location.href="<?= site_url('Billing/confirmDashboard') ?>"' id='back_button' style='display:none'><i class='fa fa-long-arrow-left'></i></button>
		</div>
	</div>
</div>
<script>
	function print_purchase_order(){
		window.print();
		$('#print_button').hide();
		$('#back_button').show();
	}
</script>
