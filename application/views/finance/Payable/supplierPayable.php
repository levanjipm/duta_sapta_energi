<?php
	$supplierName = $supplier->name;
	$supplierAddress = $supplier->address;
	$supplierCity = $supplier->city;
?>
<head>
	<title><?= $supplierName ?> Payable</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-usd'></i></a> / <a href='<?= site_url("Payable/finance") ?>'>Payable</a> / <?= $supplierName ?></p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Supplier</label>
		<p><?= $supplierName ?></p>
		<p><?= $supplierAddress ?></p>
		<p><?= $supplierCity ?></p>

		<label>Payable</label> <a role='button' href='<?= site_url('Payable/viewCompleteBySupplierId/') . $supplier->id ?>' class='button button_mini_tab'><i class='fa fa-history'></i></a>
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
		<p id='payableTableText'>There is no incomplete payable found.</p>
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

<?php if($user_login->access_level > 2){ ?>
		<form id='completeForm'>
			<p><strong>Set Transaction As Done</strong></p>
			<label>Date</label>
			<input type='date' class='form-control' name='date' id='date' required>
			<br>

			<button type='button' class='button button_default_dark' id='completeFormButton'><i class='fa fa-long-arrow-right'></i></button>

			<div class='notificationText danger' id='completeNotification'><p>Failed to set invoice as done.</p></div>
		</form>

		<script>
			$('#completeForm').validate();
			$('#completeFormButton').click(function(){
				if($('#completeForm').valid()){
					if(invoiceId == null){
						var id = otherInvoiceId;
						var type = 2;
					} else {
						var id = invoiceId;
						var type = 1;
					}

					$.ajax({
						url:"<?= site_url('Payable/setInvoiceAsDone') ?>",
						data:{
							id: id,
							date: $('#date').val(),
							type: type
						},
						type:"POST",
						beforeSend:function(){
							$("button").attr('disabled', true);
						},
						success:function(response){
							$("button").attr('disabled', false);
							refreshView();
							if(response == 1){
								$('#viewInvoiceWrapper .slide_alert_close_button').click();
								invoiceId = null;
								$('#date').val("");
							} else {
								$('#completeNotification').fadeIn(250);
								setTimeout(function(){
									$('#completeNotification').fadeOut(250);
								}, 1000)
							}
						}
					})
				}
			});
		</script>
<?php } ?>
	</div>
</div>

<div class='alert_wrapper' id='deletePayableWrapper'>
	<div class='alert_box_confirm_wrapper'>
		<div class='alert_box_confirm_icon'><i class='fa fa-trash'></i></div>
		<div class='alert_box_confirm'>
			<input type='hidden' id='delete_customer_id'>
			<h3>Delete confirmation</h3>
			
			<p>You are about to delete this data.</p>
			<p>Are you sure?</p>
			<button class='button button_default_dark' onclick="$('#deletePayableWrapper').fadeOut()">Cancel</button>
			<button class='button button_danger_dark' onclick='deletePayable()'>Delete</button>
			
			<br><br>
			
			<p style='font-family:museo;background-color:#f63e21;width:100%;padding:5px;color:white;position:relative;bottom:0;left:0;opacity:0' id='errorDeletePayable'>Deletation failed.</p>
		</div>
	</div>
</div>

<script>
	var invoiceId;
	var otherInvoiceId;
	var payableId;

	$(document).ready(function(){
		refreshView();
	});

	function refreshView(){
		$.ajax({
			url:"<?= site_url('Payable/getCompletePayableBySupplierId') ?>",
			data:{
				id: '<?= $supplier->id ?>'
			},
			success:function(response){
				$('#payableTableContent').html("");
				var payableCount = 0;
				var totalPayable = 0;
				var items = response.items;
				$.each(items, function(index, item){
					var id 		= item.id;
					var date 	= item.date;
					var name 	= item.invoice_document;
					var value 	= parseFloat(item.value);
					var is_done	= item.is_done;
					var taxInvoice = (item.tax_document == "" || item.tax_document == null) ? "<i>Not available</i>" : item.tax_document;
					var paid = parseFloat(item.paid);
					var type = item.type;
					totalPayable += value;

					if(is_done == 0){
						if(type == 1){
						$('#payableTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><p>" + name + "</p><p>" + taxInvoice + "</p><button class='button button_mini_tab active' onclick='viewInvoice(" + id + ")'><i class='fa fa-eye'></i></button></td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Rp. 0,0.00</td><td>Rp. " + numeral(totalPayable).format('0,0.00') + "</td></tr>");
						payableCount++; 
						} else {
							$('#payableTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><p>" + name + "</p><p>" + taxInvoice + "</p><button class='button button_mini_tab active' onclick='viewBlankInvoice(" + id + ")'><i class='fa fa-eye'></i></button></td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Rp. 0,0.00</td><td>Rp. " + numeral(totalPayable).format('0,0.00') + "</td></tr>");
							payableCount++; 
						}
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
					

					if(payableArray.length > 0){
						$.each(payableArray, function(index, value){
							var paymentDate = value.date;
							var paymentValue = parseFloat(value.value);
							totalPayable -= paymentValue;
<?php if($user_login->access_level > 2){ ?>
							if(value.bank_id == null){
								var id = value.id;
								$('#payableTableContent').append("<tr class='success'><td>" + my_date_format(paymentDate) + "</td><td><p>Payment</p><button class='button button_default_dark' onclick='confirmDelete(" + id + ")'><i class='fa fa-trash'></i></button></td><td>Rp. 0,0.00</td><td>Rp. " + numeral(paymentValue).format('0,0.00') + "</td><td>Rp. " + numeral(totalPayable).format('0,0.00') + "</td></tr>");
							} else {
								$('#payableTableContent').append("<tr class='success'><td>" + my_date_format(paymentDate) + "</td><td><p>Payment</p></td><td>Rp. 0,0.00</td><td>Rp. " + numeral(paymentValue).format('0,0.00') + "</td><td>Rp. " + numeral(totalPayable).format('0,0.00') + "</td></tr>");
							}		
<?php } else { ?>
							$('#payableTableContent').append("<tr class='success'><td>" + my_date_format(paymentDate) + "</td><td><p>Payment</p</td><td>Rp. 0,0.00</td><td>Rp. " + numeral(paymentValue).format('0,0.00') + "</td><td>Rp. " + numeral(totalPayable).format('0,0.00') + "</td></tr>");
<?php } ?>
						})
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
			},
			complete:function(){
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
				id:n
			},
			success:function(response){
				invoiceId			= null;
				otherInvoiceId		= n;
				var general			= response.debt;
				var date			= general.date;
				var information		= general.information;
				var invoice			= general.invoice_document;
				var tax_document	= (general.tax_document == null || general.tax_document == "") ? "<i>Not available</i>" : general.tax_document;
				var value			= general.value;

				$('#invoiceDate_p').html(my_date_format(date));
				$('#invoiceName_p').html(invoice);
				$('#invoiceTax_p').html(tax_document);

				$('#blankInvoiceValue_p').html("Rp. " + numeral(value).format('0,0.00'));
				$('#blankInvoiceNote_p').html(information);
				
				$('#goodReceiptTable').hide();
				$('#blankTable').show();
			},
			complete:function(){
				$('#viewInvoiceWrapper').fadeIn(300, function(){
					$('#viewInvoiceWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function confirmDelete(n){
		payableId = n;
		$('#deletePayableWrapper').fadeIn();
	};

	function deletePayable(){
		$.ajax({
			url:"<?= site_url('Payable/deleteBlankById') ?>",
			data:{
				id: payableId
			},
			type:"POST",
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				refreshView();
				if(response == 1){
					payableId = null;
					$('#deletePayableWrapper').fadeOut();
				} else {
					$('#errorDeletePayable').fadeTo(250, 1);
					setTimeout(function(){
						$('#errorDeletePayable').fadeTo(250, 0);
					}, 1000);
				}
			}
		})
	}
</script>
