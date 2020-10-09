<head>
	<title>Invoice - Delete</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Administrators') ?>' title='Administrators'><i class='fa fa-briefcase'></i></a> Invoice / Delete</p>
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
			<label>Sales Order</label>
			<p id='salesOrderName_p'></p>
			<p id='salesOrderDate_p'></p>
			<p>Seller: <span id='salesOrderSeller_p'></span></p>

			<label>Delivery order</label>
			<p id='deliveryOrderName_p'></p>
			<p id='deliveryOrderDate_p'></p>

			<div class='table-responsive-lg'>
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
					<tbody id='itemTableContent'></tbody>
				</table>
			</div>
		</div>

		<div id='otherTable'>
			<label>Information</label>
			<p id='invoiceInformation_p'></p>
			
			<label>Value</label>
			<p id='invoiceValue_p'></p>
		</div>

		<hr>

		<label>Receivable</label>
		<div id='receivableTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Account</th>
					<th>Value</th>
				</tr>
				<tbody id='receivableTableContent'></tbody>
			</table>
		</div>
		<div id='receivableTableText'>
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
			url:"<?= site_url('Invoice/getConfirmedItems') ?>",
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
					var id = item.id;
					var date = item.date;
					var name = item.name;
					var taxInvoice = (item.taxInvoice == null || item.taxInvoice == "") ? "<i>Not available</i>" : item.taxInvoice;
					var value = item.value;
					var opponentName = item.opponentName;
					var opponentCity = item.opponentCity;

					$('#invoiceTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><label>" + name + "</label><p>" + taxInvoice + "</p></td><td><p>" + opponentName + "</p><p>" + opponentCity + "</p></td><td><button class='button button_default_dark' onclick='viewInvoice(" + id + ")'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
					itemCount++;
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
			url:"<?= site_url('Invoice/getById') ?>",
			data:{
				id: id
			},
			success:function(response){
				var invoice = response.invoice;
				var invoiceName = invoice.name;
				var invoiceDate = invoice.date;
				var invoiceTax = (invoice.taxInvoice == null || invoice.taxInvoice == "") ? "<i>Not available</i>" : invoice.taxInvoice;

				$('#invoiceName_p').html(invoiceName);
				$('#invoiceTax_p').html(invoiceTax);
				$('#invoiceDate_p').html(my_date_format(invoiceDate));

				var customer = response.customer;
				if(customer == null){
					var opponent = response.opponent;
					var name = opponent.name;
					var description = opponent.description;
					var type = opponent.type;

					$('#opponentName_p').html(name);
					$('#opponentAddress_p').html(description);
					$('#opponentCity_p').html(type);
				} else {
					var complete_address		= '';
					var customer_name			= customer.name;
					complete_address			+= customer.address;
					var customer_city			= customer.city;
					var customer_number			= customer.number;
					var customer_rt				= customer.rt;
					var customer_rw				= customer.rw;
					var customer_postal			= customer.postal_code;
					var customer_block			= customer.block;
		
					if(customer_number != null){
						complete_address	+= ' No. ' + customer_number;
					}
					
					if(customer_block != null && customer_block != "000"){
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

					$('#opponentName_p').html(customer_name);
					$('#opponentAddress_p').html(complete_address);
					$('#opponentCity_p').html(customer_city);
				}
				
				if(response.delivery_order != null){
					var deliveryOrder = response.delivery_order;
					var deliveryOrderName = deliveryOrder.name;
					var deliveryOrderDate = deliveryOrder.date;

					$('#deliveryOrderName_p').html(deliveryOrderName);
					$('#deliveryOrderDate_p').html(my_date_format(deliveryOrderDate));

					var salesOrder = response.sales_order;
					var salesOrderName = salesOrder.name;
					var salesOrderDate = salesOrder.date;
					var salesOrderSeller = (salesOrder.seller == null) ? "<i>Not available</i>" : salesOrder.seller;

					$('#salesOrderName_p').html(salesOrderName);
					$('#salesOrderDate_p').html(my_date_format(salesOrderDate));
					$('#salesOrderSeller_p').html(salesOrderSeller);

					var items = response.items;
					$('#itemTableContent').html("");
					var deliveryOrderValue = 0;
					$.each(items, function(index, item){
						var reference = item.reference;
						var name = item.name;
						var price_list = parseFloat(item.price_list);
						var discount = parseFloat(item.discount);
						var unitPrice = price_list * (100 - discount) / 100;
						var quantity = parseInt(item.quantity);
						var totalPrice = unitPrice * quantity;

						$('#itemTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>Rp. " + numeral(price_list).format('0,0.00') + "</td><td>" + numeral(discount).format('0,0.00') + "%</td><td>Rp. " + numeral(unitPrice).format('0,0.00') + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(totalPrice).format('0,0.00') + "</td></tr>");

						deliveryOrderValue += totalPrice;
					});

					$('#itemTableContent').append("<tr><td colspan='5'></td><td>Total</td><td>Rp. " + numeral(deliveryOrderValue).format('0,0.00') + "</td></tr>");

					$('#itemTable').show();
					$('#otherTable').hide();
				} else {
					var value = invoice.value;
					var information = (invoice.information == null || invoice.information == "") ? "<i>Not available</i>" : invoice.information;
					$('#invoiceValue_p').html("Rp. " + numeral(value).format('0,0.00'));
					$('#invoiceInformation_p').html(information);


					$('#itemTable').hide();
					$('#otherTable').show();
				}
				
				var receivables = response.receivable;
				var receivableCount = 0;
				$('#receivableTableContent').html("");
				$.each(receivables, function(index, item){
					var date = item.date;
					var accountName = item.name;
					var accountNumber = item.number;
					var value = item.value;
					$('#receivableTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><p>" + accountName + "</p><p>" + accountNumber + "</p></td><td>Rp. " + numeral(value).format('0,0.00') + "</td></tr>");
					receivableCount++;
				});

				if(receivableCount > 0){
					$('#receivableTable').show();
					$('#receivableTableText').html("");
					$('#receivableTableText').hide();
				} else {
					$("#receivableTable").hide();
					$('#receivableTableText').html("<p>There is no receivable found.</p><br><button class='button button_danger_dark' onclick='confirmDeleteInvoice(" + invoice.id + ")'><i class='fa fa-trash'></i></button>");
					$('#receivableTableText').show();
				}

				$('#viewInvoiceWrapper').fadeIn(300, function(){
					$('#viewInvoiceWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function confirmDeleteInvoice(n){
		deleteInvoiceId = n;
		$('#deleteInvoiceWrapper').fadeIn(300);
	}

	function deleteInvoice(){
		$.ajax({
			url:"<?= site_url('Administrators/deleteInvoiceById') ?>",
			data:{
				id:deleteInvoiceId
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
