<head>
	<title>Customer Area</title>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

	<script src="https://js.api.here.com/v3/3.1/mapsjs-core.js" type="text/javascript" charset="utf-8"></script>
	<script src="https://js.api.here.com/v3/3.1/mapsjs-service.js" type="text/javascript" charset="utf-8"></script>
	<script>
		var markers		= [];
	</script>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /Area</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='row'>
			<div class='col-sm-6' style='flex:1'>
				<label>Area</label>
				<p><?= $area->name ?></p>
				
				<div style="width: 100%; height: calc(100% - 50px)" id="map"></div>
			</div>
			<div class='col-sm-6' style='flex:1'>
				<label>Current target</label>
				<p id='customerTarget'></p>

				<label>Achivement vs Target History</label>
				<canvas id='lineChart'></canvas>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-sm-12">
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
					
					if($customer->latitude != NULL && $customer->longitude != NULL){
				?>
					<script>
						var icon = new H.map.Icon('<?= base_url("assets/Icons/location.png") ?>');
						var coords	= {lat:<?= $customer->latitude ?>, lng:<?= $customer->longitude ?>};
						var marker = new H.map.Marker(coords, {icon: icon});
						markers.push(marker);
					</script>
				<?php
					}
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
				var result = JSON.parse(response);
				var valueArray	= result.value;
				var targetArray	= result.target;

				var monthArray = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
				var labelArray		= [];
				var currentDate		= new Date();
				for(i = 0; i < 6; i++){
					var paramDate	= new Date(currentDate.setMonth(currentDate.getMonth() - i));
					var label		= monthArray[paramDate.getMonth()] + paramDate.getFullYear();
					labelArray.push(label);
				}

				labelArray.reverse();

				var ctx = document.getElementById('lineChart').getContext('2d');
				var myLineChart = new Chart(ctx, {
					type: 'line',
					data: {
						labels: labelArray,
						datasets: [{
							backgroundColor: 'rgba(225, 155, 60, 0.4)',
							borderColor: 'rgba(225, 155, 60, 1)',
							data: valueArray
						}, {
							backgroundColor: 'rgba(1, 187, 0, 0.3)',
							borderColor: 'rgba(1, 187, 0, 1)',
							data: targetArray
						}],
					},
					options: {
						legend:{
							display:false
						}
					}
				});
			}
		});

		addMarkersToMap(map);
	});

	function addMarkersToMap(map){
		var group = new H.map.Group();
		group.addObjects(markers);
		map.addObject(group);

		map.getViewModel().setLookAtData({
			bounds: group.getBoundingBox()
		});
	}

	var platform = new H.service.Platform({
	  'apikey': '8852D09jOONrOtfoZZhCszVYPU7C5EBuQdtAZ4HANh4'
	});

	var defaultLayers = platform.createDefaultLayers();

	var map = new H.Map(document.getElementById('map'),
	  defaultLayers.vector.normal.map,{
	  center: {lat:50, lng:5},
	  zoom: 4
	});
</script>
