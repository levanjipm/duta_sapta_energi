<div class='dashboard'>
	<h2 style='font-family:bebasneue'>Create sales order</h2>
	<hr>
	<form action='<?= site_url('Sales_order/input_sales_order') ?>' method='POST' id='sales_order_form'>
	<label>Date</label>
	<input type='date' class='form-control' name='sales_order_date' id='sales_order_date' value='<?= date('Y-m-d') ?>'>
	
	<input type='hidden' value='<?= $guid ?>' name='guid'>
	
	<label>Customer</label>
	<button class='form-control' type='button' id='select_customer_button' style='text-align:left'></button>
	<p style='font-family:museo' id='customer_address_select'></p>
	
	<input type='hidden' name='customer_id' id='customer_id' required>
	<br>
	
	<label>Seller</label>
	<select class='form-control' name='sales_order_seller' id='sales_order_seller'>
	<option value=''>None</option>
<?php
	foreach($users as $user){
?>
	<option value='<?= $user->id ?>'><?= $user->name ?></option>
<?php
	}
?>
	</select>
	
	<label>Taxing</label>
	<select class='form-control' name='taxing' id='taxing'>
		<option value='0'>Non taxable</option>
		<option value='1'>Taxable</option>
	</select>
	
	<label>Invoicing method</label>
	<select class='form-control' name='method' id='method'>
		<option value='1'>Retail</option>
		<option value='2'>Coorporate</option>
	</select>
	
	<br>
	<button type='button' class='button button_default_light' id='add_item_button'><i class='fa fa-shopping-cart'></i> Add item</button>
	<br><br>
	<table class='table table-bordered' id='cart_products_table' style='display:none'>
		<tr>
			<th>Reference</th>
			<th>Name</th>
			<th>Price list</th>
			<th>Discount</th>
			<th>Quantity</th>
			<th>Action</th>
		</tr>
		<tbody id='cart_products'></tbody>
	</table>
	
	<table class='table table-bordered' id='bonus_cart_products_table' style='display:none'>
		<tr>
			<th>Item</th>
			<th>Description</th>
			<th>Quantity</th>
			<th>Action</th>
		</tr>
		<tbody id='bonus_cart_products'></tbody>
	</table>
	
	<button type='button' class='button button_default_light' onclick='validate_form()' style='display:none' id='submit_button'>Submit</button>
	
	</form>
</div>

<div class='alert_wrapper' id='select_customer_wrapper'>
	<div class='alert_box_full'>		
		<div class='row' style='text-align:center'>
			<div class='col-lg-2 col-md-2 col-sm-4 col-xs-4 col-lg-offset-5 col-md-offset-5 col-sm-offset-4 col-sm-offset-4'>
				<button type='button' class='button alert_full_close_button' title='Close select customer session'></button>
			</div>
		</div>
		<h2 style='font-family:bebasneue'>Select customer</h2>
		<br>
		<input type='text' class='form-control' id='search_customer'>
		<br>
		<table class='table table-bordered'>
			<tr>
				<th>Name</th>
				<th>Address</th>
				<th>Action</th>
			</tr>
			<tbody id='customer_table'></tbody>
		</table>
		
		<select class='form-control' id='customer_page' style='width:100px'>
			<option value='1'>1</option>
		</select>
	</div>
</div>

<div class='alert_wrapper' id='add_item_wrapper'>
	<div class='alert_box_full'>
		<div class='row' style='text-align:center'>
			<div class='col-lg-2 col-md-2 col-sm-4 col-xs-4 col-lg-offset-5 col-md-offset-5 col-sm-offset-4 col-sm-offset-4'>
				<button type='button' class='button alert_full_close_button' title='Close add item session'></button>
			</div>
		</div>
		<div class='row'>
			<div class='col-xs-12'>
				<h2 style='font-family:bebasneue'>Add item to cart</h2>
				<hr>
				<label>Search</label>
				<input type='text' class='form-control' id='search_bar'>
				<br>
				<table class='table table-bordered' id='shopping_item_list_table'>
					<tr>
						<th>Reference</th>
						<th>Name</th>
						<th>Action</th>
					</tr>
					<tbody id='shopping_item_list_tbody'>
					</tbody>
				</table>
				
				<select class='form-control' id='page' style='width:100px'>
					<option value='1'></option>
				</select>
			</div>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='validate_sales_order_wrapper'>
	<button class='alert_close_button'>&times </button>
	<div class='alert_box_default' id='validate_sales_order_box'>
		<label>Date</label>
		<p id='date'></p>
		
		<label>Taxing</label>
		<p id='taxing_p'></p>
		
		<label>Seller</label>
		<p id='seller_p'></p>
		
		<label>Customer</label>
		<p id='customer_p'></p>
		<p id='customer_address_p'></p>
		
		<label>Invoicing method</label>
		<p id='invoicing_p'></p>
		
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Price list</th>
				<th>Discount</th>
				<th>Unit price</th>
				<th>Quantity</th>
				<th>Total price</th>
			</tr>
			<tbody id='table_item_confirm'></tbody>
		</table>
		
		<button class='button button_default_dark' onclick='submit_form()'>Submit</button>
	</div>
</div>
<script>
	$('.alert_close_button').click(function(){
		$(this).parent().fadeOut();
		$('input').attr('readonly',false);
		$('select').attr('readonly',false);
		$('#table_item_confirm').html('');
	});
	
	$('#add_item_button').click(function(){
		$('#search_bar').val('');
		refresh_view();
	});
	
	function refresh_view(){
		$.ajax({
			url:'<?= site_url('Item/search_item_cart') ?>',
			data:{
				term:$('#search_bar').val(),
				page:$('#page').val()
			},
			success:function(response){
				$('#add_item_wrapper').fadeIn();
				$('#shopping_item_list_tbody').html('');
				var item_array	= response.items;
				var pages		= response.pages;
				var page		= response.page;
				
				if(item_array.length > 0){
					$('#shopping_item_list_table').show();
					$.each(item_array, function(index, item){
						var reference		= item.reference;
						var id				= item.id;
						var name			= item.name;
						
						$('#shopping_item_list_tbody').append("<tr><td>" + reference + "</td><td>" + name + "</td><td><button type='button' class='button button_success_dark' onclick='add_to_cart(" + id + ")' title='Add " + reference + " to cart'><i class='fa fa-cart-plus'></i></button> <button type='button' class='button button_danger_dark' onclick='add_to_cart_as_bonus(" + id + ")' title='Add " + reference + " to cart as bonus'><i class='fa fa-gift'></i></button></td></tr>");
					});
				} else {
					$('#shopping_item_list_table').hide();
				}
				
				$('#page').html('');
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
	
	$("#sales_order_form").validate({
		ignore: '',
		rules: {"hidden_field": {min:1}}
	});
	
	function validate_form(n){
		if(!$("#sales_order_form").valid()){
			return false;
		} else {
			$('#sales_order_tbody').html('');
			$('input').attr('readonly',true);
			$('select').attr('readonly',true);
			$('#table_item_confirm').html('');
			
			var taxing 		= $("#taxing option:selected").html();
			var date 		= $("#sales_order_date").val();
			var customer	= $("#select_customer_button").text();
			var seller		= $("#sales_order_seller option:selected").html();
			var method		= $("#method option:selected").html();
			
			$('#customer_p').html(customer);
			$('#customer_address_p').html(customer_address_select);
			$('#date').html(date);
			$('#taxing_p').html(taxing);
			$('#seller_p').html(seller);
			$('#invoicing_p').html(method);
			
			var sales_order_value = 0;
			
			$('td[id^="reference-"]').each(function(){
				var id 			= $(this).attr('id');
				var uid 		= parseInt(id.substring(10,50));
				var quantity 	= $('#quantity-' + uid).val();
				var discount 	= $('#discount-' + uid).val();
				var price_list 	= $('#price_list-' + uid).val();
				var name	 	= $('#name-' + uid).html();
				var reference	= $('#reference-' + uid).html();
				var unit_price	= price_list * ( 100 - discount) / 100;
				var total_price	= unit_price * quantity;
				sales_order_value += total_price;
				
				$('#table_item_confirm').append(
				"<tr>"+
					"<td>" + reference  + "</td>"+
					"<td>" + name + "</td>"+
					"<td>Rp. " + numeral(price_list).format('0,0.00') + "</td>"+
					"<td>" + numeral(discount).format('0,0.00') + " %</td>"+
					"<td>Rp. " + numeral(unit_price).format('0,0.00') + "</td>"+
					"<td>" + numeral(quantity).format('0,0') + "</td>"+
					"<td>Rp. " + numeral(total_price).format('0,0.00') + "</td>"+
				"</tr>"
				);
			});
			
			$('td[id^="bonus_reference-"]').each(function(){
				var id 			= $(this).attr('id');
				var uid 		= parseInt(id.substring(16,50));
				var quantity 	= $('#bonus_quantity-' + uid).val();
				var name	 	= $('#bonus_name-' + uid).html();
				var reference	= $('#bonus_reference-' + uid).html();
				$('#table_item_confirm').append(
				"<tr>"+
					"<td>" + reference  + "</td>"+
					"<td>" + name + "</td>"+
					"<td>Rp. " + numeral(0).format('0,0.00') + "</td>"+
					"<td>" + numeral(0).format('0,0.00') + " %</td>"+
					"<td>Rp. " + numeral(0).format('0,0.00') + "</td>"+
					"<td>" + numeral(quantity).format('0,0') + "</td>"+
					"<td>Rp. " + numeral(0).format('0,0.00') + "</td>"+
				"</tr>"
				);
			});
			
			$('#table_item_confirm').append(
				"<tr>"+
					"<td colspan='4'></td>"+
					"<td colspan='2'>Total</td>"+
					"<td>Rp. " + numeral(sales_order_value).format('0,0.00') + "</td>"+
				"</tr>"
			);
			
			$('#validate_sales_order_wrapper').fadeIn();
		}
	};
	
	$('.alert_full_close_button').click(function(){
		$(this).parents().find('.alert_wrapper').fadeOut();
	});
	
	function submit_form(){
		$("#sales_order_form").validate();
		
		if($("#sales_order_form").valid()){
			$('#sales_order_form').submit();
		};
	};
	
	$('#customer_page').change(function(){
		refresh_customer_view();
	});
	
	$('#search_customer').change(function(){
		refresh_customer_view(1);
	});
	
	function refresh_customer_view(page = $('#customer_page').val()){
		$.ajax({
			url:'<?= site_url('Customer/show_items') ?>',
			data:{
				page:page,
				term:$('#search_customer').val(),
			},
			success:function(response){
				$('#customer_table').html('');
				var customer_array	= response.customers;
				var pages			= response.pages;
				$.each(customer_array, function(index, customer){
					var customer_id		= customer.id;
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
					
					$('#customer_table').append("<tr><td id='customer_name-" + customer_id + "'>" + customer_name + "</td><td id='customer_address-" + customer_id + "'>" + complete_address + "</td><td><button type='button' class='button button_success_dark' onclick='select_customer(" + customer_id + ")'><i class='fa fa-check'></i></button></td>");
					
					var page		= $('#customer_page').val();
					$('#customer_page').html('');
					for(i = 1; i <= pages; i++){
						if(i == page){
							$('#customer_page').append("<option value='" + i + "' selected>" + i + "</option>");
						} else {
							$('#customer_page').append("<option value='" + i + "'>" + i + "</option>");
						}
					}
				});
			}
		});
	}
	
	$('#search_customer').change(function(){
		refresh_customer_view();
	});
	
	$('#select_customer_button').click(function(){
		$('#select_customer_wrapper').slideToggle(300);
		$.ajax({
			url:'<?= site_url('Customer/show_items') ?>',
			data:{
				page:1,
				term:'',
			},
			success:function(response){
				var customer_array	= response.customers;
				var pages			= response.pages;
				$.each(customer_array, function(index, customer){
					var customer_id		= customer.id;
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
					
					$('#customer_table').append("<tr><td id='customer_name-" + customer_id + "'>" + customer_name + "</td><td id='customer_address-" + customer_id + "'>" + complete_address + "</td><td><button type='button' class='button button_success_dark' onclick='select_customer(" + customer_id + ")'><i class='fa fa-check'></i></button></td>");
					
					var page		= $('#customer_page').val();
					$('#customer_page').html('');
					for(i = 1; i <= pages; i++){
						if(i == page){
							$('#customer_page').append("<option value='" + i + "' selected>" + i + "</option>");
						} else {
							$('#customer_page').append("<option value='" + i + "'>" + i + "</option>");
						}
					}
				});
			}
		});
	});
	
	function select_customer(n){
		var customer_name		= $('#customer_name-' + n).html();
		var customer_address	= $('#customer_address-' + n).html();
		$('#select_customer_button').html(customer_name);
		$('#customer_address_select').html(customer_address);
		$('#select_customer_wrapper').fadeOut();		
		$('#customer_id').val(n);		
	};
	
	function remove_item(n){
		$('#item_row-' + n).remove();
		
		if($('#cart_products tr').length == 0){
			$('#cart_products_table').hide();
			$('#submit_button').hide();
		}
	}
	
	function remove_bonus_item(n){
		$('#bonus_item_row-' + n).remove();
		
		if($('#bonus_cart_products tr').length == 0){
			$('#bonus_cart_products_table').hide();
		}
	}
	
	function add_to_cart(n){
		$.ajax({
			url:'<?= site_url('Item/add_item_to_cart') ?>',
			data:{
				price_list_id:n
			},
			type:'POST',
			beforeSend:function(){
				$('button').attr('disabled',true);
			},
			success:function(response){
				var item_id		= response.id;
				var reference	= response.reference;
				var name		= response.name;
				var price_list	= response.price_list;
				
				if($('#item_row-' + item_id).length == 0){
					$('#cart_products').append("<tr id='item_row-" + item_id + "'><td id='reference-" + item_id + "'>" + reference + "</td>" + 
						"<td id='name-" + item_id + "'>" + name + "</td>" + 
						"<td>Rp. " + numeral(price_list).format('0,0.00') + "</td><input type='hidden' id='price_list-" + item_id + "' value='" + price_list+ "'>" +
						"<td><input type='number' class='form-control' min='0' max='100' required name='discount[" + item_id + "]' id='discount-" + item_id + "'></td>" +
						"<td><input type='number' class='form-control' min='0' max='100' required name='quantity[" + item_id + "]' id='quantity-" + item_id + "'></td>" + 
						"<td><button type='button' class='button button_danger_dark' onclick='remove_item(" + item_id + ")'><i class='fa fa-trash'></i></button></td></tr>");
				}
				
				$('button').attr('disabled',false);
				$('.alert_full_close_button').click();
				
				if($('#cart_products tr').length > 0){
					$('#cart_products_table').show();
					$('#submit_button').show();
				}
			}
		})
	}
	
	function add_to_cart_as_bonus(n){
		if($('#cart_products tr').length > 0){
			$.ajax({
				url:'<?= site_url('Item/add_item_to_cart_as_bonus') ?>',
				data:{
					price_list_id:n
				},
				type:'POST',
				beforeSend:function(){
					$('button').attr('disabled',true);
				},
				success:function(response){
					var item_id		= response.id;
					var reference	= response.reference;
					var name		= response.name;
					
					if($('#bonus_item_row-' + item_id).length == 0){
						$('#bonus_cart_products').append("<tr id='bonus_item_row-" + n + "'><td id='bonus_reference-" + n + "'>" + reference + "</td><td id='bonus_name-" + n + "'>" + name + "</td>" + 
							"<td><input type='number' class='form-control' min='1' required name='bonus_quantity[" + n + "]' id='bonus_quantity-" + n + "'></td>" +
							"<td><button type='button' class='button button_danger_dark' onclick='remove_bonus_item(" + n + ")'><i class='fa fa-trash'></i></button></td>");
					}
					$('button').attr('disabled',false);
					$('.alert_full_close_button').click();
					
					if($('#bonus_cart_products tr').length > 0){
						$('#bonus_cart_products_table').show();
					}
				}
			})
		}
	}
	
	$('#search_bar').change(function(){
		refresh_view();
	});
	
	$('#page').change(function(){
		refresh_view();
	});
</script>