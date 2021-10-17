<head>
    <title>Sales analytics - Report</title>
	<style>
		@page {
			size: A4;
		}

		@media print{
			body{
				visibility:hidden;
			}
			
			button{
				display:none;
			}

			.dashboard_in{
				visibility:visible;
				width:100%;
				position:absolute;
				top:0;
				left:0;
			}
		}
	</style>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Inventory'><i class='fa fa-line-chart'></i></a> /<a href='<?= site_url('SalesAnalytics') ?>'>Analytics</a> / Sales Report</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='row'>
			<div class='col-xs-4 col-xs-offset-4'>
				<img src='<?= base_url('assets/Logo_dark.png') ?>' style='width:40%;margin-left:30%'></img>
			</div>
			<div class='col-xs-12'>
				<hr style='border-top:4px solid #424242;margin:0;'>
				<hr style='border-top:2px solid #E19B3C;margin:0;'>
			</div>
			<div class='col-xs-12' style='margin-top:20px;'>
				<label>Period</label>
				<p><?= date('F Y', mktime(0,0,0,$month, 1, $year)) ?></p>

				<label>Brand</label>
				<p><?= $brand->name ?></p>

				<label>Action</label>
				<form action='<?= site_url('SalesAnalytics/exportSalesReportCSV') ?>' method="POST">
					<input type='hidden' name='month' value='<?= $month ?>'>
					<input type='hidden' name='year' value='<?= $year ?>'>
					<input type='hidden' name='brand' value='<?= $brand->id ?>'>

					<button type='button' class='button button_mini_tab' onclick='window.print()'><i class='fa fa-print'></i> Print</button>
					<button class='button button_mini_tab'><i class='fa fa-file-excel-o'></i> Save as CSV</button>
				</form>
				<br>
				<label>Customer Area Overview</label>
				<table class='table table-bordered'>
					<tr>
						<th>Name</th>
						<th>Value</th>
						<th>Target</th>
					</tr>
					<tbody id='areaOverviewTable'></tbody>
				</table>

				<label>Customer Overview</label>
				<table class='table table-bordered'>
					<tr>
						<th>Active Customer</th>
					</tr>
					<tbody id='customerOverviewTable'></tbody>
				</table>
			</div>
			<div class='col-xs-12'>
	<?php if(count($customers) > 0){ ?>
				<br>
				<table class='table table-bordered'>
					<tr>
						<th>Customer</th>
						<th>Information</th>
						<th>Achivement</th>
					</tr>
		<?php 
			$customerTotalTarget = 0;
			$customerTotalValue = 0;
			$customerCountBought = 0;
			$customerCountBoughtLast = 0;
			$areaArray			= array();
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
				$areaId				= $customer->area_id;
				$areaName			= $customer->areaName;

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

				$value			= $customer->value;
				$returned		= $customer->returned;
				$totalValue		= $value - $returned;
				$currentTarget	= $customer->target;

				$customerTotalTarget += $currentTarget;
				$customerTotalValue += $totalValue;

				if(!array_key_exists($areaId, $areaArray)){
					$areaArray[$areaId]		= array(
						"name" => $areaName,
						"value" => $totalValue,
						"target" => $currentTarget
					);
				} else {
					$areaArray[$areaId]['value'] += $totalValue;
					$areaArray[$areaId]['target'] += $currentTarget;
				}

				if($value > 0){
					$customerCountBought++;
				}
		?>
					<tr>
						<td><?= $customerName ?></td>
						<td>
							<p><?= $complete_address ?></p>
							<p><?= $customer->city ?></p>
						</td>
						<td>Rp. <?= number_format($totalValue,2) ?>| Rp. <?= number_format($currentTarget,2) ?></td>
					</tr>
		<?php }; $finalAreaArray		= json_encode($areaArray); ?>
					<tr>
						<td colspan='2'>Total</td>
						<td>Rp. <?= number_format($customerTotalValue, 2) ?></td>
					</tr>
					<tr>
						<td colspan='2'>Target</td>
						<td>Rp. <?= number_format($customerTotalTarget, 2) ?></td>
					</tr>
				</table>
	<?php } else { ?>
				<p>There is no customer found.</p>
	<?php } ?>
			</div>
		</div>
	</div>
</div>
<script>
	$.each(<?= $finalAreaArray ?>, function(index, area){
		var name			= area.name;
		var value			= area.value;
		var target			= area.target;

		$('#areaOverviewTable').append("<tr><td>" + name + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Rp. " + numeral(target).format('0,0.00') + "</td></tr>");
	});

	$('#areaOverviewTable').append("<tr><td><label>Total</label></td><td>Rp. " + numeral(<?= $customerTotalValue ?>).format('0,0.00') + "</td><td>Rp. " + numeral(<?= $customerTotalTarget ?>).format('0,0.00') + "</td></tr>");

	$('#customerOverviewTable').append("<tr><td>" + numeral(<?= $customerCountBought ?>).format('0,0') + "</td></tr>");
</script>
