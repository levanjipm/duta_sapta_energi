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
					<input type='checkbox' value='<?= $area->id ?>' checked> <?= $area->name ?>
				</label>
			</div>
		<?php } ?>
		<div id="map"></div>
	</div>
</div>
<script>
	let map;

	function initMap() {
		map = new google.maps.Map(document.getElementById("map"), {
			center: { lat: -34.397, lng: 150.644 },
			zoom: 8,
		});
		var bounds = new google.maps.LatLngBounds();
		<?php foreach($customers as $customer){ ?>
			<?php if($customer->latitude != null && $customer->latitude != 0){ ?>
			var place = new google.maps.LatLng(<?= $customer->latitude ?>, <?= $customer->longitude ?>)
			var marker = new google.maps.Marker({
				position: place,
				map: map,
				title: "<?= $customer->name ?>",
				icon:'<?= base_url('assets/Icons/location.png') ?>'
			});
			bounds.extend(place);
			google.maps.event.addListener(marker, 'click', function(marker){
				return function() {
					infowindow.setContent("<?= $customer->name ?>");
					infowindow.open(map, marker);
				}
			});
			<?php } ?>
		<?php } ?>
		map.fitBounds(bounds);
	}
</script>
