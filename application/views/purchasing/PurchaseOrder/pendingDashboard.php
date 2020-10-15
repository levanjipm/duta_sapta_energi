<head>
	<title>Purchase order - Pending</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Purchasing') ?>' title='Purchasing'><i class='fa fa-briefcase'></i></a> /Purchase Order / Pending</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Supplier</label>
		<select class='form-control' id='supplier_id'>
<?php
	foreach($suppliers as $supplier){
?>
			<option value='<?= $supplier->id ?>'><?= $supplier->name ?></option>
<?php
	}
?>
		</select>
		<br>
		
		<div id='purchaseOrderTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Action</th>
				</tr>
				<tbody id='purchaseOrderTableContent'></tbody>
			</table>
			
			<select class='form-control' style='width:100px' id='page'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='purchaseOrderTableText'>There is no pending purchase order.</p>
	</div>
</div>

<div class='alert_wrapper' id='purchaseOrderWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Purchase order</h3>
		<hr>
		<label>Supplier</label>
		<p style='font-family:museo' id='supplier_name_p'></p>
		<p style='font-family:museo' id='supplier_address_p'></p>

		<label>Purchase order</label>
		<p style='font-family:museo' id='purchase_order_name_p'></p>
		<p style='font-family:museo' id='purchase_order_date_p'></p>
		<p style='font-family:museo' id='purchase_order_status_p'></p>

		<label>Delivery address</label>
		<p style='font-family:museo' id='delivery_address_p'></p>
		<p style='font-family:museo' id='delivery_city_p'></p>
		<p style='font-family:museo' id='delivery_contact_person_p'></p>
		<p style='font-family:museo' id='delivery_contact_p'></p>

		<div class='table-responsive'>
			<table class='table table-bordered'>
				<tr>
					<th>Reference</th>
					<th>Name</th>
					<th>Pricelist</th>
					<th>Discount</th>
					<th>Net price</th>
					<th>Quantity</th>
					<th>Total price</th>
					<th>Pending</th>
				</tr>
				<tbody id='purchaseOrderItemTable'></tbody>
			</table>
		</div>

		<label>Delivery</label>
		<div id='goodReceiptTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Document</th>
					<th>Information</th>
				</tr>
				<tbody id='goodReceiptTableContent'></tbody>
			</table>
		</div>
		<p id='goodReceiptTableText'>There is no delivery found.</p>

		<button class='button button_default_dark' onclick='goToPurchaseOrder()'><i class='fa fa-eye'></i></button>
	</div>
</div>
<script>
	var purchaseOrderId;

	$(document).ready(function(){
		refresh_view();
	})

	$('#page').change(function(){
		refresh_view();
	})

	$('#supplier_id').change(function(){
		refresh_view(1);
	})

	function refresh_view(page = $('#page').val(), supplier_id = $('#supplier_id').val()){
		$.ajax({
			url:'<?= site_url('Purchase_order/getPendingPurchaseOrder') ?>',
			data:{
				page: page,
				supplier_id: supplier_id
			},
			type:'GET',
			success:function(response){
				var pages = response.pages;
				$('#page').html('');
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>")
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>")
					}
				}

				var countPurchaseOrder = 0;
				$('#purchaseOrderTableContent').html('');
				var items = response.items;
				$.each(items, function(index, item){
					var id = item.id;
					var date = item.date;
					var name = item.name;
					var is_confirm = item.is_confirm;
					var is_delete = item.is_delete;

					if(is_confirm == 1 && is_delete == 0){
						$('#purchaseOrderTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td><button class='button button_default_dark' onclick='viewPurchaseOrder(" + id + ")'><i class='fa fa-eye'></i></button>")
						countPurchaseOrder++;
					}
				})

				if(countPurchaseOrder > 0){
					$('#purchaseOrderTable').show();
					$('#purchaseOrderTableText').hide();
				} else {
					$('#purchaseOrderTable').hide();
					$('#purchaseOrerTableText').show();
				}
			}
		});
	}

	function viewPurchaseOrder(n){
		$.ajax({
			url:'<?= site_url('Purchase_order/getById/') ?>' + n,
			success:function(response){
				purchaseOrderId = n;
				var supplier = response.supplier;
				var supplier_name = supplier.name;

				var complete_address		= supplier.address;
				var supplier_number			= supplier.number;
				var supplier_block			= supplier.block;
				var supplier_rt				= supplier.rt;
				var supplier_rw				= supplier.rw;
				var supplier_city			= supplier.city;
				var supplier_postal_code	= supplier.postal_code;

				if(supplier_number != null && supplier_number != ''){
					complete_address	+= ' no. ' + supplier_number;
				};
				
				if(supplier_block != null && supplier_block != ''){
					complete_address	+= ', blok ' + supplier_block;
				};
				
				if(supplier_rt != '000'){
					complete_address	+= ', RT ' + supplier_rt + ', RW ' + supplier_rw;
				}
				
				if(supplier_postal_code != ''){
					complete_address += ', ' + supplier_postal_code;
				}
				
				complete_address += ', ' + supplier_city;

				$('#supplier_name_p').html(supplier_name);
				$('#supplier_address_p').html(complete_address);

				var general = response.general;
				
				var purchaseOrderStatus = general.status;
				var purchase_order_name = general.name;
				var purchase_order_date = general.date;

				$('#purchase_order_name_p').html(purchase_order_name);
				$('#purchase_order_date_p').html(my_date_format(purchase_order_date));
				$('#purchase_order_status_p').html(purchaseOrderStatus);

				var address = general.dropship_address;
				var city = general.dropship_city;
				var contact = general.dropship_contact;
				var contact_person = general.dropship_contact_person;

				if(address == null){
					$('#delivery_address_p').html("Jalan Babakan Hantap no. 23");
					$('#delivery_city_p').html("Bandung");
					$('#delivery_contact_person_p').html('');
					$('#delivery_contact_p').html('');
				} else {
					$('#delivery_address_p').html(address);
					$('#delivery_city_p').html(city);
					$('#delivery_contact_person_p').html(contact_person);
					$('#delivery_contact_p').html(contact);
				}

				var items = response.detail;
				$('#purchaseOrderItemTable').html('');
				$.each(items, function(index, item){
					var reference = item.reference;
					var name = item.name;
					var price_list = item.price_list;
					var net_price = item.net_price;
					var discount = 100 * (1 - (net_price / price_list));
					var received = item.received;
					var quantity = item.quantity;
					var pending = quantity - received;
					var total_price = net_price * quantity;

					$('#purchaseOrderItemTable').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>Rp." + numeral(price_list).format('0,0.00') + "</td><td>" + numeral(discount).format('0,0.00') + "%</td><td>Rp. " + numeral(net_price).format('0,0.00') + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(total_price).format('0,0.00') + "</td><td>" + numeral(pending).format('0,0') + "</td></tr>");
				});

				var goodReceipts		= response.goodReceipt;
				$('#goodReceiptTableContent').html("");
				var goodReceiptCount = 0;
				$.each(goodReceipts, function(index, goodReceipt){
					var date		= goodReceipt.date;
					var name		= goodReceipt.name;
					var created_by	= goodReceipt.created_by;
					var receivedDate	= goodReceipt.received_date;

					$('#goodReceiptTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td><p>Created by " + created_by + "</p><p>Created on" + my_date_format(receivedDate) + "</p></td></tr>");
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

	function goToPurchaseOrder(){
		window.location.href=("<?= site_url('Purchase_order/viewPurchaseOrderDetail/') ?>" + purchaseOrderId);
	}
</script>
