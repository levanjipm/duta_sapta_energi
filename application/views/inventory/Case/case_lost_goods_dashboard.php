<style>
	.button_default_light{
		float:right;
	}
</style>
<h4><strong>Lost</strong></h4>
<form action='<?= site_url('Inventory_case/input/lost') ?>' method='POST' id='lostGoodsForm'>
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
	
	<button type='button' class='button button_default_light' id='validationButton' style='display:none'><i class='fa fa-long-arrow-right'></i></button>
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
				<table class='table table-bordered' id='itemListTable'>
					<tr>
						<th>Reference</th>
						<th>Name</th>
						<th>Action</th>
					</tr>
					<tbody id='itemListTableContent'>
					</tbody>
				</table>
				
				<select class='form-control' id='page' style='width:100px'>
					<option value='1'></option>
				</select>
			</div>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='lostGoodsFormValidation'>
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
	$('#lostGoodsForm').validate();

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
			url:'<?= site_url('Item/showItems') ?>',
			data:{
				term:$('#search_bar').val(),
				page:page
			},
			success:function(response){
				$('#itemListTableContent').html('');
				var item_array	= response.items;
				var pages		= response.pages;
				var page		= response.page;
				
				if(item_array.length > 0){
					$('#itemListTable').show();
					$.each(item_array, function(index, item){
						var reference		= item.reference;
						var id				= item.id;
						var name			= item.name;
						
						$('#itemListTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td><button type='button' class='button button_default_dark' onclick='addToCart(" + id + ")' title='Add " + reference + " to list'><i class='fa fa-cart-plus'></i></button></td></tr>");
					});
				} else {
					$('#itemListTable').hide();
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
				$('button').attr('disabled',false);
				var item_id		= response.id;
				var reference	= response.reference;
				var name		= response.name;
				
				if($('#item_row-' + item_id).length == 0){
					$('#cart_products').append("<tr id='item_row-" + item_id + "'><td id='reference-" + item_id + "'>" + reference + "</td>" + 
						"<td id='name-" + item_id + "'>" + name + "</td><td><input type='number' class='form-control' min='1' required name='quantity[" + item_id + "]' id='quantity-" + item_id + "'></td><td><button type='button' class='button button_danger_dark' onclick='remove_item(" + item_id + ")'><i class='fa fa-trash'></i></button></tr>");
						$('#cart_products_table').show();
						$('#validationButton').attr('disabled', false);
						$('#validationButton').show();
				}
				
				$('button').attr('disabled',false);
				$('.alert_full_close_button').click();
				
				if($('#cart_products tr').length == 0){
					$('#cart_products_table').hide();
					$('#validationButton').attr('disabled', true);
					$('#validationButton').hide();
				}
			}
		})
	}
	
	function remove_item(n){
		$('#item_row-' + n).remove();
		if($('#cart_products tr').length > 0){
			$('#cart_products_table').show();
			$('#validationButton').attr('disabled', false);
			$('#validationButton').show();
		} else {
			$('#cart_products_table').hide();
			$('#validationButton').attr('disabled', true);
			$('#validationButton').hide();
		}
	};
	
	$('#validationButton').click(function(){	
		if($('#lostGoodsForm').valid()){
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
			$('#lostGoodsFormValidation').fadeIn(300, function(){
				$('#lostGoodsFormValidation .alert_box_slide').show("slide", { direction: "right" }, 250);
			});
		};
	});
	
	$('.alert_full_close_button').click(function(){
		$(this).parent().parent().fadeOut();
	});
	
	$('#confirm_button').click(function(){	
		if($('#lostGoodsForm').valid()){
			$('#lostGoodsForm').submit();
		};
	});

	$('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
</script>