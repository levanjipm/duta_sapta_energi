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
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
	<script>
		$(document).ready(function(){
			$.ajax({
				url:"<?= site_url('SalesAnalytics/getSalesOrderByCustomerId') ?>",
				data:{
					id: <?= $customer->id ?>
				},
				dataType: "json",
				success:function(response){
					var monthArray = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
					var dataArray = [];
					var labelArray = [];
					$.each(response, function(index, item){
						var date = index;
						var year = parseInt(date.substring(2,4));
						var month = parseInt(date.substring(4,6));
						var value = parseFloat(item.value);
						dataArray.push(value);
						labelArray.push(monthArray[month - 1] + " " + year);
					});

					var ctx = document.getElementById('chartWrapper').getContext('2d');
					var myLineChart = new Chart(ctx, {
						type: 'line',
						data: {
							labels: labelArray,
							datasets: [{
								backgroundColor: 'rgba(225, 155, 60, 0.4)',
								borderColor: 'rgba(225, 155, 60, 1)',
								data: dataArray
							}],
						},
						options: {
							legend:{
								display:false
							}
						}
					});

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
								<th>Action</th>
							</tr>
							<tbody id='receivableTableContent'></tbody>
						</table>
					</div>

					<p id='receivableTableText'>There is no receivable found.</p>
				</div>
				<div id='analyticsWrapper' class='viewWrapper'>
					<canvas id='chartWrapper' width="100" height="30"></canvas>
					<form id='valueForm'>
						<label>View Sales Value</label>
						<div class='input_group'>
							<input type='date' class='form-control' id='dateStart'>
							<input type='date' class='form-control' id='dateEnd'>
							<div class='input_group_append'>
								<button type='button' class='button button_default_dark' onclick='calculateValue()'><i class='fa fa-long-arrow-right'></i></button>
							</div>
						</div>
						<br>
						<p>Rp. <span id='CustomerValueP'>0.00</span></p>
						<label>Information</label>
						<p>The value mentioned above is the sum of invoices value of the current customer.</p>
					</form>
					<hr>
					<label>Target History</label>
					<table class='table table-bordered'>
						<tr>
							<th>Effective Date</th>
							<th>Value</th>
						</tr>
						<tbody id='targetTableContent'></tbody>
					</table>

					<select class='form-control' id='targetPage' style='width:100px'>
						<option value='1'>1</option>
					</select>
				</div>
			</div>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='viewInvoiceWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Invoice archive</h3>
		<hr>
		<label>Customer</label>
		<p id='customer_name_p'></p>
		<p id='customer_address_p'></p>
		<p id='customer_city_p'></p>

		<label>Invoice</label>
		<p id='invoice_name_p'></p>
		<p id='invoice_tax_p'></p>
		<p id='invoice_date_p'></p>


		<div id='regularInvoice'>
			<label>Other</label>
			<p id='invoicing_method_p'></p>
			<p id='taxing_p'></p>

			<label>Sales order</label>
			<p id='sales_order_name_p'></p>
			<p id='sales_order_date_p'></p>
			<p id='sales_order_seller_p'></p>

			<label>Items</label>
			<div class='table-responsive-md'>
				<table class='table table-bordered'>
					<tr>
						<th>Reference</th>
						<th>Name</th>
						<th>Price list</th>
						<th>Discount</th>
						<th>Net price</th>
						<th>Quantity</th>
						<th>Total price</th>
					</tr>
					<tbody id='deliveryOrderTableContent'></tbody>
				</table>
			</div>
		</div>
		<div id='otherInvoice'>
			<label>Value</label>
			<p id='invoiceValue_p'></p>

			<label>Information</label>
			<p id='invoiceInformation_p'></p>
		</div>
	</div>
</div>

<script>
	var target;
	$("#valueForm").validate();

	$(document).ready(function(){
		$('#salesOrderButton').click();
	});

	$('#salesOrderButton').click(function(){
		$('.button_mini_tab').attr('disabled', false);
		$('.button_mini_tab').removeClass('active');

		$(this).attr('disabled', true);
		$(this).addClass('active');

		fetchIncompletedSalesOrders();
		$('.viewWrapper').fadeOut(300, function(){
			setTimeout(function(){
				$('#pendingSalesOrderTableWrapper').fadeIn(300);
			}, 300);
		});
	});

	$('#receivableButton').click(function(){
		$('.button_mini_tab').attr('disabled', false);
		$('.button_mini_tab').removeClass('active');

		$(this).attr('disabled', true);
		$(this).addClass('active');

		fetchReceivable();
		$('.viewWrapper').fadeOut(300, function(){
			setTimeout(function(){
				$('#receivableTableWrapper').fadeIn(300);
			}, 300);
		});
	});

	$('#analyticButton').click(function(){
		$('.button_mini_tab').attr('disabled', false);
		$('.button_mini_tab').removeClass('active');

		$(this).attr('disabled', true);
		$(this).addClass('active');

		fetchAnalytics(1);
		$('.viewWrapper').fadeOut(300, function(){
			setTimeout(function(){
				$('#analyticsWrapper').fadeIn(300);
			}, 300);
		});
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
					var id		= receivable.id;
					var date = receivable.date;
					var name = receivable.name;
					var taxInvoice = (receivable.taxInvoice == null || receivable.taxInvoice == "") ? "<i>Not available</i>" : receivable.taxInvoice;
					var paid = parseFloat(receivable.paid);
					var value = parseFloat(receivable.value);
					var remainder = value - paid;
					totalValue += remainder;

					$('#receivableTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Rp. " + numeral(paid).format('0,0.00') + "</td><td>Rp. " + numeral(remainder).format('0,0.00') + "</td><td><button class='button button_default_dark' onclick='viewInvoice(" + id + ")'><i class='fa fa-eye'></i></button></tr>");
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

	$('#targetPage').change(function(){
		var length	= target.length;
		var pages	= Math.max(1, Math.ceil(length / 5));
		$('#targetTableContent').html("");

		var page			= $('#targetPage').val();
		var startArray		= (page - 1) * 5;
		var endArray		= startArray + 5;
		var targetArray		= target.slice(startArray, endArray);
		$.each(targetArray, function(index, item){
			var value = item.value;
			var dateCreated = item.dateCreated;
			$('#targetTableContent').append("<tr><td>" + my_date_format(dateCreated) + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td></tr>");
		});
	})

	function fetchAnalytics(page = $('#targetPage').val()){
		$.ajax({
			url:"<?= site_url('SalesAnalytics/getByCustomerId') ?>",
			data:{
				id: <?= $customer->id ?>
			},
			success:function(response){
				target = response.target;
				var length	= target.length;
				var pages	= Math.max(1, Math.ceil(length / 5));

				$('#targetTableContent').html("");

				var startArray		= (page - 1) * 5;
				var endArray		= startArray + 5;
				var targetArray		= target.slice(startArray, endArray);
				$.each(targetArray, function(index, item){
					var value = item.value;
					var dateCreated = item.dateCreated;
					$('#targetTableContent').append("<tr><td>" + my_date_format(dateCreated) + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td></tr>");
				});

				$('#targetPage').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#targetPage').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#targetPage').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		})
	}

	function viewInvoice(n){
		$.ajax({
			url:'<?= site_url('Invoice/getById') ?>',
			data:{
				id:n
			},
			success:function(response){
				var customer = response.customer;
				if(customer != null){
					var customer_name = customer.name;
					var complete_address = customer.address;
					var customer_number = customer.number;
					var customer_block = customer.block;
					var customer_rt = customer.rt;
					var customer_rw = customer.rw;
					var customer_city = customer.city;
					var customer_postal = customer.postal;
				
					if(customer_number != null){
						complete_address	+= ' No. ' + customer_number;
					}
				
					if(customer_block != null && customer_block != '000'){
						complete_address	+= ' Blok ' + customer_block;
					}
			
					if(customer_rt != '000'){
						complete_address	+= ' RT ' + customer_rt;
					}
				
					if(customer_rw != '000' && customer_rt != '000'){
						complete_address	+= ' /RW ' + customer_rw;
					}
				
					if(customer_postal != null){
						complete_address	+= ', ' + customer_postal;
					}
				} else {
					var opponent		= response.opponent;
					var customer_name	= opponent.name;
					var complete_address	= opponent.description;
					var customer_city		= opponent.type;
				}

				$('#customer_name_p').html(customer_name);
				$('#customer_address_p').html(complete_address);
				$('#customer_city_p').html(customer_city);
				var salesOrder = response.sales_order;
				if(salesOrder != null){
					var invoice = response.invoice;
					var date = invoice.date;
					var name = invoice.name;

					$('#invoice_name_p').html(name);
					$('#invoice_date_p').html(my_date_format(date));

					var taxing = salesOrder.taxing;
					if(taxing == 0){
						var taxingText = "Non-taxable";
						var taxInvoice = "<i>Not available</i>";
					} else {
						var taxingText = "Taxable";
						if(invoice.is_confirm == 1){
							var taxInvoice = invoice.taxInvoice;
						} else {
							var taxInvoice = "<i>Not available</i>";
						}
					}
				
					$('#invoice_tax_p').html(taxInvoice);

					var invoicing_method = salesOrder.invoicing_method;
					if(invoicing_method == 1){
						var invoicingMethodText = "Retail";
					} else {
						var invoicingMethodText = "Coorporate";
					}

					$('#invoicing_method_p').html(invoicingMethodText);
					$('#taxing_p').html(taxingText);

					var name = salesOrder.name;

					var seller = salesOrder.seller;
					if(seller == null){
						var sellerText = "<i>Not available</i>";
					} else {
						var sellerText = seller;
					}

					$('#sales_order_name_p').html(name);
					$('#sales_order_date_p').html(my_date_format(date));
					$('#sales_order_seller_p').html(sellerText);

					$('#deliveryOrderTableContent').html('');
					var items = response.items;
					var invoiceValue = 0;
					$.each(items, function(index, item){
						var reference = item.reference;
						var name = item.name;
						var discount = parseFloat(item.discount);
						var priceList = parseFloat(item.price_list);
						var quantity = parseInt(item.quantity);
						var netPrice = (100 - discount) * priceList / 100;
						var totalPrice = netPrice * quantity;
						invoiceValue += totalPrice;

						$('#deliveryOrderTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>Rp. " + numeral(priceList).format('0,0.00') + "</td><td>" + numeral(discount).format('0,0.00') + "%</td><td>Rp. " + numeral(netPrice).format("0,0.00") + "</td><td>" + numeral(quantity).format("0,0") + "</td><td>Rp. " + numeral(totalPrice).format('0,0.00') + "</td></tr>");
					});

					$('#deliveryOrderTableContent').append("<tr><td colspan='4'><td colspan='2'>Total</td><td>Rp. " + numeral(invoiceValue).format('0,0.00') + "</td></tr>");

					$('#otherInvoice').hide();
					$('#regularInvoice').show();
				} else {
					var invoice = response.invoice;
					var date = invoice.date;
					var name = invoice.name;
					var value	= invoice.value;
					var information	= invoice.information;

					$('#invoice_name_p').html(name);
					$('#invoice_date_p').html(my_date_format(date));
					$('#invoiceValue_p').html("Rp. " + numeral(value).format('0,0.00'));
					$('#invoiceInformation_p').html(information);

					var taxInvoice	= (invoice.taxInvoice == "" || invoice.taxInvoice == null) ? "<i>Not available</i>" : invoice.taxInvoice;
					$('#invoice_tax_p').html(taxInvoice)

					$('#otherInvoice').show();
					$('#regularInvoice').hide();
				}

				$('#viewInvoiceWrapper').fadeIn(300, function(){
					$('#viewInvoiceWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function calculateValue(){
		if($('#valueForm').valid()){
			$.ajax({
				url:"<?= site_url('Customer/getValueByDateRange') ?>",
				data:{
					id: <?= $customer->id ?>,
					start: $('#dateStart').val(),
					end: $('#dateEnd').val()
				},
				type:"POST",
				success:function(response){
					var value		= response.value;
					$('#CustomerValueP').html(numeral(value).format('0,0.00'));

					var distribution	= response.distribution;
					var totalValue		= 0;
					$.each(distribution, function(index, item){
						var name		= item.name;
						var value		= item.value;

						totalValue += parseFloat(value);
					});
				}
			})
		}
	}
</script>
