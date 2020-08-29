<head>
	<title>Asset</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> /Asset</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<input type='text' class='form-control' id='search_bar'>
			<div class='input_group_append'>
				<button class="button button_default_dark" title="Add an asset" id='addAssetButton'><i class='fa fa-plus'></i> Add asset</button>
			</div>
		</div>
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

<div class="alert_wrapper" id='addAssetWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Add an asset</h3>
		<hr>
		<form id='addAssetForm'>
			<hr>
			<label>Date aquired</label>
			<input type="date" class="form-control" id='date' name='date' required min='2000-01-01'>

			<label>Asset name</label>
			<input type='text' class="form-control" id='name' name='name' required>

			<label>Asset description</label>
			<textarea class="form-control" rows='3' style='resize:none' id='description' name='description' minlength='25'></textarea>

			<label>Value</label>
			<input type='number' class="form-control" id='value' name='value' min='0' required>

			<label>Residual value</label>
			<input type='number' class='form-control' id='residualValue' name='residualValue' min='0' required>

			<label>Depreciation time (month)</label>
			<input type='number' class='form-control' min='0' id='depreciation' name='depreciation' required>

			<label>Type</label>
			<button type='button' class='form-control' id='addAssetTypeButton' style='text-align:left'></button>
			<input type='hidden'id='assetType' name='assetType' required><br>

			<button type='button' class='button button_default_dark' title='Add Asset' id='insertAssetButton'><i class='fa fa-long-arrow-right'></i></button>
			<div class='notificationText danger' id='failedInsertAsset'><p>Failed to insert asset data.</p></div>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='editAssetWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Add an asset</h3>
		<hr>
		<form id='editAssetForm'>
			<input type='hidden' id='editAssetId' name='id'>
			<label>Date aquired</label>
			<input type="date" class="form-control" id='editDate' name='date' required min='2000-01-01'>

			<label>Asset name</label>
			<input type='text' class="form-control" id='editName' name='name' required>

			<label>Asset description</label>
			<textarea class="form-control" rows='3' style='resize:none' id='editDescription' name='description' minlength='25'></textarea>

			<label>Value</label>
			<input type='number' class="form-control" id='editValue' name='value' min='0' required>

			<label>Residual value</label>
			<input type='number' class='form-control' id='editResidualValue' name='residualValue' min='0' required>

			<label>Depreciation time (month)</label>
			<input type='number' class='form-control' min='0' id='editDepreciation' name='depreciation' required>


			<label>Type</label>
			<button type='button' class='form-control' id='editAssetTypeButton' style='text-align:left'></button>
			<input type='hidden' id='editAssetType' name='assetType' required><br>

			<button type='button' class='button button_default_dark' title='Edit Asset' id='editAssetButton'><i class='fa fa-long-arrow-right'></i></button>
			<div class='notificationText danger' id='failedInsertAsset'><p>Failed to update asset data.</p></div>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='selectClassWrapper'>
	<div class='alert_box_full'>
		<button class='button alert_full_close_button'>&times;</button>
		<h3 style='font-family:bebasneue'>Select Class</h3>
		<hr>
		<input type='text' class='form-control' id='classSearchBar'><br>
		<div id='assetClassTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Name</th>
					<th>Description</th>
					<th>Action</th>
				</tr>
				<tbody id='assetClassTableContent'></tbody>
			</table>

			<select class='form-control' id='classPage' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='assetClassTableText'>There is no class found.</p>
	</div>
</div>

<div class='alert_wrapper' id='sellAssetWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Sell an asset</h3>
		<hr>
		<form id='sellAssetForm'>
			<label>Date aquired</label>
			<p id='sellDate_p'></p>

			<label>Asset name</label>
			<p id='sellName_p'></p>

			<label>Asset description</label>
			<p id='sellDescription_p'></p>

			<label>Value</label>
			<p>Rp. <span id='sellValue_p'></span></p>

			<label>Residual value</label>
			<p>Rp. <span id='sellResidualValue_p'></span></p>

			<label>Depreciation time</label>
			<p id='sellDepreciation_p'></p>

			<label>Current residual value</label>
			<p>Rp. <span id='sellCurrentResidualValue_p'></span></p>

			<label>Type</label>
			<p id='sellType_p'></p>

			<label>Sold date</label>
			<input type='hidden' id='sellId' name='id'>
			<input type='date' class='form-control' id='soldDate' name='date' required><br>

			<button type='button' class='button button_default_dark' title='Sell Asset' id='sellAssetButton'><i class='fa fa-long-arrow-right'></i></button>
			<div class='notificationText danger' id='failedSellAsset'><p>Failed to update asset data.</p></div>
		</form>
	</div>
</div>

<script>
	var mode;
	$('#addAssetForm').validate({
		ignore:"",
		rules: {"hidden_field": {required: true}}
	});
	$('#editAssetForm').validate({
		ignore:"",
		rules: {"hidden_field": {required: true}}
	});

	$('#sellAssetForm').validate();

	$(document).ready(function(){
		refreshView();
	})

	$('#page').change(function(){
		refreshView();
	})

	$('#search_bar').change(function(){
		refreshView(1);
	})

	$('#addAssetButton').click(function(){
		$('#addAssetWrapper').fadeIn(300, function(){
			$('#addAssetWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	});

	$('#sellAssetButton').click(function(){
		if($('#sellAssetForm').valid()){
			$.ajax({
				url:"<?= site_url('Asset/setSoldById') ?>",
				data:{
					id: $('#sellId').val(),
					date: $('#soldDate').val()
				},
				type:"POST",
				beforeSend:function(){
					$('button').attr('disabled', true);
					$('input').attr('readonly', true);
				},
				success:function(response){
					$("button").attr('disabled', false);
					$('input').attr('readonly', false);
					refreshView();
					if(response == 1){
						$('#sellAssetWrapper .slide_alert_close_button').click();
					} else {
						$('#failedSellAsset').fadeIn(250);
						setTimeout(function(){
							$('#failedSellAsset').fadeOut(250);
						}, 1000)
					}
				}
			})
		}
	})

	$('#addAssetTypeButton').click(function(){
		mode = 1;
		$('#classSearchBar').val("");
		refreshClassView(1);
		$('#selectClassWrapper').fadeIn();
	})

	$('#editAssetTypeButton').click(function(){
		mode = 2;
		$('#classSearchBar').val("");
		refreshClassView(1);
		$('#selectClassWrapper').fadeIn();
	})

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Asset/getExistingItems') ?>",
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

					$('#assetTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td>" + description + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td><button class='button button_success_dark' onclick='openEditAsset(" + id + ")'><i class='fa fa-pencil'></i></button> <button class='button button_danger_dark' onclick='setAsSold(" + id + ")'><i class='fa fa-credit-card'></i></button></tr>")
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

	$('#classSearchBar').change(function(){
		refreshClassView(1);
	});

	$('#classPage').change(function(){
		refreshClassView();
	})

	function refreshClassView(page = $("#classPage").val()){
		$.ajax({
			url:"<?= site_url('Asset/getClassItems') ?>",
			data:{
				page: page,
				term: $('#classSearchBar').val()
			},
			success:function(response){
				var items = response.types;
				$('#assetClassTableContent').html("");
				var assetClassCount = 0;
				$.each(items, function(index, item){
					var name = item.name;
					var description = item.description;
					var id = item.id;
					if(mode == 1){
						$('#assetClassTableContent').append("<tr><td>" + name + "</td><td>" + description + "</td><td><button class='button button_default_dark' id='addAssetClassButton-" + id + "'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
						$('#addAssetClassButton-' + id).click(function(){
							$('#assetType').val(id);
							$('#addAssetTypeButton').html(name);
							$('#selectClassWrapper .alert_full_close_button').click();
						})
					} else if(mode == 2){
						$('#assetClassTableContent').append("<tr><td>" + name + "</td><td>" + description + "</td><td><button class='button button_default_dark' id='editAssetClassButton-" + id + "'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
						$('#editAssetClassButton-' + id).click(function(){
							$('#editAssetType').val(id);
							$('#editAssetTypeButton').html(name);
							$('#selectClassWrapper .alert_full_close_button').click();
						})
					}
					assetClassCount++;
				})
				
				var pages = response.pages;
				$('#classPage').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#classPage').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#classPage').append("<option value='" + i + "'>" + i + "</option>");
					}
				}

				if(assetClassCount > 0){
					$('#assetClassTable').show();
					$('#assetClassTableText').hide();
				} else {
					$('#assetClassTable').hide();
					$('#assetClassTableText').show();
				}
			}
		})
	}

	function setAsSold(n){
		$.ajax({
			url:"<?= site_url('Asset/getById') ?>",
			data:{
				id:n
			},
			success:function(response){
				$('#sellId').val(n);

				var date = response.date;
				var name = response.name;
				var description = response.description;
				var assetType = response.assetType;
				var residualValue = parseFloat(response.residual_value);
				var value = parseFloat(response.value);
				var depreciation = parseInt(response.depreciation_time);

				var difference = (new Date().getFullYear()*12 + new Date().getMonth()) - (new Date(date).getFullYear()*12 + new Date(date).getMonth());

				if(difference <= 0 || depreciation == 0){
					var estimatedCurrentValue = value;
				} else {
					var estimatedCurrentValue = value - value * (difference / depreciation);
				}

				$('#sellDate_p').html(my_date_format(date));
				$('#sellName_p').html(name);
				$('#sellDescription_p').html(description);
				$('#sellType_p').html(description);
				$('#sellResidualValue_p').html(numeral(residualValue).format('0,0.00'));
				$('#sellValue_p').html(numeral(value).format('0,0.00'));
				$('#sellDepreciation_p').html(numeral(depreciation).format('0,0') + " month(s)");
				$('#sellCurrentResidualValue_p').html(numeral(estimatedCurrentValue).format('0,0.00'));

				$('#sellAssetWrapper').fadeIn(300, function(){
					$('#sellAssetWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function openEditAsset(n){
		$.ajax({
			url:"<?= site_url('Asset/getById') ?>",
			data:{
				id:n
			},
			success:function(response){
				$('#editAssetId').val(n);
				$('#editName').val(response.name);
				$('#editDescription').val(response.description);
				$('#editAssetType').val(response.type);
				$('#editAssetTypeButton').html(response.typeName);
				$('#editResidualValue').val(response.residue_value);
				$('#editDepreciation').val(response.depreciation_time);
				$('#editValue').val(response.value);
				$("#editDate").val(response.date);

				$('#editAssetWrapper').fadeIn(300, function(){
					$('#editAssetWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	$('#insertAssetButton').click(function(){
		if($('#addAssetForm').valid()){
			$.ajax({
				url:"<?= site_url('Asset/insertItem') ?>",
				data:$('#addAssetForm').serialize(),
				type:'POST',
				beforeSend:function(){
					$('button').attr('disabled', true);
					$('input').attr('readonly', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					$('input').attr('readonly', false);
					refreshView();
					if(response == 1){
						$('#addAssetForm').trigger("reset");
						$('#addAssetTypeButton').html("");
						$('#addAssetWrapper .slide_alert_close_button').click();
					} else {
						$('#failedInsertAsset').fadeIn(250);
						setTimeout(function(){
							$('#failedInsertAsset').fadeOut(250);
						}, 1000)
					}
				}
			})
		}
	})

	$('#editAssetButton').click(function(){
		if($('#editAssetForm').valid()){
			$.ajax({
				url:"<?= site_url('Asset/updateById') ?>",
				data:$('#editAssetForm').serialize(),
				type:'POST',
				beforeSend:function(){
					$('button').attr('disabled', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					refreshView();
					if(response == 1){
						$('#editAssetWrapper .slide_alert_close_button').click();
					} else {
						$('#failedUpdateAsset').fadeIn(250);
						setTimeout(function(){
							$('#failedUpdateAsset').fadeOut(250);
						}, 1000)
					}
				}
			})
		}
	});

	$('.alert_full_close_button').click(function(){
		$(this).parent().parent().fadeOut();
	})
</script>
