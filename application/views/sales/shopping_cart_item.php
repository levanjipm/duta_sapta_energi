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