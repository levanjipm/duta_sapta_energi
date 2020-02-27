<?php
	if(empty($carts)){
?>
<p style='font-family:museo'>There is no item in the shopping cart</p>
<?php
	} else {
?>
<table class='table table-bordered'>
	<tr>
		<th>Item</th>
		<th>Description</th>
		<th>Price list</th>
		<th>Discount</th>
		<th>Quantity</th>
	</tr>
<?php
	foreach($carts as $cart){
?>
	<tr>
		<td id='reference-<?= $cart->id ?>'><?= $cart->reference ?></td>
		<td id='name-<?= $cart->id ?>'><?= $cart->name ?></td>
		<td>Rp. <?= number_format($cart->price_list,2) ?><span id='price_list-<?= $cart->id ?>' style='display:none'><?= $cart->price_list ?></span></td>
		<td><input type='number' class='form-control' name='discount[<?= $cart->id ?>]' id='discount-<?= $cart->id ?>' min='0' max='100' required></td>
		<td><input type='number' class='form-control' name='quantity[<?= $cart->id ?>]' id='quantity-<?= $cart->id ?>' min='0' required></td>
		<td><button type='button' class='button button_danger_dark' onclick='remove_item(<?= $cart->id ?>)'><i class='fa fa-trash'></i></button></td>
	</tr>
<?php
	}
?>
</table>
<button type='button' class='button button_success_dark' onclick='show_sales_order()'><i class='fa fa-long-arrow-right'></i></button>
<?php
	}
?>