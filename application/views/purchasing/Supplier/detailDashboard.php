<?php
	$complete_address		= '';
	$complete_address		.= $supplier->address;
	$supplier_number		= $supplier->number;
	$supplier_block			= $supplier->block;
	$supplier_rt			= $supplier->rt;
	$supplier_rw			= $supplier->rw;
	$supplier_postal_code	= $supplier->postal_code;

	$complete_address		.= 'No. ' . $supplier_number;
	
	if($supplier_block		== '' && $supplier_block == '000'){
		$complete_address	.= 'Block ' . $supplier_block;
	};
	
	if($supplier_rt != '' && $supplier_rt != '000'){
		$complete_address	.= 'RT ' . $supplier_rt . '/ RW ' . $supplier_rw;
	}
	
	if($supplier_postal_code != ''){
		$complete_address	.= ', ' . $supplier_postal_code;
	}
?>
<head>
	<title>Supplier</title>
	<style>
		.viewPane{
		}
	</style>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Purchasing') ?>' title='Purchasing'><i class='fa fa-shopping-cart'></i></a> /<a href="<?= site_url('Supplier') ?>">Supplier</a> / <?= $supplier->name ?></p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class="row">
			<div class='col-md-4 col-sm-4 col-xs-12'>
				<label>General Data</label>
				<p><?= $supplier->name ?></p>
				<p><?= $complete_address ?></p>
				<p><?= $supplier->city ?></p>

				<label>Contact</label>
				<p><?= $supplier->pic_name ?></p>
				<p><?= $supplier->phone_number ?></p>
			</div>
			<div class='col-md-8 col-sm-8 col-xs-12'>
				<button class='button button_mini_tab' id='purchaseOrderButton'>Pending Purchase Orders</button>
				<button class='button button_mini_tab' id='payableButton'>Payable</button>
				<button class='button button_mini_tab' id='analyticsButton'>Analytics</button>
				<br><br>
				<div id='pendingPurchaseOrder' class='viewPane'>
					<div id='purchaseOrderTable'>
						<table class='table table-bordered'>
							<tr>
								<th>Date</th>
								<th>Name</th>
								<th>Action</th>
							</tr>
							<tbody id='purchaseOrderTableContent'></tbody>
						</table>

						<select class='form-control' id='page' style='width:100px'>
							<option value='1'>1</option>
						</select>
					</div>
					<p id='purchaseOrderTableText'>There is no pending purchase order found.</p>
				</div>
				<div id='payable' class='viewPane'>
					<div id='invoiceTable'>
						<table class='table table-bordered'>
							<tr>
								<th>Date</th>
								<th>Name</th>
								<th>Value</th>
								<th>Paid</th>
								<th>Action</th>
							</tr>
							<tbody id='invoiceTableContent'></tbody>
						</table>
					</div>
					<p id='invoiceTableText'>There is no payable left.</p>
				</div>
				<div id='analytics' class='viewPane'>
					<canvas id='chartWrapper' width="100" height="30"></canvas>
					<form id='valueForm'>
						<label>View Purchase Value</label>
						<div class='input_group'>
							<input type='date' class='form-control' id='dateStart' required>
							<input type='date' class='form-control' id='dateEnd' required>
							<div class='input_group_append'>
								<button type='button' class='button button_default_dark' onclick='calculateValue()'><i class='fa fa-long-arrow-right'></i></button>
							</div>
						</div>
						<p>Rp. <span id='purchaseValue'>0.00</span></p>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='invoiceWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<label>Invoice</label>
		<p id='invoiceNameP'></p>
		<p id='invoiceDateP'></p>
		<p id='invoiceTaxP'></p>

		<div id='regularDetail'>
			<table class='table table-bordered'>
				<tr>
					<th>Reference</th>
					<th>Name</th>
					<th>Price</th>
					<th>Quantity</th>
					<th>Total price</th>
				</tr>
				<tbody id='goodReceiptItemDetailContent'></tbody>
			</table>

			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
				</tr>
				<tbody id='goodReceiptContent'></tbody>
			</table>
		</div>

		<div id='blankDetail'>
			<label>Value</label>
			<p id='invoiceValueP'></p>

			<label>Note</label>
			<p id='invoiceNoteP'></p>

			<label>Type</label>
			<p id='invoiceTypeP'></p>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='purchaseOrderWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<label>Purchase Order</label>
		<p id='purchaseOrderDateP'></p>
		<p id='purchaseOrderNameP'></p>

		<label>Items</label>
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
				<th>Received</th>
				<th>Pdnding</th>
			</tr>
			<tbody id='itemTableContent'></tbody>
		</table>

		<label>Good Receipt</label>
		<table class='table table-bordered' id='goodReceiptTable'>
			<tr>
				<th>Date</th>
				<th>Name</th>
			</tr>
			<tbody id='goodReceiptTableContent'></tbody>
		</table>
		<p id='goodReceiptTableText'>There is no good receipt found.</p>
	</div>
</div>

<script>
	$('#valueForm').validate();
	
	$(document).ready(function(){
		$('.viewPane').hide();
		$('#purchaseOrderButton').click();
		getAnalytics();
	});

	$('.button_mini_tab').click(function(){
		$('.button_mini_tab').removeClass('active');
		$(this).addClass('active');

		$('.button_mini_tab').attr('disabled', false);
		$(this).attr('disabled', true);
	})

	$('#analyticsButton').click(function(){
		$('.viewPane').fadeOut(300);
		setTimeout(function(){
			$('#analytics').fadeIn(300);
		}, 300);
	})

	$('#payableButton').click(function(){
		getPayable(1);
		$('.viewPane').fadeOut(300);
		setTimeout(function(){
			$('#payable').fadeIn(300);
		}, 300);
	});

	$('#purchaseOrderButton').click(function(){
		getPendingItems(1);
		$('.viewPane').fadeOut(300);
		setTimeout(function(){
			$('#pendingPurchaseOrder').fadeIn(300);
		}, 300);
	});


	$('#page').change(function(){
		getPendingItems();
	})

	function getPendingItems(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Purchase_order/getPendingPurchaseOrder') ?>",
			data:{
				id:<?= $supplier->id ?>,
				page: page
			},
			success:function(response){
				var items		= response.items;
				var itemCount = 0;
				$('#purchaseOrderTableContent').html("");
				$.each(items, function(index, value){
					var name		= value.name;
					var date		= value.date;
					var id			= value.id;

					$('#purchaseOrderTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td><button class='button button_default_dark' onclick='viewPurchaseOrder(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
					itemCount++;
				});

				if(itemCount > 0){
					$('#purchaseOrderTable').show();
					$('#purchaseOrderTableText').hide();
				} else {
					$('#purchaseOrderTable').hide();
					$('#purchaseOrderTableText').show();
				}

				var pages		= response.pages;
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

	function viewPurchaseOrder(n){
		$.ajax({
			url:'<?= site_url('Purchase_order/getById/') ?>' + n,
			success:function(response){
				var general			= response.general;
				var name			= general.name;
				var date			= general.date;

				$('#purchaseOrderDateP').html(my_date_format(date));
				$('#purchaseOrderNameP').html(name);

				var items			= response.detail;
				$('#itemTableContent').html("");
				$.each(items, function(index, item){
					var reference		= item.reference;
					var name			= item.name;
					var net_price		= item.net_price;
					var quantity		= parseInt(item.quantity);
					var received		= parseInt(item.received);
					var pending			= quantity - pending;

					$('#itemTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>" + numeral(received).format('0,0') + "</td><td>" + numeral(pending).format('0,0') + "</td></tr>");
				});

				var goodReceipt			= response.goodReceipt;
				var goodReceiptCount	= 0;
				$('#goodReceiptTableContent').html("");
				$.each(goodReceipt, function(index, item){
					var date		= item.date;
					var name		= item.name;

					$('#goodReceiptTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td></tr>");
					goodReceiptCount++;
				});

				if(goodReceiptCount > 0){
					$('#goodReceiptTable').show();
					$('#goodReceiptTableText').hide();
				} else {
					$('#goodReceiptTable').hide();
					$('#goodReceiptTableText').show();
				}
			},
			complete:function(){
				$('#purchaseOrderWrapper').fadeIn(300, function(){
					$('#purchaseOrderWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function getPayable(page = $('#payablePage').val()){
		$.ajax({
			url:"<?= site_url('Payable/getCompletePayableBySupplierId/') ?>",
			data:{
				id: <?= $supplier->id ?>
			},
			success:function(response){
				var items = response.items;
				var itemCount = 0;
				$('#invoiceTableContent').html("");
				$.each(items, function(index, item){
					var date		= item.date;
					var name		= item.invoice_document;
					var value		= parseFloat(item.value);
					var paid		= parseFloat(item.paid);
					var pending		= value - paid;
					var id			= item.id;
					var type		= item.type;
					if(type == 1){
						$('#invoiceTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Rp. " + numeral(paid).format('0,0.00') + "</td><td><button class='button button_default_dark' onclick='viewInvoice(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
					} else {
						$('#invoiceTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Rp. " + numeral(paid).format('0,0.00') + "</td><td><button class='button button_default_dark' onclick='viewBlankInvoice(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
					}

					itemCount++;
				});

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
	
	function viewInvoice(n){
		$.ajax({
			url:"<?= site_url("Debt/getById") ?>",
			data:{
				id: n
			},
			beforeSend:function(){
				$('#regularDetail').show();
				$('#blankDetail').hide();
			},
			success:function(response){
				var debt				= response.debt;
				var date				= debt.date;
				var invoice_document 	= debt.invoice_document;
				var tax_document		= debt.tax_document;

				$('#invoiceNameP').html(invoice_document);
				$('#invoiceDateP').html(my_date_format(date));
				$('#invoiceTaxP').html(tax_document);

				var details				= response.details;
				$('#goodReceiptItemDetailContent').html("");
				var invoiceValue		= 0;
				$.each(details, function(index, value){
					var reference		= value.reference;
					var name			= value.name;
					var quantity		= parseFloat(value.quantity);
					var billed_price	= parseFloat(value.billed_price);
					var totalPrice		= quantity * billed_price;
					invoiceValue		+= totalPrice;

					$('#goodReceiptItemDetailContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>Rp. " + numeral(billed_price).format('0,0.00') + "</td><td>Rp. " + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(totalPrice).format('0,0.00') + "</td></tr>");
				});

				$('#goodReceiptItemDetailContent').append("<tr><td colspan='3'></td><td>Total</td><td>Rp. " + numeral(invoiceValue).format('0,0.00') + "</td></tr>");

				var documents		= response.documents;
				$('#goodReceiptContent').html("");
				$.each(documents, function(index, item){
					var name		= item.name;
					var date		= item.date;
					$('#goodReceiptContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td></tr>");
				})

				
			},
			complete:function(){
				$('#invoiceWrapper').fadeIn(300, function(){
					$('#invoiceWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}

	function viewBlankInvoice(n){
		$.ajax({
			url:"<?= site_url("Debt/getBlankById") ?>",
			data:{
				id: n
			},
			beforeSend:function(){
				$('#regularDetail').hide();
				$('#blankDetail').show();
			},
			success:function(response){
				var debt				= response.debt;
				var date				= debt.date;
				var invoice_document 	= debt.invoice_document;
				var tax_document		= debt.tax_document;

				$('#invoiceNameP').html(invoice_document);
				$('#invoiceDateP').html(my_date_format(date));
				$('#invoiceTaxP').html((tax_document == "" || tax_document == null) ? "<i>Not available</i>" : tax_document);

				var value				= debt.value;
				var type				= debt.type;
				var information			= debt.information;

				$('#invoiceValueP').html("Rp. " + numeral(value).format('0,0.00'));
				$('#invoiceNoteP').html(information);
				$('#invoiceTypeP').html(type);
			},
			complete:function(){
				$('#invoiceWrapper').fadeIn(300, function(){
					$('#invoiceWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}

	function getAnalytics(){
		$.ajax({
			url:"<?= site_url('Supplier/getPurchaseBySupplierId') ?>",
			data:{
				id: <?= $supplier->id ?>
			},
			success:function(response){
				var labelArray		= [];
				var valueArray		= [];
				$.each(response, function(index, item){
					var label = item.label;
					var value	= item.value;
					labelArray.push(label);
					valueArray.push(value);
				});

				var labelChartArray = labelArray.reverse();
				var valueChartArray	 = valueArray.reverse();

				var ctx = document.getElementById('chartWrapper').getContext('2d');
				var myLineChart = new Chart(ctx, {
					type: 'line',
					data: {
						labels: labelChartArray,
						datasets: [{
							backgroundColor: 'rgba(225, 155, 60, 0.4)',
							borderColor: 'rgba(225, 155, 60, 1)',
							data: valueChartArray
						}],
					},
					options: {
						legend:{
							display:false
						},
					}
				});
			}
		})
	}

	function calculateValue(){
		if($('#valueForm').valid()){
			$.ajax({
				url:"<?= site_url('Supplier/getValueByDateRange') ?>",
				data:{
					supplierId: <?= $supplier->id ?>,
					start: $('#dateStart').val(),
					end: $('#dateEnd').val()
				},
				type:"POST",
				success:function(response){
					$('#purchaseValue').html(numeral(response).format('0,0.00'));
				}
			})
		}
	}
</script>
