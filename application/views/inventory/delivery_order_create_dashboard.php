<div class='dashboard'>
	<h2 style='font-family:bebasneue'>Delivery order</h2>
	<hr>
	<table class='table table-bordered'>
		<tr>
			<th>Date</th>
			<th>Name</th>
			<th>Customer</th>
			<th>Action</th>
		</tr>
<?php
	foreach($sales_orders as $sales_order){
?>
		<tr>
			<td><?= date('d M Y',strtotime($sales_order->date)) ?></td>
			<td><?= $sales_order->name ?></td>
			<td>
				<p style='font-family:museo'><?= $sales_order->customer_name ?></p> 
				<p style='font-family:museo'><?= $sales_order->customer_address ?></p> 
				<p style='font-family:museo'><?= $sales_order->customer_city ?></p>
			</td>
			<td>
				<button type='button' class='button button_success_dark' onclick='view_sales_order(<?= $sales_order->id ?>)'><i class='fa fa-long-arrow-right'></i></button>
			</td>
		</tr>
<?php
	}
?>
	</table>
</div>

<div class='alert_wrapper' id='view_sales_order_wrapper'>
	<div class='alert_box_default'>
	</div>
</div>

<script>
	function view_sales_order(n){
		$.ajax({
			url:'<?= site_url('Delivery_order/view_sales_order') ?>',
			data:{
				sales_order_id:n
			},
			type:'GET',
			success:function(response){
				$('#view_sales_order_wrapper .alert_box_default').html(response);
				$('#view_sales_order_wrapper').fadeIn();
			}
		});
	}
</script>