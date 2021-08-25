<head>
	<title>Create Invoice</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-bar-chart'></i></a> /<a href='<?= site_url('Invoice') ?>'>Invoice </a> /Create invoice</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<button type='button' class='button button_mini_tab active' id='retail_button' disabled>Retail invoice</button>
		<button type='button' class='button button_mini_tab' id='coorporate_button'>Coorporate invoice</button>
		<br><br>
		
		<input type='text' class='form-control' id='search_bar'>
		<br>
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
		<div id='deliveryOrderTableText'><p>There is no delivery order found.</p></div>
	</div>
</div>
<form action='<?= site_url('Invoice/createRetailInvoice') ?>' method='POST' id='retailInvoiceForm'>
	<input type='hidden' id='retailDeliveryOrderId' name='id'>
</form>
<form action='<?= site_url('Invoice/createCoorporateInvoice') ?>' method='POST' id='coorporateInvoiceForm'>
	<input type='hidden' id='coorporateDeliveryOrderId' name='id'>
</form>
<script>
	$(document).ready(function(){
		refreshView(1);
	})

	$('#retail_button').click(function(){
		$('#search_bar').val('');
		$('.button_mini_tab').attr('disabled', false);
		$('.button_mini_tab').removeClass('active');

		$(this).addClass('active');
		$(this).attr('disabled', true);
		refreshView(1, 1);
	});
	
	$('#coorporate_button').click(function(){
		$('#search_bar').val('');
		$('.button_mini_tab').attr('disabled', false);
		$('.button_mini_tab').removeClass('active');

		$(this).addClass('active');
		$(this).attr('disabled', true);
		refreshView(2, 1);
	});

	$('#page').change(function(){
		refreshView();
	})
	
	function refreshView(type = 1, page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Invoice/getUninvoicedDeliveryOrders') ?>',
			data:{
				type:type,
				page:page,
				term:$('#search_bar').val()
			},
			beforeSend:function(){
				$('#page').html("");
			},
			success:function(response){
				$('#deliveryOrderTableContent').html('');
				var delivery_order_array	= response.delivery_orders;
				var deliveryOrderCount = 0;
				$.each(delivery_order_array, function(i, delivery_order){
					var date					= delivery_order.date;
					var delivery_order_id		= delivery_order.id;
					var delivery_order_name		= delivery_order.name;
					var customer_name			= delivery_order.customer_name;
					var customer_address		= delivery_order.customer_address;
					var customer_city			= delivery_order.customer_city;
					var is_sent					= delivery_order.is_sent;
					
					if(is_sent == 1 && type == 2){
						$('#deliveryOrderTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + delivery_order_name + "</td><td><p>" + customer_name + "</p><p>" + customer_address + "</p><p>" + customer_city + "</p></td><td><button class='button button_default_dark' onclick='createCoorporateInvoice(" + delivery_order_id + ")'><i class='fa fa-long-arrow-right'></i></button></td>");
						deliveryOrderCount++;
					} else if(type == 1){
						$('#deliveryOrderTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + delivery_order_name + "</td><td><p>" + customer_name + "</p><p>" + customer_address + "</p><p>" + customer_city + "</p></td><td><button class='button button_default_dark' onclick='createRetailInvoice(" + delivery_order_id + ")'><i class='fa fa-long-arrow-right'></i></button></td>");
						deliveryOrderCount++;
					}
				});

				if(deliveryOrderCount > 0){
					$('#deliveryOrderTable').show();
					$('#deliveryOrderTableText').hide();
				} else {
					$('#deliveryOrderTable').hide();
					$('#deliveryOrderTableText').show();
				}

				var pages					= response.pages;
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		});
	}

	function createRetailInvoice(n){
		$('#retailDeliveryOrderId').val(n);
		$('#retailInvoiceForm').submit();
	}

	function createCoorporateInvoice(n){
		$('#coorporateDeliveryOrderId').val(n);
		$('#coorporateInvoiceForm').submit();
	}
</script>
