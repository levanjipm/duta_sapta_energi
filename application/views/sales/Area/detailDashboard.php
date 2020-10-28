<head>
	<title>Customer Area</title>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /Area</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='row'>
			<div class='col-sm-6'>
				<label>Area</label>
				<p><?= $area->name ?></p>
				<a href='#'><i class='fa fa-location-arrow'></i> View on maps</a>
			</div>
			<div class='col-sm-6'>
				<label>Current target</label>
				<p id='customerTarget'></p>

				<label>Achivement vs Target History</label>
				<canvas id='lineChart'></canvas>
			</div>
		</div>
		<hr>

		<label>Customers (<?= count($customers) ?>)</label> <button class='button button_mini_tab' onclick='$("#customerTable").toggle(300)'><i class='fa fa-eye'></i></button>
		<table class='table table-bordered' id='customerTable' style='display:none'>
			<tr>
				<th>Customer</th>
				<th>Information</th>
				<th>Target</th>
			</tr>
		<?php 
		$totalTarget		= 0;
		foreach($customers as $customer){ 
			$customerName		= $customer->name;
			$complete_address	= $customer->address;
			$customer_number	= $customer->number;
			$customer_block		= $customer->block;
			$customer_rt		= $customer->rt;
			$customer_rw		= $customer->rw;
			$customer_city		= $customer->city;
			$customer_postal	= $customer->postal_code;
			$customer_pic		= $customer->pic_name;
			if($customer_number != null && $customer_number != ''){
				$complete_address	.= ' no. ' . $customer_number;
			};
			
			if($customer_block != null && $customer_block != ''){
				$complete_address	.= ', blok ' . $customer_block;
			};
			
			if($customer_rt != '000'){
				$complete_address	.= ', RT ' . $customer_rt . ', RW ' . $customer_rw;
			}
			
			if($customer_postal != ''){
				$complete_address .= ', ' . $customer_postal;
			}

			$target		= $customer->target;
			$totalTarget += $target;	
		?>
			<tr>
				<td><?= $customerName ?></td>
				<td><?= $complete_address ?>, <?= $customer_city ?></td>
				<td>Rp. <?= number_format($target, 2) ?></td>
			</tr>
		<?php } ?>
		</table>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('#customerTarget').html("Rp. " + numeral(<?= $totalTarget ?>).format('0,0.00'));
		$.ajax({
			url:"<?= site_url('Area/getChartItems') ?>",
			data:{
				area:<?= $this->input->post('id') ?>
			},
			success:function(response){
				console.log(response);
			}
		})
	});
</script>
