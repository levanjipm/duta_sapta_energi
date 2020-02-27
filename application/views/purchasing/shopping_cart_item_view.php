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
<script>
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
			success:function(){
				$('button').attr('disabled',false);
				$.ajax({
					url:'<?= site_url('Purchase_order/update_cart_view') ?>',
					success:function(response){
						$('#purchase_order_items').html(response);
						$('#add_item_wrapper').fadeOut();
					}
				});
			}
		})
	}
	
	$('.alert_close_button').click(function(){
		$('#add_item_wrapper').fadeOut();
	});
</script>