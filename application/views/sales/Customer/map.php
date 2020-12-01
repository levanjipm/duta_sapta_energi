<head>
	<title>Customer - Map</title>
	<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBX4UnIiCLVbq-LPeTA__c3NKIEZA1rhAw&callback=initMap&libraries=&v=weekly" defer></script>
	<style>
		#map {
			height: 400px;
			width:100%;
		}
	</style>
	<script>
		var includedAreas	= [];
		var areas			= [];
	</script>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> / Customer / Map</p>
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
