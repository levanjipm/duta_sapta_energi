<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Purchasing') ?>' title='Purchasing'><i class='fa fa-briefcase'></i></a> / Return / Create</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<form action='<?= site_url('Item_return/purchasing_input') ?>' id='purchase_return_form' method='POST'>
			<label>Supplier</label>
			<select class='form-control' name='supplier' id='supplier'>
<?php
	foreach($suppliers as $supplier){
?>
				<option value='<?= $supplier->id ?>'><?= $supplier->name ?></option>
<?php
	}
?>
			</select><br>
		
			<label>Invoice reference</label>
			<input type='text' class='form-control' name='invoice_reference' required><br>
			
			<button type='button' class='button button_default_dark' id='add_item_button'><i class='fa fa-shopping-cart'></i> Add item</button><br><br>
			
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
			
			<button type='button' class='button button_success_dark' id='submit_button' onclick='show_purchase_order()' style='display:none'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='add_item_wrapper'>
	<div class='alert_box_full'>
		<button type='button' class='button alert_full_close_button' title='Close add item session'>&times;</button>

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

<div class='alert_wrapper' id='purchase_return_validation'>
	<button type='button' class='slide_alert_close_button'>&times </button>
	<div class='alert_box_slide'>
		<label>Supplier</label>
		<p style='font-family:museo' id='supplier_name_p'></p>
		<p style='font-family:museo' id='supplier_address_p'></p>
		<p style='font-family:museo' id='supplier_city_p'></p>
		
		<label>Invoice reference</label>
		<p style='font-family:museo' id='invoice_reference_p'></p>
		
		<label>Items</label>
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Price list</th>
				<th>Discount</th>
				<th>Net price</th>
				<th>Quantity</th>
				<th>Total price</th>
			</tr>
			<tbody id='return_table_validation'></tbody>
		</table>
		
		<button type='button' id='confirm_button' class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
	</div>
</div>

<script>
	$('#submit_button').click(function(){
		$('#purchase_return_form').validate();
		
		if($('#purchase_return_form').valid()){
			$('#return_table_validation').html('');
			var supplier		= $('#supplier').val();
			
			$.ajax({
				url:'<?= site_url('Supplier/select_by_id') ?>',
				data:{
					id:supplier
				},
				type:'GET',
				success:function(response){
					var complete_address		= '';
					var supplier_name			= response.name;
					complete_address			+= response.address;
					var supplier_city			= response.city;
					var supplier_number			= response.number;
					var supplier_rt				= response.rt;
					var supplier_rw				= response.rw;
					var supplier_postal			= response.postal_code;
					var supplier_block			= response.block;
		
					if(supplier_number != null){
						complete_address	+= ' No. ' + supplier_number;
					}
					
					if(supplier_block != null){
						complete_address	+= ' Blok ' + supplier_block;
					}
				
					if(supplier_rt != '000'){
						complete_address	+= ' RT ' + supplier_rt;
					}
					
					if(supplier_rw != '000' && supplier_rt != '000'){
						complete_address	+= ' /RW ' + supplier_rw;
					}
					
					if(supplier_postal != null){
						complete_address	+= ', ' + supplier_postal;
					}
					
					$('#supplier_p').html(supplier_name);
					$('#supplier_address_p').html(complete_address);
					$('#supplier_city_p').html(supplier_city);
				}
			});
			
			var purchase_return_value = 0;
			
			$('td[id^="reference-"]').each(function(){
				var id 			= $(this).attr('id');
				var uid 		= parseInt(id.substring(10,265));
				var quantity 	= $('#quantity-' + uid).val();
				var discount 	= $('#discount-' + uid).val();
				var price_list 	= $('#price_list-' + uid).val();
				
				var name	 	= $('#name-' + uid).html();
				var reference	= $('#reference-' + uid).html();
				var unit_price	= price_list * ( 100 - discount) / 100;
				var total_price	= unit_price * quantity;
				
				purchase_return_value += total_price;
				
				$('#return_table_validation').append(
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
			
			$('#return_table_validation').append(
				"<tr>"+
					"<td colspan='4'></td>"+
					"<td colspan='2'>Total</td>"+
					"<td>Rp. " + numeral(purchase_return_value).format('0,0.00') + "</td>"+
				"</tr>"
			);
			
			$('#purchase_return_validation').fadeIn(300, function(){
				$('#purchase_return_validation .alert_box_slide').show("slide", { direction: "right" }, 250);
			});
		};
	});
	
	$('.slide_alert_close_button').click(function(){
		$('#purchase_return_validation .alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$('#purchase_return_validation').fadeOut();
		});
	});
	
	$('#add_item_button').click(function(){
		$('#search_bar').val('');
		refresh_view();
	});
	
	$('#search_bar').change(function(){
		refresh_view(1);
	});
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Item/search_item_cart') ?>',
			data:{
				term:$('#search_bar').val(),
				page:page
			},
			success:function(response){
				$('#add_item_wrapper').slideDown(300);
				$('#shopping_item_list_tbody').html('');
				var item_array	= response.items;
				var pages		= response.pages;
				var page		= response.page;
				
				if($('#cart_products tr').length > 0){
					$('#cart_products_table').show();
				} else {
					$('#cart_products_table').hide();
				}
				
				$.each(item_array, function(index, item){
					var reference		= item.reference;
					var id				= item.item_id;
					var name			= item.name;
					
					$('#shopping_item_list_tbody').append("<tr><td>" + reference + "</td><td>" + name + "</td><td><button type='button' class='button button_success_dark' onclick='add_to_cart(" + id + ")' title='Add " + reference + " to cart'><i class='fa fa-cart-plus'></i></button></td></tr>");
				});
				
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
	
	function remove_item(n){
		$('#item_row-' + n).remove();
		
		if($('#cart_products tr').length == 0){
			$('#cart_products_table').hide();
			$('#submit_button').hide();
		}
	}
	
	function add_to_cart(n){
		$.ajax({
			url:'<?= site_url('Purchase_order/add_item_to_cart') ?>',
			data:{
				item_id:n
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
					$('#cart_products').append("<tr id='item_row-" + n + "'><td id='reference-" + n + "'>" + reference + "</td><td id='name-" + n + "'>" + name + "</td>" + 
						"<td><input type='number' class='form-control' min='1' required name='price_list[" + n + "]' id='price_list-" + n + "'><br><label>" + numeral(price_list).format('0,0.00') + "</label> <button type='button' class='button button_default_dark' onclick='copy_price_list(" + item_id + "," + price_list + ")'><i class='fa fa-copy'></i></button></td>" +
						"<td><input type='number' class='form-control' min='0' max='100' required name='discount[" + n + "]' id='discount-" + n + "'></td>" +
						"<td><input type='number' class='form-control' min='1' required name='quantity[" + n + "]' id='quantity-" + n + "'></td>" + 
						"<td><button type='button' class='button button_danger_dark' onclick='remove_item(" + n + ")'><i class='fa fa-trash'></i></button></td>");
				}
				$('button').attr('disabled',false);
				$('#add_item_wrapper').fadeOut();
				
				if($('#cart_products tr').length > 0){
					$('#cart_products_table').show();
					$('#submit_button').show();
				}
			}
		})
	}
	
	function copy_price_list(item_id, price_list){
		$('#price_list-' + item_id).val(price_list);
	}
	
	$('.alert_close_button').click(function(){
		$('#add_item_wrapper').fadeOut();
	});
	
	$('#page').change(function(){
		refresh_view();
	});
	
	$('#confirm_button').click(function(){
		$('#purchase_return_form').validate();
		
		if($('#purchase_return_form').valid()){
			$('#purchase_return_form').submit();
		}
	});
</script>