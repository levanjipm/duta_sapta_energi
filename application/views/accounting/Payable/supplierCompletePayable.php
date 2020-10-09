<?php
	$supplierName = $supplier->name;
	$supplierAddress = $supplier->address;
	$supplierCity = $supplier->city;
?>
<head>
	<title><?= $supplierName ?> Payable</title>
	<style>
		@media print{
			body{
				visibility:hidden;
			}

			.dashboard_in{
				visibility:visible;
				width:100%;
				margin:0;
				left:0;
				top:0;
				position: absolute;
			}

			.button_mini_tab {
				display:none;
			}
		}
	</style>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> /Payable</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Supplier</label>
		<p><?= $supplierName ?></p>
		<p><?= $supplierAddress ?></p>
		<p><?= $supplierCity ?></p>

		<label>Payable</label> <a role='button' href='<?= site_url('Payable/viewBySupplierId/') . $supplier->id ?>' class='button button_mini_tab'><i class='fa fa-calendar-o' title='View incomplete transactions'></i></a> <button class='button button_mini_tab'><i class='fa fa-print'></i></button>
		<div id='payableTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Debit</th>
					<th>Credit</th>
					<th>Balance</th>
				</tr>
				<tbody id='payableTableContent'></tbody>
			</table>
		</div>
		<p id='payableTableText'>There is no payable found.</p>
	</div>
</div>

<div class='alert_wrapper' id='viewInvoiceWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>View Invoice</h3>
		<hr>
		<label>Invoice</label>
		<p id='invoiceDate_p'></p>
		<p id='invoiceName_p'></p>
		<p id='invoiceTax_p'></p>

		<div id='goodReceiptTable'>
			<label>Good Receipts</label>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
				</tr>
				<tbody id='goodReceiptTableContent'></tbody>
			</table>
			<br>

			<div class='table-responsive-lg'>
				<table class='table table-bordered'>
					<tr>
						<th>Reference</th>
						<th>Name</th>
						<th>Price</th>
						<th>Quantity</th>
						<th>Total price</th>
					</tr>
					<tbody id='itemTableContent'></tbody>
				</table>
			</div>
		</div>

		<div id='blankTable'>
			<label>Value</label>
			<p id='blankInvoiceValue_p'></p>

			<label>Note</label>
			<p id='blankInvoiceNote_p'></p>
		</div>
	</div>
</div>

<script>
	var invoiceId;
	var payableId;
	var otherInvoiceId;

	$(document).ready(function(){
		refreshView();
	});

	function refreshView(){
		$.ajax({
			url:"<?= site_url('Payable/getCompletePayableBySupplierIdAll') ?>",
			data:{
				id: '<?= $supplier->id ?>'
			},
			success:function(response){
				$('#payableTableContent').html("");
				var payableCount = 0;
				var totalPayable = 0;
				var items = response.items;
				$.each(items, function(index, item){
					var id = item.id;
					var date = item.date;
					var name = item.invoice_document;
					var value = parseFloat(item.value);
					var taxInvoice = (item.tax_document == "" || item.tax_document == null) ? "<i>Not available</i>" : item.tax_document;
					var paid = parseFloat(item.paid);
					var type = item.type;
					totalPayable += value;

					if(type == 1){
						$('#payableTableContent').append("<tr id='invoiceRow-" + id + "'><td>" + my_date_format(date) + "</td><td><p>" + name + "</p><p>" + taxInvoice + "</p><button class='button button_mini_tab active' onclick='viewInvoice(" + id + ")'><i class='fa fa-eye'></i></button></td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Rp. 0,0.00</td><td>Rp. " + numeral(totalPayable).format('0,0.00') + "</td></tr>");
						payableCount++; 
					} else {
						$('#payableTableContent').append("<tr id='blankInvoiceRow-" + id + "'><td>" + my_date_format(date) + "</td><td><p>" + name + "</p><p>" + taxInvoice + "</p><button class='button button_mini_tab active' onclick='viewBlankInvoice(" + id + ")'><i class='fa fa-eye'></i></button></td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Rp. 0,0.00</td><td>Rp. " + numeral(totalPayable).format('0,0.00') + "</td></tr>");
						payableCount++; 
					}
					

					if(type == 1){
						var payableArray = response.payable.filter(function(payable){
							return payable.purchase_id == id;
						});
					} else {
						var payableArray = response.payable.filter(function(payable){
							return payable.other_purchase_id == id;
						});
					}

					var paymentString = "";
					if(payableArray.length > 0){
						$.each(payableArray, function(index, value){
							var paymentDate = value.date;
							var paymentValue = parseFloat(value.value);
							totalPayable -= paymentValue;
							paymentString += "<tr><td>" + my_date_format(paymentDate) + "</td><td>Payment</td><td>Rp. 0,0.00</td><td>Rp. " + numeral(paymentValue).format('0,0.00') + "</td><td>Rp. " + numeral(totalPayable).format('0,0.00') + "</td></tr>";
						});
						console.log(paymentString);
						if(type == 1){
							$('#invoiceRow-' + id).after(paymentString);
						} else {
							$('#blankInvoiceRow-' + id).after(paymentString);
						}
					}
				});

				var pendingBankValue = parseFloat(response.pendingBankData);

				if(pendingBankValue > 0){
					$('#payableTableContent').append("<tr><td colspan='2'><label>Pending bank data</label></td><td></td><td>Rp. " + numeral(pendingBankValue).format('0,0.00') + "</td><td></td></tr>");
					payableCount++;
				}

				if(payableCount > 0){
					$('#payableTableText').hide();
					$('#payableTable').show();
				} else {
					$('#payableTableText').show();
					$('#payableTable').hide();
				}
			}
		});
	}

	function viewInvoice(n){
		$.ajax({
			url:"<?= site_url('Debt/getById') ?>",
			data:{
				id: n
			},
			success:function(response){
				invoiceId = n;
				otherInvoiceId = null;
				var debt			= response.debt;
				var date			= debt.date;
				var taxDocument		= (debt.tax_document == "" || debt.tax_document == null) ? "<i>Not available</i>" : debt.tax_document;
				var invoiceDocument	= debt.invoice_document;

				$('#invoiceDate_p').html(my_date_format(date));
				$('#invoiceName_p').html(invoiceDocument);
				$('#invoiceTax_p').html(taxDocument);

				var goodReceipts	= response.documents;
				$('#goodReceiptTableContent').html("");
				$.each(goodReceipts, function(index, goodReceipt){
					var name = goodReceipt.name;
					var date = goodReceipt.date;
					var creator	= goodReceipt.created_by;
					$('#goodReceiptTableContent').append("<tr><td><label>" + my_date_format(date) + "</label><p>Created by " + creator + "</p></td><td>" + name + "</td></tr>");
				});

				$('#itemTableContent').html("");
				var items		= response.details;
				var debtValue = 0;
				$.each(items, function(index, item){
					var reference	= item.reference;
					var name		= item.name;
					var quantity	= parseInt(item.quantity);
					var price		= parseFloat(item.billed_price);
					var totalPrice	= quantity * price;
					debtValue += totalPrice;

					$('#itemTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>Rp. " + numeral(price).format('0,0.00') + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(totalPrice).format('0,0.00') + "</td></tr>");
				})

				$('#itemTableContent').append("<tr><td colspan='3'></td><td>Total</td><td>Rp. " + numeral(debtValue).format('0,0.00') + "</td></tr>");

				$('#blankTable').hide();
				$('#goodReceiptTable').show();

				$('#viewInvoiceWrapper').fadeIn(300, function(){
					$('#viewInvoiceWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function viewBlankInvoice(n){
		$.ajax({
			url:"<?= site_url('Debt/getBlankById') ?>",
			data:{
				id: n
			},
			success:function(response){
				console.log(response);
			}
		})
	}

	function confirmDelete(n){
		receivableId = n;
		$('#deleteReceivableWrapper').fadeIn();
	};

	function deleteReceivable(){
		$.ajax({
			url:"<?= site_url('Receivable/deleteBlankById') ?>",
			data:{
				id: receivableId
			},
			type:"POST",
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				refreshView();
				if(response == 1){
					receivableId = null;
					$('#deleteReceivableWrapper').fadeOut();
				} else {
					$('#errorDeleteReceivable').fadeTo(250, 1);
					setTimeout(function(){
						$('#errorDeleteReceivable').fadeTo(250, 0);
					}, 1000);
				}
			}
		})
	}
</script>
