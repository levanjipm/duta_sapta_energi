<head>
	<title>Manage items</title>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Item') ?>'>Items</a> / <?= $item->reference ?></p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='row'>
			<div class='col-sm-6 col-xs-12'>
				<label>Reference</label>
				<p><?= $item->reference ?></p>

				<label>Name</label>
				<p><?= $item->name ?></p>

				<label>Class</label>
				<p><?= $item->className ?></p>

				<label>Confidence Level</label>
				<p><?= number_format($item->confidence_level,2) ?>%</p>

				<table class='table table-hover'>
					<tr>
						<th>No.</th>
						<th>Value</th>
					</tr>
				<?php $i = 1; foreach($price as $item){ ?>
					<tr <?= ($i == count($price)) ? "class='success'" : "" ?>>
						<td><?= number_format($i) ?></td>
						<td>Rp. <?= number_format($item->price_list) ?></td>
					</tr>
				<?php $i++;} ?>
				</table>
			</div>
			<div class='col-sm-6 col-xs-12'>
				<label>Monthly Output</label>
				<canvas id='lineChart'></canvas>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		var ctx = document.getElementById('lineChart').getContext('2d');
		var stockArray		= <?= $stock ?>;
		var monthArray = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

		var currentDate		= new Date();
		var labelArray			= [];
		var valueArray			= [];

		$.each(stockArray, function(index, value){
			var difference		= index;
			var date =  new Date();
			date.setDate(1);
			date.setMonth(currentDate.getMonth() - difference);
			var label = monthArray[date.getMonth()] + " " + date.getFullYear();
			labelArray.push(label);

			var value			= value;
			valueArray.push(value);
		});

		valueArray.reverse();
		labelArray.reverse();
		var myLineChart = new Chart(ctx, {
			type: 'line',
			data: {
				labels: labelArray,
				datasets: [{
					backgroundColor: 'rgba(225, 155, 60, 0.4)',
					borderColor: 'rgba(225, 155, 60, 1)',
					data: valueArray
				}],
			},
			options: {
				legend:{
					display:false
				}
			}
		});
	});
</script>