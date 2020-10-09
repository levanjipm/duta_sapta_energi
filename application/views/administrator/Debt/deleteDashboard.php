<head>
	<title>Debt - Delete</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Administrators') ?>' title='Administrators'><i class='fa fa-briefcase'></i></a> Debt / Delete</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<select class='form-control' id='month'>
			<?php for($i = 1; $i <= 12; $i++){ ?>
				<option value='<?= $i ?>' <?= ($i == date('m')) ? 'selected' : ''; ?>><?= date("F", mktime(0,0,0,$i,1, 2020)) ?></option>
			<?php } ?>
			</select>
			<select class='form-control' id='year'>
			<?php for($i = 2020; $i <= date('Y'); $i++){ ?>
				<option value='<?= $i ?>'><?= $i ?></option>
			<?php } ?>
			</select>
			<div class='input_group_append'>
				<button class="button button_default_dark" id='searchInvoiceButton'><i class='fa fa-long-arrow-right'></i></button>
			</div>
		</div>
		<hr>
		<div id='invoiceTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Information</th>
					<th>Opponent</th>
					<th>Action</th>
				</tr>
				<tbody id='invoiceTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='invoiceTableText'>There is no confirmed invoice found.</p>
	</div>
</div>

<div class='alert_wrapper' id='viewInvoiceWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>View Invoice</h3>
		<hr>
		<label>Invoice</label>
		<p id='invoiceName_p'></p>
		<p id='invoiceTax_p'></p>
		<p id='invoiceDate_p'></p>

		<label>Opponent</label>
		<p id='opponentName_p'></p>
		<p id='opponentAddress_p'></p>
		<p id='opponentCity_p'></p>

		<div id='itemTable'>
			<label>Good Receipts</label>
			<div class='table-responsive-lg'>
				<table class='table table-bordered'>
					<tr>
						<th>Date</th>
						<th>Name</th>
					</tr>
					<tbody id='goodReceiptTableContent'></tbody>
				</table>
			</div>

			<label>Items</label>
			<div class='table-responsive-lg'>
				<table class='table table-bordered'>
					<tr>
						<th>Reference</th>
						<th>Name</th>
						<th>Unit price</th>
						<th>Quantity</th>
						<th>Total price</th>
					</tr>
					<tbody id='itemTableContent'></tbody>
				</table>
			</div>
		</div>

		<div id='otherTable'>
			<label>Information</label>
			<p id='invoiceInformation_p'></p>
			<p id='invoiceType_p'></p>
			
			<label>Value</label>
			<p id='invoiceValue_p'></p>
		</div>

		<hr>

		<label>Payable</label>
		<div id='payableTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Value</th>
				</tr>
				<tbody id='payableTableContent'></tbody>
			</table>
		</div>
		<div id='payableTableText'>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='deleteInvoiceWrapper'>
	<div class='alert_box_confirm_wrapper'>
		<div class='alert_box_confirm_icon'><i class='fa fa-trash'></i></div>
		<div class='alert_box_confirm'>
			<h3>Delete confirmation</h3>
			
			<p>You are about to delete this data.</p>
			<p>Are you sure?</p>
			<button class='button button_default_dark' onclick="$('#deleteInvoiceWrapper').fadeOut()">Cancel</button>
			<button class='button button_danger_dark' onclick='deleteInvoice()'>Delete</button>
			
			<br><br>
			
			<p style='font-family:museo;background-color:#f63e21;width:100%;padding:5px;color:white;position:relative;bottom:0;left:0;opacity:0' id='errorDeleteInvoice'>Deletation failed.</p>
		</div>
	</div>
</div>

<script>
	var deleteInvoiceId;
	var blankDeleteInvoiceId;

	$(document).ready(function(){
		refresh_view();
	});

	$('#searchInvoiceButton').click(function(){
		refresh_view(1);
	})
	
	$('#page').change(function(){
		refresh_view();
	});
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Debt/getConfirmedItems') ?>",
			data:{
				page: page,
				month: $('#month').val(),
				year: $('#year').val()
			},
			success:function(response){
				$('#invoiceTableContent').html("");
				var items = response.items;
				var itemCount = 0;
				$.each(items, function(index, item){
					var id						= item.id;
					var date					= item.date;
					var name					= item.invoice_document;
					var taxInvoice				= (item.tax_document == null || item.tax_document == "") ? "<i>Not available</i>" : item.tax_document;
					var value					= item.value;
					var opponentName			= item.supplierName;
					var opponentCity			= item.type;
					var invoiceClass			= item.class;
					if(invoiceClass == "regular"){
						$('#invoiceTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><label>" + name + "</label><p>" + taxInvoice + "</p></td><td><p>" + opponentName + "</p><p>" + opponentCity + "</p></td><td><button class='button button_default_dark' onclick='viewInvoice(" + id + ")'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
						itemCount++;
					} else {
						$('#invoiceTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><label>" + name + "</label><p>" + taxInvoice + "</p></td><td><p>" + opponentName + "</p><p>" + opponentCity + "</p></td><td><button class='button button_default_dark' onclick='viewBlankInvoice(" + id + ")'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
						itemCount++;
					}
					
				});

				if(itemCount > 0){
					$('#invoiceTableText').hide();
					$('#invoiceTable').show();
				} else {
					$('#invoiceTableText').show();
					$('#invoiceTable').hide();
				}

				var pages = response.pages;
				$('#page').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		})
	}

	function viewInvoice(id){
		$.ajax({
			url:"<?= site_url('Debt/getById') ?>",
			data:{
				id: id
			},
			success:function(response){
				var debt = response.debt;
				var name = debt.invoice_document;
				var date = debt.date;
				var tax_document = (debt.tax_document == "" || debt.tax_document == null) ? "<i>Not Available</i>" : debt.tax_document;
				$('#invoiceName_p').html(name);
				$('#invoiceDate_p').html(my_date_format(date));
				$('#invoiceTax_p').html(tax_document);

				var supplier = response.supplier;
				var complete_address		= '';
				var supplier_name			= supplier.name;
				complete_address			+= supplier.address;
				var supplier_city			= supplier.city;
				var supplier_number			= supplier.number;
				var supplier_rt				= supplier.rt;
				var supplier_rw				= supplier.rw;
				var supplier_postal			= supplier.postal_code;
				var supplier_block			= supplier.block;
				var supplier_id				= supplier.id;
			
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

				$('#opponentName_p').html(supplier_name);
				$('#opponentAddress_p').html(complete_address);
				$('#opponentCity_p').html(supplier_city);

				var details = response.details;
				var debtValue = 0;
				$('#itemTableContent').html("");
				$.each(details, function(index, detail){
					var reference = detail.reference;
					var name = detail.name;
					var quantity = parseInt(detail.quantity);
					var price = parseFloat(detail.billed_price);
					var totalPrice = quantity * price;
					debtValue += totalPrice;
					$('#itemTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>Rp. " + numeral(price).format('0,0.00') + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(totalPrice).format('0,0.00') + "</td></tr>");
				});

				$('#itemTableContent').append("<tr><td colspan='2'><td colspan='2'>Total</td><td>Rp. " + numeral(debtValue).format('0,0.00') + "</td></tr>");

				$('#goodReceiptTableContent').html("");
				var documents = response.documents;
				$.each(documents, function(index, document){
					var date = document.date;
					var name = document.name;

					$('#goodReceiptTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td></tr>");
				});

				var payables = response.payable;
				$('#payableTableContent').html("");
				$.each(payables, function(index, value){
					var date = value.date;
					var payment = value.value;
					$('#payableTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>Rp. " + numeral(payment).format('0,0.00') + "</td></tr>");
				});

				if(payables.length > 0){
					$('#payableTable').show();
					$('#payableTableText').html("");
				} else {
					$('#payableTable').hide();
					$('#payableTableText').html("<p>There is no payable found.</p><button class='button button_danger_dark' onclick='confirmDeleteInvoice(" + id + ")'><i class='fa fa-trash'></i></button>");
				}

				$('#otherTable').hide();
				$('#itemTable').show();

				$('#viewInvoiceWrapper').fadeIn(300, function(){
					$('#viewInvoiceWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function viewBlankInvoice(id){
		$.ajax({
			url:"<?= site_url('Debt/getBlankById') ?>",
			data:{
				id: id
			},
			success:function(response){
				var general = response.debt;
				var name = general.invoice_document;
				var tax = (general.tax_document == null || general.tax_document == "") ? "<i>Not Available</i>" : general.tax_document;
				var note = (general.note == null || general.note == "") ? "<i>Not available</i>" : general.note;
				var date = general.date;
				var type = general.type;
				var value = general.value;

				$('#invoiceName_p').html(name);
				$('#invoiceTax_p').html(tax);
				$('#invoiceDate_p').html(my_date_format(date));
				$('#invoiceType_p').html(type);
				$('#invoiceInformation_p').html(note);

				$('#invoiceValue_p').html("Rp. " + numeral(value).format('0,0.00'));

				var supplierId = general.supplier_id;
				var opponentId = general.other_opponent_id;
				if(supplierId == null){
					var supplier = response.supplier;
					var opponentName = supplier.name;
					var complete_address = supplier.description;
					var opponentCity = supplier.type;
				} else {
					var supplier = response.supplier;
					var complete_address		= '';
					var opponentName			= supplier.name;
					complete_address			+= supplier.address;
					var opponentCity			= supplier.city;
					var supplier_number			= supplier.number;
					var supplier_rt				= supplier.rt;
					var supplier_rw				= supplier.rw;
					var supplier_postal			= supplier.postal_code;
					var supplier_block			= supplier.block;
					var supplier_id				= supplier.id;
			
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

				$('#opponentName_p').html(opponentName);
				$('#opponentAddress_p').html(complete_address);
				$('#opponentCity_p').html(opponentCity);

				var payables = response.payable;
				$('#payableTableContent').html("");
				$.each(payables, function(index, value){
					var date = value.date;
					var payment = value.value;
					$('#payableTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>Rp. " + numeral(payment).format('0,0.00') + "</td></tr>");
				});

				if(payables.length > 0){
					$('#payableTable').show();
					$('#payableTableText').html("");
				} else {
					$('#payableTable').hide();
					$('#payableTableText').html("<p>There is no payable found.</p><button class='button button_danger_dark' onclick='confirmDeleteBlankInvoice(" + id + ")'><i class='fa fa-trash'></i></button>");
				}

				$('#otherTable').show();
				$('#itemTable').hide();

				$('#viewInvoiceWrapper').fadeIn(300, function(){
					$('#viewInvoiceWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function confirmDeleteInvoice(n){
		deleteInvoiceId = n;
		blankDeleteInvoiceId = null;
		$('#deleteInvoiceWrapper').fadeIn(300);
	}

	function confirmDeleteBlankInvoice(n){
		blankDeleteInvoiceId = n;
		deleteInvoiceId = null;
		$('#deleteInvoiceWrapper').fadeIn(300);
	}

	function deleteInvoice(){
		var deleteMode = (deleteInvoiceId == null) ? "blank" : "regular";
		$.ajax({
			url:"<?= site_url('Administrators/deleteDebtById') ?>",
			data:{
				id:deleteInvoiceId,
				type:deleteMode
			},
			type:"POST",
			beforeSend:function(){
				$('button').attr('disabled', true);
				$('input').attr('readonly', true);
			},
			success:function(response){
				refresh_view();
				$('button').attr('disabled', false);
				$('input').attr('readonly', false);
				if(response == 1){
					deleteInvoiceId = null;
					deleteInvoiceId = null;
					$('#deleteInvoiceWrapper').fadeOut();
					$('#viewInvoiceWrapper .slide_alert_close_button').click();
				} else {
					$('#errorDeleteInvoice').fadeTo(1, 250);
					setTimeout(function(){
						$('#errorDeleteInvoice').fadeTo(0, 250);
					}, 1000)
				}
			}
		})
	}
</script>
