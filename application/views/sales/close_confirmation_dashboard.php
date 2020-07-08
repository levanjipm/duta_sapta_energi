<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Sales_order') ?>'>Sales order</a> /Close</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<table class='table table-bordered'>
			<tr>
				<th>Request data</th>
				<th>Customer</th>
				<th>Sales order</th>
				<th>Action</th>
			</tr>
			<tbody id='sales_order_table'></tbody>
		</table>
	</div>
</div>
<div class='alert_wrapper' id='close_sales_order_wrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Close sales order</h3>
		<hr>
		<label>Submission date</label>
		<p id='submission_date_p'></p>
		
		<label>Information</label>
		<p id='submission_information_p'></p>
		
		<label>Customer</label>
		<p id='customer_name_p'></p>
		<p id='customer_address_p'></p>
		<p id='customer_city_p'></p>
		
		<label>Sales order</label>
		<p id='sales_order_name_p'></p>
		<p id='sales_order_date_p'></p>
		
		<label>Close request by</label>
		<p id='requested_by_p'></p>
		
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Price</th>
				<th>Discount</th>
				<th>Net price</th>
				<th>Quantity</th>
				<th>Total price</th>
				<th>Sent</th>
				<th>Pending</th>
			</tr>
			<tbody id='item_table'></tbody>
		</table>
		
		<input type='hidden' id='close_sales_order_request_id'>
		<button class='button button_default_dark' onclick='confirm_request(1)'><i class='fa fa-long-arrow-right'></i></button> <button class='button button_danger_dark'><i class='fa fa-trash' onclick='confirm_request(0)'></i></button>
	</div>
</div>
<script>
	$(document).ready(function(){
		get_sales_orders();
	});
	
	function get_sales_orders(){
		$.ajax({
			url:'<?= site_url('Sales_order/get_unconfirmed_closed_sales_order') ?>',
			type:'GET',
			success:function(response){
				$('#sales_order_table').html('');
				$.each(response, function(index, value){
					var date = value.date;
					var customer_name = value.name;
					var complete_address = value.address;
					var customer_number = value.number;
					var customer_block = value.block;
					var customer_rt = value.rt;
					var customer_rw = value.rw;
					var customer_postal = value.postal_code;
					var customer_city = value.city;
					var seller = value.seller;
					var id = value.id;
					
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
					
					var created_by = value.created_by;
					
					
					var sales_order_name = value.sales_order_name;
					var sales_order_date = value.sales_order_date;
					
					if(seller == null){
						var seller_text = "<i>None</i>";
					} else {
						var seller_text = seller;
					}
					
					$('#sales_order_table').append("<tr><td><label>Request date</label><p>" + my_date_format(date) + "</p><label>Requested by</label><p>" + created_by + "</p></td><td><p>" + customer_name + "</p><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><label>Date</label><p>" + my_date_format(sales_order_date) + "</p><label>Name</label><p>" + sales_order_name + "</p><label>Salesman</label><p>" + seller_text + "</p></td><td><button class='button button_transparent' onclick='open_view(" + id + ")'><i class='fa fa-eye'></i></button></tr>");
				});
			}
		});
	}
	
	function open_view(n){
		$.ajax({
			url:'<?= site_url('Sales_order/get_close_request_by_id') ?>',
			data:{
				id:n
			},
			success:function(response){
				var general = response.general;
				var submission_date = general.date;
				var information = general.information;
				
				var close_request_id = general.id;
				$('#close_sales_order_request_id').val(close_request_id);
				
				var sales_order = response.sales_order;
				var customer_name = sales_order.customer_name;
				var complete_address = sales_order.address;
				var customer_number = sales_order.number;
				var customer_block = sales_order.block;
				var customer_rt = sales_order.rt;
				var customer_rw = sales_order.rw;
				var customer_postal = sales_order.postal_code;
				var customer_city = sales_order.city;
				var created_by = sales_order.created_by;
				
				var requested_by = general.requested_by;
				if(requested_by == 1){
					var requested_by_text = "Corresponding salesman";
				} else if(requested_by == 2){
					var requested_by_text = "Customer";
				} else {
					requested_by_text = "Other";
				}
				
				var sales_order_name = sales_order.name;
				var sales_order_date = sales_order.date;
				
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
				
				$('#submission_date_p').text(my_date_format(submission_date));
				$('#submission_information_p').text(information);
				$('#customer_name_p').text(customer_name);
				$('#customer_address_p').text(complete_address);
				$('#customer_city_p').text(customer_city);
				
				$('#sales_order_name_p').text(sales_order_name);
				$('#sales_order_date_p').text(my_date_format(sales_order_date));
				
				$('#requested_by_p').text(requested_by_text);
				
				$('#item_table').html('');
				var item_array = response.items;
				$.each(item_array, function(index, item){
					var name = item.name;
					var reference = item.reference;
					var price_list = parseFloat(item.price_list);
					var quantity = parseInt(item.quantity);
					var discount = item.discount;
					var net_price = price_list * (100 - parseFloat(discount)) / 100;
					var total_price = parseFloat(net_price) * quantity;
					var sent = parseInt(item.sent);
					var pending = quantity - sent;
					
					$('#item_table').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>Rp. " + numeral(price_list).format('0,0.00') + "</td><td>" + numeral(discount).format('0,0.00') + "%</td><td>Rp. " + numeral(net_price).format('0,0.00') + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(total_price).format('0,0.00') + "</td><td>" + numeral(sent).format('0,0') + "</td><td>" + numeral(pending).format('0,0') + "</td></tr>"); 
				});
				
				$('#close_sales_order_wrapper').fadeIn(300, function(){
					$('#close_sales_order_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
				
			}
		});
	}
	
	$('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
	
	function confirm_request(status){
		var request_id = $('#close_sales_order_request_id').val();
		$.ajax({
			url:'<?= site_url('Sales_order/update_close_status') ?>',
			data:{
				id: request_id,
				status: status
			},
			type:"POST",
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(){
				$('button').attr('disabled', false);
				get_sales_orders();
				$('.slide_alert_close_button').click();
			}
		});
	}
</script>