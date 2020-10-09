<head>
	<?php
		$complete_address		= '';
		$customer_name			= $customer->name;
		$complete_address		.= $customer->address;
		$customer_city			= $customer->city;
		$customer_number		= $customer->number;
		$customer_rt			= $customer->rt;
		$customer_rw			= $customer->rw;
		$customer_postal		= $customer->postal_code;
		$customer_block			= $customer->block;
		
		if($customer_number != null){
			$complete_address	.= ' No. ' . $customer_number;
		}
					
		if($customer_block != null && $customer_block != "000"){
			$complete_address	.= ' Blok ' . $customer_block;
		}
				
		if($customer_rt != '000'){
			$complete_address	.= ' RT ' . $customer_rt;
		}
					
		if($customer_rw != '000' && $customer_rt != '000'){
			$complete_address	.= ' /RW ' . $customer_rw;
		}
					
		if($customer_postal != null){
			$complete_address	.= ', ' . $customer_postal;
		}
	?>
	<title><?= $customer->name ?> Detail</title>
	<style>
		.progressBarWrapper{
			width:100%;
			height:30px;
			background-color:white;
			border-radius:10px;
			padding:5px;
			position:relative;
		}

		.progressBar{
			width:0;
			height:20px;
			background-color:#01bb00;
			position:relative;
			border-radius:10px;
			opacity:0.2;
		}

		.progressBarWrapper p{
			font-family:museo;
			color:black;
			font-weight:"bold";
			z-index:50;
			position:absolute;
			right:5px;
		}
	</style>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Customer') ?>'>Customer</a> / <a href='<?= site_url("Customer/viewCustomerDetail/") . $customer->id ?>'><?= $customer->name ?></a> / Sales Order</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='row'>
			<div class='col-md-12 col-sm-12 col-xs-12'>
				<label>General data</label>
				<p><?= $customer_name ?></p>
				<p><?= $complete_address ?>, <?= $customer_city ?></p>
				<p><?= $customer->npwp ?></p>

				<div id='salesOrderTable'>
					<table class='table table-bordered'>
						<tr>
							<th>Date</th>
							<th>Name</th>
							<th>Action</th>
						</tr>
						<tbody id='salesOrderTableContent'></tbody>
					</table>

					<select class='form-control' id='page' style='width:100px'>
						<option value='1'>1</option>
					</select>
				</div>
				<p id='salesOrderTableText'>There is no sales order found.</p>
			</div>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='viewSalesOrderWrapper'>
	<button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Sales Order Archive</h3>
		<hr>
		<label>Sales order</label>
		<p style='font-family:museo' id='sales_order_name_p'></p>
		<p style='font-family:museo' id='sales_order_date_p'></p>
		<p style='font-family:museo'>Created by <span id='created_by_p'></span></p>
		<p style='font-family:museo' id='sales_order_confirm'></p>

		<label>Seller</label>
		<p style='font-family:museo' id='sales_order_seller_p'></p>		
		
		<label>Taxing</label>
		<p style='font-family:museo' id='taxing_name_p'></p>
		
		<label>Invoicing method</label>
		<p style='font-family:museo' id='invoicing_method_p'></p>
		
		<label>Customer</label>
		<p style='font-family:museo' id='customer_name_p'></p>
		<p style='font-family:museo' id='customer_address_p'></p>
		<p style='font-family:museo' id='customer_city_p'></p>
		
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Price list</th>
				<th>Discount</th>
				<th>Net price</th>
				<th>Quantity</th>
				<th>Total price</th>
			</tr>
			<tbody id='sales_order_table'></tbody>
		</table>

		<label>Delivery history</label>
		<div id='deliveryOrderTable'>
			<table class='table table-bordered' id='delivery_order_history_table'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Status</th>
				</tr>
				<tbody id='deliveryOrderTableContent'></tbody>
			</table>
		</div>
		<p style='font-family:museo' id='deliveryOrderTableText' style='display:none'>There is no delivery history found</p>
	</div>
</div>

<script>
	$(document).ready(function(){
		refreshView();
	});

	$('#page').change(function(){
		refreshView();
	})

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Sales_order/getConfirmedByCustomerId/') . $customer->id ?>",
			data:{
				page: page
			},
			success:function(response){
				var items = response.items;
				var salesOrderCount = 0;
				$('#salesOrderTableContent').html("");
				$.each(items, function(index, item){
					var date		= item.date;
					var id			= item.id;
					var name		= item.name;
					$('#salesOrderTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td><button class='button button_default_dark' onclick='viewSalesOrder(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
					salesOrderCount++;
				});

				if(salesOrderCount > 0){
					$('#salesOrderTable').show();
					$('#salesOrderTableText').hide();
				} else {
					$('#salesOrderTable').hide();
					$('#salesOrderTableText').show();
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

	function viewSalesOrder(id){
		$.ajax({
			url:'<?= site_url('Sales_order/showById') ?>',
			data:{
				id: id
			},
			success:function(response){
				var general					= response.general;
				var sales_order_date		= general.date;
				var sales_order_name		= general.name;
				var seller					= general.seller;
				var creator					= general.creator;
				var confirmed_by			= general.confirmed_by;
				var complete_address		= '';

				var customer				= response.customer;
				var customer_name			= customer.name;
				complete_address			+= customer.address;
				var customer_city			= customer.city;
				var customer_number			= customer.number;
				var customer_rt				= customer.rt;
				var customer_rw				= customer.rw;
				var customer_postal			= customer.postal_code;
				var customer_block			= customer.block;
				var taxing					= customer.taxing;
				var invoicing_method		= customer.invoicing_method;
				
				if(taxing		== 0){
					var taxing_p	= 'Non taxable sales';
				} else {
					var taxing_p	= 'Taxable sales';
				}
				
				var invoicing_method		= general.invoicing_method;
				if(invoicing_method	== 1){
					var invoicing_method_p	= 'Retail type';
				} else {
					var invoicing_method_p	= 'Coorporate type';
				}
				
				$('#taxing_name_p').html(taxing_p);
				$('#invoicing_method_p').html(invoicing_method_p);
	
				if(customer_number != ''){
					complete_address	+= ' No. ' + customer_number;
				}
				
				if(customer_block != ''){
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

				$('#sales_order_name_p').html(sales_order_name);
				$('#sales_order_date_p').html(my_date_format(sales_order_date));
				$('#sales_order_seller_p').html((seller == null)? "<i>Not available</i>": seller);
				$('#created_by_p').html((creator == null)? "<i>Not available</i>": creator);
				$('#sales_order_confirm').html((confirmed_by == null)? "<i>Has not been confirmed</i>": "Confirmed by " + confirmed_by)
				
				$('#customer_name_p').html(customer_name);
				$('#customer_address_p').html(complete_address);
				$('#customer_city_p').html(customer_city);
				
				$('#sales_order_table').html('');
				
				var items		= response.detail;
				var sales_order_value		= 0;
				$.each(items, function(index, item){
					var reference		= item.reference;
					var name				= item.name;
					var quantity			= parseFloat(item.quantity);
					var price_list			= parseFloat(item.price_list);
					var discount			= parseFloat(item.discount);
					var net_price			= (100 - discount) * price_list / 100;
					var total_price			= quantity * net_price;
					sales_order_value	+= total_price;
					
					$('#sales_order_table').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>Rp. " + numeral(price_list).format('0,0.00') + "</td><td>" + numeral(discount).format('0,0.00') + "%</td><td>Rp. " + numeral(net_price).format('0,0.00') + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(total_price).format('0,0.00') + "</td></tr>");
				});
				
				$('#sales_order_table').append("<tr><td colspan='4'></td><td colspan='2'>Total</td><td>Rp. " + numeral(sales_order_value).format('0,0.00') + "</td></tr>");

				var deliveryOrders = response.deliveryOrders;
				var deliveryOrderCount = 0;
				$('#deliveryOrderTableContent').html('');
				$.each(deliveryOrders, function(index, deliveryOrder){
					var is_confirm = deliveryOrder.is_confirm;
					var name = deliveryOrder.name;
					var date = deliveryOrder.date;
					
					if(is_confirm == 0){
						var status = "Pending";
					} else if(is_confirm == 1){
						var status = "Sent";
					}

					$('#deliveryOrderTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td>" + status + "</td></tr>");
					deliveryOrderCount++;
				});

				if(deliveryOrderCount > 0){
					$('#deliveryOrderTable').show();
					$('#deliveryOrderTableText').hide();
				} else {
					$('#deliveryOrderTable').hide();
					$('#deliveryOrderTableText').show();
				}
				
				$('#viewSalesOrderWrapper').fadeIn(300, function(){
					$('#viewSalesOrderWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}
</script>
