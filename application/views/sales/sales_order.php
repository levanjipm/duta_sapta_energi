<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /Sales order </p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<input type='text' class='form-control' id='search_bar'>
			<div class='input_group_append'>
				<a href='<?= site_url('Sales_order/create') ?>'><button type='button' class='button button_default_dark'>Create Sales Order</button></a>
				<a href='<?= site_url('Sales_order/track') ?>'><button type='button' class='button button_default_dark'>Track Sales Order</button></a>
			</div>
		</div>
		<br>

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
<div class='alert_wrapper' id='sales_order_wrapper'>
	<button type='button' class='alert_close_button'>&times </button>
	<div class='alert_box_default'>
		<label>Customer</label>
		<p style='font-family:museo' id='customer_name_p'></p>
		<p style='font-family:museo' id='customer_address_p'></p>
		<p style='font-family:museo' id='customer_city_p'></p>
		
		<label>Sales order</label>
		<p style='font-family:museo' id='sales_order_name'></p>
		
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
				var pages					= response.pages;
				$('#page').html('');
				$('#sales_order_table').html('');
				$.each(sales_order_array, function(index, sales_order){
					var customer_name		= sales_order.customer_name;
					var customer_address	= sales_order.customer_address;
					var customer_city		= sales_order.customer_city;
					var sales_order_date	= sales_order.date;
					var sales_order_name	= sales_order.name;
					var sales_order_id		= sales_order.id;
					var seller				= sales_order.seller;
					if(seller == null){
						seller		= "<i>Not available</i>";
					}
					
					$('#sales_order_table').append("<tr><td>" + sales_order_date + "</td><td><label>Name</label><p>" + sales_order_name + "</p><label>Seller</label><p>" + seller + "</p></td><td><p style='font-family:museo'>" + customer_name + "</p><p style='font-family:museo'>" + customer_address + "</p><p style='font-family:museo'>" + customer_city + "</p></td><td><button type='button' class='button button_success_dark' title='View " + sales_order_name + "' onclick='view_sales_order(" + sales_order_id + ")'><i class='fa fa-eye'></i></button></td></tr>");
				});
				$('#page').html('');
				for(i = 1; i <= pages; i++){
					$('#page').append("<option value='" + i + "'>" + i + "</option>");
				};
				
				setTimeout(function(){
					refresh_sales_order();
				},500);
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
				var access_level		= response.access_level;
				
				var receivable_array	= response.receivable;
				var date				= receivable_array.date;
				console.log(date);
				var term_of_payment		= receivable_array.term_of_payment;
				
				var time_difference		= Math.abs(date - <?= date('Y-m-d'); ?>);
				var day_difference		= Math.ceil(time_difference / (1000 * 60 * 60 * 24));
				
				if(date != null && day_difference > term_of_payment){
					$('#warning_text').show();
					if(access_level > 1){
						$('#confirm_button').attr('disabled', true);
					} else {
						$('#confirm_button').attr('disabled', true);
					}
				} else {
					$('#warning_text').hide();
					$('#confirm_button').attr('disabled', false);
				}
				
				var sales_order_array	= response.general;
				var sales_order_id		= sales_order_array.id;
				var customer_name		= sales_order_array.customer_name;
				var customer_address	= sales_order_array.customer_address;
				var customer_city		= sales_order_array.customer_city;
				var seller				= sales_order_array.seller;
				var invoicing_method	= sales_order_array.invoicing_method;
				var sales_order_name	= sales_order_array.name;
				
				$('#sales_order_id').val(sales_order_id);
				
				$('#customer_name_p').html(customer_name);
				$('#customer_address_p').html(customer_address);
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
				
				$('#sales_order_wrapper').fadeIn();
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
	
	$('.alert_close_button').click(function(){
		$(this).parent().fadeOut();
	});
</script>