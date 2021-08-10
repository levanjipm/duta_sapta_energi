<head>
	<title>Sales order - Confirm</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-line-chart'></i></a> /Sales order </p>
	</div>
	<br>
	<div class='dashboard_in'>
		<input type='text' class='form-control' id='search_bar' placeholder="Search sales order">
		<br>
		
		<div id='empty_text'>
			<p>There is no sales order to be confirmed</p>
			<a href='<?= site_url('Sales_order/createDashboard') ?>'>Create a new one</a>
		</div>
		
		<div id='sales_order_text'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Customer</th>
					<th>Action</th>
				</tr>
				<tbody id='sales_order_table'></tbody>
			</table>
		
			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
	</div>
</div>
<div class='alert_wrapper' id='sales_order_wrapper'>
	<button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Sales order</h3>
		<hr>
		<label>Customer</label>
		<p style='font-family:museo' id='customer_name_p'></p>
		<p style='font-family:museo' id='customer_address_p'></p>
		<p style='font-family:museo' id='customer_city_p'></p>
		
		<label>Plafond</label>
		<p style='font-family:museo'>Rp. <span id='customer_plafond_p'></span></p>
		
		<label>Pending sales order</label>
		<p style='font-family:museo'>Rp. <span id='pending_sales_order_value_p'></span></p>
		
		<label>Unpaid invoice</label>
		<p style='font-family:museo'>Rp. <span id='receivable_value_p'></span></p>
		
		<label>Pending bank data</label>
		<p style='font-family:museo'>Rp. <span id='pending_bank_value_p'></span></p>
		<hr>
		
		<label>Sales order</label>
		<p style='font-family:museo' id='sales_order_name'></p>
		<p style='font-family:museo' id='taxing_p'></p>
		<p style='font-family:museo' id='invoicing_method_p'></p>

		<label>Payment</label>
		<p style='font-family:museo;font-weight:bold' id='sales_order_payment'></p>
		
		<label>Item</label>
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
				<th>Price list</th>
				<th>Discount</th>
				<th>Unit price</th>
				<th>Total price</th>
			</tr>
			<tbody id='sales_order_item_table'></tbody>
		</table>

		<label>Note</label>
		<p style='font-family:museo' id='salesOrderNote_p'></p>

		<div class='notificationText danger' id='warningDebtText'><i class='fa fa-exclamation-triangle'></i> Warning! We found a problem while checking customer's account.</p></div>
		<div class='notificationText warning' id='warningPlafondText'><i class='fa fa-exclamation-triangle'></i> Warning! Plafond exceeded with current sales order.</p></div>

		<input type='hidden' id='sales_order_id' name='id'>
		<button class='button button_default_dark' title='Confirm sales order' id='confirm_button' onclick='confirmSalesOrder()'><i class='fa fa-long-arrow-right'></i></button>
		<button type='button' class='button button_danger_dark' title='Delete sales order' onclick='deleteSalesOrder()'><i class='fa fa-trash'></i></button>

		<div class='notificationText danger' id='notificationFailedDelete'><p>Failed to delete item.</p></div>
		<div class='notificationText danger' id='notificationFailedConfirm'><p>Failed to confirm item.</p></div>
	</div>
</div>
<script>
	refresh_sales_order();
	
	$('#page').change(function(){
		refresh_sales_order();
	});
	
	$('#search_bar').change(function(){
		refresh_sales_order(1);
	});
	
	function refresh_sales_order(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Sales_order/showUnconfirmedSalesOrders') ?>',
			data:{
				page:page,
				term:$('#search_bar').val()
			},
			success:function(response){
				var sales_order_array		= response.sales_orders;
				var i 						= sales_order_array.length;
				if(i == 0){
					$('#empty_text').show();
					$('#sales_order_text').hide();
				} else {
					$('#empty_text').hide();
					$('#sales_order_text').show();
				}
				
				var pages					= response.pages;
				$('#page').html('');
				$('#sales_order_table').html('');
				$.each(sales_order_array, function(index, sales_order){
					var customer_name		= sales_order.customer_name;
					var sales_order_date	= sales_order.date;
					var sales_order_name	= sales_order.name;
					var sales_order_id		= sales_order.id;
					var seller				= sales_order.seller;
					var note				= sales_order.note;
					
					var complete_address		= '';
					complete_address			+= sales_order.customer_address;
					var customer_city			= sales_order.customer_city;
					var customer_number			= sales_order.customer_number;
					var customer_rt				= sales_order.customer_rt;
					var customer_rw				= sales_order.customer_rw;
					var customer_postal			= sales_order.customer_postal_code;
					var customer_block			= sales_order.customer_block;
		
					if(customer_number != null){
						complete_address	+= ' No. ' + customer_number;
					}
					
					if(customer_block != null){
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
					
					$('#sales_order_table').append("<tr><td>" + my_date_format(sales_order_date) + "</td><td><label>Name</label><p>" + sales_order_name + "</p><label>Seller</label><p>" + seller + "</p></td><td><p style='font-family:museo'>" + customer_name + "</p><p style='font-family:museo'>" + complete_address + "</p><p style='font-family:museo'>" + customer_city + "</p></td><td><button type='button' class='button button_default_dark' title='View " + sales_order_name + "' onclick='view_sales_order(" + sales_order_id + ")'><i class='fa fa-eye'></i></button></td></tr>");
				});
				
				$('#page').html('');
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
					
				};
				
				setTimeout(function(){
					refresh_sales_order();
				},5000);
			}
		});
	}
	
	function view_sales_order(n){
		$.ajax({
			url:'<?= site_url('Sales_order/showById') ?>',
			data:{
				id:n
			},
			success:function(response){
				var user				= response.user;
				var access_level		= user.access_level;
				
				var sales_order_array	= response.general;
				var sales_order_id		= sales_order_array.id;
				var seller				= sales_order_array.seller;
				var invoicing_method	= sales_order_array.invoicing_method;
				var sales_order_name	= sales_order_array.name;
				var payment				= sales_order_array.payment;
				var taxing				= sales_order_array.taxing;
				var note				= sales_order_array.note;

				$('#sales_order_name').html(sales_order_name);
				$('#taxing_name_p').html(taxing_name);
				$('#sales_order_id').val(n);
				$('#sales_order_payment').html(numeral(payment).format('0,0') + ((payment == 0 || payment == 1) ? " day" : " days"));
				
				if(taxing == 0){
					var taxing_name		= 'Non taxable sales';
				} else {
					var taxing_name		= 'Taxable sales';
				}
				
				if(invoicing_method == 1){
					var invoicing_method_text = "Retail method";
				} else {
					var invoicing_method_text = "Coorporate method";
				}

				$('#invoicing_method_p').html(invoicing_method_text);
				$('#taxing_p').html(taxing_name);

				var customer = response.customer;
				
				var customer_name			= customer.name;
				var complete_address		= '';
				complete_address			+= customer.address;
				var customer_city			= customer.city;
				var customer_number			= customer.number;
				var customer_rt				= customer.rt;
				var customer_rw				= customer.rw;
				var customer_postal			= customer.postal_code;
				var customer_block			= customer.block;
				var plafond					= customer.plafond;
				var termOfPayment			= customer.term_of_payment;
	
				if(customer_number != null){
					complete_address	+= ' No. ' + customer_number;
				}
				
				if(customer_block != null){
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

				$('#customer_plafond_p').html(numeral(plafond).format('0,0.00'))				
				$('#customer_name_p').html(customer_name);
				$('#customer_address_p').html(complete_address);
				$('#customer_city_p').html(customer_city);
				
				$('#sales_order_item_table').html('');
				var salesOrderValue	= 0;
				var detail_array		= response.detail;
				$.each(detail_array, function(index, detail){
					var reference		= detail.reference;
					var name			= detail.name;
					var quantity		= detail.quantity;
					var price_list		= detail.price_list;
					var discount		= detail.discount;
					var net_price		= price_list * (100 - discount) / 100;
					var item_price		= net_price * quantity;
					salesOrderValue	+= item_price;
					
					$('#sales_order_item_table').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(price_list).format('0,0.00') + "</td><td>" + numeral(discount).format('0,0.00') + "%</td><td>Rp. " + numeral(net_price).format('0,0.00') + "</td><td>Rp. " + numeral(item_price).format('0,0.00') + "</td></tr>");
				});

				$('#sales_order_item_table').append("<tr><td colspan='5'></td><td>Total</td><td>Rp. " + numeral(salesOrderValue).format('0,0.00') + "</td></tr>");
				
				var pendingBank			= parseFloat(response.pendingBankData);
				var pendingValue		= parseFloat(response.pendingValue);
				
				$('#pending_sales_order_value_p').html(numeral(pendingValue - salesOrderValue).format('0,0.00'));
				$('#pending_bank_value_p').html(numeral(pendingBank).format('0,0.00'));

				var receivable = response.receivable;
				var debt = receivable.debt;

				var minimumDate = new Date(receivable.date);
				var todayDate = new Date();

				$('#receivable_value_p').html(numeral(debt).format('0,0.00'));

				var difference		= Math.floor(Math.abs((todayDate - minimumDate) / (60 * 60 * 24 * 1000)));

				if(debt > plafond || (receivable.date != null && difference > termOfPayment)){
					$('#warningDebtText').show();
					$('#confirm_button').attr('disabled', true);
				} else {
					$('#warningDebtText').hide();
					$('#confirm_button').attr('disabled', false);
				}

				if(access_level > 2){
					$('#confirm_button').attr('disabled', false);
				}

				if(pendingValue > plafond){
					$('#warningPlafondText').show();
				} else {
					$('#warningPlafondText').hide();
				}

				$('#sales_order_wrapper').fadeIn(300, function(){
					$('#sales_order_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});

				$('#salesOrderNote_p').html((note == "" || note == null) ? "<i>Not available</i>" : note);
			}
		});
	}
	
	function deleteSalesOrder(){
		$.ajax({
			url:'<?= site_url('Sales_order/deleteById') ?>',
			data:{
				id:$('#sales_order_id').val()
			},
			type:'POST',
			success:function(response){
				if(response == 1){
					refresh_sales_order();
					$('#sales_order_wrapper .slide_alert_close_button').click();
				} else {
					$('#notificationFailedDelete').fadeIn(250);
					setTimeout(function(){
						$('#notificationFailedDelete').fadeOut(250)
					}, 1000)
				}
			}
		});
	}

	function confirmSalesOrder(){
		$.ajax({
			url:'<?= site_url('Sales_order/confirmSalesOrder') ?>',
			data:{
				id:$('#sales_order_id').val()
			},
			type:'POST',
			success:function(response){
				if(response == 1){
					refresh_sales_order();
					$('#sales_order_wrapper .slide_alert_close_button').click();
				} else {
					$('#notificationFailedConfirm').fadeIn(250);
					setTimeout(function(){
						$('#notificationFailedConfirm').fadeOut(250)
					}, 1000)
				}
			}
		})
	}
	
	
</script>
