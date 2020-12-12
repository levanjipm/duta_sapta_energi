<head>
	<title>Customer - Map</title>
	<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBX4UnIiCLVbq-LPeTA__c3NKIEZA1rhAw&callback=initMap&libraries=&v=weekly" defer></script>
	<style>
		#map {
			height: 400px;
			width:100%;
		}

		.headerText{
			font-family:sans-serif;
			font-size:14px;
			font-weight:700;
		}

		.bodyText{
			font-family:sans-serif;
			font-size:12px;
			color:#333;
			margin-bottom:0;
		}
	</style>
	<script>
		var includedAreas	= [];
		var areas			= [];
	</script>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-line-chart'></i></a> / Customer / Map</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Area Filter</label>
		<div class="row">
		<?php foreach($areas as $area){ ?>
			<div class='col-sm-2'>
				<label>
					<input type='checkbox' value='<?= $area->id ?>' checked id='checkbox-<?= $area->id ?>'><?= $area->name ?>
				</label>
				<script>
					areas.push(parseInt(<?= $area->id ?>));
					$('#checkbox-<?= $area->id ?>').change(function(){
						if($(this).is(":checked") && !includedAreas.includes(parseInt(<?= $area->id ?>))){
							includedAreas.push(parseInt(<?= $area->id ?>));
						} else {
							includedAreas.splice(includedAreas.indexOf(<?= $area->id ?>), 1);
						}

						if(includedAreas.length == 0){
							includedAreas = areas;
							$('input[id^="checkbox-"]').each(function(){
								$(this).prop('checked', true);
							})
						}
						setMapOnAll(map);
					})
					includedAreas.push(parseInt(<?= $area->id ?>));
				</script>
			</div>
		<?php } ?>
		<div id="map"></div>
	</div>
</div>
<script>
	let map;
	let markers = [];
	var customers = <?= json_encode($customers) ?>;

	function initMap() {
		map = new google.maps.Map(document.getElementById("map"), {
			center: { lat: -34.397, lng: 150.644 },
			zoom: 8,
		});

		$.each(customers, function(index, customer){
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

			markers.push(marker);
		})

		setMapOnAll(map);
	}

	function setMapOnAll(map){
		for (let i = 0; i < markers.length; i++) {
			markers[i].setMap(null);
		};

		var bounds = new google.maps.LatLngBounds();
		$.each(customers, function(index, customer){
			if(customer.latitude != null && customer.latitude != 0){
				if(includedAreas.includes(parseInt(customer.area_id))){
					var marker		= markers[index];
					marker.setMap(map);
					bounds.extend(marker.position);
					marker.setMap(map);
				}
			}
		});

		map.fitBounds(bounds);
	}
</script>
