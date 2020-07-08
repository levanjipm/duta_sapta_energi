<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Inventory') ?>' title='Inventory'><i class='fa fa-briefcase'></i></a> / <a href='<?= site_url('Inventory_case') ?>'
		>Cases</a> / Lost goods</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<form action='<?= site_url('Inventory_case/case_lost_goods_input') ?>' method='POST' id='lost_goods_form'>
			<label>Date</label>
			<input type='date' class='form-control' name='date' id='case_date' required><br>
			
			<button type='button' class='button button_default_dark' id='add_item_button'>Add item</button><br><br>
			
			<table class='table table-bordered' id='cart_products_table' style='display:none'>
				<tr>
					<th>Reference</th>
					<th>Name</th>
					<th>Quantity</th>
					<th>Action</th>
				</tr>
				<tbody id='cart_products'></tbody>
			</table>
			
			<button type='button' class='button button_default_dark' id='shopping_item_list_button' style='display:none'><i class='fa fa-long-arrow-right'></i></button>
		</form>
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

<div class='alert_wrapper' id='lost_goods_form_validation'>
	<button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Add lost goods case</h3>
		<hr>
		
		<label>Date of lost goods</label>
		<p style='font-family:museo' id='date_p'></p>
		
		<table class='table table-bordered'>
			<tr>
				<tr>
					<th>Reference</th>
					<th>Name</th>
					<th>Quantity</th>
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
		refresh_view();
	});
	
	$('#search_bar').change(function(){
		refresh_view(1);
	});
	
	$('#page').change(function(){
		refresh_view();
	});
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Item/search_item_cart') ?>',
			data:{
				term:$('#search_bar').val(),
				page:page
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
						var id				= item.id;
						var name			= item.name;
						
						$('#shopping_item_list_tbody').append("<tr><td>" + reference + "</td><td>" + name + "</td><td><button type='button' class='button button_default_dark' onclick='add_to_cart(" + id + ")' title='Add " + reference + " to list'><i class='fa fa-cart-plus'></i></button></td></tr>");
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
	
	$('.slide_alert_close_button').click(function(){
		$('#view_delivery_order_wrapper .alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$('#view_delivery_order_wrapper').fadeOut();
		});
	});
	
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
				
				if($('#item_row-' + item_id).length == 0){
					$('#cart_products').append("<tr id='item_row-" + item_id + "'><td id='reference-" + item_id + "'>" + reference + "</td>" + 
						"<td id='name-" + item_id + "'>" + name + "</td><td><input type='number' class='form-control' min='1' required name='quantity[" + item_id + "]' id='quantity-" + item_id + "'></td><td><button type='button' class='button button_danger_dark' onclick='remove_item(" + item_id + ")'><i class='fa fa-trash'></i></button></tr>");
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
	
	$('#shopping_item_list_button').click(function(){
		$('#lost_goods_form').validate();
		
		if($('#lost_goods_form').valid()){
			$('#validate_cart_products').html('');
			$("td[id^='reference-']").each(function(){
				var id				= $(this).attr('id');
				var uid				= parseInt(id.substring(10, 265));
				var reference		= $(this).html();
				var name			= $('#name-' + uid).html();
				var quantity		= $('#quantity-' + uid).val();
				
				$('#validate_cart_products').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td></tr>");
			});
			
			var date		= $('#case_date').val();
			$('#date_p').html(my_date_format(date));
			$('#lost_goods_form_validation').fadeIn(300, function(){
				$('#lost_goods_form_validation .alert_box_slide').show("slide", { direction: "right" }, 250);
			});
		};
	});
	
	$('.alert_full_close_button').click(function(){
		$(this).parent().parent().parent().parent().fadeOut();
	});
	
	$('#confirm_button').click(function(){
		$('#lost_goods_form').validate();
		
		if($('#lost_goods_form').valid()){
			$('#lost_goods_form').submit();
		};
	});
	
	$('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
</script>