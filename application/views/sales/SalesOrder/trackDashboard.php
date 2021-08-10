<head>
	<title>Sales order - Track</title>
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
			cursor:pointer;
			opacity:0.4;
			transition:0.3s all ease;
		}

		.progressBar:hover{
			opacity:1;
			transition:0.3s all ease;
		}

		.progressBarWrapper p{
			font-family:museo;
			color:black;
			font-weight:700;
			z-index:50;
			position:absolute;
			right:10px;
		}
	</style>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-line-chart'></i></a> /<a href='<?= site_url('Sales_order') ?>'>Sales order</a>/ Track</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<input type='text' class='form-control' id='search_bar'>
		<br>
		<div id='salesOrderTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Customer</th>
					<th>Action</th>
				</tr>
				<tbody id='salesOrderTableContent'></tbody>
			</table>
			
			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='salesOrderTableText'>There is no sales order to be tracked.</p>
	</div>
</div>

<div class='alert_wrapper' id='track_sales_order_wrapper'>
	<button type='button' class='slide_alert_close_button'>&times </button>
	<div class='alert_box_slide'>
		<label>Sales order</label>
		<p id='sales_order_name_p'></p>
		<p id='sales_order_date_p'></p>

		<label>Sales</label>
		<p id='sales_order_seller_p'></p>
		
		<label>Customer</label>
		<p id='customer_name_p'></p>
		<p id='customer_address_p'></p>
		<br>
		
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
				<th>Sent</th>
				<th>Pending</th>
			</tr>
			<tbody id='salesOrderDetailTable'></tbody>
		</table>

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
		refresh_view();
	})
	$('#page').change(function(){
		refresh_view();
	});
	
	$('#search_bar').change(function(){
		refresh_view(1);
	});

	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Sales_order/getIncompleteSalesOrder') ?>',
			data:{
				term:$('#search_bar').val(),
				page: page
			},
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

				var sales_orders = response.items;
				$('#salesOrderTableContent').html('');
				var salesOrderCount = 0;
				$.each(sales_orders, function(index, sales_order){
					var id = sales_order.id;
					var name = sales_order.name;
					var date = sales_order.date;
					var customer = sales_order.customer;

					var customer_name	= customer.name;
					var customer_address	= customer.address;
					var customer_number		= customer.number;
					var customer_block		= customer.block;
					var customer_rt			= customer.rt;
					var customer_rw			= customer.rw;
					var customer_city		= customer.city;
					var customer_postal		= customer.postal_code;
					var customer_pic		= customer.pic_name;
					var complete_address	= customer_address;
					if(customer_number != null && customer_number != ''){
						complete_address	+= ' no. ' + customer_number;
					};
					
					if(customer_block != null && customer_block != ''){
						complete_address	+= ', blok ' + customer_block;
					};
					
					if(customer_rt != '000'){
						complete_address	+= ', RT ' + customer_rt + ', RW ' + customer_rw;
					}
					
					if(customer_postal != ''){
						complete_address += ', ' + customer_postal;
					}
					
					complete_address += ', ' + customer_city;
					var quantity = parseInt(sales_order.quantity);
					var sent = parseInt(sales_order.sent);
					var progress = sent * 100 / quantity;

					$('#salesOrderTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><p>" + name + "</p><div class='progressBarWrapper'><p>" + numeral(progress).format('0,0.0') + "%</p><div class='progressBar' id='progressBar-" + id + "'></div></div></td><td><p><strong>" + customer_name + "</strong></p><p>" + complete_address + "</p></td><td><button class='button button_default_dark' onclick='track(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
					$('#progressBar-' + id).animate({
						width: progress + "%"
					}, "slow");

					salesOrderCount++;
				})

				if(salesOrderCount > 0){
					$('#salesOrderTable').show();
					$('#salesOrderTableText').hide();
				} else {
					$('#salesOrderTable').hide();
					$('#salesOrderTableText').show();
				}
			}
		});
	}
	
	function track(n){		
		$.ajax({
			url:'<?= site_url('Sales_order/trackById') ?>',
			data:{
				id:n
			},
			success:function(response){
				var customer = response.customer;
				var customer_name	= customer.name;
				var customer_address	= customer.address;
				var customer_number		= customer.number;
				var customer_block		= customer.block;
				var customer_rt			= customer.rt;
				var customer_rw			= customer.rw;
				var customer_city		= customer.city;
				var customer_postal		= customer.postal_code;
				var customer_pic		= customer.pic_name;
				var complete_address	= customer_address;
				if(customer_number != null && customer_number != ''){
					complete_address	+= ' no. ' + customer_number;
				};
				
				if(customer_block != null && customer_block != ''){
					complete_address	+= ', blok ' + customer_block;
				};
				
				if(customer_rt != '000'){
					complete_address	+= ', RT ' + customer_rt + ', RW ' + customer_rw;
				}
				
				if(customer_postal != ''){
					complete_address += ', ' + customer_postal;
				}
				
				complete_address += ', ' + customer_city;

				$('#customer_name_p').html(customer_name);
				$('#customer_address_p').html(complete_address);

				var items = response.detail;
				$('#salesOrderDetailTable').html('');
				$.each(items, function(index, item){
					var name = item.name;
					var reference = item.reference;
					var quantity = item.quantity;
					var sent	= item.sent;

					$('#salesOrderDetailTable').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>" + numeral(sent).format('0,0') + "</td><td>" + numeral(quantity - sent).format('0,0') + "</td></tr>")
				})

				var general = response.general;
				var sales_order_name = general.name;
				var sales_order_date = general.date;
				var sales_order_seller = general.seller;

				if(sales_order_seller == null){
					$('#sales_order_seller_p').html("<i>Not available</i>");
				} else {
					$('#sales_order_seller_p').html(sales_order_seller);
				};

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

				$('#sales_order_name_p').html(sales_order_name);
				$('#sales_order_date_p').html(my_date_format(sales_order_date));
			}
		});
		
		$('#track_sales_order_wrapper').fadeIn(300, function(){
			$('#track_sales_order_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	}
	
	$('.alert_close_button').click(function(){
		$(this).parent().fadeOut();
	});
	
	
</script>
