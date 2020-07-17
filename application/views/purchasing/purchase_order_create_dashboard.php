<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Purchasing') ?>' title='Purchasing'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Purchase_order') ?>'>Purchase order</a> /Create</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<form action='<?= site_url('Purchase_order/inputItem') ?>' method='POST' id='purchase_order_form'>
			<input type='hidden' value='<?= $guid ?>'name='guid'>
	
			<label>Date</label>
			<input type='date' class='form-control' name='date' required min='2020-01-01' id='purchase_order_date'>
			
			<label>Send date</label>
			<select class='form-control' name='status' id='purchase_order_status'>
				<option value='1'>Choose date</option>
				<option value='2'>Urgent delivery</option>
				<option value='3'>Unknown date</option>
			</select>
	
			<div id='purchase_order_status_detail'>
				<label>Send date request</label>
				<input type='date' class='form-control' name='request_date' required min='2020-01-01'>
			</div>
			
			<label>Supplier</label>
			<select class='form-control' name='supplier' id='supplier'>
<?php
	foreach($suppliers as $supplier){
?>
				<option value='<?= $supplier->id ?>'><?= $supplier->name ?></option>
<?php
	}
?>
			</select>
	
			<label>Taxing</label>
			<select class='form-control' name='taxing' id='taxing'>
				<option value='0'>Non - tax</option>
				<option value='1' selected>Tax</option>
			</select>
	
			<br>
			<label><input type='checkbox' id='dropship'> Dropship</label>
			
			<div id='dropship_detail' style='display:none'>
				<label>Address</label>
				<textarea class='form-control' name='dropship_address'></textarea>
				
				<label>City</label>
				<input type='text' class='form-control' name='dropship_city'>
				
				<label>Person in charge</label>
				<input type='text' class='form-control' name='dropship_contact_person'>
				
				<label>Contact number</label>
				<input type='text' class='form-control' name='dropship_contact'>
			</div>
			
			<br><br>
			<label>Note</label>
			<textarea class='form-control' style='resize:none' name='note' rows='3' id='note'></textarea>
	
			<br>
			<button type='button' class='button button_default_dark' id='add_item_button'><i class='fa fa-shopping-cart'></i> Add item</button>
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
	
			<button type='button' class='button button_success_dark' id='submit_button' onclick='show_purchase_order()' style='display:none'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='add_item_wrapper'>
	<div class='alert_box_full'>
		<div class='row' style='text-align:center'>
			<div class='col-lg-2 col-md-2 col-sm-4 col-xs-4 col-lg-offset-5 col-md-offset-5 col-sm-offset-4 col-sm-offset-4'>
				<button type='button' class='button alert_full_close_button' title='Close add item session' onclick="$('#add_item_wrapper').fadeOut()"></button>
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
<div class='alert_wrapper' id='validate_purchase_order_wrapper'>
	<button type='button' class='slide_alert_close_button'>&times </button>
	<div class='alert_box_slide'>
		<label>Date</label>
		<p id='date'></p>
		
		<label>Taxing</label>
		<p id='taxing_p'></p>
		
		<label>Supplier</label>
		<p id='supplier_p'></p>			
		<p id='supplier_address_p'></p>		
		<p id='supplier_city_p'></p>
		
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
			<tbody id='purchase_order_tbody'></tbody>
		</table>
		
		<label>Note</label>
		<p id='purchases_order_note'></p>
		<button class='button button_default_dark' onclick='submit_form()'>Submit</button>
	</div>
</div>

<script>
	$('#dropship').change(function(){
		if($(this).prop('checked') == true){
			$('#dropship_detail').fadeIn();
			$('#dropship_detail input').attr('required',true);
		} else {
			$('#dropship_detail').fadeOut();
			$('#dropship_detail input').attr('required',false);
		}
	});

	$('#add_item_button').click(function(){
		$('#search_bar').val('');
		refresh_view();
	});
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Item/showItems') ?>',
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
					
					$('#shopping_item_list_tbody').append("<tr><td>" + reference + "</td><td>" + name + "</td><td><button type='button' class='button button_success_dark' onclick='add_to_cart(" + id + ")' title='Add " + reference + " to cart'><i class='fa fa-cart-plus'></i></button> <button type='button' class='button button_danger_dark' onclick='add_to_cart_as_bonus(" + id + ")' title='Add " + reference + " to cart as bonus'><i class='fa fa-gift'></i></button></td></tr>");
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
	
	function remove_bonus_item(n){
		$('#bonus_item_row-' + n).remove();
		
		if($('#bonus_cart_products tr').length == 0){
			$('#bonus_cart_products_table').hide();
		}
	}
	
	function show_purchase_order(){
		if(!$("#purchase_order_form").valid()){
			return false;
		} else {
			$('#purchase_order_tbody').html('');
			$('input').attr('readonly',true);
			$('select').attr('readonly',true);
			$('textarea').attr('readonly', true);
			
			var taxing 		= $("#taxing").val();
			
			if(taxing == 1){
				var taxing_p	= 'Taxable purchase';
			} else {
				var taxing_p	= 'Non-taxable purchase';
			}
			
			var date 		= $("#purchase_order_date").val();
			var supplier	= $("#supplier").val();
			var note		= $('#note').val();
			
			$.ajax({
				url:'<?= site_url('Supplier/getById') ?>',
				data:{
					id:supplier
				},
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
			
			$('#date').html(my_date_format(date));
			$('#taxing_p').html(taxing_p);
			
			$('#purchases_order_note').html(note);
			
			var purchase_order_value = 0;
			
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
				
				purchase_order_value += total_price;
				
				$('#net_price_value-' + id).val(unit_price);
				
				$('#purchase_order_tbody').append(
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
			
			$('td[id^="bonus_reference-"]').each(function(){
				var id 			= $(this).attr('id');
				var uid 		= parseInt(id.substring(16,271));
				var quantity 	= $('#bonus_quantity-' + uid).val();
				
				var name	 	= $('#bonus_name-' + uid).html();
				var reference	= $('#bonus_reference-' + uid).html();
				var unit_price	= 0;
				var total_price	= unit_price * quantity;
				
				purchase_order_value += total_price;
				
				$('#net_price_value-' + id).val(unit_price);
				
				$('#purchase_order_tbody').append(
				"<tr>"+
					"<td>" + reference  + "</td>"+
					"<td>" + name + "</td>"+
					"<td>Rp. " + numeral(0).format('0,0.00') + "</td>"+
					"<td>" + numeral(0).format('0,0.00') + " %</td>"+
					"<td>" + numeral(unit_price).format('0,0.00') + "</td>"+
					"<td>" + numeral(quantity).format('0,0') + "</td>"+
					"<td>Rp. " + numeral(total_price).format('0,0.00') + "</td>"+
				"</tr>");
			});
			
			$('#purchase_order_tbody').append(
				"<tr>"+
					"<td colspan='4'></td>"+
					"<td colspan='2'>Total</td>"+
					"<td>Rp. " + numeral(purchase_order_value).format('0,0.00') + "</td>"+
				"</tr>"
			);
			
			$('#validate_purchase_order_wrapper').fadeIn(300, function(){
				$('#validate_purchase_order_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
			});
		}
	};
	
	$('.slide_alert_close_button').click(function(){
		$('#validate_purchase_order_wrapper .alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$('#validate_purchase_order_wrapper').fadeOut();
			$('input').attr('readonly',false);
			$('select').attr('readonly',false);
			$('textarea').attr('readonly', false);
		});
	});
	
	function submit_form(){
		$('#purchase_order_form').submit();
	};
	
	function add_to_cart(n){
		$.ajax({
			url:'<?= site_url('Item/showById') ?>',
			data:{
				id:n
			},
			beforeSend:function(){
				$('button').attr('disabled',true);
			},
			success:function(response){
				var reference	= response.reference;
				var name		= response.name;
				var price_list	= response.price_list;
				
				if($('#item_row-' + n).length == 0){
					$('#cart_products').append("<tr id='item_row-" + n + "'><td id='reference-" + n + "'>" + reference + "</td><td id='name-" + n + "'>" + name + "</td>" + 
						"<td><input type='number' class='form-control' min='1' required name='price_list[" + n + "]' id='price_list-" + n + "'><br><label>" + numeral(price_list).format('0,0.00') + "</label> <button type='button' class='button button_default_dark' onclick='copy_price_list(" + n + "," + price_list + ")'><i class='fa fa-copy'></i></button></td>" +
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
	
	function add_to_cart_as_bonus(n){
		$.ajax({
			url:'<?= site_url('Item/showById') ?>',
			data:{
				id:n
			},
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
				$('#add_item_wrapper').fadeOut();
				
				if($('#bonus_cart_products tr').length > 0){
					$('#bonus_cart_products_table').show();
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
	
	$('#search_bar').change(function(){
		refresh_view(1)
	});
	
	$('#purchase_order_status').change(function(){
		var status		= $('#purchase_order_status').val();
		if(status == 1){
			$('#purchase_order_status_detail').show();
			$('#purchase_order_status_detail input').attr('required', true);
		} else {
			$('#purchase_order_status_detail').hide();
			$('#purchase_order_status_detail input').attr('required', false);
		}
	});
</script>