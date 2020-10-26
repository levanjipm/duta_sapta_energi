<head>
	<title>Delivery Order - Delete</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Administrators') ?>' title='Administrators'><i class='fa fa-briefcase'></i></a> /Delivery Order / Delete</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Warning</label>
		<p>Delivery order(s) to be deleted must fullfill these requirements:</p>
		<ul>
			<li>Delivery order has been conirmed and sent.</li>
			<li>Delivery order have not been invoiced.</li>
		</ul>
		<div id='deliveryOrderTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Customer</th>
					<th>Action</th>
				</tr>
				<tbody id='deliveryOrderTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='deliveryOrderTableText'>There is no delivery order to be deleted.</p>
	</div>
</div>

<div class='alert_wrapper' id='deliveryOrderWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Delete Delivery Order</h3>
		<hr>
		<label>Customer</label>
		<p id='customerName_p'></p>
		<p id='customerAddress_p'></p>
		<p id='customerCity_p'></p>

		<label>Delivery Order</label>
		<p id='deliveryOrderName_p'></p>
		<p id='deliveryOrderDate_p'></p>

		<label>Sales Order</label>
		<p id='salesOrderName_p'></p>
		<p id='salesOrderDate_p'></p>
		<p id='salesOrderSeller_p'></p>

		<label>Items</label>
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
			</tr>
			<tbody id='itemTableContent'></tbody>
		</table>

		<button class='button button_danger_dark' onclick='confirmDeleteDeliveryOrder()'><i class='fa fa-trash'></i></button>

		<div class='notificationText danger' id='deleteDeliveryOrderNotification'><p>Failed to delete delivery order</p></div>
	</div>
</div>

<div class='alert_wrapper' id='deleteDeliveryOrderWrapper'>
	<div class='alert_box_confirm_wrapper'>
		<div class='alert_box_confirm_icon'><i class='fa fa-trash'></i></div>
		<div class='alert_box_confirm'>
			<h3>Delete confirmation</h3>
			
			<p>You are about to delete this data.</p>
			<p>Are you sure?</p>
			<button class='button button_default_dark' onclick="$('#deleteDeliveryOrderWrapper').fadeOut()">Cancel</button>
			<button class='button button_danger_dark' onclick='deleteDeliveryOrder()'>Delete</button>
			
			<br><br>
			
			<p style='font-family:museo;background-color:#f63e21;width:100%;padding:5px;color:white;position:relative;bottom:0;left:0;opacity:0' id='errorDeleteDeliveryOrder'>Deletation failed.</p>
		</div>
	</div>
</div>

<script>
	var deliveryOrderId;

	$(document).ready(function(){
		refreshView();
	});

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Delivery_order/getUninvoicedItems') ?>",
			data:{
				page: page
			},
			success:function(response){
				var items = response.items;
				$('#deliveryOrderTableContent').html("");
				var deliveryOrderCount = 0;
				$.each(items, function(index, item){
					var id = item.id;
					var name = item.name;
					var date = item.date;
					var customer = item.customer;
					var complete_address		= '';
					var customer_name			= customer.name;
					complete_address		+= customer.address;
					var customer_city			= customer.city;
					var customer_number			= customer.number;
					var customer_rt				= customer.rt;
					var customer_rw				= customer.rw;
					var customer_postal			= customer.postal_code;
					var customer_block			= customer.block;
					var customer_id				= customer.id;
		
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
					$('#deliveryOrderTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td><p>" + customer_name + "</p><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><button class='button button_default_dark' onclick='viewDeliveryOrder(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
					deliveryOrderCount++;
				});

				var pages = response.pages;
				$('#page').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}

				if(deliveryOrderCount > 0){
					$('#deliveryOrderTable').show();
					$('#deliveryOrderTableText').hide();
				} else {
					$('#deliveryOrderTable').hide();
					$('#deliveryOrderTableText').show();
				}
			}
		})
	}

	function viewDeliveryOrder(id){
		$.ajax({
			url:"<?= site_url('Delivery_order/getById') ?>",
			data:{
				id: id
			},
			success:function(response){
				deliveryOrderId = id;
				var customer = response.customer;
				var complete_address		= '';
				var customer_name			= customer.name;
				complete_address		+= customer.address;
				var customer_city			= customer.city;
				var customer_number			= customer.number;
				var customer_rt				= customer.rt;
				var customer_rw				= customer.rw;
				var customer_postal			= customer.postal_code;
				var customer_block			= customer.block;
				var customer_id				= customer.id;
		
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

				$('#customerName_p').html(customer_name);
				$('#customerAddress_p').html(complete_address);
				$('#customerCity_p').html(customer_city);

				var general = response.general;
				var name = general.name;
				var date = general.date;

				var salesOrderName = general.sales_order_name;
				var salesOrderDate = general.sales_order_date;
				var seller = general.seller;

				$('#deliveryOrderName_p').html(name);
				$('#deliveryOrderDate_p').html(my_date_format(date));

				$('#salesOrderName_p').html(salesOrderName);
				$('#salesOrderDate_p').html(my_date_format(salesOrderDate));
				$('#salesOrderSeller_p').html((seller == null || seller == "") ? "<i>Not available</i>" : seller);

				var items = response.items;
				$('#itemTableContent').html("");
				$.each(items, function(index, item){
					var reference = item.reference;
					var name = item.name;
					var quantity = item.quantity;

					$('#itemTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td></tr>");
				})

				$('#deliveryOrderWrapper').fadeIn(300, function(){
					$('#deliveryOrderWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function confirmDeleteDeliveryOrder()
	{
		$('#deleteDeliveryOrderWrapper').fadeIn();
	}

	function deleteDeliveryOrder(){
		$.ajax({
			url:"<?= site_url('Administrators/deleteDeliveryOrderById') ?>",
			data:{
				id: deliveryOrderId
			},
			type:"POST",
			beforeSend:function(){
				$('button').attr('disabled', true);
			}, 
			success:function(response){
				$('button').attr('disabled', false);
				refreshView();
				if(response == 1){
					deliveryOrderId = null;
					$('#deleteDeliveryOrderWrapper').fadeOut();
					$('#deliveryOrderWrapper .slide_alert_close_button').click();
				} else {
					$('#errorDeleteDeliveryOrder').fadeTo(250, 1);
					setTimeout(function(){
						$('#errorDeleteDeliveryOrder').fadeTo(250, 0);
					}, 1000);
				}
			}
		})
	}
</script>
