<head>
    <title>Sales analytics - Salesman Report</title>
	<style>
		@page {
			size: A4;
		}

		@media print{
			body{
				visibility:hidden;
			}
			
			button{
				display:none;
			}

			.dashboard_in{
				visibility:visible;
				width:100%;
				position:absolute;
				top:0;
				left:0;
			}
		}
	</style>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['Work',     11],
          ['Eat',      2],
          ['Commute',  2],
          ['Watch TV', 2],
          ['Sleep',    7]
        ]);

        var options = {
          title: 'My Daily Activities'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-line-chart'></i></a> /<a href='<?= site_url('SalesAnalytics') ?>'>Analytics</a> / Salesmanb Report</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='row'>
			<div class='col-xs-4 col-xs-offset-4'>
                <img src='<?= base_url('assets/Logo_dark.png') ?>' style='width:40%;margin-left:30%'></img>
            </div>
            <div class='col-xs-12'>
                <label>Salesman</label>
                <p><?= $salesman->name ?></p>
                <p><?= $salesman->email ?></p>
            </div>
            <div class='col-xs-12'>
                <label>Value</label>
                <p>Rp. <?= number_format($sales['value'], 2) ?></p>
			</div>
			<div class='col-xs-6'>
			</div>
			<div class='col-xs-6'>
				<label>Brands</label>
				<table class='table table-bordered'>
					<tr>
						<th>Brand</th>
						<th>Value</th>
					</tr>
					<?php foreach($sales['brands'] as $brandItem){ ?>
						<tr>
							<td><?= $brandItem['name'] ?></td>
							<td>Rp. <?= number_format($brandItem['value'],2) ?></td>
						</tr>
					<?php } ?>
				</table>
            </div>
			<div class='col-xs-12'>
				<label>Item Type</label>
				<table class='table table-bordered'>
					<tr>
						<th>Brand</th>
						<th>Value</th>
					</tr>
					<?php foreach($sales['types'] as $typeItem){ ?>
						<tr>
							<td><?= $typeItem['name'] ?></td>
							<td>Rp. <?= number_format($typeItem['value'],2) ?></td>
						</tr>
					<?php } ?>
				</table>

				<table class='table table-bordered'>
					<tr>
						<th>Brand</th>
						<th>Value</th>
					</tr>
					<?php foreach($sales['customers'] as $customerItem){ ?>
						<tr>
							<td><?= $customerItem['name'] ?></td>
							<td>Rp. <?= number_format($customerItem['value'],2) ?></td>
						</tr>
					<?php } ?>
				</table>
            </div>
        </div>
    </div>
</div>