<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Inventory') ?>' title='Inventory'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Delivery_order') ?>'>Delivery order</a> /Create</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<table class='table table-bordered'>
			<tr>
				<th>Date</th>
				<th>Name</th>
				<th>Customer</th>
				<th>Action</th>
			</tr>
<?php
	foreach($sales_orders as $sales_order){
?>
			<tr>
				<td><?= date('d M Y',strtotime($sales_order->date)) ?></td>
				<td><?= $sales_order->name ?></td>
				<td>
					<p style='font-family:museo'><?= $sales_order->customer_name ?></p> 
					<p style='font-family:museo'><?= $sales_order->customer_address ?></p> 
					<p style='font-family:museo'><?= $sales_order->customer_city ?></p>
				</td>
				<td>
					<button type='button' class='button button_success_dark' onclick='view_sales_order(<?= $sales_order->id ?>)'><i class='fa fa-long-arrow-right'></i></button>
				</td>
			</tr>
<?php
	}
?>
		</table>
	</div>
</div>

<div class='alert_wrapper' id='view_sales_order_wrapper'>
	<button class='alert_close_button'>&times </button>
	<div class='alert_box_default'>
		<form action='<?= site_url('Delivery_order/input_delivery_order') ?>' method='POST' id='delivery_order_form'>
			<label>Date</label>
			<input type='date' class='form-control' name='date' required min='2020-01-01'>
			<input type='hidden' value='<?= $guid ?>' name='guid' id='guid' required minlength='36' maxlength='36'>
			
			<label>Customer</label>
			<p style='font-family:museo' id='customer_name_p'></p>
			<p style='font-family:museo' id='customer_address_p'></p>
			<p style='font-family:museo' id='customer_city_p'></p>
			
			<label>Sales order</label>
			<p style='font-family:museo' id='sales_order_name_p'></p>
			<p style='font-family:museo' id='sales_order_date_p'></p>
			<p style='font-family:museo' id='sales_order_tax_p'></p>
			
			<label>Seller</label>
			<p style='font-family:museo' id='sales_order_seller_p'></p>
			
			<table class='table table-bordered'>
				<tr>
					<th>Reference</th>
					<th>Name</th>
					<th>Ordered</th>
					<th>Sent</th>
					<th>Quantity</th>
				</tr>
				<tbody id='sales_order_table'></tbody>
			</table>
			
			<input type='hidden' value='0' id='total' name='total' min='1'><br>
			
			<button type='submit' class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<script>
	function view_sales_order(n){
		$.ajax({
			url:'<?= site_url('Delivery_order/view_sales_order') ?>',
			data:{
				sales_order_id:n
			},
			type:'GET',
			success:function(response){
				var sales_order_array	= response.sales_order;
				var sales_order_name	= sales_order_array.name;
				var sales_order_date	= sales_order_array.date;
				var taxing_code			= sales_order_array.taxing;
				var invoicing_method	= sales_order_array.invoicing_method;
				
				var customer_name		= sales_order_array.customer_name;
				var customer_address	= sales_order_array.customer_address;
				var customer_city		= sales_order_array.customer_city;
				
				var seller				= sales_order_array.seller;
				if(seller	== null){
					seller				= "<i>Not available</i>";
				}
				
				if(taxing_code == 1){
					var taxing			= "Taxable sales";
				} else {
					var taxing			= "Non-taxable sales";
				}
				
				$('#customer_name_p').html(customer_name);
				$('#customer_address_p').html(customer_address);
				$('#customer_city_p').html(customer_city);
				
				$('#sales_order_name_p').html(sales_order_name);
				$('#sales_order_date_p').html(sales_order_date);
				$('#sales_order_tax_p').html(taxing);
				
				$('#sales_order_seller_p').html(seller);
				
				var guid				= response.guid;
				
				$('#guid').val(guid);
				
				$('#sales_order_table').html('');
				
				var details				= response.details;
				$.each(details, function(index, detail){
					var id			= detail.id;
					var name		= detail.name;
					var reference	= detail.reference;
					var quantity	= detail.quantity;
					var sent		= detail.sent;
					var maximum		= quantity - sent;
					
					$('#sales_order_table').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>" + numeral(sent).format('0,0') + "</td><td><input type='number' class='form-control' name='quantity[" + id + "]' min='0' max='" + maximum + "' onkeyup='change_total()' value='0'></tr>")
				});
				$('#view_sales_order_wrapper').fadeIn();
			}
		});
	}
	
	$('.alert_close_button').click(function(){
		$(this).parent().fadeOut();
	});
	
	$('#delivery_order_form').validate({
		ignore: '',
		rules: {"hidden_field": {min:1}}
	});
	
	function change_total(){
		var total_delivery_order = 0;
		$('input[name^="quantity"]').each(function(){
			total_delivery_order += parseInt($(this).val());
		});
		
		$('#total').val(total_delivery_order);
	};
</script>