<head>
	<title>Billing</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-briefcase'></i></a> / Print Billing</p>
	</div>
	<br>
	<div class='dashboard_in'>
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
</div>
