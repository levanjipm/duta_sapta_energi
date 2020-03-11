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
<script>
	refresh_view();
	
	function refresh_view(){
		$.ajax({
			url:'<?= site_url('Item/search_item_cart') ?>',
			data:{
				term:$('#search_bar').val(),
				page:$('#page').val(),
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
	
	$('#search_bar').change(function(){
		refresh_view();
	});
	
	$('#page').change(function(){
		refresh_view();
	});
	
	$('.alert_full_close_button').click(function(){
		$('#add_item_wrapper').fadeOut();
	});
</script>