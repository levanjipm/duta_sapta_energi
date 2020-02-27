<div class='dashboard'>
	<h2 style='font-family:bebasneue'>Delivery Order</h2>
	<hr>
	<a href='<?= site_url('Delivery_order/create_dashboard'); ?>'><button type='button' class='button button_default_light'>Create delivery order</button></a>
	<br><br>
<?php
	if(!empty($delivery_orders)){
?>
	<h3 style='font-family:bebasneue'>Confirm delivery order</h3>
	<table class='table table-bordered'>
		<tr>
			<th>Date</th>
			<th>Name</th>
			<th>Customer</th>
			<th>Action</th>
		</tr>
<?php
	foreach($delivery_orders as $delivery_order){
?>
		<tr>
			<td><?= date('d M Y',strtotime($delivery_order->date)) ?></td>
			<td><?= $delivery_order->name ?></td>
			<td>
				<p><?= $delivery_order->customer_name ?></p>
				<p><?= $delivery_order->address ?></p>
				<p><?= $delivery_order->city ?></p>
			</td>
			<td>
				<button type='button' class='button button_success_dark' onclick='view_delivery_order_detail(<?= $delivery_order->id ?>)'><i class='fa fa-long-arrow-right'></i></button>
			</td>
		</tr>
<?php
	}
?>
	</table>
<?php
	}

	if(!empty($unsent_delivery_orders)){
?>
	<h3 style='font-family:bebasneue'>Confirm delivery order</h3>
	<table class='table table-bordered'>
		<tr>
			<th>Date</th>
			<th>Name</th>
			<th>Customer</th>
			<th>Action</th>
		</tr>
<?php
	foreach($unsent_delivery_orders as $unsent_delivery_order){
?>
		<tr>
			<td><?= date('d M Y',strtotime($unsent_delivery_order->date)) ?></td>
			<td><?= $unsent_delivery_order->name ?></td>
			<td>
				<p><?= $unsent_delivery_order->customer_name ?></p>
				<p><?= $unsent_delivery_order->address ?></p>
				<p><?= $unsent_delivery_order->city ?></p>
			</td>
			<td>
				<button type='button' class='button button_success_dark' onclick='send_delivery_order_detail(<?= $unsent_delivery_order->id ?>)'><i class='fa fa-long-arrow-right'></i></button>
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

<div class='alert_wrapper' id='view_delivery_order_wrapper'>
	<div class='alert_box_default' id='view_delivery_order_box'>
		<button class='alert_close_button'>&times </button>
		<label>Customer</label>
		<p id='customer_name'></p>
		<p id='customer_address'></p>
		<p id='customer_city'></p>
		
		<label>Delivery order</label>
		<p id='delivery_order_name'></p>
		<p id='delivery_order_date'></p>
		
		
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
			</tr>
			<tbody id='delivery_order_table'>
			</tbody>
		</table>
		<form action='<?= site_url('Delivery_order/confirm') ?>' method='POST' id='delivery_order_form'>
			<input type='hidden' id='delivery_order_id' name='id'>
			<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<script>
	function view_delivery_order_detail(n){
		$('#delivery_order_table').html('');
		$.ajax({
			url:"<?= site_url('Delivery_order/show_by_code_delivery_order_id/') ?>" + n,
			success:function(response){
				$.each(response, function(index, value){
					$('#delivery_order_table').append("<tr><td>" + value.reference + "</td><td>" + value.name + "</td><td>" + numeral(value.quantity).format('0,0') + "</td></tr>");
				});
				$('#delivery_order_name').html(response[0].do_name);
				$('#delivery_order_date').html(response[0].date);
				$('#customer_name').html(response[0].customer_name);
				$('#customer_address').html(response[0].address);
				$('#customer_city').html(response[0].city);
				$('#delivery_order_id').val(response[0].id);
				$('#delivery_order_form').attr('action', '<?= site_url('Delivery_order/confirm') ?>');
				$('#view_delivery_order_wrapper').fadeIn();
			}
		});
	}
	
		function send_delivery_order_detail(n){
		$('#delivery_order_table').html('');
		$.ajax({
			url:"<?= site_url('Delivery_order/show_by_code_delivery_order_id/') ?>" + n,
			success:function(response){
				$.each(response, function(index, value){
					$('#delivery_order_table').append("<tr><td>" + value.reference + "</td><td>" + value.name + "</td><td>" + numeral(value.quantity).format('0,0') + "</td></tr>");
				});
				$('#delivery_order_name').html(response[0].do_name);
				$('#delivery_order_date').html(response[0].date);
				$('#customer_name').html(response[0].customer_name);
				$('#customer_address').html(response[0].address);
				$('#customer_city').html(response[0].city);
				$('#delivery_order_id').val(response[0].id);
				$('#delivery_order_form').attr('action', '<?= site_url('Delivery_order/send') ?>');
				$('#view_delivery_order_wrapper').fadeIn();
			}
		});
	}
	
	$('.alert_close_button').click(function(){
		$(this).parent().parent().fadeOut();
	});
</script>