<div class='dashboard'>
	<h2 style='font-family:bebasneue'>Create sales order</h2>
	<hr>
	<form action='<?= site_url('Sales_order/input_sales_order') ?>' method='POST' id='sales_order_form'>
	<label>Date</label>
	<input type='date' class='form-control' name='sales_order_date' id='sales_order_date' value='<?= date('Y-m-d') ?>'>
	
	<input type='hidden' value='<?= $guid ?>' name='guid'>
	
	<label>Customer</label>
	<button class='form-control' type='button' id='select_customer_button' style='text-align:left'></button>
	
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
	
	<br><br>
	<button type='button' class='button button_default_light' id='add_item_button'><i class='fa fa-shopping-cart'></i> Add item</button>
	<div id='sales_order_items' style='padding:10px'></div>
	</form>
</div>

<div class='alert_wrapper' id='select_customer_wrapper'>
	<div class='alert_box_default'>
		<button class='alert_close_button'>&times </button>
		
		<h2 style='font-family:bebasneue'>Select customer</h2>
		<br>
		<input type='text' class='form-control' id='search_customer'>
		<br>
		<div id='select_customer'></div>
	</div>
</div>

<div class='alert_wrapper' id='add_item_wrapper'>
	<div class='alert_box_default'></div>
</div>

<div class='alert_wrapper' id='validate_sales_order_wrapper'>
	<div class='alert_box_default' id='validate_sales_order_box'>
		<button class='alert_close_button'>&times</button>
		<label>Date</label>
		<p id='date'></p>
		
		<label>Taxing</label>
		<p id='taxing_p'></p>
		
		<label>Seller</label>
		<p id='seller_p'></p>
		
		<label>Customer</label>
		<p id='customer_p'></p>
		
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
				<th>Total</th>
			</tr>
			<tbody id='sales_order_tbody'></tbody>
		</table>
		<button class='button button_default_dark' onclick='submit_form()'>Submit</button>
	</div>
</div>
<script>	
	$(document).ready(function(){
		$.ajax({
			url:'<?= site_url('Sales_order/update_cart_view') ?>',
			success:function(response){
				$('#sales_order_items').html(response);
			}
		});
	});
	
	$('#add_item_button').click(function(){
		$.ajax({
			url:'<?= site_url('Item/shopping_cart_view') ?>',
			success:function(response){
				$('#add_item_wrapper .alert_box_default').html(response);
				$('#add_item_wrapper').fadeIn();
			}
		});
	});

	function remove_item(n){
		$.ajax({
			url:'<?= site_url('Sales_order/remove_item_from_cart') ?>',
			data:{
				item_id:n
			},
			type:'POST',
			success:function(){
				$.ajax({
					url:'<?= site_url('Sales_order/update_cart_view') ?>',
					success:function(response){
						$('#sales_order_items').html(response);
					}
				});
			}
		})
	}
	$("#sales_order_form").validate({
		ignore: '',
		rules: {"hidden_field": {min:1}}
	});
	
	function show_sales_order(n){
		if(!$("#sales_order_form").valid()){
			return false;
		} else {
			$('#sales_order_tbody').html('');
			$('input').attr('readonly',true);
			$('select').attr('readonly',true);
			
			var taxing 		= $("#taxing option:selected").html();
			var date 		= $("#sales_order_date").val();
			var customer	= $("#select_customer_button").text();
			var seller		= $("#sales_order_seller option:selected").html();
			var method		= $("#method option:selected").html();
			
			$('#customer_p').html(customer);
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
				var price_list 	= $('#price_list-' + uid).html();
				var name	 	= $('#name-' + uid).html();
				var reference	= $('#reference-' + uid).html();
				var unit_price	= price_list * ( 100 - discount) / 100;
				var total_price	= unit_price * quantity;
				sales_order_value += total_price;
				
				
				$('#sales_order_tbody').append(
				"<tr>"+
					"<td>" + reference  + "</td>"+
					"<td>" + name + "</td>"+
					"<td>Rp. " + numeral(price_list).format('0,0.00') + "</td>"+
					"<td>" + numeral(discount).format('0,0.00') + " %</td>"+
					"<td>" + numeral(unit_price).format('0,0.00') + "</td>"+
					"<td>" + numeral(quantity).format('0,0') + "</td>"+
					"<td>Rp. " + numeral(total_price).format('0,0.00') + "</td>"+
				"</tr>"
				);
			});
			
			$('#sales_order_tbody').append(
				"<tr>"+
					"<td colspan='4'></td>"+
					"<td colspan='2'>Total</td>"+
					"<td>Rp. " + numeral(sales_order_value).format('0,0.00') + "</td>"+
				"</tr>"
			);
			
			$('#validate_sales_order_wrapper').fadeIn();
		}
	};
	
	$('.alert_close_button').click(function(){
		$('input').attr('readonly',false);
		$('select').attr('readonly',false);
		$(this).parent().parent().fadeOut();
	});
	
	function submit_form(){
		$("#sales_order_form").validate();
		
		if($("#sales_order_form").valid()){
			$('#sales_order_form').submit();
		};
	};
	
	function update_view(){
		$.ajax({
			url:'<?= site_url('Customer/update_view_select') ?>',
			data:{
				page:$('#page').val(),
				term:$('#search_customer').val(),
			},
			success:function(response){
				$('#select_customer').html(response);
				$('#select_customer_wrapper').fadeIn();
			}
		});
	};
	
	$('#search_customer').change(function(){
		$.ajax({
			url:'<?= site_url('Customer/update_view_select') ?>',
			data:{
				page:1,
				term:$('#search_customer').val(),
			},
			success:function(response){
				$('#select_customer').html(response);
				$('#select_customer_wrapper').fadeIn();
			}
		});
	});
	
	$('#select_customer_button').click(function(){
		$.ajax({
			url:'<?= site_url('Customer/update_view_select') ?>',
			data:{
				page:1,
				term:'',
			},
			success:function(response){
				$('#select_customer').html(response);
				$('#select_customer_wrapper').fadeIn();
			}
		});
	});
	
	function select_customer(id, name){
		$('#select_customer_button').html(name);
		$('#customer_id').val(id);
		$('#select_customer_wrapper').fadeOut();
	};
</script>