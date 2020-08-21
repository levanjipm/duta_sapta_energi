<head>
	<title>Debt document - Confirm</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Sales'><i class='fa fa-bar-chart'></i></a> /Debt document</p>
	</div>
	<br>
	<div class='dashboard_in'>
        <input type='text' class='form-control input-lg' id='search_bar' placeholder="Search debt document">
		<br>
		<div id='debtTable'>
			<table class='table table-bordered' id='debt_document_table'>
				<tr>
					<th>Date</th>
					<th>Supplier</th>
					<th>Document</th>
					<th>Action</th>
				</tr>
				<tbody id='debtTableContent'></tbody>
			</table>
		
			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<div id='debtTableText'>There is no debt document to be confirmed.</p>
</div>

<div class='alert_wrapper' id='view_debt_wrapper'>
	<button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Debt document</h3>
		
		<input type='hidden' id='purchase_invoice_id' name='id'>
		<hr>
		<label>Purchase invoice</label>
		<p style='font-family:museo' id='invoice_date_p'></p>
		<p style='font-family:museo' id='invoice_document_p'></p>
		<p style='font-family:museo' id='invoice_tax_document_p'></p>
		
		<label>Supplier</label>
		<p style='font-family:museo' id='supplier_name_p'></p>
		<p style='font-family:museo' id='supplier_address_p'></p>
		<p style='font-family:museo' id='supplier_city_p'></p>

		<label>Good receipts</label>
		<table class='table table-bordered'>
			<tr>
				<th>Date</th>
				<th>Name</th>
				<th>Received date</th>
			</tr>
			<tbody id='goodReceiptInformation'></tbody>
		</table>
		<hr>

		<div class='table-responsive-md'>
			<table class='table table-bordered'>
				<tr>
					<th>Reference</th>
					<th>Name</th>
					<th>Unit price</th>
					<th>Quantity</th>
					<th>Total price</th>
				</tr>
				<tbody id='good_receipt_table'></tbody>
			</table>
		</div>
		<button type='button' class='button button_default_dark' onclick='confirmDebt()'><i class='fa fa-long-arrow-right'></i></button>
		<button type='button' class='button button_danger_dark' onclick='deleteDebt()'><i class='fa fa-trash'></i></button>

		<div class='notificationText danger' id='failedDeleteNotification'><p>Failed to delete debt documnet.</p></div>
		<div class='notificationText danger' id='failedConfirmNotification'><p>Failed to confirm debt document.</p></div>
	</div>
</div>

<div class='alert_wrapper' id='viewBlankDebtWrapper'>
	<button type='button' class='slide_alert_close_button'>&times;</button>
		<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Blank debt document</h3>
		
		<input type='hidden' id='blankPurchaseInvoiceId'>
		<hr>
		<label>Purchase invoice</label>
		<p style='font-family:museo' id='blankInvoiceDate_p'></p>
		<p style='font-family:museo' id='blankInvoiceDocument_p'></p>
		<p style='font-family:museo' id='blankTaxInvoiceDocument_p'></p>
		<p style='font-family:museo' id='blankInvoiceTaxing_p'></p>
		<br>

		<table class='table'>
			<tr>
				<td><label>Supplier</label></td>
				<td>
					<p style='font-family:museo' id='blankSupplierName_p'></p>
					<p style='font-family:museo' id='blankSupplierAddress_p'></p>
					<p style='font-family:museo' id='blankSupplierCity_p'></p>
				</td>
			</tr>
			<tr>
				<td><label>Type</label></td>
				<td><p style='font-family:museo' id='blankInvoiceType_p'></p></td>
			</tr>
			<tr>
				<td><label>Information</label></td>
				<td><p style='font-family:museo' id='blankInvoiceInformation_p'></p></td>
			</tr>
			<tr>
				<td><label>Value</label></td>
				<td>Rp. <span id='blankInvoiceValue_p'></span></td>
			</tr>
		</table>

		<p style='font-family:museo;float:right'>Created by <span id='blankInvoiceCreator_p'></span></p><br><br>
		

		<button type='button' class='button button_default_dark' onclick='confirmBlankDebt()'><i class='fa fa-long-arrow-right'></i></button>
		<button type='button' class='button button_danger_dark' onclick='deleteBlankDebt()'><i class='fa fa-trash'></i></button>

		<div class='notificationText danger' id='failedDeleteBlankNotification'><p>Failed to delete debt documnet.</p></div>
		<div class='notificationText danger' id='failedConfirmBlankNotification'><p>Failed to confirm debt document.</p></div>
	</div>
</div>


<script>
	$(document).ready(function(){
		refresh_view();
	})
	
	$('#page').change(function(){
		refresh_view();
	});
	
	$('#search_bar').change(function(){
		refresh_view(1);
	});
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Debt/showUnconfirmedDocuments') ?>',
			data:{
				page:page,
				term:$('#search_bar').val()
			},
			success:function(response){
				var page	= $('#page').val();
				array	= response.invoices;
				var debtCount = 0;
				$('#debtTableContent').html('');
				$.each(array, function(index, value){
					var id					= value.id;
					var date				= value.date;
					var tax_document		= value.tax_document;
					if(tax_document == null){
						var taxDocumentText = "<i>Not available</i>";
					} else {
						var taxDocumentText = tax_document;
					}
					var invoice_document	= value.invoice_document;
					var supplier_name		= value.name;
					var supplier_address	= value.address;
					var supplier_city		= value.city;

					var debtType			= value.class;

					if(debtType == "Blank"){
						var type			= value.type;

						$('#debtTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><label>" + supplier_name + "</label><p>" + supplier_address + "</p><p>" + supplier_city + "</p></td><td><label>" + invoice_document + "</label><p>" + taxDocumentText + "</p><label>" + type + "</label><p>(Blank debt document)</p></td><td><button type='button' class='button button_default_dark' onclick='viewBlankDebtDocument(" + id + ")' title='View " + invoice_document + "'><i class='fa fa-eye'></i></button></td></tr>");
					} else {
						$('#debtTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><label>" + supplier_name + "</label><p>" + supplier_address + "</p><p>" + supplier_city + "</p></td><td><label>" + invoice_document + "</label><p>" + taxDocumentText + "</p></td><td><button type='button' class='button button_default_dark' onclick='viewDebtDocument(" + id + ")' title='View " + invoice_document + "'><i class='fa fa-eye'></i></button></td></tr>");
					}

					debtCount++;
					
				});

				if(debtCount > 0){
					$('#debtTable').show();
					$('#debtTableText').hide();
				} else {
					$('#debtTable').hide();
					$('#debtTableText').show();
				}
				
				$('#page').html('');
				var pages		= response.pages;
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		});
	}
	
	function viewDebtDocument(n){
		var code_purchase_invoice_id	= n;
		$.ajax({
			url:'<?= site_url('Debt/getById') ?>',
			data:{
				id:n
			},
			success:function(response){
				$('#purchase_invoice_id').val(n);
				var total_value = 0;
				debt_array		= response.debt;
				document_array	= response.documents;
				detail_array	= response.details;
				
				var invoice_date		= debt_array.date;
				var invoice_name		= debt_array.invoice_document;
				var invoice_tax_name	= debt_array.tax_document;
				
				var supplier_name		= debt_array.name;
				var supplier_address	= debt_array.address;
				var supplier_city		= debt_array.city;
				
				$('#invoice_date_p').html(my_date_format(invoice_date));
				$('#invoice_document_p').html(invoice_name);
				$('#invoice_tax_document_p').html(invoice_tax_name);
				
				$('#supplier_name_p').html(supplier_name);
				$('#supplier_address_p').html(supplier_address);
				$('#supplier_city_p').html(supplier_city);
				$('#good_receipt_table').html('');
				$('#goodReceiptInformation').html("");
				$.each(document_array, function(index, value){
					var document_date			= value.date;
					var document_name			= value.name;
					var document_received_date	= value.received_date;
					var document_id				= value.id;

					$("<tr><td>" + document_name + "</td><td>" + my_date_format(document_date) + "</td><td>" + my_date_format(document_received_date) + "</td></tr>").appendTo( $('#goodReceiptInformation') );
					var document_value			= 0;
					$.each(detail_array, function (index_a, value_a){
						if(value_a.code_good_receipt_id == document_id){
							var reference			= value_a.reference;
							var name				= value_a.name;
							var quantity			= value_a.quantity;
							var billed_price		= value_a.billed_price;
							var total_price			= billed_price * quantity;
							$('#good_receipt_table').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>Rp. " + numeral(billed_price).format('0,0.00') + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(total_price).format('0,0.00') + "</td></tr>");
							
							document_value += total_price;
						};
					});
					
					total_value		+= document_value;
					$('#document_value-' + document_id).html('Rp. ' + numeral(document_value).format('0,0.00'));
				});
				
				$('#good_receipt_table').append("<tr><td colspan='2'></td><td>Total</td><td colspan='2'>Rp. " + numeral(total_value).format('0,0.00') + "</td></tr>");
				
				$('#view_debt_wrapper').fadeIn(300, function(){
					$('#view_debt_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}

	function viewBlankDebtDocument(n){
		$.ajax({
			url:'<?= site_url('Debt/getBlankById') ?>',
			data:{
				id: n
			},
			success:function(response){
				$('#blankPurchaseInvoiceId').val(n);

				var supplier 				= response.supplier;
				var supplierName 			= supplier.name;
				if(supplier.address == null){
					var complete_address = supplier.description;
					var supplier_city	= supplier.type;
				} else {
					var complete_address		= '';
					complete_address			+= supplier.address;
					var supplier_city			= supplier.city;
					var supplier_number			= supplier.number;
					var supplier_rt				= supplier.rt;
					var supplier_rw				= supplier.rw;
					var supplier_postal			= supplier.postal_code;
					var supplier_block			= supplier.block;
	
					if(supplier_number != null){
						complete_address	+= ' No. ' + supplier_number;
					}
				
					if(supplier_block != null){
						complete_address	+= ' Blok ' + supplier_block;
					}
			
					if(supplier_rt != '000'){
						complete_address	+= ' RT ' + supplier_rt;
					}
				
					if(supplier_rw != '000' && supplier_rt != '000'){
						complete_address	+= ' /RW ' + supplier_rw;
					}
				
					if(supplier_postal != null){
						complete_address	+= ', ' + supplier_postal;
					}
				}
				$('#blankSupplierName_p').html(supplierName);
				$('#blankSupplierAddress_p').html(complete_address);
				$('#blankSupplierCity_p').html(supplier_city);

				var debt = response.debt;
				var taxDocumentName = debt.tax_document;
				var documentName	= debt.invoice_document;

				var taxing			= debt.taxing;
				if(taxing == 1){
					var taxingText = "Taxable purchase";
				} else {
					var taxingText = "Non-taxable purchase";
				}

				var createdBy 		= debt.created_by;
				var information 	= debt.information;
				var type			= debt.type;

				if(information != "" && information != null){
					var informationText = information;
				} else {
					var informationText = "<i>Not available</i>"
				}

				var value = debt.value;
				var date = debt.date;

				$('#blankInvoiceDate_p').html(my_date_format(date));
				$('#blankInvoiceDocument_p').html(documentName);
				$("#blankTaxInvoiceDocument_p").html(taxDocumentName);

				$('#blankInvoiceCreator_p').html(createdBy);
				$('#blankInvoiceInformation_p').html(informationText);
				$('#blankInvoiceType_p').html(type);
				$('#blankInvoiceTaxing_p').html(taxingText);
				$('#blankInvoiceValue_p').html(numeral(value).format('0,0.00'));

				$('#viewBlankDebtWrapper').fadeIn(300, function(){
					$('#viewBlankDebtWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function deleteDebt(){
		$.ajax({
			url:'<?= site_url('Debt/deleteById') ?>',
			data:{
				id:$('#purchase_invoice_id').val()
			},
			type:'POST',
			beforeSend:function(){
				$('button').attr('disabled', true);
			}, success:function(response){
				$('button').attr('disabled', false);
				if(response == 0){
					refresh_view();
					$('#failedDeleteNotification').fadeTo(250, 1);
					setTimeout(function(){
						$('#failedDeleteNotification').fadeTo(250, 0);
					}, 1000);
				} else {
					refresh_view();
					$('#view_debt_wrapper .slide_alert_close_button').click();
				}
			}
		});
	};

	function confirmDebt(){
		$.ajax({
			url:'<?= site_url('Debt/confirmById') ?>',
			data:{
				id:$('#purchase_invoice_id').val()
			},
			type:'POST',
			beforeSend:function(){
				$('button').attr('disabled', true);
			}, success:function(response){
				$('button').attr('disabled', false);
				if(response == 0){
					refresh_view();
					$('#failedConfirmNotification').fadeIn(250);
					setTimeout(function(){
						$('#failedConfirmNotification').fadeOut(250);
					}, 1000);
				} else {
					refresh_view();
					$('#view_debt_wrapper .slide_alert_close_button').click();
				}
			}
		});
	}

	function confirmBlankDebt(){
		$.ajax({
			url:'<?= site_url('Debt/confirmBlankById') ?>',
			data:{
				id:$('#blankPurchaseInvoiceId').val()
			},
			type:'POST',
			beforeSend:function(){
				$('button').attr('disabled', true);
			}, success:function(response){
				$('button').attr('disabled', false);
				if(response == 0){
					refresh_view();
					$('#failedConfirmBlankNotification').fadeIn(250);
					setTimeout(function(){
						$('#failedConfirmBlankNotification').fadeOut(250);
					}, 1000);
				} else {
					refresh_view();
					$('#viewBlankDebtWrapper .slide_alert_close_button').click();
				}
			}
		})
	}

	function deleteBlankDebt(){
		$.ajax({
			url:'<?= site_url('Debt/deleteBlankById') ?>',
			data:{
				id:$('#blankPurchaseInvoiceId').val()
			},
			type:'POST',
			beforeSend:function(){
				$('button').attr('disabled', true);
			}, success:function(response){
				$('button').attr('disabled', false);
				if(response == 0){
					refresh_view();
					$('#failedDeleteBlankNotification').fadeTo(250, 1);
					setTimeout(function(){
						$('#failedDeleteBlankNotification').fadeTo(250, 0);
					}, 1000);
				} else {
					refresh_view();
					$('#viewBlankDebtWrapper .slide_alert_close_button').click();
				}
			}
		});
	}
</script>	
