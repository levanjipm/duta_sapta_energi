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
			
			#printButton{
				display:none!important;
			}

			.dashboard_in{
				visibility:visible;
				width:100%;
				position:absolute;
				top:0;
				left:0;
			}
		}

		hr{
			margin-top:0;
		}

		.placeholder_wrapper{
			height:3rem;
			background-image:linear-gradient(to right, rgba(255, 0, 0, 0.2), rgba(0, 255, 0, 0.2));
			border:1px solid #ccc;
			width:100%;
			border-radius:5px;
			position:relative;
			display:block;
			box-shadow:3px 3px 3px rgba(50, 50, 50, 0.8);
		}

		.placeholder_wraper__minvalue{
			font-family:Museo;
			color:black;
			font-size:12px;
			position:relative;
			float:left;
			top:50%;
			padding-left:0.5rem;
			transform:translateY(-50%);
		}

		.placeholder_wraper__maxvalue{
			font-family:Museo;
			color:black;
			font-size:12px;
			position:relative;
			float:right;
			top:50%;
			padding-right:0.5rem;
			transform:translateY(-50%);
		}

		.lineIndicator{
			width:3px;
			border-right:2px solid #E19B3C;
			position:absolute;
			height:3rem;
		}
	</style>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
		let brands = <?= json_encode($sales['brands']) ?>;
		let brandsArray = [['Brands', 'Value']];
		brands.forEach(brand => {
			brandsArray.push([brand.name, brand.value]);
		})
		google.charts.load('current', {'packages':['corechart']});
		google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable(brandsArray);
        var options = { };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }

	  $('document').ready(function(){
		  let customers = <?= json_encode($sales['customers']) ?>;
		  let customerCount = 0;

		  customers.forEach(customer => {
			  $('#customerTableBody').append("<tr><td>" + customer.name + "</td><td>" + customer.area + "</td><td>Rp. " + numeral(customer.value).format('0,0.00') + "</td></tr>");
			  customerCount++;
		  });

		  $('#customerCount').html(numeral(customerCount).format('0,0'));

		  let salesOrders = <?= json_encode($salesOrders) ?>;
		  let salesOrdersValue = 0;
		  let salesOrdersCount = salesOrders.length;
		  let salesOrderItemCount = 0;

		  let rangeMinSalesOrderValue = 0;
		  let rangeMaxSalesOrderValue = 0;

		  let rangeMinSalesOrderCount = 0;
		  let rangeMaxSalesOrderCount = 0;
		  
		  salesOrders.forEach(salesOrder => {
			let count = parseInt(salesOrder.count);
			let value = parseFloat(salesOrder.value);
			salesOrdersValue += value;
			salesOrderItemCount += count;
			
			if(rangeMinSalesOrderValue == 0 || rangeMinSalesOrderValue > value){
				rangeMinSalesOrderValue = value;
			}

			if(value > rangeMaxSalesOrderValue){
				rangeMaxSalesOrderValue = value;
			}

			if(rangeMinSalesOrderCount == 0 || rangeMinSalesOrderCount > count){
				rangeMinSalesOrderCount = count;
			}

			if(count > rangeMaxSalesOrderCount){
				rangeMaxSalesOrderCount = count;
			}
		  });

		  $('#rangeMaxSalesOrderValue').html("Rp. " + numeral(rangeMaxSalesOrderValue).format("0,0.00"));
		  $('#rangeMinSalesOrderValue').html("Rp. " + numeral(rangeMinSalesOrderValue).format("0,0.00"));

		  $('#rangeMaxSalesOrderCount').html(numeral(rangeMaxSalesOrderCount).format("0,0"));
		  $('#rangeMinSalesOrderCount').html(numeral(rangeMinSalesOrderCount).format("0,0"));

		  $('#placeholder_minValue').html("Rp. " + numeral(rangeMinSalesOrderValue).format("0,0.00"));
		  $('#placeholder_maxValue').html("Rp. " + numeral(rangeMaxSalesOrderValue).format("0,0.00"));
		  
		  let salesOrdersMeanValue = salesOrdersValue / salesOrdersCount;
		  $('#salesOrderValue').html("<strong>Rp. " + numeral(salesOrdersMeanValue).format('0,0.00') + "</strong>");

		  if(rangeMinSalesOrderValue > 0){
			$('#valueMeanIndicator').css('left', (salesOrdersMeanValue - rangeMinSalesOrderValue) / (rangeMaxSalesOrderValue - rangeMinSalesOrderValue) * 100 + "%");
			$('#valueMeanIndicator').attr('title', "Average : Rp." + numeral(salesOrdersMeanValue).format('0,0.00'));
		  } else {
			  $('#valueMeanIndicator').hide();
		  }

		  let salesOrderMeanCount = salesOrderItemCount / salesOrdersCount;

		  $('#salesOrderItemCount').html(numeral(salesOrderMeanCount).format("0,0.00"));
		  $('#placeholder_minCount').html(numeral(rangeMinSalesOrderCount).format("0,0"));
		  $('#placeholder_maxCount').html(numeral(rangeMaxSalesOrderCount).format("0,0"));

		  if(rangeMinSalesOrderCount > 0){
			  $('#itemCountMeanIndicator').css('left', (salesOrderMeanCount - rangeMinSalesOrderCount) / (rangeMaxSalesOrderCount - rangeMinSalesOrderCount) * 100 + "%");
			  $('#itemCountMeanIndicator').attr('title', "Average : Rp." + numeral(salesOrdersMeanValue).format('0,0.00'));
		  } else {
			  $('#itemCountMeanIndicator').hide();
		  }
	  })

	  $(function () {
	  	$('[data-toggle="tooltip"]').tooltip()
	  });
    </script>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-line-chart'></i></a> /<a href='<?= site_url('SalesAnalytics') ?>'>Analytics</a> / Salesman Report</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='row'>
			<div class='col-xs-4 col-xs-offset-4'>
                <img src='<?= base_url('assets/Logo_dark.png') ?>' style='width:40%;margin-left:30%'></img>
            </div>
			<div class='col-xs-12'>
				<hr style='border-top:4px solid #424242;margin:0;'>
				<hr style='border-top:2px solid #E19B3C;margin:0;'>
			</div>
			<div class='col-xs-12'>
				<button 
					type='button' 
					class='button button_mini_tab'
					id='printButton'
					onclick='window.print()'>
					<i class='fa fa-print'></i> Print
				</button>
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
			<div class='col-xs-12'>
				<label>Brands</label>
				<hr>
			</div>
			<div class='col-xs-6'>
	  			<div id='piechart'></div>
			</div>
			<div class='col-xs-6'>
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
				<hr>
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
				
				<label>Customers</label>
				<hr>
				<table class='table table-bordered'>
					<tr>
						<th>Customer</th>
						<th>Area</th>
						<th>Value</th>
					</tr>
					<tbody id='customerTableBody'></tbody>
				</table>

				<label>Sales Order</label>
				<p><?= number_format(count($salesOrders), 0) ?> sales orders created</p>
				<p><span id='customerCount'></span> customer(s) ordered</p>
				<p><span id='salesOrderValue'></span> average sales order value</p>
				<p>Ranging from <span id='rangeMinSalesOrderValue'></span> to <span id='rangeMaxSalesOrderValue'></span></p>
				<div class='placeholder_wrapper'>
						<p 
							class='placeholder_wraper__minvalue'
							id='placeholder_minValue'></p>
						<div 
							class='lineIndicator' 
							id='valueMeanIndicator' 
							data-toggle="tooltip" 
							data-placement="top" 
							title=""></div>
						<p 
							class='placeholder_wraper__maxvalue'
							id='placeholder_maxValue'></p>
				</div>
				<hr>
				<p><span id='salesOrderItemCount'></span> average sales order item</p>
				<p>Ranging from <span id='rangeMinSalesOrderCount'></span> to <span id='rangeMaxSalesOrderCount'></span></p>
				<div class='placeholder_wrapper'>
						<p 
							class='placeholder_wraper__minvalue'
							id='placeholder_minCount'></p>
						<div 
							class='lineIndicator' 
							id='itemCountMeanIndicator' 
							data-toggle="tooltip" 
							data-placement="top" 
							title=""></div>
						<p 
							class='placeholder_wraper__maxvalue'
							id='placeholder_maxCount'></p>
				</div>
            </div>
        </div>
    </div>
</div>