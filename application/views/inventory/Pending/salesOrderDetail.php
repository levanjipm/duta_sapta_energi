<title>Pending Sales Orders</title>
<?php
	$complete_address		= '';
	$customer_name			= $customer->name;
	$complete_address		.= $customer->address;
	$customer_city			= $customer->city;
	$customer_number		= $customer->number;
	$customer_rt			= $customer->rt;
	$customer_rw			= $customer->rw;
	$customer_postal		= $customer->postal_code;
	$customer_block			= $customer->block;
		
	if($customer_number != null){
		$complete_address	.= ' No. ' . $customer_number;
	}
					
	if($customer_block != null && $customer_block != "000"){
		$complete_address	.= ' Blok ' . $customer_block;
	}
				
	if($customer_rt != '000'){
		$complete_address	.= ' RT ' . $customer_rt;
	}
					
	if($customer_rw != '000' && $customer_rt != '000'){
		$complete_address	.= ' /RW ' . $customer_rw;
	}
					
	if($customer_postal != null){
		$complete_address	.= ', ' . $customer_postal;
	}
?>
<div class="dashboard">
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Inventory/pendingSalesOrderDashboard') ?>'>Pending Sales Orders</a> / Detail</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Customer</label>
		<p><?= $customer_name ?></p>
		<p><?= $complete_address ?></p>
		<p><?= $customer_city ?></p>

		<label>Sales Order</label>
		<p><?= $salesOrder->name ?></p>
		<p><?= date('d M Y', strtotime($salesOrder->date)) ?></p>
		<p>Created by <?= $salesOrder->creator ?></p>
		<p>Confirmed by <?= $salesOrder->confirmed_by ?></p>
		
		<label>Sales</label>
		<p><?= ($salesOrder->seller == NULL || $salesOrder->seller == "") ? "<i>Not available</i>" : $salesOrder->seller; ?></p>

		<label>Items</label>
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
				<th>Sent</th>
			</tr>
<?php foreach($items as $item){ ?>
			<tr>
				<td><?= $item->reference ?></td>
				<td><?= $item->name ?></td>
				<td><?= number_format($item->quantity, 0) ?></td>
				<td><?= number_format($item->sent, 0) ?></td>
			</tr>
<?php } ?>
		</table>
		
		<label>Delivery Orders</label>
<?php if(count($deliveryOrder) > 0){ ?>
		<table class='table table-bordered'>
			<tr>
				<th>Date</th>
				<th>Name</th>
				<th>Action</th>
			</tr>
<?php foreach($deliveryOrder as $delivery){ ?>
			<tr>
				<td><?= date('d M Y', strtotime($delivery->date)) ?></td>
				<td><?= $delivery->name ?></td>
				<td><button class='button button_default_dark' onclick='viewDeliveryOrder(<?= $delivery->id ?>)'><i class='fa fa-eye'></i></button>
			</tr>
<?php } ?>
		</table>
<?php } ?>
	</div>
</div>

<div class='alert_wrapper' id='deliveryOrderWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>View Delivery</h3>
		<hr>
		<label>Delivery Order</label>
		<p id='deliveryOrderDateP'></p>
		<p id='deliveryOrderNameP'></p>
		
		<label>Items</label>
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
			</tr>
			<tbody id='deliveryOrderTableContent'></tbody>
		</table>

		<label>Status</label>
		<p id='deliveryOrderStatusP'></p>
	</div>
</div>

<script>
	function viewDeliveryOrder(n){
		$.ajax({
			url:"<?= site_url('Delivery_order/getById') ?>",
			data:{
				id: n
			},
			success:function(response){
				var general		= response.general;
				var name		= general.name;
				var date		= general.date;
				var is_confirm	= general.is_confirm;
				var is_sent		= general.is_sent;
				var invoice_id	= general.invoice_id;

				$('#deliveryOrderNameP').html(name);
				$('#deliveryOrderDateP').html(my_date_format(date));

				if(is_confirm == 0){
					$('#deliveryOrderStatusP').html("Delivery order has not been confirmed.");
				} else if(is_confirm == 1 && is_sent == 0){
					$('#deliveryOrderStatusP').html("Delivery order has not been sent");
				} else if(is_sent == 1 && invoice_id == null){
					$('#deliveryOrderStatusP').html("Delivery order has not been invoiced.");
				} else if(is_sent == 1 && invoice_id != null){
					$('#deliveryOrderStatusP').html("Delivery order process has been completed.")
				}

				var items		= response.items;
				$('#deliveryOrderTableContent').html("");
				$.each(items, function(index, item){
					var reference		= item.reference;
					var name			= item.name;
					var quantity		= item.quantity;

					$('#deliveryOrderTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td></tr>");
				})
			},
			complete:function(){
				$('#deliveryOrderWrapper').fadeIn(300, function(){
					$('#deliveryOrderWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}
</script>
