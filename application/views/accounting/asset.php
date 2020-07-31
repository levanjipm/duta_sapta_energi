<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> /Asset</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<button class='button button_mini_tab' id='inventoryTab' onclick='loadTab(1)'>Operational</button>
		<button class='button button_mini_tab' id='nonInventoryTab' onclick='loadTab(2)'>Non-operational</button>
		<hr>
		<div class='row'>
			<div class='col-xs-12' id='inventoryView' style='display:none'>
				<label>Date</label>
				<input type='date' class='form-control' id='date'>
				<br>
				<label>Value</label>
				<p id='operationalValue'></p>
			</div>
			<div class='col-xs-12' id='nonInventoryView' style='display:none'>
				<button class="button button_default_dark" title="Add an asset" id='addAssetButton'>Add an asset</button>
				<br><br>
				<label>Date</label>
				<input type='date' class='form-control' id='date'>
				<br>
				<label>Value</label>
				<p id='nonOperationalValue'></p>
			</div>
		</div>
	</div>
</div>

<div class="alert_wrapper" id='addAssetWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Add an asset</h3>
		<hr>
		<label>Date aquired</label>
		<input type="date" class="form-control" id='date' required min='2000-01-01'>

		<label>Asset name</label>
		<input type='text' class="form-control" id='name' required>

		<label>Asset description</label>
		<textarea class="form-control" rows='3' style='resize:none' id='description' minlength='25'></textarea>

		<label>Value</label>
		<input type='number' class="form-control" id='value' min='0' required>

		<label>Residual value</label>
		<input type='number' class='form-control' id='residualValue' min='0' required>

		<label>Depreciation time</label>
		<input type='number' class='form-control' min='0' required>

		<label>Type</label>
		<select class='form-control' id='assetType' required>
		</select><br>

		<div class='notificationText danger' id='failedInsertAsset'><p>Failed to insert asset.</p></div>

		<button type='button' class='button button_default_dark' title='Add Asset' id='insertAssetButton'><i class='fa fa-long-arrow-right'></i></button>
	</div>
</div>
<script>
	$('document').ready(function(){
		loadTab(1);
	})
	var method = 1;

	function loadTab(event){
		$('.button_mini_tab').removeClass('active');
		$('.button_mini_tab').attr('disabled', false);

		if(event == 1){
			$('#inventoryTab').attr('disabled', true);
			$('#inventoryTab').addClass('active');
			$('#nonInventoryView').fadeOut(250);
			setTimeout(function(){
				$('#inventoryView').fadeIn(250);
			}, 250);
		} else {
			$('#nonInventoryTab').attr('disabled', true);
			$('#nonInventoryTab').addClass('active');
			$('#inventoryView').fadeOut(250);
			setTimeout(function(){
				$('#nonInventoryView').fadeIn(250);
			}, 250);
		}
	}

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

	$('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
</script>
