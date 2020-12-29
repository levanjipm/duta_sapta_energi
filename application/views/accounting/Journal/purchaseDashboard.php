<head>
	<title>Sales Journal</title>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
</head>

<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-bar-chart'></i></a> /Journal /Purchase</p>
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
			<button class='button button_default_dark' onclick='downloadMonthYearReport()'><i class='fa fa-file-text'></i></button>
		</div>
		<br>
		<label>Daily Purchase</label>
		<canvas id='lineChart' height="50"></canvas>
		<label>Monthly Purchase</label>
		<p>Rp. <span id='monthlyPurchase'>0.00</span></p>
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
		<p id='invoiceTableText'>There is no purchase invoice found.</p>
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

<div class='alert_wrapper' id='viewInvoiceWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Invoice archive</h3>
		<hr>
		<label>Supplier</label>
		<p id='supplier_name_p'></p>
		<p id='supplier_address_p'></p>
		<p id='supplier_city_p'></p>

		<label>Invoice</label>
		<p id='invoice_name_p'></p>
		<p id='invoice_tax_p'></p>
		<p id='invoice_date_p'></p>
		<p><strong>Rp. <span id='invoice_value_p'></span></strong></p>

		<hr>
		<div id='goodReceiptWrapper'></div>
	</div>
</div>

<div class='alert_wrapper' id='viewBlankInvoiceWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Invoice archive</h3>
		<hr>
		<label>Supplier</label>
		<p id='blankSupplierName_p'></p>
		<p id='blankSupplierAddress_p'></p>
		<p id='blankSupplierCity_p'></p>

		<label>Invoice</label>
		<p id='blankInvoiceName_p'></p>
		<p id='blankInvoiceTax_p'></p>
		<p id='blankInvoiceDate_p'></p>
		<p><strong>Rp. <span id='blankInvoiceValue_p'></span></strong></p>

		<label>Information</label>
		<p id='informationText_p'></p>

		<p class='subtitleText'>Created by <span id='createdBy_p'></span></p>
	</div>
</div>

<script>
	var myLineChart;

	function downloadMonthYearReport(){
		window.open("<?= site_url('Debt/downloadMonthYearReport') ?>" + "?month=" + $('#month').val() + "&year=" + $('#year').val(), '_blank');
	}

	$('document').ready(function(){
		refreshView(1);
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
			url:"<?= site_url('Debt/getItems') ?>",
			data:{
				page: page,
				month: $('#month').val(),
				year: $('#year').val()
			},
			success:function(response){
				var items		= response.items;
				var itemCount = 0;
				$('#invoiceTableContent').html("");
				$.each(items, function(index, item){
					var id			= item.id;
					var name		= item.invoice_document;
					var date		= item.date;
					var value		= parseFloat(item.value);
					var delivery	= parseFloat(item.delivery);
					var discount	= parseFloat(item.discount);
					var type		= parseInt(item.type);
					var supplier	= item.supplier;
					var taxDocument	= (item.tax_document == null) ? "<i>Not available</i>" : item.tax_document;
					var invoiceValue	= item.value;
					var type			= item.type;

					if(item.other_opponent_id == null){
						var supplierName	= supplier.name;
						var supplierCity	= supplier.city;
					} else {
						var supplierName	= supplier.name;
						var supplierCity	= "";
					}

					if(type == null){
						$('#invoiceTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><p>" + name + "</p><p>" + taxDocument + "</p></td><td>" + supplierName + ", " + supplierCity + "</td><td>Rp. " + numeral(invoiceValue).format('0,0.00') + "</td><td><button class='button button_default_dark' onclick='viewById(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
					} else {
						$('#invoiceTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><p>" + name + "</p><p>" + taxDocument + "</p></td><td>" + supplierName + ", " + supplierCity + "</td><td>Rp. " + numeral(invoiceValue).format('0,0.00') + "</td><td><button class='button button_default_dark' onclick='viewBlankById(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
					}
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

				getValue();
			}
		})
	}

	function getValue()
	{
		$.ajax({
			url:"<?= site_url('Debt/getValueByMonthYearDaily') ?>",
			data:{
				month: $('#month').val(),
				year: $('#year').val()
			},
			success:function(response){
				var ctx = document.getElementById('lineChart').getContext('2d');
				var labelArray = [];
				var valueArray = [];
				var value	= 0;

				$.each(response, function(index, item){
					labelArray.push(index);
					valueArray.push(item);
					value += parseFloat(item);
				});
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

				$('#monthlyPurchase').html(numeral(value).format('0,0.00'));
			}
		})
	}

	function viewById(n){
		$.ajax({
			url:'<?= site_url('Debt/getById') ?>',
			data:{
				id:n
			},
			success:function(response){
				var supplier = response.supplier;
				var supplier_name = supplier.name;
				var complete_address = supplier.address;
				var supplier_number = supplier.number;
				var supplier_block = supplier.block;
				var supplier_rt = supplier.rt;
				var supplier_rw = supplier.rw;
				var supplier_city = supplier.city;
				var supplier_postal = supplier.postal_code;
				
				if(supplier_number != null){
					complete_address	+= ' No. ' + supplier_number;
				}
				
				if(supplier_block != null && supplier_block != '000'){
					complete_address	+= ' Blok ' + supplier_block;
				}
			
				if(supplier_rt != '000'){
					complete_address	+= ' RT ' + supplier_rt;
				}
				
				if(supplier_rw != '000' && supplier_rw != '000'){
					complete_address	+= ' /RW ' + supplier_rw;
				}
				
				if(supplier_postal != null){
					complete_address	+= ', ' + supplier_postal;
				}

				$('#supplier_name_p').html(supplier_name);
				$('#supplier_address_p').html(complete_address);
				$('#supplier_city_p').html(supplier_city);
				
				var debt = response.debt;

				var date = debt.date;
				var invoiceDocument = debt.invoice_document;
				var taxDocument = debt.tax_document;

				if(taxDocument == null){
					var taxDocumentName = "<i>Not available</i>";
				} else {
					var taxDocumentName = taxDocument;
				}

				$('#invoice_name_p').html(invoiceDocument);
				$('#invoice_tax_p').html(taxDocumentName);
				$('#invoice_date_p').html(my_date_format(date));

				$('#goodReceiptWrapper').html("");
				var documents = response.documents;
				$.each(documents, function(index, document){
					var name= document.name;
					var date = document.date;
					var received_date = document.received_date;
					var id = document.id;

					$('#goodReceiptWrapper').append("<label>Name</label><p>" + name + "</p><label>Date</label><p>" + my_date_format(date) + "</p><p>Received on " + my_date_format(received_date) + "</p><br><table class='table table-bordered'><tr><th>Reference</th><th>Name</th><th>Quantity</th><th>Price</th><th>Total</th></tr><tbody id='goodReceiptDetailTable-" + id + "'></tbody></table>");
				});

				var items = response.details;
				var invoiceValue = 0;
				$.each(items, function(index, item){
					var quantity = parseInt(item.quantity);
					var price = parseFloat(item.billed_price);
					var reference = item.reference;
					var name = item.name;
					var codeGoodReceiptId = item.code_good_receipt_id;
					var totalPrice = quantity * price;

					invoiceValue += totalPrice;

					$('#goodReceiptDetailTable-' + codeGoodReceiptId).append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format("0,0.00") + "</td><td>Rp. " + numeral(price).format('0,0.00') + "</td><td>Rp. " + numeral(totalPrice).format('0,0.00') + "</td></tr>");
				});

				$('#invoice_value_p').html(numeral(invoiceValue).format('0,0.00'));
			},
			complete:function(){

				$('#viewInvoiceWrapper').fadeIn(300, function(){
					$('#viewInvoiceWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function viewBlankById(n){
		$.ajax({
			url:"<?= site_url('Debt/getBlankById') ?>",
			data:{
				id:n
			},
			success:function(response){
				var general = response.debt;
				var invoiceDate = general.date;
				var invoiceName = general.invoice_document;
				var information = general.information;
				var taxInvoice = general.tax_document;
				var supplierId = general.supplier_id;
				var opponentId = general.opponentId;
				var invoiceValue = general.value;
				var createdBy	= general.created_by;
				var information = general.information;
				if(information == null || information == ""){
					var informationText = "<i>Not available</i>";
				} else {
					var informationText = information;
				}

				$('#informationText_p').html(informationText);
				if(supplierId != null){
					var supplier = response.supplier;
					var supplier_name = supplier.name;
					var complete_address = supplier.address;
					var supplier_number = supplier.number;
					var supplier_block = supplier.block;
					var supplier_rt = supplier.rt;
					var supplier_rw = supplier.rw;
					var supplier_city = supplier.city;
					var supplier_postal = supplier.postal_code;
					
					if(supplier_number != null){
						complete_address	+= ' No. ' + supplier_number;
					}
					
					if(supplier_block != null && supplier_block != '000'){
						complete_address	+= ' Blok ' + supplier_block;
					}
				
					if(supplier_rt != '000'){
						complete_address	+= ' RT ' + supplier_rt;
					}
					
					if(supplier_rw != '000' && supplier_rw != '000'){
						complete_address	+= ' /RW ' + supplier_rw;
					}
					
					if(supplier_postal != null){
						complete_address	+= ', ' + supplier_postal;
					}

					$('#blankSupplierName_p').html(supplier_name);
					$('#blankSupplierAddress_p').html(complete_address);
					$('#blankSupplierCity_p').html(supplier_city);

					$('#blankInvoiceName_p').html(invoiceName);
					$('#blankInvoiceDate_p').html(my_date_format(invoiceDate));
					$('#blankInvoiceValue_p').html(numeral(invoiceValue).format("0,0.00"));
					if(taxInvoice == "" || taxInvoice == null){
						$('#blankInvoiceTax_p').html("<i>Not available</i>");
					} else {
						$('#blankInvoiceTax_p').html(taxInvoice);
					};

					$('#createdBy_p').html(createdBy);

					$('#viewBlankInvoiceWrapper').fadeIn(300, function(){
						$('#viewBlankInvoiceWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
					});
				} else {
					var supplier 		= response.supplier;
					var name		 	= supplier.name;
					var description		= supplier.description;
					var type			= supplier.type;

					$('#blankSupplierName_p').html(name);
					$('#blankSupplierAddress_p').html(description);
					$('#blankSupplierCity_p').html(type);

					$('#blankInvoiceName_p').html(invoiceName);
					$('#blankInvoiceDate_p').html(my_date_format(invoiceDate));
					$('#blankInvoiceValue_p').html(numeral(invoiceValue).format("0,0.00"));
					if(taxInvoice == "" || taxInvoice == null){
						$('#blankInvoiceTax_p').html("<i>Not available</i>");
					} else {
						$('#blankInvoiceTax_p').html(taxInvoice);
					};

					$('#createdBy_p').html(createdBy);

					$('#viewBlankInvoiceWrapper').fadeIn(300, function(){
						$('#viewBlankInvoiceWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
					});
				}
			}
		})
	}
</script>
