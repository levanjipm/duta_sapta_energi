<head>
	<title>Sales Journal</title>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
</head>

<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-bar-chart'></i></a> /Journal /Sales</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Period</label>
		<div class='input_group'>
			<select class='form-control' id='month'>
			<?php for($i = 1; $i <= 12; $i++){ ?>
				<option value='<?= $i ?>' <?= ($i == date("m")) ? "selected" : "" ?>><?= date("F", mktime(0,0,0,$i, 1, date("Y"))) ?></option>
			<?php } ?>
			</select>
			<select class='form-control' id='year'>
			<?php for($i = 2020; $i <= date("Y"); $i++){ ?>
				<option value='<?= $i ?>'><?= $i ?></option>
			<?php } ?>
			</select>
		</div>
		<br>
		<label>Daily Sales</label>
		<canvas id='lineChart' height="50"></canvas>
		<label>Monthly Sales</label>
		<p>Rp. <span id='monthlySales'>0.00</span></p>
		<div id='invoiceTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Invoice</th>
					<th>Customer</th>
					<th>Value</th>
					<th>Action</th>
				</tr>
				<tbody id='invoiceTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='invoiceTableText'>There is no sales invoice found.</p>
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
	var myLineChart;
	$(document).ready(function(){
		refreshView();
	});

	$('#month').change(function(){
		refreshView(1);
	})

	$('#year').change(function(){
		refreshView(1);
	})

	$('#page').change(function(){
		refreshView();
	})

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Invoice/getItems') ?>",
			data:{
				page: page,
				month: $('#month').val(),
				year: $('#year').val()
			},
			beforeSend:function(){
				getValue();
			},
			success:function(response){
				var items		= response.items;
				var itemCount = 0;
				$('#invoiceTableContent').html("");
				$.each(items, function(index, item){
					var id			= item.id;
					var name		= item.name;
					var date		= item.date;
					var value		= parseFloat(item.value);
					var delivery	= parseFloat(item.delivery);
					var discount	= parseFloat(item.discount);
					var type		= parseInt(item.type);
					var customer	= item.customer;
					var taxDocument	= (item.taxInvoice == null) ? "<i>Not available</i>" : item.taxInvoice;

					var customerName	= customer.name;
					var customerCity	= customer.city;


					var invoiceValue	= value + delivery - discount;
					$('#invoiceTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><p>" + name + "</p><p>" + taxDocument + "</p></td><td>" + customerName + ", " + customerCity + "</td><td>Rp. " + numeral(invoiceValue).format('0,0.00') + "</td><td><button class='button button_default_dark' onclick='viewById(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
					
					itemCount++;
				});

				var pages		= response.pages;
				$('#page').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='i' selected>" + i + "</option>")
					} else {
						$('#page').append("<option value='i'>" + i + "</option>")
					}
				}

				if(itemCount > 0){
					$('#invoiceTable').show();
					$('#invoiceTableText').hide();
				} else {
					$('#invoiceTable').hide();
					$('#invoiceTableText').show();
				}
			}
		})
	}

	function getValue()
	{
		$.ajax({
			url:"<?= site_url('Invoice/getValueByMonthYearDaily') ?>",
			data:{
				month: $('#month').val(),
				year: $('#year').val()
			},
			success:function(response){
				var labelArray = [];
				var valueArray = [];
				var value	= 0;
				$.each(response, function(index, item){
					labelArray.push(index);
					valueArray.push(item);
					value += parseFloat(item);
				})

				var ctx = document.getElementById('lineChart').getContext('2d');
				if (myLineChart) myLineChart.destroy();
				myLineChart = new Chart(ctx, {
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

				$('#monthlySales').html(numeral(value).format('0,0.00'));
			}
		})
	}

	function viewById(id){
		$.ajax({
			url:'<?= site_url('Invoice/getById') ?>',
			data:{
				id:id
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
			}, 
			complete:function(){
				$('#viewInvoiceWrapper').fadeIn(300, function(){
					$('#viewInvoiceWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}
</script>
