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

			<label>Depreciation time</label>
			<input type='number' class='form-control' min='0' id='depreciation' name='depreciation' required>

			<label>Type</label>
			<select class='form-control' id='assetType' name='assetType' required>
			</select><br>

			<button type='button' class='button button_default_dark' title='Add Asset' id='insertAssetButton'><i class='fa fa-long-arrow-right'></i></button>
			<div class='notificationText danger' id='failedInsertAsset'><p>Failed to insert asset data.</p></div>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='editAssetWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Add an asset</h3>
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

			<label>Depreciation time</label>
			<input type='number' class='form-control' min='0' id='editDepreciation' name='depreciation' required>


			<label>Type</label>
			<select class='form-control' id='editAssetType' name='assetType' required>
			</select><br>

			<button type='button' class='button button_default_dark' title='Edit Asset' id='editAssetButton'><i class='fa fa-long-arrow-right'></i></button>
			<div class='notificationText danger' id='failedInsertAsset'><p>Failed to update asset data.</p></div>
		</form>

<script>
	$('#addAssetForm').validate();
	$('#editAssetForm').validate();

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
		$.ajax({
			url:"<?= site_url('Asset/getAllTypes') ?>",
			success:function(response){
				$('#assetType').html('');
				$.each(response, function(index, value){
					var id = value.id;
					var name = value.name;
					$('#assetType').append("<option value='" + id + "'>" + name + "</option>");
				});

				$('#addAssetWrapper').fadeIn(300, function(){
					$('#addAssetWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	});

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

					$('#assetTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td>" + description + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td><button class='button button_default_dark' onclick='viewAsset(" + id + ")'><i class='fa fa-eye'></i></button> <button class='button button_success_dark' onclick='openEditAsset(" + id + ")'><i class='fa fa-pencil'></i></button></tr>")
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

	function openEditAsset(n){
		$.ajax({
			url:"<?= site_url('Asset/getAllTypes') ?>",
			success:function(response){
				$('#editAssetType').html('');
				$.each(response, function(index, value){
					var id = value.id;
					var name = value.name;
					$('#editAssetType').append("<option value='" + id + "'>" + name + "</option>");
				});
			}
		});

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
				},
				success:function(response){
					$('button').attr('disabled', false);
					refreshView();
					if(response == 1){
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
	})

	function viewAsset(n){
		$.ajax({
			url:"<?= site_url('Asset/getById') ?>",
			data:{
				id:n
			},
			success:function(response){
				console.log(response);
			}
		})
	}
</script>
