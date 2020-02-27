<form action='<?= site_url('Good_receipt/input') ?>' method='POST' id='good_receipt_form'>
	<input type='hidden' id='submit_date' name='submit_date'>
	<input type='hidden' name='guid' value='<?= $guid ?>'>
	
	<label>Document</label>
	<input type='text' class='form-control' name='document' required minlength='1'><br>
	
	<label>Purchase order</label>
	<p style='font-family:museo'><?= $general[0]->name ?></p>
	<p style='font-family:museo'><?= date('d M Y',strtotime($general[0]->date)) ?></p>

	<label>Supplier</label>
	<p style='font-family:museo'><?= $general[0]->supplier_name ?></p>
	<p style='font-family:museo'><?= $general[0]->supplier_address ?></p>
	<p style='font-family:museo'><?= $general[0]->supplier_city ?></p>

	<table class='table table-bordered'>
		<tr>
			<th>Reference</th>
			<th>Name</th>
			<th>Quantity</th>
			<th>Received quantity</th>
			<th>Action</th>
		</tr>
<?php
	foreach($purchase_orders as $purchase_order){
		$id				= $purchase_order->id;
		$reference		= $purchase_order->reference;
		$name			= $purchase_order->name;
		$quantity		= $purchase_order->quantity;
		$received		= $purchase_order->received;
		
		$maximum_value	= $quantity - $received;
?>
		<tr>
			<td><?= $reference ?></td>
			<td><?= $name ?></td>
			<td><?= number_format($quantity) ?></td>
			<td><?= number_format($received) ?></td>
			<td><input type='number' class='form-control' id='quantity-<?= $id ?>' name='quantity[<?= $id ?>]' max='<?= $maximum_value ?>' min='0' onchange='update_value()'></td>
		</tr>
<?php
	}
?>
	</table>
	<input type='hidden' value='0' id='total_quantity' name='total_quantity' min='1'><br>
	<button type='submit' class='button button_default_dark'>Submit</button>
</form>
<script>
	function update_value(){
		var total_quantity = 0;
		$('input[id^="quantity"]').each(function(){
			var quantity	= parseInt($(this).val());
			total_quantity += quantity;
		});
		
		$('#total_quantity').val(total_quantity);
	}

	$('#good_receipt_form').validate({
		ignore: ''
	});
</script>