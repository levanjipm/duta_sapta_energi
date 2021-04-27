<head>
	<title>Customer Area</title>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
	<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBX4UnIiCLVbq-LPeTA__c3NKIEZA1rhAw&callback=initMap&libraries=&v=weekly" defer></script>
	<style>
		#map {
			height: 300px;
			width:100%;
		}

		.viewPane{
			width:100%;
			display:none;
		}
	</style>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-line-chart'></i></a> /<a href='<?= site_url('Area') ?>'>Area</a> /<?= $area->name ?></p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='row'>
			<div class='col-sm-12'>
				<button class='button button_mini_tab' id='generalButton'><i class='fa fa-database'></i> General Data</button>
				<button class='button button_mini_tab' id='customerButton'><i class='fa fa-table'></i> Customers</button>
				<button class='button button_mini_tab' id='paymentButton'><i class='fa fa-money'></i> Payment</button>
				<hr>
			</div>
			<div class='viewPane' id='generalView'>
				<div class='col-sm-6'>
					<label>Area</label>
					<p><?= $area->name ?></p>
					<div id="map"></div>
				</div>
				<div class='col-sm-6'>
					<label>Brand</label>
					<select 
					class='form-control' 
					id='brandTargetSelector'
					onchange="updateBrandTarget()">
					<?php foreach($brands as $brand){ ?>
						<option value='<?= $brand->id ?>'><?= $brand->name ?></option>
					<?php } ?>
					</select>

					<label>Current target</label>
					<p id='customerTarget'></p>

					<label>Achivement vs Target History</label>
					<canvas id='lineChart'></canvas>
				</div>
			</div>
			<div class='viewPane' id='customerView'>
				<table class='table table-bordered'>
					<tr>
						<th>Customer</th>
						<th>Information</th>
						<th>Action</th>
					</tr>
				<?php 
				$page				= 1;
				$i					= 0;
				foreach($customers as $customer){
					$customerName		= $customer['name'];
					$complete_address	= $customer['address'];
					$customer_number	= $customer['number'];
					$customer_block		= $customer['block'];
					$customer_rt		= $customer['rt'];
					$customer_rw		= $customer['rw'];
					$customer_city		= $customer['city'];
					$customer_postal	= $customer['postal_code'];
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
				?>
					<tr class='table-<?= $page?>'>
					<td><?= $customerName ?></td>
					<td><p><?= $complete_address ?>, <?= $customer_city ?></p></td>
					<td>
						<button class="button button_default_dark" onclick='viewCustomerDetail(<?= $customer["id"] ?>)'>
							<i class='fa fa-eye'></i>
						</button>
					</td>
					</tr>
				<?php 
					$i++;
					if($i % 10 == 0){
						$page++;
					}
				}
				?>
				</table>
				<br>
				<select class='form-control' id='page' style='width:100px' onchange='changePage()'>
				<?php for($x = 1; $x <= $page; $x++){ ?>
					<option value='<?= $x ?>' <?= ($x == 1) ? "selected" : "" ?>><?= $x ?></option>
				<?php } ?>
				</select>
			</div>
			<div class='viewPane' id='paymentView'>
				<table class='table table-bordered'>
					<tr>
						<th>Property</th>
						<th>Value - Weighted Average</th>
						<th>Plain Average</th>
						<th>Value</th>
					</tr>
					<tr>
						<td>3 months</td>
						<td><?= number_format($payments[0]->vwa,2) ?></td>
						<td><?= number_format($payments[0]->pa,2) ?></td>
						<td>Rp. <?= number_format($payments[0]->value,2) ?> (<?= $payments[0]->count ?>)</td>
					</tr>
					<tr>
						<td>6 months</td>
						<td><?= number_format($payments[1]->vwa,2) ?></td>
						<td><?= number_format($payments[1]->pa,2) ?></td>
						<td>Rp. <?= number_format($payments[1]->value,2) ?> (<?= $payments[1]->count ?>)</td>
					</tr>
					<tr>
						<td>12 months</td>
						<td><?= number_format($payments[2]->vwa,2) ?></td>
						<td><?= number_format($payments[2]->pa,2) ?></td>
						<td>Rp. <?= number_format($payments[2]->value,2) ?> (<?= $payments[2]->count ?>)</td>
					</tr>
					<tr>
						<td>Overall</td>
						<td><?= number_format($payments[3]->vwa,2) ?></td>
						<td><?= number_format($payments[3]->pa,2) ?></td>
						<td>Rp. <?= number_format($payments[3]->value,2) ?> (<?= $payments[3]->count ?>)</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<script>
	let targets = [];

	$(document).ready(function(){
		$('#generalButton').click();

		$.each(<?= json_encode($customers) ?>, function(index, customer){
			$.each(customer.target, function(brand, target){
				if(targets[brand] === undefined){
					targets[brand] = parseFloat(target.value);
				} else {
					targets[brand] += parseFloat(target.value);
				}
			});
		});

		updateBrandTarget();
	});

	function updateBrandTarget(){
		let brand = $('#brandTargetSelector').val();
		$('#customerTarget').html(numeral(targets[brand]).format('0,0.00'));

		
		$.ajax({
			url:"<?= site_url('Area/getChartItems') ?>",
			data:{
				area:<?= $this->input->post('id') ?>,
				brand: brand
			},
			success:function(response){
				var result = JSON.parse(response);
				var valueArrays	= result.value;
				var targetArrays	= result.target;

				var labelArray		= [];
				var valueArray		= [];
				var targetArray		= [];

				$.each(valueArrays, function (index, value){
					labelArray.push(value.label);
					valueArray.push(value.value);
				});

				$.each(targetArrays, function(index, value){
					targetArray.push(value.value);
				})

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

		changePage();
	}

	function viewCustomerDetail(id){
		window.open("<?= site_url('Customer/viewCustomerDetail/') ?>" + id);
	}

	function changePage(){
		var page		= $('#page').val();
		$('tr[class^="table-"]').hide();
		$('.table-' + page).show();
	}

	$('.button_mini_tab').click(function(){
		$('.button_mini_tab').removeClass('active');
		$('.button_mini_tab').attr('disabled', false);

		$(this).addClass('active');
		$(this).attr('disabled', true);
	});

	$('#generalButton').click(function(){
		$('.viewPane').fadeOut(300);
		setTimeout(function(){
			$('#generalView').fadeIn(300);
		}, 300);
	});

	$('#customerButton').click(function(){
		$('.viewPane').fadeOut(300);
		setTimeout(function(){
			$('#customerView').fadeIn(300);
		}, 300);
	});

	$('#paymentButton').click(function(){
		$('.viewPane').fadeOut(300);
		setTimeout(function(){
			$('#paymentView').fadeIn(300);
		}, 300);
	})
	let map;
	function initMap() {
		map = new google.maps.Map(document.getElementById("map"), {
			zoom: 8,
		});
		var bounds = new google.maps.LatLngBounds();
		$.each(<?= json_encode($customers) ?>, function(index, customer){
			if(customer.latitude != 0 && customer.latitude != null){
				var place = new google.maps.LatLng(parseFloat(customer.latitude), parseFloat(customer.longitude));
				var marker = new google.maps.Marker({
					position: place,
					map: map,
					title: customer.name,
					icon:'<?= base_url('assets/Icons/location.png') ?>'
				});
				var complete_address		= '';
				complete_address		+= customer.address;
				var customer_city			= customer.city;
				var customer_number			= customer.number;
				var customer_rt				= customer.rt;
				var customer_rw				= customer.rw;
				var customer_postal			= customer.postal_code;
				var customer_block			= customer.block;
				var customer_id				= customer.id;

				if(customer_number != null){
					complete_address	+= ' No. ' + customer_number;
				}
				
				if(customer_block != null && customer_block != "000"){
					complete_address	+= ' Blok ' + customer_block;
				}
			
				if(customer_rt != '000'){
					complete_address	+= ' RT ' + customer_rt;
				}
				
				if(customer_rw != '000' && customer_rt != '000'){
					complete_address	+= ' /RW ' + customer_rw;
				}
				
				if(customer_postal != null){
					complete_address	+= ', ' + customer_postal;
				}

				const contentString =
					'<div id="content"><h4 class="headerText">' + customer.name + '</h4>' +
					'<div id="bodyContent">' +
					"<p class='bodyText'>" + complete_address + "</p>" +
					"<p class='bodyText'>" + customer_city + "</p>" +
					"</div>" +
					"</div>";
				const infowindow = new google.maps.InfoWindow({
					content: contentString,
				});

				marker.addListener("click", () => {
					infowindow.open(map, marker);
				});

				bounds.extend(marker.position);
			}
		})	
		map.fitBounds(bounds);
	}
</script>