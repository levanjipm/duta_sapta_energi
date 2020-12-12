<head>
	<title>Asset - Archive</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-bar-chart'></i></a> /<a href='<?= site_url('Asset') ?>'>Asset</a> / Archive</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<input type='text' class='form-control' id='search_bar'>
		<br>
		<div id='assetTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Purchase date</th>
					<th>Name</th>
					<th>Description</th>
					<th>Value</th>
					<th>Action</th>
				</tr>
				<tbody id='assetTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='assetTableText'>There is no asset found.</p>
	</div>
</div>

<div class='alert_wrapper' id='viewAssetWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Asset Information</h3>
		<hr>
		<form id='sellAssetForm'>
			<label>Date aquired</label>
			<p id='date_p'></p>

			<label>Asset name</label>
			<p id='name_p'></p>

			<label>Asset description</label>
			<p id='description_p'></p>

			<label>Value</label>
			<p>Rp. <span id='value_p'></span></p>

			<label>Residual value</label>
			<p>Rp. <span id='residualValue_p'></span></p>

			<label>Depreciation time</label>
			<p id='depreciation_p'></p>

			<label>Type</label>
			<p id='type_p'></p>

			<div id='soldDateWrapper'>
				<label>Sold date</label>
				<p id='soldDate_p'></p>
			</div>
			<div id='currentValueWrapper'>
				<label>Estimated current value</label>
				<p id='currentValue_p'></p>
			</div>
		</form>
	</div>
</div>

<script>
	$(document).ready(function(){
		refreshView();
	})

	$('#page').change(function(){
		refreshView();
	})

	$('#search_bar').change(function(){
		refreshView(1);
	})

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Asset/getItems') ?>",
			data:{
				page: page,
				term: $('#search_bar').val()
			},
			success:function(response){
				var items = response.items;
				var assetCount = 0;
				$('#assetTableContent').html("");
				$.each(items, function(index, item){
					var name		= item.name;
					var description	= item.description;
					var date		= item.date;
					var value		= item.value;
					var id			= item.id;
					var soldDate	= item.sold_date;

					if(soldDate == null){
						$('#assetTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td>" + description + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td><button class='button button_transparent' onclick='viewAsset(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
					} else {
						$('#assetTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td>" + description + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td><button class='button button_transparent' onclick='viewAsset(" + id + ")'><i class='fa fa-eye'></i></button> | <strong>Sold</strong></td></tr>");
					}
					
					assetCount++;
				})
				var pages = response.pages;
				$('#page').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}

				if(assetCount > 0){
					$('#assetTable').show();
					$('#assetTableText').hide();
				} else {
					$('#assetTable').hide();
					$('#assetTableText').show();
				}
			}
		})
	}

	function viewAsset(n){
		$.ajax({
			url:"<?= site_url('Asset/getById') ?>",
			data:{
				id:n
			},
			success:function(response){
				var date = response.date;
				var name = response.name;
				var description = response.description;
				var assetType = response.assetType;
				var residualValue = parseFloat(response.residual_value);
				var value = parseFloat(response.value);
				var depreciation = parseInt(response.depreciation_time);

				var soldDate = response.sold_date;

				var difference = (new Date().getFullYear()*12 + new Date().getMonth()) - (new Date(date).getFullYear()*12 + new Date(date).getMonth());

				if(difference <= 0 || depreciation == 0){
					var estimatedCurrentValue = value;
				} else {
					var estimatedCurrentValue = value - value * (difference / depreciation);
				}

				$('#date_p').html(my_date_format(date));
				$('#name_p').html(name);
				$('#description_p').html(description);
				$('#type_p').html(description);
				$('#residualValue_p').html(numeral(residualValue).format('0,0.00'));
				$('#value_p').html(numeral(value).format('0,0.00'));
				$('#depreciation_p').html(numeral(depreciation).format('0,0') + " month(s)");

				if(soldDate != null){
					$('#soldDate_p').html(my_date_format(soldDate));
					$('#soldDateWrapper').show();
					$('#currentValueWrapper').hide();
				} else {
					$('#currentValue_p').html(numeral(estimatedCurrentValue).format('0,0.00'));
					$('#soldDateWrapper').hide();
					$('#currentValueWrapper').show();
				}
				$('#viewAssetWrapper').fadeIn(300, function(){
					$('#viewAssetWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}
</script>
