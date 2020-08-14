<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> /Asset</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<input type='text' class='form-control' id='search_bar'>
			<div class='input_group_append'>
				<button class="button button_default_dark" title="Add an asset" id='addAssetButton'>Add asset</button>
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
			<div class='notificationText danger' id='failedInsertAsset'><p>Failed to insert asset.</p></div>
		</form>
	</div>
</div>
<script>
	$('#addAssetForm').validate();

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

					$('#addAssetWrapper').fadeIn(300, function(){
						$('#addAssetWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
					});
				})
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

					$('#assetTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td>" + description + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td><button class='button button_default_dark'><i class='fa fa-eye'></i></button></tr>")
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
					refresh_view();
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

	
</script>
