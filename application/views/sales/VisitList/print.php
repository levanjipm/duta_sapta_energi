<head>
	<title>Visit List - Print</title>
	<style>
		@media print{
			body{
				visibility:hidden;
			}

			.dashboard_in{
				position:absolute;
				top:0;
				left:0;
				width:100%;
				visibility:visible;
			}
		}
	</style>
</head>
<div class='dashboard'>
	<br>
	<div class='dashboard_in'>
		<label>Date</label>
		<p><?= date('d M Y', strtotime($general->date)) ?></p>
		<label>Salesman</label>
		<p><?= $general->visited_by ?></p>

		<table class='table table-bordered'>
			<tr>
				<th>Customer</th>
				<th>Signature</th>
				<th>Result</th>
			</tr>
<?php 
	foreach($items as $item) { 
		$customerName		= $item->name;
		$complete_address		= '';
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
		
		if($customer_block != NULL || $customer_block == "000"){
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
			<tr style='height:150px'>
				<td style='width:25%'>
					<label><?= $item->name ?></label>
					<p><?= $complete_address ?></p>
				</td>
				<td></td>
				<td></td>
			</tr>
<?php } ?>
		</table>
	</div>
	<div class='row' style='margin:0;margin-top:20px'>
		<div class='col-xs-12' style='text-align:center'>
			<button class='button button_default_light' onclick='window.print()'><i class='fa fa-print'></i></button>
		</div>
	</div>
</div>