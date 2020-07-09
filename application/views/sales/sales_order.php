<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /Sales order </p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<input type='text' class='form-control input-lg' id='search_bar' placeholder="Search sales order">
			<div class='input_group_append'>
				<button type='button' class='button button_default_dark' onclick='window.location.href="<?= site_url('Sales_order/create') ?>"'>Create Sales Order</button>
			</div>
		</div>
		<br>
		
		<div id='empty_text'>
			<p>There is no sales order to be confirmed</p>
			<a href='<?= site_url('Sales_order/create') ?>'>Create a new one</a>
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
	<button type='button' class='slide_alert_close_button'>&times </button>
	<div class='alert_box_slide'>
		<label>Customer</label>
		<div class='information_box' id='customer_address_select' style='height:350px'>
			<label>Detail</label>
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
		</div>
		<div style='padding:2px 10px;background-color:#ffc107;width:100%;display:none' id='warning_text_1'>
			<p ><i class='fa fa-exclamation-triangle'></i> Customer's plafond exceeded</p>
		</div>
		
		<label>Sales order</label>
		<p style='font-family:museo' id='sales_order_name'></p>
		<p style='font-family:museo' id='taxing_name_p'></p>
		<p style='font-family:museo' id='invoicing_method_p'></p>
		
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
		
		<form action='<?= site_url('Sales_order/confirm') ?>' method='POST'>
			<input type='hidden' id='sales_order_id' name='id'>
			<button class='button button_default_dark' title='Confirm sales order' id='confirm_button'><i class='fa fa-long-arrow-right'></i></button>
			<button type='button' class='button button_danger_dark' title='Delete sales order' onclick='delete_sales_order()'><i class='fa fa-trash'></i></button>
			
			<br><br>
			<p style='font-family:museo' id='warning_text'>This customer has a pending invoice payment, please contact your supervisor</p>
		</form>
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
			url:'<?= site_url('Sales_order/view_unconfirmed_sales_order') ?>',
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
					if(seller == null){
						seller		= "<i>Not available</i>";
					}
					
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
					
					$('#sales_order_table').append("<tr><td>" + my_date_format(sales_order_date) + "</td><td><label>Name</label><p>" + sales_order_name + "</p><label>Seller</label><p>" + seller + "</p></td><td><p style='font-family:museo'>" + customer_name + "</p><p style='font-family:museo'>" + complete_address + "</p><p style='font-family:museo'>" + customer_city + "</p></td><td><button type='button' class='button button_success_dark' title='View " + sales_order_name + "' onclick='view_sales_order(" + sales_order_id + ")'><i class='fa fa-eye'></i></button></td></tr>");
				});
				
				$('#page').html('');
				for(i = 1; i <= pages; i++){
					$('#page').append("<option value='" + i + "'>" + i + "</option>");
				};
				
				setTimeout(function(){
					refresh_sales_order();
				},5000);
			}
		});
	}
	
	function view_sales_order(n){
		$.ajax({
			url:'<?= site_url('Sales_order/view_sales_order') ?>',
			data:{
				id:n
			},
			success:function(response){
				var user				= response.user;
				var access_level		= user.access_level;
				
				var receivable_array	= response.receivable;
				var date				= receivable_array.date;
				
				var term_of_payment		= receivable_array.term_of_payment;
				
				var time_difference		= Math.abs(date - <?= date('Y-m-d'); ?>);
				var day_difference		= Math.ceil(time_difference / (1000 * 60 * 60 * 24));
				
				if(date != null && day_difference > term_of_payment){
					$('#warning_text').show();
					if(access_level > 2){
						$('#confirm_button').attr('disabled', false);
					} else {
						$('#confirm_button').attr('disabled', true);
					}
				} else {
					$('#warning_text').hide();
					$('#confirm_button').attr('disabled', false);
				}
				
				var sales_order_array	= response.general;
				var sales_order_id		= sales_order_array.id;
				var seller				= sales_order_array.seller;
				var invoicing_method	= sales_order_array.invoicing_method;
				var sales_order_name	= sales_order_array.name;
				var taxing				= sales_order_array.taxing;
				var customer_name		= sales_order_array.customer_name;
				
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
				
				var complete_address		= '';
				complete_address			+= sales_order_array.address;
				var customer_city			= sales_order_array.city;
				var customer_number			= sales_order_array.number;
				var customer_rt				= sales_order_array.rt;
				var customer_rw				= sales_order_array.rw;
				var customer_postal			= sales_order_array.postal_code;
				var customer_block			= sales_order_array.block;
	
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
				
				var receivable			= response.receivable;
				var receivable_value	= 0;
				$.each(receivable, function(index, value){
					if(value.value == null){
						var invoice_value	= 0;
					} else {
						var invoice_value		= parseFloat(value.value);
					}
					
					var paid_value			= parseFloat(value.paid);
					
					receivable_value		= receivable_value + invoice_value - paid_value;
				});
				
				var customer_array			= response.customer;
				var plafond					= parseFloat(customer_array.plafond);
				
				
				$('#invoicing_method_p').html(invoicing_method_text);
				$('#customer_plafond_p').html(numeral(plafond).format('0,0.00'));
				$('#receivable_value_p').html(numeral(receivable_value).format('0,0.00'));
				
				$('#taxing_name_p').html(taxing_name);

				$('#sales_order_id').val(sales_order_id);
				
				$('#customer_name_p').html(customer_name);
				$('#customer_address_p').html(complete_address);
				$('#customer_city_p').html(customer_city);
				$('#sales_order_name').html(sales_order_name);
				
				$('#sales_order_item_table').html('');
				var sales_order_value	= 0;
				var detail_array		= response.detail;
				$.each(detail_array, function(index, detail){
					var reference		= detail.reference;
					var name			= detail.name;
					var quantity		= detail.quantity;
					var price_list		= detail.price_list;
					var discount		= detail.discount;
					var net_price		= price_list * (100 - discount) / 100;
					var item_price		= net_price * quantity;
					sales_order_value	+= item_price;
					
					$('#sales_order_item_table').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(price_list).format('0,0.00') + "</td><td>" + numeral(discount).format('0,0.00') + "%</td><td>Rp. " + numeral(net_price).format('0,0.00') + "</td><td>Rp. " + numeral(item_price).format('0,0.00') + "</td></tr>");
				});
				$('#sales_order_item_table').append("<tr><td colspan='5'></td><td>Total</td><td>Rp. " + numeral(sales_order_value).format('0,0.00') + "</td></tr>");
				
				var pending_value_array		= response.pending_value;
				var pending_value			= parseFloat(pending_value_array.value);
				
				$('#pending_sales_order_value_p').html(numeral(pending_value - sales_order_value).format('0,0.00'));
				
				var pending_bank_value_array		= response.pending_bank_data;
				if(pending_bank_value_array.value == null){
					var pending_bank_value				= 0;
				} else {
					var pending_bank_value				= parseFloat(pending_bank_value_array.value);
				}
				
				$('#pending_bank_value_p').html(numeral(pending_bank_value).format('0,0.00'));
				
				var total_debit				= pending_value + receivable_value;
				var total_credit			= plafond + pending_bank_value;
				
				if(total_credit <= total_debit){
					$('#warning_text_1').show();
					if(access_level > 2){
						$('#confirm_button').attr('disabled', false);
					} else {
						$('#confirm_button').attr('disabled', true);
					}
				} else {
					$('#warning_text_1').hide();
					$('#confirm_button').attr('disabled', false);
				}
				
				if(total_credit < total_debit){
					$('#warning_text_1').show();
				} else {
					$('#warning_text_1').hide();
				}
				
				$('#sales_order_wrapper').fadeIn(300, function(){
					$('#sales_order_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}
	
	function delete_sales_order(){
		$.ajax({
			url:'<?= site_url('Sales_order/delete') ?>',
			data:{
				id:$('#sales_order_id').val()
			},
			type:'POST',
			success:function(){
				location.reload();
			}
		});
	}
	
	$('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
</script>