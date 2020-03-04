<div class='dashboard'>
	<h2 style='font-family:bebasneue'>Purchase order</h2>
	<hr>
	<a href='<?= site_url('Purchase_order/create') ?>'>
		<button type='button' class='button button_default_light'>Create purchase order</button>
	</a>
	<br><br>
<?php
	if(!empty($purchase_orders)){
?>
	<table class='table table-bordered'>
		<tr>
			<th>Date</th>
			<th>Name</th>
			<th>Supplier</th>
			<th>Action</th>
		</tr>
<?php
	foreach($purchase_orders as $purchase_order){
		$id					= $purchase_order->id;
		$date				= $purchase_order->date;
		$name				= $purchase_order->name;
		$supplier_name		= $purchase_order->supplier_name;
		$supplier_address	= $purchase_order->supplier_address;
		$supplier_city		= $purchase_order->supplier_city;
?>
		<tr>
			<td><?= date('d M Y',strtotime($date)) ?></td>
			<td><?= $name ?></td>
			<td>
				<p style='font-family:museo'><?= $supplier_name ?></p>
				<p style='font-family:museo'><?= $supplier_address ?></p>
				<p style='font-family:museo'><?= $supplier_city ?></p>
			</td>
			<td>
				<button type='button' class='button button_default_light' onclick='show_purchase_order_detail(<?= $id ?>)'><i class='fa fa-eye'></i></button>
			</td>
		</tr>
<?php
	}
?>
	</table>
	
	<div class='alert_wrapper' id='purchase_order_detail_wrapper'>
		<button type='button' class='alert_close_button'>&times </button>
		<div class='alert_box_default'>
			<label>Supplier</label>
			<p style='font-family:museo' id='supplier_name_p'></p>
			<p style='font-family:museo' id='supplier_address_p'></p>
			<p style='font-family:museo' id='supplier_city_p'></p>
			
			<label>Purchase order</label>
			<p style='font-family:museo' id='purchase_order_name_p'></p>
			<p style='font-family:museo' id='purchase_order_date_p'></p>
			
			<table class='table table-bordered'>
				<tr>
					<th>Reference</th>
					<th>Name</th>
					<th>Quantity</th>
					<th>Price list</th>
					<th>Discount</th>
					<th>Net price</th>
					<th>Total price</th>
				</tr>
				<tbody id='purchase_order_table'></tbody>
			</table>
			<form action='<?= site_url('Purchase_order/confirm') ?>' method='POST'>
				<input type='hidden' id='purchase_order_id' name='id'>
				<button class='button button_default_dark' style='display:inline-block'>Submit</button>
				<button type='button' class='button button_danger_dark' style='display:inline-block' onclick='delete_purchase_order()'>Delete</button>
			</form>
		</div>
	</div>
	
	<script>
		function show_purchase_order_detail(n){
			$.ajax({
				url:'<?= site_url('Purchase_order/get_purchase_order_detail_by_id/') ?>' + n,
				dataType:'json',
				success:function(response){
					var purchase_order_id	= response[0].code_purchase_order_id;
					var supplier_name 		= response[0].supplier_name;
					var supplier_address 	= response[0].supplier_address;
					var supplier_city 		= response[0].supplier_city;
					
					$('#supplier_name_p').html(supplier_name);
					$('#supplier_address_p').html(supplier_address);
					$('#supplier_city_p').html(supplier_city);
					
					$('#purchase_order_id').val(purchase_order_id);
					
					var purchase_order_name	= response[0].purchase_order_name;
					var purchase_order_date	= response[0].purchase_order_date;
					
					$('#purchase_order_name_p').html(purchase_order_name);
					$('#purchase_order_date_p').html(purchase_order_date);
					var purchase_order_value = 0;
					$.each(response, function(index, value){
						var reference 	= value.reference;
						var name		= value.name
						var price_list	= value.price_list;
						var net_price	= value.net_price;
						var discount	= 100 * (1 - (net_price / price_list));
						var quantity	= value.quantity;
						var total_price	= quantity * net_price;
						purchase_order_value += total_price;
						
						$('#purchase_order_table').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(price_list).format('0,0.00') + "</td><td>" + numeral(discount).format('0,0.00') + "%</td><td>Rp. " + numeral(net_price).format('0,0.00') + "</td><td>" + numeral(total_price).format('0,0.00') + "</td></tr>");
					});
					$('#purchase_order_table').append("<tr><td colspan='5'></td><td>Total</td><td>" + numeral(purchase_order_value).format('0,0.00') + "</td></tr>");
					$('#purchase_order_detail_wrapper').fadeIn();
				}
			});
		}
		
		function delete_purchase_order(){
			$.ajax({
				url:'<?= site_url('Purchase_order/delete') ?>',
				data:{
					id:$('#purchase_order_id').val()
				},
				type:'GET',
				success:function(){
					location.reload();
				}
			});
		};
		
		$('.alert_close_button').click(function(){
			$(this).parent().fadeOut();
		});
	</script>
<?php
	}
?>
</div>