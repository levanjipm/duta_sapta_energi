<div class='dashboard'>
	<h2 style='font-family:bebasneue'>Sales order</h2>
	<hr>
	<a href='<?= site_url('Sales_order/create') ?>'>
		<button type='button' class='button button_default_light'>Create sales order</button>
	</a>
	<br><br>
	<input type='text' class='form_control' id='search_bar'>
	<br><br>
<?php
	if(empty($sales_orders)){
?>
	<p style='font-family:museo'>There is no sales order to be sent</p>
<?php
	} else {
?>
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
				<button type='button' class='button button_success_dark' title='View <?= $sales_order->name ?>' onclick='view_sales_order(<?= $sales_order->id ?>)'><i class='fa fa-eye'></i></button>
			</td>
		</tr>
<?php
	}
?>
	</table>
<?php
	}
?>
</div>
<script>
	function view_sales_order(n){
		$.ajax({
		});
	}
</script>