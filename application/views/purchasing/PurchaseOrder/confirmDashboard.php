<head>
	<title>Purchase order - Confirm</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Purchasing') ?>' title='Purchasing'><i class='fa fa-briefcase'></i></a> /Purchase order / Confirm</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div id='purchaseOrderTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Supplier</th>
					<th>Action</th>
				</tr>
				<tbody id='purchaseOrderTableContent'></tbody></tbody>
			</table>
		</div>

		<div id='purchaseOrderTableText'><p>There is no unconfirmed purchase order found.</p><p>Create a <a href='<?= site_url('Purchase_order/createDashboard') ?>'>new one</a>.</p></div>
	</div>
</div>
	
<div class='alert_wrapper' id='purchase_order_detail_wrapper'>
	<button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Confirm purchase order</h3>
		<hr>
		<label>Supplier</label>
		<p style='font-family:museo' id='supplier_name_p'></p>
		<p style='font-family:museo' id='supplier_address_p'></p>
		<p style='font-family:museo' id='supplier_city_p'></p>
		
		<label>Purchase order</label>
		<p style='font-family:museo' id='purchase_order_name_p'></p>
		<p style='font-family:museo' id='purchase_order_date_p'></p>
		<p style='font-family:museo' id='purchase_order_payment_p'></p>
		<p style='font-family:museo' id='purchase_order_taxing_p'></p>

		<p style='font-family:museo'>Created by <span id='createdBy_p'></span></p>

		<label>Status</label>
		<p style='font-family:museo' id='purchase_order_status_p'></p>
		<p style='font-family:museo' id='purchase_order_date_send_p'></p>
		
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
				<th>Price list</th>
				<th>Discount</th>
				<th>Net price</th>
				<th>Total price</th>
			</tr>
			<tbody id='purchase_order_table'></tbody>
		</table>

		<label>Note</label>
		<p id='note'></p>

		<input type='hidden' id='purchase_order_id'>
		<button type='button' class='button button_default_dark' style='display:inline-block' onclick='confirmPurchaseOrder()'><i class='fa fa-long-arrow-right'></i></button>
		<button type='button' class='button button_danger_dark' style='display:inline-block' onclick='deletePurchaseOrder()'><i class='fa fa-trash'></i></button>

		<div class='notificationText danger' id='confirmFailedNotification'><p>Failed to confirm purchase order.</p></div>
		<div class='notificationText danger' id='deleteFailedNotification'><p>Failed to delete purchase order.</p></div>

	</div>
</div>
<script>
	$(document).ready(function(){
		refresh_view()
	});

	function refresh_view(){
		$.ajax({
			url:'<?= site_url('Purchase_order/getUnconfirmedPurchaseOrders') ?>',
			success:function(response){
				var purchaseOrderCount = 0;
				$('#purchaseOrderTableContent').html('');

				$.each(response, function(index, value){
					var id = value.id;
					var date = value.date;
					var name = value.name;
					var supplier = value.supplier;

					var supplier_name = supplier.name;
					var supplier_address		= supplier.address;
					var supplier_number			= supplier.number;
					var supplier_block			= supplier.block;
					var supplier_rt				= supplier.rt;
					var supplier_rw				= supplier.rw;
					var supplier_city			= supplier.city;
					var supplier_postal_code	= supplier.postal_code;
					var complete_address		= supplier_address;

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

					$('#purchaseOrderTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td><p>" + supplier_name + "</p><p>" + complete_address + "</p></td><td><button class='button button_default_dark' onclick='viewPurchaseOrder(" + id + ")'><i class='fa fa-eye'></i></button>");

					purchaseOrderCount++;
				});

				if(purchaseOrderCount > 0){
					$('#purchaseOrderTable').show();
					$('#purchaseOrderTableText').hide();
				} else {
					$('#purchaseOrderTable').hide();
					$('#purchaseOrderTableText').show();
				}
			}
		});
	}

	function viewPurchaseOrder(n){
		$.ajax({
			url:'<?= site_url('Purchase_order/getById/') ?>' + n,
			dataType:'json',
			success:function(response){
				var general_data		= response.general;
				var purchase_order_id	= general_data.id;
				var note				= general_data.note;
				var status				= general_data.status;
				var date_send_request	= general_data.date_send_request;
				var creator				= general_data.created_by;
				var taxing				= general_data.taxing;

				$('#purchase_order_taxing_p').html((taxing == 1) ? "( Taxable purchase )" : "( Non taxable purchase )");

				$('#createdBy_p').html((creator == "" || creator == null) ? "<i>Not available</i>" : creator);

				$('#purchase_order_status_p').html((status == "" || status == null)? "<i>-</i>" : status);
				$('#purchase_order_date_send_p').html((date_send_request == "" || date_send_request == null)? "<i>-</i>" : my_date_format(date_send_request));

				if(note == ""){
					var noteText = "<i>Not availble</i>";
				} else {
					var noteText = note;
				}

				$('#note').html(noteText);

				var supplier 				= response.supplier;
				var supplierName 			= supplier.name;
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
				
				$('#supplier_name_p').html(supplierName);
				$('#supplier_address_p').html(complete_address);
				$('#supplier_city_p').html(supplier_city);
				
				$('#purchase_order_id').val(purchase_order_id);
				
				var purchase_order_name	= general_data.name;
				var purchase_order_date	= general_data.date;
				
				$('#purchase_order_name_p').html(purchase_order_name);
				$('#purchase_order_date_p').html(my_date_format(purchase_order_date));

				$('#purchase_order_payment_p').html(numeral(general_data.payment).format('0,0') + " day(s)");
				
				var detail_data			= response.detail;
				var purchase_order_value = 0;
				$('#purchase_order_table').html('');
				$.each(detail_data, function(index, value){
					var reference 	= value.reference;
					var name		= value.name
					var price_list	= value.price_list;
					var net_price	= value.net_price;
					var discount	= 100 * (1 - (net_price / price_list));
					var quantity	= value.quantity;
					var total_price	= quantity * net_price;
					purchase_order_value += total_price;
					
					$('#purchase_order_table').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(price_list).format('0,0.00') + "</td><td>" + numeral(discount).format('0,0.00') + "%</td><td>Rp. " + numeral(net_price).format('0,0.00') + "</td><td>" + numeral(total_price).format('0,0.00') + "</td></tr>");
				});
				
				$('#purchase_order_table').append("<tr><td colspan='5'></td><td>Total</td><td>" + numeral(purchase_order_value).format('0,0.00') + "</td></tr>");
				
				$('#purchase_order_detail_wrapper').fadeIn(300, function(){
					$('#purchase_order_detail_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}
	
	function deletePurchaseOrder(){
		$.ajax({
			url:'<?= site_url('Purchase_order/deleteById') ?>',
			data:{
				id:$('#purchase_order_id').val()
			},
			type:'POST',
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				refresh_view();

				if(response != 1){
					$('#deleteFailedNotification').fadeIn(250);
					setTimeout(function(){
						$('#deleteFailedNotification').fadeOut(250);
					}, 1000);
				} else {
					$('#purchase_order_detail_wrapper .slide_alert_close_button').click();	
				}
			}
		});
	};

	function confirmPurchaseOrder(){
		$.ajax({
			url:'<?= site_url('Purchase_order/confirmById') ?>',
			data:{
				id:$('#purchase_order_id').val()
			},
			type:'POST',
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				refresh_view();

				if(response != 1){
					$('#confirmFailedNotification').fadeIn(250);
					setTimeout(function(){
						$('#confirmFailedNotification').fadeOut(250);
					}, 1000);
				} else {
					window.location.href="<?= site_url('Purchase_order/print/') ?>" + $('#purchase_order_id').val();
				}
			}
		});
	};
</script>
