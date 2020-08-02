<style>
	.button_default_light{
		float:right;
	}
</style>
<form action='<?= site_url('Inventory_case/input/found') ?>' method='POST' id='foundGoodsForm'>
	<label>Date</label>
	<input type='date' class='form-control' name='date' id='case_date' required><br>
	
	<button type='button' class='button button_default_dark' id='add_item_button'>Add item</button><br><br>
	
	<table class='table table-bordered' id='cart_products_table' style='display:none'>
		<tr>
			<th>Reference</th>
			<th>Name</th>
			<th>Quantity</th>
			<th>Unit price</th>
			<th>Action</th>
		</tr>
		<tbody id='cart_products'></tbody>
	</table>
	
	<button type='button' class='button button_default_light' id='shopping_item_list_button' style='display:none'><i class='fa fa-long-arrow-right'></i></button>
</form>

<div class='alert_wrapper' id='add_item_wrapper'>
	<div class='alert_box_full'>
	<button type='button' class='button alert_full_close_button' title='Close add item session'>&times;</button>
		<div class='row'>
			<div class='col-xs-12'>
				<h2 style='font-family:bebasneue'>Add item to list</h2>
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

<div class='alert_wrapper' id='foundGoodsFormValidation'>
	<button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Add found goods case</h3>
		<hr>
		
		<label>Date of found goods</label>
		<p style='font-family:museo' id='date_p'></p>
		
		<table class='table table-bordered'>
			<tr>
				<tr>
					<th>Reference</th>
					<th>Name</th>
					<th>Unit price</th>
					<th>Quantity</th>
					<th>Total price</th>
				</tr>
				<tbody id='validate_cart_products'></tbody>
			</tr>
		</table>
		
		<button type='button' class='button button_default_dark' id='confirm_button'><i class='fa fa-long-arrow-right'></i></button>
	</div>
</div>

<script>
	$('#add_item_button').click(function(){
		$('#search_bar').val('');
		refresh_view(1);
	});
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Item/showItems') ?>',
			data:{
				term:$('#search_bar').val(),
				page: page,
			},
			success:function(response){
				$('#shopping_item_list_tbody').html('');
				var item_array	= response.items;
				var pages		= response.pages;
				var page		= response.page;
				
				if(item_array.length > 0){
					$('#shopping_item_list_table').show();
					$.each(item_array, function(index, item){
						var reference		= item.reference;
						var id				= item.item_id;
						var name			= item.name;
						
						$('#shopping_item_list_tbody').append("<tr><td>" + reference + "</td><td>" + name + "</td><td><button type='button' class='button button_default_dark' onclick='addToCart(" + id + ")' title='Add " + reference + " to list'><i class='fa fa-cart-plus'></i></button></td></tr>");
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
				
				$('#add_item_wrapper').fadeIn();
			}
		});
	}
	
	$('#page').change(function(){
		refresh_view();
	});
	
	$('#search_bar').change(function(){
		refresh_view(1);
	});
	
	$('.alert_full_close_button').click(function(){
		$(this).parents().find('.alert_wrapper').fadeOut();
	});
	
	function addToCart(n){
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
				
				if($('#item_row-' + item_id).length == 0){
					$('#cart_products').append("<tr id='item_row-" + item_id + "'><td id='reference-" + item_id + "'>" + reference + "</td>" + 
						"<td id='name-" + item_id + "'>" + name + "</td><td><input type='number' class='form-control' min='1' required name='quantity[" + item_id + "]' id='quantity-" + item_id + "'></td><td><input type='number' class='form-control' min='0' required name='price[" + item_id + "]' id='price-" + item_id + "'></td><td><button type='button' class='button button_danger_dark' onclick='remove_item(" + item_id + ")'><i class='fa fa-trash'></i></button></tr>");
				}
				
				$('button').attr('disabled',false);
				$('.alert_full_close_button').click();
				
				if($('#cart_products tr').length > 0){
					$('#cart_products_table').show();
					$('#shopping_item_list_button').attr('disabled', false);
					$('#shopping_item_list_button').show();
				}
			}
		})
	}
	
	function remove_item(n){
		$('#item_row-' + n).remove();
		if($('#cart_products tr').length > 0){
			$('#cart_products_table').show();
			$('#shopping_item_list_button').attr('disabled', false);
			$('#shopping_item_list_button').show();
		} else {
			$('#cart_products_table').hide();
			$('#shopping_item_list_button').attr('disabled', true);
			$('#shopping_item_list_button').hide();
		}
	};
	$('#foundGoodsForm').validate();
	
	$('#shopping_item_list_button').click(function(){
		if($('#foundGoodsForm').valid()){
			$('#validate_cart_products').html('');
			var total_price			= 0;
			$("td[id^='reference-']").each(function(){
				var id				= $(this).attr('id');
				var uid				= parseInt(id.substring(10, 265));
				var reference		= $(this).html();
				var name			= $('#name-' + uid).html();
				var quantity		= parseFloat($('#quantity-' + uid).val());
				var unit_price		= parseFloat($('#price-' + uid).val());
				var price			= quantity * unit_price;
				total_price			+= price;
				
				$('#validate_cart_products').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>Rp." + numeral(unit_price).format('0,0') + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp." + numeral(price).format('0,0') + "</td></tr>");
			});
			
			var date		= $('#case_date').val();
			$('#date_p').html(my_date_format(date));
			$('#validate_cart_products').append("<tr><td colspan='3'></td><td>Total</td><td>Rp." + numeral(total_price).format('0,0') + "</td></tr>");
			
			$('#foundGoodsFormValidation').fadeIn(300, function(){
				$('#foundGoodsFormValidation .alert_box_slide').show("slide", { direction: "right" }, 250);
			});
		};
	});
	
	$('.alert_close_button').click(function(){
		$(this).parent().fadeOut();
	});
	
	$('#confirm_button').click(function(){
		$('#foundGoodsForm').validate();
		
		if($('#foundGoodsForm').valid()){
			$('#foundGoodsForm').submit();
		};
	});

	$('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
</script>