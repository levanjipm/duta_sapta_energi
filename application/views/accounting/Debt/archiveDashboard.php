<head>
	<title>Debt document archive</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> / Invoice/ Archive</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='row'>
			<div class='col-md-2 col-sm-4 col-xs-6'>
				<select class='form-control' id='month'>
				<?php
	for($i = 1; $i <= 12; $i++){
?>
					<option value='<?= $i ?>' <?php if($i == date('m')){ echo('selected');} ?>><?= date('F', mktime(0,0,0,$i, 1)) ?></option>
<?php
	}
?>
				</select>
			</div>
			<div class='col-md-2 col-sm-4 col-xs-6'>
				<select class='form-control' id='year'>
<?php
	foreach($years as $year){
?>
					<option value='<?= $year->years ?>' <?php if($year->years == date('Y')){ echo('selected');} ?>><?= $year->years ?></option>
<?php
	}
?>
				</select>
			</div>
		</div>
		<br><br>
		<div id='archiveTable'></div>
		<p id='archiveTableText'>There is no archive found.</p>
		<br>

		<select class='form-control' id='page' style='width:100px'>
			<option value='1'>1</option>
		</select>
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
<script>
	$(document).ready(function(){
		refreshView();
	})

	$('#month').change(function(){
		refreshView(1);
	})

	$('#year').change(function(){
		refreshView(1);
	})

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Debt/getItems') ?>',
			data:{
				page: page,
				month: $('#month').val(),
				year: $('#year').val()
			},
			type:"GET",
			beforeSend:function(){
				$('#archiveTable').html('');
			},
			success:function(response){
				var items = response.items;
				var pages = response.pages;
				var invoiceCount = 0;

				$.each(items, function(index, item){
					var invoiceName = item.invoice_document;
					var date = item.date;
					var taxInvoiceName = item.tax_document;
					var isConfirm = item.is_confirm;
					var id = item.id;
					
					var supplier = item.supplier;
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

					if(isConfirm == 0){
						$('#archiveTable').append("<div class='row archive_row'><div class='col-md-3 col-sm-3 col-xs-4'><p><strong>" + invoiceName + "</strong></p></div><div class='col-md-3 col-sm-3 col-xs-3'><p><strong>" + supplier_name + "</strong></p><p>" + complete_address + "</p><p>" + supplier_city + "</p></div><div class='col-md-4 col-sm-5 col-xs-5 col-md-offset-2 col-sm-offset-1 col-xs-offset-2'><p style='display:inline-block'>" + my_date_format(date) + " <strong>|</strong> </p> <button type='button' class='button button_transparent' onclick='openView(" + id + ")' title='View " + invoiceName + "'><i class='fa fa-eye'></i></button></div>");
					} else {
						$('#archiveTable').append("<div class='row archive_row'><div class='col-md-3 col-sm-3 col-xs-4'><p><strong>" + invoiceName + "</strong></p></div><div class='col-md-3 col-sm-3 col-xs-3'><p><strong>" + supplier_name + "</strong></p><p>" + complete_address + "</p><p>" + supplier_city + "</p></div><div class='col-md-4 col-sm-5 col-xs-5 col-md-offset-2 col-sm-offset-1 col-xs-offset-2'><p style='display:inline-block'>" + my_date_format(date) + " <strong>|</strong> </p> <button type='button' class='button button_transparent' onclick='openView(" + id + ")' title='View " + invoiceName + "'><i class='fa fa-eye'></i></button> <button type='button' class='button button_verified' title='Confirmed'><i class='fa fa-check'></i></button></div>");
					}

					invoiceCount++;
				})

				if(invoiceCount > 0){
					$('#archiveTableText').hide();
					$('#archiveTable').show();
				} else {
					$('#archiveTableText').show();
					$('#archiveTable').hide();
				}

				$('#page').html('');

				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>")
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>")
					}			
				}	
			}
		})
	}

	function openView(n){
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

				// var name = salesOrder.name;

				// var seller = salesOrder.seller;
				// if(seller == null){
				// 	var sellerText = "<i>Not available</i>";
				// } else {
				// 	var sellerText = seller;
				// }

				// $('#sales_order_name_p').html(name);
				// $('#sales_order_date_p').html(my_date_format(date));
				// $('#sales_order_seller_p').html(sellerText);

				// $('#deliveryOrderTableContent').html('');
				// var items = response.items;
				// var invoiceValue = 0;
				// $.each(items, function(index, item){
				// 	var reference = item.reference;
				// 	var name = item.name;
				// 	var discount = parseFloat(item.discount);
				// 	var priceList = parseFloat(item.price_list);
				// 	var quantity = parseInt(item.quantity);
				// 	var netPrice = (100 - discount) * priceList / 100;
				// 	var totalPrice = netPrice * quantity;
				// 	invoiceValue += totalPrice;

				// 	$('#deliveryOrderTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>Rp. " + numeral(priceList).format('0,0.00') + "</td><td>" + numeral(discount).format('0,0.00') + "%</td><td>Rp. " + numeral(netPrice).format("0,0.00") + "</td><td>" + numeral(quantity).format("0,0") + "</td><td>Rp. " + numeral(totalPrice).format('0,0.00') + "</td></tr>");
				// });

				// $('#deliveryOrderTableContent').append("<tr><td colspan='4'><td colspan='2'>Total</td><td>Rp. " + numeral(invoiceValue).format('0,0.00') + "</td></tr>");

				$('#viewInvoiceWrapper').fadeIn(300, function(){
					$('#viewInvoiceWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	$('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
</script>