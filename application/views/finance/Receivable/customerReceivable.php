<?php
	$customerName = $customer->name;
	$customerAddress = $customer->address;
	$customerCity = $customer->city;
?>
<head>
	<title><?= $customerName ?> Receivable</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Receivable') ?>'>Receivable</a> / <?= $customerName ?></p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Customer</label>
		<p><?= $customerName ?></p>
		<p><?= $customerAddress ?></p>
		<p><?= $customerCity ?></p>

		<label>Receivable</label><a role='button' href='<?= site_url("Receivable/viewCompleteByCustomerId/") . $customer->id ?>' class='button button_mini_tab'><i class='fa fa-history'></i></a>
		<div id='receivableTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Debit</th>
					<th>Credit</th>
					<th>Balance</th>
				</tr>
				<tbody id='receivableTableContent'></tbody>
			</table>
		</div>
		<p id='receivableTableText'>There is no incomplete receivable found.</p>
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

		<div id='deliveryOrderTable'>
			<label>Sales Order</label>
			<p id='salesOrderDate_p'></p>
			<p id='salesOrderName_p'></p>
			<p>Salesman: <span id='salesOrderSeller_p'></span></p>

			<label>Delivery Order</label>
			<p id='deliveryOrderName_p'></p>
			<p id='deliveryOrderDate_p'></p>
			<br>
			<table class='table table-bordered'>
				<tr>
					<th>Reference</th>
					<th>Name</th>
					<th>Pricelist</th>
					<th>Discount</th>
					<th>Unit price</th>
					<th>Quantity</th>
					<th>Total price</th>
				</tr>
				<tbody id='deliveryOrderTableContent'></tbody>
			</table>
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
					$.ajax({
						url:"<?= site_url('Receivable/setInvoiceAsDone') ?>",
						data:{
							id: invoiceId,
							date: $('#date').val()
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

<div class='alert_wrapper' id='deleteReceivableWrapper'>
	<div class='alert_box_confirm_wrapper'>
		<div class='alert_box_confirm_icon'><i class='fa fa-trash'></i></div>
		<div class='alert_box_confirm'>
			<input type='hidden' id='delete_customer_id'>
			<h3>Delete confirmation</h3>
			
			<p>You are about to delete this data.</p>
			<p>Are you sure?</p>
			<button class='button button_default_dark' onclick="$('#deleteReceivableWrapper').fadeOut()">Cancel</button>
			<button class='button button_danger_dark' onclick='deleteReceivable()'>Delete</button>
			
			<br><br>
			
			<p style='font-family:museo;background-color:#f63e21;width:100%;padding:5px;color:white;position:relative;bottom:0;left:0;opacity:0' id='errorDeleteReceivable'>Deletation failed.</p>
		</div>
	</div>
</div>

<script>
	var invoiceId;
	var receivableId;

	$(document).ready(function(){
		refreshView();
	});

	function refreshView(){
		$.ajax({
			url:"<?= site_url('Receivable/getCompleteReceivableByCustomerId') ?>",
			data:{
				id: '<?= $customer->id ?>'
			},
			success:function(response){
				$('#receivableTableContent').html("");
				var receivableCount = 0;
				var totalReceivable = 0;
				var items = response.invoices;
				$.each(items, function(index, item){
					var id = item.id;
					var date = item.date;
					var name = item.name;
					var value = parseFloat(item.value) + parseFloat(item.delivery) - parseFloat(item.discount);
					var taxInvoice = (item.taxInvoice == "" || item.taxInvoice == null) ? "<i>Not available</i>" : item.taxInvoice;
					var paid = parseFloat(item.paid);
					totalReceivable += value;

					if(item.is_done == 0){
						$('#receivableTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><p>" + name + "</p><p>" + taxInvoice + "</p><button class='button button_mini_tab active' onclick='viewInvoice(" + id + ")'><i class='fa fa-eye'></i></button></td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Rp. 0,0.00</td><td>Rp. " + numeral(totalReceivable).format('0,0.00') + "</td></tr>");
						receivableCount++; 

						var receivableArray = response.receivables.filter(function(receivable){
							return receivable.invoice_id == id;
						});

						if(receivableArray.length > 0){
							$.each(receivableArray, function(index, value){
								var paymentDate = value.date;
								var paymentValue = parseFloat(value.value);
								totalReceivable -= paymentValue;
<?php if($user_login->access_level > 2){ ?>
							if(value.bank_id == null){
								var id = value.id;
								$('#receivableTableContent').append("<tr class='success'><td>" + my_date_format(paymentDate) + "</td><td><p>Payment</p><button class='button button_default_dark' onclick='confirmDelete(" + id + ")'><i class='fa fa-trash'></i></button></td><td>Rp. 0,0.00</td><td>Rp. " + numeral(paymentValue).format('0,0.00') + "</td><td>Rp. " + numeral(totalReceivable).format('0,0.00') + "</td></tr>");
							} else {
								$('#receivableTableContent').append("<tr class='success'><td>" + my_date_format(paymentDate) + "</td><td><p>Payment</p></td><td>Rp. 0,0.00</td><td>Rp. " + numeral(paymentValue).format('0,0.00') + "</td><td>Rp. " + numeral(totalReceivable).format('0,0.00') + "</td></tr>");
							}
							
<?php } else { ?>
							$('#receivableTableContent').append("<tr class='success'><td>" + my_date_format(paymentDate) + "</td><td><p>Payment</p</td><td>Rp. 0,0.00</td><td>Rp. " + numeral(paymentValue).format('0,0.00') + "</td><td>Rp. " + numeral(totalReceivable).format('0,0.00') + "</td></tr>");
<?php } ?>
							})
						}
					}	
				});

				var pendingBankValue = 0;
				var items = response.pendingBank;
				$.each(items, function(index, item ){
					pendingBankValue += parseFloat(item.value);
				});

				if(pendingBankValue > 0){
					$('#receivableTableContent').append("<tr><td colspan='2'><label>Pending bank data</label></td><td></td><td>Rp. " + numeral(pendingBankValue).format('0,0.00') + "</td><td></td></tr>");
					receivableCount++;
				}

				if(receivableCount > 0){
					$('#receivableTableText').hide();
					$('#receivableTable').show();
				} else {
					$('#receivableTableText').show();
					$('#receivableTable').hide();
				}
			}
		});
	}

	function viewInvoice(n){
		$.ajax({
			url:"<?= site_url('Invoice/getById') ?>",
			data:{
				id: n
			},
			success:function(response){
				invoiceId = n;
				if(response.sales_order != null){
					var salesOrder = response.sales_order;
					var salesOrderName = salesOrder.name;
					var salesOrderDate = salesOrder.date;
					var salesOrderSeller = (salesOrder.seller == null) ? "<i>Not available</i>" : salesOrder.seller;

					$('#salesOrderName_p').html(salesOrderName);
					$('#salesOrderSeller_p').html(salesOrderSeller);
					$('#salesOrderDate_p').html(my_date_format(salesOrderDate));

					var invoice = response.invoice;
					var invoiceDate = invoice.date;
					var is_done		= invoice.is_done;
					var invoiceName = invoice.name;
					var invoiceTax = (invoice.taxInvoice == null || invoice.taxInvoice == "") ? "<i>Not available</i>" : invoice.taxInvoice;
					var invoiceDiscount = parseFloat(invoice.discount);
					var invoiceDelivery = parseFloat(invoice.delivery);

					$('#invoiceDate_p').html(my_date_format(invoiceDate));
					$('#invoiceName_p').html(invoiceName);
					$('#invoiceTax_p').html(invoiceTax);

					var items = response.items;
					var invoiceValue = 0;
					$('#deliveryOrderTableContent').html("");
					$.each(items, function(index, value){
						var reference = value.reference;
						var name = value.name;
						var pricelist = parseFloat(value.price_list);
						var discount = parseFloat(value.discount);
						var unitprice = (pricelist) * (100 - discount) / 100;
						var quantity = parseInt(value.quantity);
						var totalPrice = quantity * unitprice;
						invoiceValue += totalPrice;
					
						$('#deliveryOrderTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>Rp. " + numeral(pricelist).format("0,0.00") + "</td><td>" + numeral(discount).format('0,0.00') + "%</td><td>Rp. " + numeral(unitprice).format('0,0.00') + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(totalPrice).format('0,0.00') + "</td></tr>");
					});

					var totalValue = invoiceValue - invoiceDiscount + invoiceDelivery;

					$('#deliveryOrderTableContent').append("<tr><td colspan='4'></td><td colspan='2'>Sub Total</td><td col>Rp. " + numeral(invoiceValue).format('0,0.00') + "</td></tr>");
					$('#deliveryOrderTableContent').append("<tr><td colspan='4'></td><td colspan='2'>Discount</td><td>Rp. " + numeral(invoiceDiscount).format('0,0.00') + "</td></tr>");
					$('#deliveryOrderTableContent').append("<tr><td colspan='4'></td><td colspan='2'>Delivery</td><td>Rp. " + numeral(invoiceDelivery).format('0,0.00') + "</td></tr>");
					$('#deliveryOrderTableContent').append("<tr><td colspan='4'></td><td colspan='2'>Grand Total</td><td>Rp. " + numeral(totalValue).format('0,0.00') + "</td></tr>");

					var deliveryOrder = response.delivery_order;
					var deliveryOrderName = deliveryOrder.name;
					var deliveryOrderDate = deliveryOrder.date;

					$('#deliveryOrderName_p').html(deliveryOrderName);
					$("#deliveryOrderDate_p").html(my_date_format(deliveryOrderDate));

					$('#deliveryOrderTable').show();
					$('#blankTable').hide();
				} else {
					var invoice			= response.invoice;
					var is_done			= invoice.is_done;
					var invoiceDate		= invoice.date;
					var invoiceName		= invoice.name;
					var invoiceTax		= (invoice.taxInvoice == null || invoice.taxInvoice == "") ? "<i>Not available</i>" : invoice.taxInvoice;
					var invoiceValue = invoice.value;
					var note = (invoice.information == null || invoice.information == "") ? "<i>Not available</i>" : invoice.information;

					$('#invoiceDate_p').html(my_date_format(invoiceDate));
					$('#invoiceName_p').html(invoiceName);
					$('#invoiceTax_p').html(invoiceTax);
					$('#blankInvoiceValue_p').html("Rp. " + numeral(invoiceValue).format('0,0.00'));

					$('#blankInvoiceNote_p').html(note);

					$('#deliveryOrderTable').hide();
					$('#blankTable').show();
					
				}

				if(is_done == 1){
					$('#completeForm').hide();
				} else {
					$('#completeForm').show();
				}

				$('#viewInvoiceWrapper').fadeIn(300, function(){
					$('#viewInvoiceWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
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
