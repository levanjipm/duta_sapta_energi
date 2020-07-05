<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Inventory') ?>' title='Inventory'><i class='fa fa-briefcase'></i></a> /Delivery order</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<a href='<?= site_url('Delivery_order/create_dashboard'); ?>'><button type='button' class='button button_default_dark'>Create delivery order</button></a>
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
	<h3 style='font-family:bebasneue'>Confirm sent delivery order</h3>
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
	<button type='button' class='slide_alert_close_button'>&times </button>
	<div class='alert_box_slide'>
		<label>Customer</label>
		<p id='customer_name'></p>
		<p id='customer_address'></p>
		<p id='customer_city'></p>
		
		<label>Delivery order</label>
		<p id='delivery_order_name'></p>
		<p id='delivery_order_date'></p>
		
		
		<table class='table table-bordered' style='margin-bottom:0'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
			</tr>
			<tbody id='delivery_order_table'>
			</tbody>
		</table>
		
		<div style='padding:2px 10px;background-color:#ffc107;width:100%;display:none;' id='warning_text'><p style='font-family:museo'><i class='fa fa-exclamation-triangle'></i> Warning! Insufficient stock detected.</p></div><br>
		
		<form action='<?= site_url('Delivery_order/confirm') ?>' method='POST' id='delivery_order_form'>
			<input type='hidden' id='delivery_order_id' name='id'>
			<button class='button button_default_dark' id='send_delivery_order_button'><i class='fa fa-long-arrow-right'></i></button>
			<button type='button' class='button button_danger_dark' id='cancel_delivery_order_button'><i class='fa fa-trash'></i></button>
		</form>
	</div>
</div>

<script>
	$('#cancel_delivery_order_button').click(function(){
		$.ajax({
			url:'<?= site_url('Delivery_order/cancel') ?>',
			data:{
				id:$('#delivery_order_id').val()
			},
			type:'POST',
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(){
				$('button').attr('disabled', false);
				window.location.reload();
			}
		});
	});
	
	function view_delivery_order_detail(n){
		$('#delivery_order_table').html('');
		$.ajax({
			url:"<?= site_url('Delivery_order/show_by_code_delivery_order_id/') ?>" + n,
			success:function(response){
				var delivery_orders		= response.general;
				$.each(delivery_orders, function(index, delivery_order){
					$('#delivery_order_table').append("<tr><td>" + delivery_order.reference + "</td><td>" + delivery_order.name + "</td><td>" + numeral(delivery_order.quantity).format('0,0') + "</td></tr>");
				});
				
				$('#delivery_order_name').html(delivery_orders[0].name);
				$('#delivery_order_date').html(delivery_orders[0].date);
				$('#customer_name').html(delivery_orders[0].customer_name);
				$('#customer_address').html(delivery_orders[0].address);
				$('#customer_city').html(delivery_orders[0].city);
				$('#delivery_order_id').val(delivery_orders[0].id);
				$('#delivery_order_form').attr('action', '<?= site_url('Delivery_order/confirm') ?>');
				
				var info		= response.info;
				if(info == 'Stock'){
					$('#warning_text').show();
				} else {
					$('#warning_text').hide();
				}
				
				$('#view_delivery_order_wrapper').fadeIn(300, function(){
					$('#view_delivery_order_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}
	
	function send_delivery_order_detail(n){
		$('#delivery_order_table').html('');
		$.ajax({
			url:"<?= site_url('Delivery_order/show_by_code_delivery_order_id/') ?>" + n,
			success:function(response){
				var general		= response.general;
				$.each(general, function(index, value){
					var reference	= value.reference;
					var name		= value.name;
					var quantity	= value.quantity;
					
					$('#delivery_order_table').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td></tr>");
				});
				
				$('#delivery_order_name').html(general[0].do_name);
				$('#delivery_order_date').html(general[0].date);
				$('#customer_name').html(general[0].customer_name);
				$('#customer_address').html(general[0].address);
				$('#customer_city').html(general[0].city);
				$('#delivery_order_id').val(general[0].id);
				$('#delivery_order_form').attr('action', '<?= site_url('Delivery_order/send') ?>');
				
				var info		= response.info;
				if(info == 'Stock'){
					$('#warning_text').show();
					$('#send_delivery_order_button').attr('disabled', true);
				} else {
					$('#warning_text').hide();
					$('#send_delivery_order_button').attr('disabled', false);
				}
				
				$('#view_delivery_order_wrapper').fadeIn(300, function(){
					$('#view_delivery_order_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}
	
	$('.slide_alert_close_button').click(function(){
		$('#view_delivery_order_wrapper .alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$('#view_delivery_order_wrapper').fadeOut();
		});
	});
</script>