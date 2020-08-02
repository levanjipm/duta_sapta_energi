<head>
	<title>Delivery order - Create</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Inventory') ?>' title='Inventory'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Delivery_order') ?>'>Delivery order</a> /Create</p>
	</div>
	<br>
	<div class='dashboard_in'>
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
		<p id='salesOrderTableText'>There is no sales order found.</p>
	</div>
</div>

<div class='alert_wrapper' id='view_sales_order_wrapper'>
	<button class='slide_alert_close_button'>&times </button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Create delivery order</h3>
		<hr>
		<input type='hidden' id='pending_bank_value'>
		<input type='hidden' id='pending_invoice'>
		<input type='hidden' id='pending_value'>
		<input type='hidden' id='access_level'>
		<input type='hidden' id='customer_plafond'>
		<input type='hidden' id='invoice_status' value='1'>
	
		<form action='<?= site_url('Delivery_order/insertItem') ?>' method='POST' id='delivery_order_form'>
			<label>Date</label>
			<input type='date' class='form-control' name='date' required min='2020-01-01'>
			<input type='hidden' value='<?= $guid ?>' name='guid' id='guid' required minlength='36' maxlength='36'><br>
			
			<label>Customer</label>
			<p style='font-family:museo' id='customer_name_p'></p>
			<p style='font-family:museo' id='customer_address_p'></p>
			<p style='font-family:museo' id='customer_city_p'></p>
			<div style='padding:2px 10px;background-color:#ffc107;width:100%;display:none' id='warning_text_invoice'>
				<p ><i class='fa fa-exclamation-triangle'></i> Customer has pending invoice</p>
			</div>
			
			<label>Sales order</label>
			<p style='font-family:museo' id='sales_order_name_p'></p>
			<p style='font-family:museo' id='sales_order_date_p'></p>
			<p style='font-family:museo' id='sales_order_tax_p'></p>
			
			<label>Seller</label>
			<p style='font-family:museo' id='sales_order_seller_p'></p>
			
			<table class='table table-bordered' style='margin:0'>
				<tr>
					<th>Reference</th>
					<th>Name</th>
					<th>Ordered</th>
					<th>Sent</th>
					<th>Stock</th>
					<th>Quantity</th>
				</tr>
				<tbody id='sales_order_table'></tbody>
			</table>
			<div style='padding:2px 10px;background-color:#ffc107;width:100%;display:none' id='warning_text'>
				<p ><i class='fa fa-exclamation-triangle'></i> Customer's plafond exceeded</p>
			</div>
			
			<input type='hidden' id='salesOrderId' name='salesOrderId' required>
			<input type='hidden' id='taxing' name='taxing' required>
			<input type='hidden' value='0' id='total' name='total' min='1'><br>
			
			<button title='Submit delivery order' type='submit' class='button button_default_dark' id='submit_button'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<script>
	var pendingBankData;
	var pendingValue;
	var receivable;
	var customerPlafond;
	var accessLevel;

	$(document).ready(function(){
		refresh_view(1);
	})

	$('#page').change(function(){
		refresh_view();
	})

	function viewSalesOrder(n){
		$.ajax({
			url:'<?= site_url('Delivery_order/viewSalesOrderbyId') ?>',
			data:{
				sales_order_id:n
			},
			type:'GET',
			success:function(response){
				$('#salesOrderId').val(n);
				var user					= response.user;
				accessLevel			= user.access_level;
				
				var sales_order_array		= response.general;
				var sales_order_name		= sales_order_array.name;
				var sales_order_date		= sales_order_array.date;
				var taxing_code				= sales_order_array.taxing;
				var invoicing_method		= sales_order_array.invoicing_method;
				
				var customer_array			= response.customer;
				var complete_address		= '';
				var customer_name			= customer_array.name;
				complete_address			+= customer_array.address;
				var customer_city			= customer_array.city;
				var customer_number			= customer_array.number;
				var customer_rt				= customer_array.rt;
				var customer_rw				= customer_array.rw;
				var customer_postal			= customer_array.postal_code;
				var customer_block			= customer_array.block;
				var customer_plafond		= customer_array.plafond;
				var term_of_payment			= customer_array.term_of_payment;
	
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
				
				var seller				= sales_order_array.seller;
				if(seller	== null){
					seller				= "<i>Not available</i>";
				}
				
				if(taxing_code == 1){
					var taxing			= "Taxable sales";
				} else {
					var taxing			= "Non-taxable sales";
				}

				$('#taxing').val(taxing_code);
				
				$('#customer_name_p').html(customer_name);
				$('#customer_address_p').html(complete_address);
				$('#customer_city_p').html(customer_city);
				
				$('#sales_order_name_p').html(sales_order_name);
				$('#sales_order_date_p').html(my_date_format(sales_order_date));
				$('#sales_order_tax_p').html(taxing);
				
				$('#sales_order_seller_p').html(seller);
				
				var guid				= response.guid;
				
				$('#guid').val(guid);
				$('#sales_order_table').html('');

				if(response.pending_bank_data.value == null){
					pendingBankData = 0;
				} else {
					pendingBankData = parseFloat(response.pending_bank_data.value);
				}

				pendingValue = parseFloat(response.pending_value.value);
				receivable = parseFloat(response.receivable.value - response.receivable.paid);
				customerPlafond	= customer_plafond;
				
				var sales_order_value	= 0;
				var details				= response.details;
				$.each(details, function(index, detail){
					var id			= detail.id;
					var name		= detail.name;
					var reference	= detail.reference;
					var quantity	= parseInt(detail.quantity);
					var sent		= detail.sent;
					var stock		= detail.stock;
					
					var price_list	= parseFloat(detail.price_list);
					var discount	= parseFloat(detail.discount);
					var maximum		= quantity - sent;
					
					var net_price	= price_list * (100 - discount) / 100;
					
					
					$('#sales_order_table').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>" + numeral(sent).format('0,0') + "</td><td>" + numeral(stock).format('0,0') + "</td><td><input type='number' class='form-control' name='quantity[" + id + "]' min='0' max='" + maximum + "' onchange='changeTotalValue()' value='0' id='quantity-" + id + "'><input type='hidden' id='net_price-" + id + "' value='" + net_price + "'></tr>")
				});
				
				var receivable_status		= 0;
				var receivable_array		= response.receivable;
				$.each(receivable_array, function(index, receivable){
					var date				= receivable.date;
					var term_of_payment		= receivable.term_of_payment;
					var today 				= new Date('<?= date('Y-m-d') ?>');
					var	diffTime 			= (today - date) / (1000 * 60 * 60 * 24);
					
					if(date == null || diffTime < term_of_payment){
					} else {
						receivable_status++;
					}
				});
				
				if(receivable_status == 0){
					$('#warning_text_invoice').hide();
					$('#invoice_status').val(1);
				} else {
					$('#warning_text_invoice').show();
					$('#invoice_status').val(0);
				}
				
				$('#view_sales_order_wrapper').fadeIn(300, function(){
					$('#view_sales_order_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}
	
	$('.slide_alert_close_button').click(function(){
		$('#view_sales_order_wrapper .alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$('#view_sales_order_wrapper').fadeOut();
		});
	});
	
	$('#delivery_order_form').validate({
		ignore: '',
		rules: {"hidden_field": {min:1}}
	});
	
	function changeTotalValue(){
		total_delivery_order = 0;
		pending_bank_value 	= pendingBankData;
		debt				= receivable;
		plafond				= customerPlafond;
		send_value			= 0;
		access_level		= accessLevel
		
		$('input[id^="quantity-"]').each(function(){
			var quantity			= parseInt($(this).val());
			var id_string			= $(this).attr('id');
			var uid					= id_string.substring(9,264);
			var net_price			= parseFloat($('#net_price-' + uid).val());
			var total_price			= quantity * net_price;
			
			send_value				+= total_price;
			
			total_delivery_order 	+= quantity;
		});
		
		var total_credit	= plafond + pending_bank_value;
		var total_debit		= debt + send_value;
		
		if(total_credit < total_debit){
			$('#warning_text').show();
			if(access_level > 2){
				$('#submit_button').attr('disabled', false);
			} else {
				$('#submit_button').attr('disabled', true);
			}
		} else {
			$('#warning_text').hide();
			$('#submit_button').attr('disabled', false);
		}
		$('#total').val(total_delivery_order);
	};

	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Sales_order/getIncompleteSalesOrder') ?>',
			data:{
				page: page
			},
			success:function(response){
				var sales_orders = response.items;
				$('#salesOrderTableContent').html('');
				var salesOrderCount = 0;
				$.each(sales_orders, function(index, sales_order){
					var customer = sales_order.customer;
					var customer_name			= customer.name;
					var complete_address		= customer.address;
					var customer_city			= customer.city;
					var customer_number			= customer.number;
					var customer_rt				= customer.rt;
					var customer_rw				= customer.rw;
					var customer_postal			= customer.postal_code;
					var customer_block			= customer.block;
		
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

					var name = sales_order.name;
					var date = sales_order.date;
					var taxing = sales_order.taxing;
					var id = sales_order.id;

					$('#salesOrderTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td><p>" + customer_name + "</p><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><button class='button button_default_dark' onclick='viewSalesOrder(" + id + ")'><i class='fa fa-eye'></i></button></tr>");
					salesOrderCount++;
				})

				var pages = response.pages;
				$('#page').html('');
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}

				if(salesOrderCount > 0){
					$('#salesOrderTable').show();
					$('#salesOrderTableText').hide();
				} else {
					$('#salesOrderTable').hide();
					$('#salesOrderTableText').show();
				}
			}
		})
	}
</script>