<head>
    <title>Purchasing Return</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Purchasing') ?>' title='Purchasing'><i class='fa fa-briefcase'></i></a> /Return</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Date</label>
		<input type='date' class='form-control' id='date' name='date' required min='2020-01-01'>

		<label>Supplier</label>
		<button class='form-control' id='supplierButton' style='text-align:left!important'></button>
		<input type='hidden' id='supplier' name='supplier' required>
		<br>
		<button type='button' class='button button_default_dark' id='addItemButton'><i class='fa fa-plus'></i> Add item</button>
		<br><br>
		<div id='returnItemTable' style='display:none'>
			<table class='table table-bordered'>
				<tr>
					<th>Reference</th>
					<th>Name</th>
					<th>Quantity</th>
					<th>Price</th>
					<th>Action</th>
				</tr>
				<tbody id='returnItemTableContent'></tbody>
			</table>

			<button type='button' id='createReturnButton' class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</div>
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
<script>
	$('#addItemButton').click(function(){
		$('#search_bar').val('');
		refresh_view(1);
	});

	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Item/showItems') ?>',
			data:{
				term:$('#search_bar').val(),
				page:page
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
						var name			= item.name;
						var id				= item.id;
						
						$('#shopping_item_list_tbody').append("<tr><td>" + reference + "</td><td>" + name + "</td><td><button type='button' class='button button_success_dark' onclick='addItem(" + id + ")' title='Add " + reference + " to cart'><i class='fa fa-cart-plus'></i></button></td></tr>");
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

	function addItem(n){
		if($('#tr-' + n).length == 0){
			$.ajax({
				url:'<?= site_url("Item/showById") ?>',
				data:{
					id:n
				},
				success:function(response){
					var reference = response.reference;
					var name = response.name;
					$('#returnItemTableContent').append("<tr id='tr-" + n + "'><td>" + reference + "</td><td>" + name + "</td><td><input type='number' class='form-control' name='quantity[" + n + "]' required min='0'></td><td><input type='number' class='form-control' name='price[" + n + "]' required min='0'></td><td><button class='button button_danger_dark' onclick='removeItem(" + n + ")'><i class='fa fa-trash'></i></button></td></tr>");
				}
			})
		}
		$('#add_item_wrapper .alert_full_close_button').click();
		if($('#returnItemTableContent tr').length > 0){
			$('#returnItemTable').show();
		} else {
			$('#returnItemTable').hide();
		}
	}

	$('.alert_full_close_button').click(function(){
		$(this).parent().parent().fadeOut();
	})
</script>
