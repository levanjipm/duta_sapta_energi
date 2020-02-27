<div class='dashboard'>
	<h2 style='font-family:bebasneue'>Purchase order</h2>
	<hr>
	
	<form action='<?= site_url('Purchase_order/input_purchase_order') ?>' method='POST' id='purchase_order_form'>
	<input type='hidden' value='<?= $guid ?>'name='guid'>
	
	<label>Date</label>
	<input type='date' class='form-control' name='date' required min='2020-01-01' id='purchase_order_date'>
	
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
	<select class='form-control' name='taxing'>
		<option value='0'>Non - tax</option>
		<option value='1' selected>Tax</option>
	</select>
	
	<label><input type='checkbox' id='dropship'>Dropship</label>
	
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
	
	<br>
	<button type='button' class='button button_default_light' id='add_item_button'><i class='fa fa-shopping-cart'></i> Add item</button>
	<br><br>
	
	<div id='purchase_order_items'></div>
	
	</form>
</div>

<div class='alert_wrapper' id='add_item_wrapper'>
	<div class='alert_box_default'></div>
</div>

<div class='alert_wrapper' id='validate_purchase_order_wrapper'>
	<div class='alert_box_default' id='validate_purchase_order_box'>
		<button class='alert_close_button'>&times</button>
		<label>Date</label>
		<p id='date'></p>
		
		<label>Taxing</label>
		<p id='taxing_p'></p>
		
		<label>Supplier</label>
		<p id='supplier_p'></p>		
		
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
		$.ajax({
			url:'<?= site_url('Item/shopping_cart_view_purchase') ?>',
			data:{
				page:1
			},
			success:function(response){
				$('#add_item_wrapper .alert_box_default').html(response);
				$('#add_item_wrapper').fadeIn();
			}
		});
	});
	
	function remove_item(n){
		$.ajax({
			url:'<?= site_url('Purchase_order/remove_item_from_cart') ?>',
			data:{
				item_id:n
			},
			type:'POST',
			success:function(){
				$.ajax({
					url:'<?= site_url('Purchase_order/update_cart_view') ?>',
					success:function(response){
						$('#purchase_order_items').html(response);
					}
				});
			}
		})
	}
	
	function show_purchase_order(n){
		if(!$("#purchase_order_form").valid()){
			return false;
		} else {
			$('#purchase_order_tbody').html('');
			$('input').attr('readonly',true);
			$('select').attr('readonly',true);
			
			var taxing 		= $("#taxing option:selected").html();
			var date 		= $("#purchase_order_date").val();
			var supplier	= $("#supplier option:selected").html();
			
			$('#supplier_p').html(supplier);
			$('#date').html(date);
			$('#taxing_p').html(taxing);
			
			var purchase_order_value = 0;
			
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
			
			$('#purchase_order_tbody').append(
				"<tr>"+
					"<td colspan='4'></td>"+
					"<td colspan='2'>Total</td>"+
					"<td>Rp. " + numeral(purchase_order_value).format('0,0.00') + "</td>"+
				"</tr>"
			);
			
			$('#validate_purchase_order_wrapper').fadeIn();
		}
	};
	
	function submit_form(){
		$('#purchase_order_form').submit();
	};
</script>