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
		<td><input type='number' class='form-control' name='price_list[<?= $cart->id ?>]' id='price_list-<?= $cart->id ?>' min='0'></td>
		<td><input type='number' class='form-control' name='discount[<?= $cart->id ?>]' id='discount-<?= $cart->id ?>' min='0' max='100' required></td>
		<td><input type='number' class='form-control' name='quantity[<?= $cart->id ?>]' id='quantity-<?= $cart->id ?>' min='1' required></td>
		<td><button type='button' class='button button_danger_dark' onclick='remove_item(<?= $cart->id ?>)'><i class='fa fa-trash'></i></button></td>
	</tr>
<?php
	}
?>
</table>

<?php
	if(!empty($bonus_carts)){
?>
	<table class='table table-bordered'>
		<tr>
			<th>Item</th>
			<th>Description</th>
			<th>Quantity</th>
		</tr>
<?php
	foreach($bonus_carts as $bonus_cart){
?>
	<tr>
		<td id='bonus_reference-<?= $bonus_cart->id ?>'><?= $bonus_cart->reference ?></td>
		<td id='bonus_name-<?= $bonus_cart->id ?>'><?= $bonus_cart->name ?></td>
		<td><input type='number' class='form-control' name='bonus_quantity[<?= $bonus_cart->id ?>]' id='bonus_quantity-<?= $bonus_cart->id ?>' min='1' required></td>
		<td><button type='button' class='button button_danger_dark' onclick='remove_bonus_item(<?= $bonus_cart->id ?>)'><i class='fa fa-trash'></i></button></td>
	</tr>
<?php
	}
?>
	</table>
<?php
	}
?>
<button type='button' class='button button_success_dark' onclick='show_purchase_order()'><i class='fa fa-long-arrow-right'></i></button>
<?php
	}
?>