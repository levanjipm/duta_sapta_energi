<button class='alert_close_button'>&times</button>
<label>Customer</label>
<p style='font-family:museo'><?= $sales_order->customer_name ?></p>
<p style='font-family:museo'><?= $sales_order->customer_address ?></p>
<p style='font-family:museo'><?= $sales_order->customer_city ?></p>

<?php
	if($sales_order->taxing == 0){
		$taxing = 'Non taxable';
	} else {
		$taxing = 'Taxable';
	}
?>

<label>Sales order</label>
<p style='font-family:museo'><?= date('d M Y',strtotime($sales_order->date)) ?></p>
<p style='font-family:museo'><?= $sales_order->name ?></p>
<p style='font-family:museo'><?= $taxing ?></p>

<form action='<?= site_url('Delivery_order/input_delivery_order') ?>' method='POST' id='delivery_order_form'>

<label>Delivery order</label>
<input type='date' class='form-control' name='date' required min='2020-01-01'>

<input type='hidden' value='<?= $guid ?>' name='guid' required minlength='36' maxlength='36'>
<input type='hidden' value='<?= $sales_order->taxing ?>' name='taxing' required>

<br>
<table class='table table-bordered'>
	<tr>
		<th>Reference</th>
		<th>Description</th>
		<th>Quantity</th>
		<th>Sent</th>
		<th>Action</th>
	</tr>
<?php
	foreach($details as $detail){
?>
	<tr>
		<td><?= $detail->reference ?></td>
		<td><?= $detail->name ?></td>
		<td><?= number_format($detail->quantity) ?></td>
		<td><?= number_format($detail->sent) ?></td>
		<td><input type='number' class='form-control' name='quantity[<?= $detail->id ?>]' min='0' max='<?= $detail->quantity - $detail->sent ?>' onkeyup='change_total()' value='0'>
	</tr>
<?php
	}
?>
</table>
<input type='hidden' value='0' id='total' name='total' min='1'>
<br>
<button type='submit' class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
</form>

<script>
	$('#delivery_order_form').validate({
		ignore: '',
		rules: {"hidden_field": {min:1}}
	});
	
	function change_total(){
		var total_delivery_order = 0;
		$('input[name^="quantity"]').each(function(){
			total_delivery_order += parseInt($(this).val());
		});
		
		$('#total').val(total_delivery_order);
	};
	
	$('.alert_close_button').click(function(){
		$(this).parent().parent().fadeOut();
	});
</script>