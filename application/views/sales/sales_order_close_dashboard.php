<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Sales_order') ?>'>Sales order</a>/ Close</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Sales order</label>
		<p style='font-family:museo'><?= $sales_order->name ?></p>
		<p style='font-family:museo'><?= date('d M Y',strtotime($sales_order->date)) ?></p>	
		
		<label>Seller</label>
		<p style='font-family:museo'><?php if($sales_order->seller == null){ echo "<i>Not available</i>"; } else { echo $sales_order->seller; }; ?></p>
		
		<label>Customer</label>
		<p style='font-family:museo'><?= $sales_order->customer_name ?></p>
		<p style='font-family:museo'><?= $sales_order->customer_address ?></p>
		<p style='font-family:museo'><?= $sales_order->customer_city ?></p>
		
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
				<th>Sent</th>
				<th>Pending</th>
			</tr>
<?php
	foreach($details as $detail){
		$reference		= $detail->reference;
		$name			= $detail->name;
		$quantity		= $detail->quantity;
		$sent			= $detail->sent;
		$pending		= $quantity - $sent;
?>
			<tr>
				<td><?= $reference ?></td>
				<td><?= $name ?></td>
				<td><?= number_format($quantity) ?></td>
				<td><?= number_format($sent) ?></td>
				<td><?= number_format($pending) ?></td>
			</tr>
<?php
	}
?>
		</table>
		<form action='<?= site_url('Sales_order/close_do') ?>' method='POST' id='close_sales_order_form'>
			<input type='hidden' value='<?= $sales_order->id ?>' name='id'>
			<label>Requested by</label>
			<select class='form-control' name='requested_by'>
				<option value='1'>Corresponding salesman</option>
				<option value='2'>Customer</option>
				<option value='3'>Other</option>
			</select>
			
			<label>Information</label>
			<textarea class='form-control' style='resize:none' required minlength='25' name='information'></textarea>
			<br>
			<button class='button button_default_dark'><i class='fa fa-long-arrow-left'></i></button>
		</form>
	</div>
</div>
<script>
	$('#close_sales_order_form').validate();
</script>