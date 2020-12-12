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
						<td><button class='button button_default_dark'><i class='fa fa-eye'></i></button>
					</tr>
					<tr>
						<td>Fixed Asset value</td>
						<td>Rp. <span id='assetValueP'></span></td>
						<td><button class='button button_default_dark'><i class='fa fa-eye'></i></button>
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

<script>
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
</script>
