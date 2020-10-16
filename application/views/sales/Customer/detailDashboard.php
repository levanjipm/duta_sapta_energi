<head>
	<?php
		$complete_address		= '';
		$customer_name			= $customer->name;
		$complete_address		.= $customer->address;
		$customer_city			= $customer->city;
		$customer_number		= $customer->number;
		$customer_rt			= $customer->rt;
		$customer_rw			= $customer->rw;
		$customer_postal		= $customer->postal_code;
		$customer_block			= $customer->block;
		
		if($customer_number != null){
			$complete_address	.= ' No. ' . $customer_number;
		}
					
		if($customer_block != null && $customer_block != "000"){
			$complete_address	.= ' Blok ' . $customer_block;
		}
				
		if($customer_rt != '000'){
			$complete_address	.= ' RT ' . $customer_rt;
		}
					
		if($customer_rw != '000' && $customer_rt != '000'){
			$complete_address	.= ' /RW ' . $customer_rw;
		}
					
		if($customer_postal != null){
			$complete_address	.= ', ' . $customer_postal;
		}
	?>
	<title><?= $customer->name ?> Detail</title>
	<style>
		.progressBarWrapper{
			width:100%;
			height:30px;
			background-color:white;
			border-radius:10px;
			padding:5px;
			position:relative;
		}

		.progressBar{
			width:0;
			height:20px;
			background-color:#01bb00;
			position:relative;
			border-radius:10px;
			opacity:0.2;
		}

		.progressBarWrapper p{
			font-family:museo;
			color:black;
			font-weight:"bold";
			z-index:50;
			position:absolute;
			right:5px;
		}
	</style>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script>
		google.charts.load('current', {'packages':["corechart", "line"]});
		google.charts.setOnLoadCallback(function(){
			$.ajax({
				url:"<?= site_url('SalesAnalytics/getSalesOrderByCustomerId') ?>",
				data:{
					id: <?= $customer->id ?>
				},
				dataType: "json",
				success:function(response){
					var monthArray = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
					var data = new google.visualization.DataTable();
					data.addColumn('string', 'X');
					data.addColumn('number', 'Sales');
					var dataArray = [];
					$.each(response, function(index, item){
						var date = index;
						var year = parseInt(date.substring(2,4));
						var month = parseInt(date.substring(5,7));
						var value = parseInt(item.value);
						dataArray.push([monthArray[month] + " " + year, value]);
					});
					data.addRows(dataArray);

					var options = {
						width: $('#rightDiv').width(),
						colors:['#E19B3C'],
						lineWidth: 4,
						pointSize: 10,
					};
					var chart = new google.visualization.LineChart(document.getElementById('chartWrapper'));
					chart.draw(data, options);
				}
			});
		});
	</script>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Customer') ?>'>Customer</a> / <?= $customer->name ?></p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='row'>
			<div class='col-md-4 col-sm-12 col-xs-12'>
				<label>General data</label>
				<p><?= $customer_name ?></p>
				<p><?= $complete_address ?>, <?= $customer_city ?></p>
				<p><?= $area->name ?></p>
				<p><?= $customer->npwp ?></p>
				<label>Location</label>
				<p>Latitude: <?= ($customer->latitude == null || $customer->latitude == "")? "<i>Not available</i>" : number_format($customer->latitude, 8) ?></p>
				<p>Longitude: <?= ($customer->longitude == null || $customer->longitude == "")? "<i>Not available</i>" : number_format($customer->longitude, 8) ?></p>
				<?php if($customer->latitude != "" && $customer->latitude != NULL && $customer->longitude != "" && $customer->longitude != null){ ?><a href='https://maps.google.com/?q=<?= number_format($customer->latitude,8) ?>,<?= number_format($customer->longitude,8) ?>' target='_blank'><i class='fa fa-location-arrow'></i> View on Maps</a><br><br><?php } ?>

				<label>Contact</label>
				<p><?= $customer->pic_name ?></p>
				<p><?= $customer->phone_number ?></p>

				<label>Plafond</label>
				<p>Rp. <?= number_format($customer->plafond, 2) ?></p>

				<label>Term of payment</label>
				<p><?= number_format($customer->term_of_payment) ?></p>

				<label>Unique ID</label>
				<p><?= $customer->uid ?></p>
			</div>
			<div class='col-md-8 col-sm-12 col-xs-12' id='rightDiv'>
				<button class='button button_mini_tab' id='salesOrderButton'>Pending Sales Orders</button>
				<button class='button button_mini_tab' id='receivableButton'>Receivable</button>
				<button class='button button_mini_tab' id='analyticButton'>Analytics</button>
				<br><br>
				<div id='pendingSalesOrderTableWrapper' class='viewWrapper'>
					<div id='pendingSalesOrderTable'>
						<table class='table table-bordered'>
							<tr>
								<th>Date</th>
								<th>Information</th>
								<th>Action</th>
							</tr>
							<tbody id='pendingSalesOrderTableContent'></tbody>
						</table>
					</div>
					<p id='pendingSalesOrderTableText'>There is no pending sales order found.</p>

					<a href="<?= site_url('Customer/salesOrderArchive/') . $customer->id ?>" role='button' class='button button_default_dark'><i class='fa fa-history'></i></a>
				</div>
				<div id='receivableTableWrapper' class='viewWrapper'>
					<label>Receivable value</label>
					<p>Rp. <span id='receivableValue_p'></span></p>
					<div id='receivableTable' class='table-responsive-lg'>
						<table class='table table-bordered'>
							<tr>
								<th>Date</th>
								<th>Name</th>
								<th>Value</th>
								<th>Paid</th>
								<th>Remainder</th>
							</tr>
							<tbody id='receivableTableContent'></tbody>
						</table>
					</div>

					<p id='receivableTableText'>There is no receivable found.</p>
				</div>
				<div id='analyticsWrapper' class='viewWrapper'>
					<div id='chartWrapper'></div>
					<label>Target History</label>
					<table class='table table-bordered'>
						<tr>
							<th>Effective Date</th>
							<th>Value</th>
						</tr>
						<tbody id='targetTableContent'></tbody>
					</table>

					<button class='button button_default_dark' id='viewDetailAnalyticsButton'><i class='fa fa-eye'></i></button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='viewDetailAnalyticsWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Analytics Detail</h3>
		<hr>
		<table class='table table-bordered'>
			<tr>
				<th>Item Type</th>
				<th>Value</th>
			</tr>
			<tbody id='itemTypeTableContent'></tbody>
		</table>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('#salesOrderButton').click();
	});

	$('#salesOrderButton').click(function(){
		$('.button_mini_tab').attr('disabled', false);
		$('.button_mini_tab').removeClass('active');
		$('.viewWrapper').hide(0, function(){
			$('#pendingSalesOrderTableWrapper').fadeIn(300);
		});

		$(this).attr('disabled', true);
		$(this).addClass('active');

		fetchIncompletedSalesOrders();
	});

	$('#receivableButton').click(function(){
		$('.button_mini_tab').attr('disabled', false);
		$('.button_mini_tab').removeClass('active');
		$('.viewWrapper').hide(0, function(){
			$('#receivableTableWrapper').fadeIn(300);
		});

		$(this).attr('disabled', true);
		$(this).addClass('active');

		fetchReceivable();
	});

	$('#analyticButton').click(function(){
		$('.button_mini_tab').attr('disabled', false);
		$('.button_mini_tab').removeClass('active');
		$('.viewWrapper').hide(0, function(){
			$('#analyticsWrapper').fadeIn(300);
		});

		$(this).attr('disabled', true);
		$(this).addClass('active');

		fetchAnalytics();
	});

	function fetchIncompletedSalesOrders(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Sales_order/getIncompletedSalesOrdersByCustomerId') ?>",
			data:{
				customerId: <?= $customer->id ?>,
			},
			success:function(response){
				$('#pendingSalesOrderTableContent').html("");
				var salesOrderCount = 0;
				$.each(response, function(index, value){
					var date = value.date;
					var name = value.name;
					var seller = (value.seller == null) ? "<i>Not available</i>" : value.seller;
					$('#pendingSalesOrderTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><p>" + name + "</p><p>Seller : " + seller + "</p></td><td><button class='button button_default_dark'><i class='fa fa-eye'></i></button></td></tr>");
					salesOrderCount++;
				});

				if(salesOrderCount > 0){
					$('#pendingSalesOrderTable').show();
					$('#pendingSalesOrderTableText').hide();
				} else {
					$('#pendingSalesOrderTableText').show();
					$('#pendingSalesOrderTable').hide();
				}
			}
		})
	}

	function fetchReceivable(){
		$.ajax({
			url:"<?= site_url('Receivable/getReceivableByCustomerId') ?>",
			data:{
				id: <?= $customer->id ?>
			},
			success:function(response){
				var pendingBankData = 0;
				var receivableCount = 0;
				var pendingBanks = response.pendingBank;
				$.each(pendingBanks, function(index, pendingBank){
					pendingBankData += parseFloat(pendingBank.value);
				});

				if(pendingBankData > 0){
					receivableCount++;
				}

				var receivables = response.receivable;
				$('#receivableTableContent').html("");
				var totalValue = 0;
				$.each(receivables, function(index, receivable){
					var date = receivable.date;
					var name = receivable.name;
					var taxInvoice = (receivable.taxInvoice == null || receivable.taxInvoice == "") ? "<i>Not available</i>" : receivable.taxInvoice;
					var paid = parseFloat(receivable.paid);
					var value = parseFloat(receivable.value);
					var remainder = value - paid;
					totalValue += remainder;

					$('#receivableTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Rp. " + numeral(paid).format('0,0.00') + "</td><td>Rp. " + numeral(remainder).format('0,0.00') + "</td></tr>");
					receivableCount++;
				});

				if(pendingBankData > 0){
					totalValue -= pendingBankData;
					$('#receivableTableContent').append("<tr><td colspan='3'><label>Pending Bank Data</td><td>Rp. " + numeral(pendingBankData).format('0,0.00') + "</td></td><td>Rp. " + numeral(0).format('0,0.00') + "</td></tr>");
				}

				$('#receivableValue_p').html(numeral(totalValue).format('0,0.00'));

				if(receivableCount > 0){
					$('#receivableTable').show();
					$('#receivableTableText').hide();
				} else {
					$('#receivableTable').hide();
					$('#receivableTableText').show();
				}
			}
		})
	}

	function fetchAnalytics(){
		$.ajax({
			url:"<?= site_url('SalesAnalytics/getByCustomerId') ?>",
			data:{
				id: <?= $customer->id ?>
			},
			success:function(response){
				var target = response.target;
				$('#targetTableContent').html("");
				$.each(target, function(index, item){
					var value = item.value;
					var dateCreated = item.dateCreated;
					$('#targetTableContent').append("<tr><td>" + my_date_format(dateCreated) + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td></tr>");
				});

				$('#calculationType').val(1);
			}
		})
	}

	function fetchItemType(type = $('#calculationType').val()){
		$.ajax({
			url:"<?= site_url('SalesAnalytics/getValueByItemType') ?>",
			data:{
				customerId: <?= $customer->id ?>,
				type: type
			},
			success:function(response){
				$('#itemTypeTableContent').html("");
				var total = 0;
				$.each(response, function(index, item){
					var id = item.id;
					var name = item.name;
					var value = parseFloat(item.value);
					var returned = parseFloat(item.returned);
					var totalValue = value - returned;
					total += totalValue;
					$('#itemTypeTableContent').append("<tr><td>" + name + "</td><td><div class='progressBarWrapper'><p id='progressBarText-" + id + "'></p><div class='progressBar' id='progressBar-" + id + "' data-value='" + totalValue + "'></div></div></td></tr>");
				});

				var timeout = 0;
				$('div[id^="progressBar-"]').each(function(){
					var id = $(this).attr('id');
					var uid = parseInt(id.substring(12, 267));
					var value = parseFloat($(this).attr('data-value'));
					var percentage = value * 100 / total;

					$(this).animate({
						width: (percentage) + "%"
					}, timeout);
					$("#progressBarText-" + uid).html(numeral(percentage).format('0,0.00') + "%");
					timeout += 300;
				})
			}
		})
	}

	$('#viewDetailAnalyticsButton').click(function(){
		fetchItemType();
		$('#viewDetailAnalyticsWrapper').fadeIn(300, function(){
			$('#viewDetailAnalyticsWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	})
</script>
