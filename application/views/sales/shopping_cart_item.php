<button class='alert_close_button'>&times</button>
<div class='row'>
	<div class='col-xs-12'>
		<h2 style='font-family:bebasneue'>Add item to cart</h2>
		<hr>
		<label>Search</label>
		<input type='text' class='form-control' id='search_bar'>
		<br>
		
		<div id='item_view_pane'>
			<table class='table table-bordered'>
				<tr>
					<th>Reference</th>
					<th>Name</th>
					<th>Action</th>
				</tr>
<?php
	foreach($items as $item){
?>
				<tr>
					<td><?= $item->reference ?></td>
					<td><?= $item->name ?></td>
					<td><button type='button' class='button button_success_dark' onclick='add_to_cart(<?= $item->id ?>)'><i class='fa fa-cart-plus'></i></button></td>
				</tr>
<?php
	}
?>
			</table>
		</div>
	</div>
</div>
<script>
	function add_to_cart(n){
		$.ajax({
			url:'<?= site_url('Sales_order/add_item_to_cart') ?>',
			data:{
				item_id:n
			},
			type:'POST',
			beforeSend:function(){
				$('button').attr('disabled',true);
			},
			success:function(){
				$('button').attr('disabled',false);
				$.ajax({
					url:'<?= site_url('Sales_order/update_cart_view') ?>',
					success:function(response){
						$('#sales_order_items').html(response);
						$('#add_item_wrapper').fadeOut();
					}
				});
			}
		})
	}
	
	$('.alert_close_button').click(function(){
		$('#add_item_wrapper').fadeOut();
	});
	
	$('#search_bar').change(function(){
		$.ajax({
			url:'<?= site_url('Item/search_item_cart') ?>',
			data:{
				term:$('#search_bar').val()
			},
			beforeSend:function(){
				$('#search_bar').attr('disabled',true);
				$('#item_view_pane').html('');
			},
			success:function(response){
				$('#search_bar').attr('disabled',false);
				$('#item_view_pane').html(response);
			}
		});
	});
</script>