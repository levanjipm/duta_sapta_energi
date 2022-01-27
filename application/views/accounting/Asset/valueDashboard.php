<head>
	<title>Asset - Value</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-bar-chart'></i></a> /<a href='<?= site_url('Asset') ?>'>Asset</a> / Value</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<form id='valueForm'>
			<label>Date</label>
			<div class='input_group'>
				<input type='date' class='form-control' id='date' required min='2020-01-01'>
				<div class='input_group_append'>
					<button type='button' class='button button_default_dark' id='calculateValueButton'><i class='fa fa-long-arrow-right'></i></button>
				</div>
			</div>
			<br>
			<div id='assetTable' style='display:none'>
				<table class='table table-bordered'>
					<tr>
						<th>Property</th>
						<th>Value</th>
						<th>Action</th>
					</tr>
					<tr>
						<td>Inventory value</td>
						<td>Rp. <span id='stockValueP'></span></td>
						<td>
							<button 
								type='button'
								class='button button_default_dark'>
								<i class='fa fa-eye'></i>
							</button>
						</td>
					</tr>
					<tr>
						<td>Fixed Asset value</td>
						<td>Rp. <span id='assetValueP'></span></td>
						<td>
							<button 
								type='button'
								onclick='checkFixedAssetValue()'
								class='button button_default_dark'>
								<i class='fa fa-eye'></i>
							</button>
						</td>
					</tr>
					<tr>
						<td>Total Asset value</td>
						<td colspan='2'>Rp. <span id='totalValueP'></span></td>
					</tr>
				</table>
			</div>
		</form>
	</div>
</div>

<div class="alert_wrapper" id='assetWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Asset Value</h3>
		<hr>
		<div class='table-responsive' id='assetTable'>
			<table class='table table-bordered'>
				<thead>
					<tr>
						<th>Date</th>
						<th>Name</th>
						<th>Description</th>
						<th>Initial Value</th>
						<th>Depreciation time</th>
						<th>Current Value</th>
					</tr>
				</thead>
				<tbody id='assetTableBody'></tbody>
			</table>
		</div>
	</div>
</div>

<script>
	var date;

	$('#valueForm').validate();
	$('#valueForm input').keydown(function (e) {
		return e.keyCode != 13;
	});

	$('#calculateValueButton').click(function(){
		if($('#valueForm').valid()){
			$.ajax({
				url:"<?= site_url('Asset/calculateAsset') ?>",
				data:{date: $('#date').val()},
				beforeSend:function(){
					$('input').attr('readonly', true);
					$('button').attr('disabled', true);

					date = $('#date').val();
				},
				success:function(response){
					$('input').attr('readonly', false);
					$('button').attr('disabled', false);
					var assetValue = response.asset;
					var stockValue = response.stock;
					var totalValue = assetValue + stockValue;

					$('#assetValueP').html(numeral(assetValue).format('0,0.00'));
					$('#stockValueP').html(numeral(stockValue).format('0,0.00'));
					$('#totalValueP').html(numeral(totalValue).format('0,0.00'));

					$('#assetTable').fadeIn(300);
				}
			})
		}
	})

	function checkFixedAssetValue(){
		$.ajax({
			url:"<?= site_url('Asset/getAssetListByDate') ?>",
			data:{
				date: date
			},
			beforeSend:function(){
				$('input').attr('readonly', true);
				$('button').attr('disabled', true);
			},
			success:function(response){
				if(response.length == 0){
					$('#assetTable').hide();
				} else {
					$.each(response, function(index, item){
						var months = (new Date(item.date).getFullYear() - (new Date()).getFullYear()) * 12;
						months -= (new Date()).getMonth();
						months += (new Date(item.date)).getMonth();
						months	= (-1) * months;

						var resValue = (item.value - item.residue_value) * (item.depreciation_time - months) / item.depreciation_time;

						$('#assetTableBody').append("<tr><td>" + my_date_format(item.date) + "</td><td>" + item.name + "</td><td>" + item.description + "</td><td>Rp. " + numeral(item.value).format('0,0.00') + "</td><td>" + numeral(item.depreciation_time).format('0,0') + " month(s)" + "</td><td>Rp. " + numeral(resValue).format('0,0.00') + "</td></tr>")
					})

					$('#assetTable').show();
				}
				
				$('#assetWrapper').fadeIn(300, function(){
					$('#assetWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});

				$('input').attr('readonly', false);
				$('button').attr('disabled', false);
			}
		})
	}
</script>
