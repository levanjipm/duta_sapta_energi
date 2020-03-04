<?php
	if(!empty($items)){
?>
<table class='table table-bordered'>
	<tr>
		<th>Reference</th>
		<th>Name</th>
		<th>Action</th>
	</tr>
<?php
	foreach($items as $item){
?>
	<tr>
		<td><?= $item->reference ?></td>
		<td><?= $item->name ?></td>
		<td><button type='button' class='button button_success_dark' onclick='add_to_cart(<?= $item->id ?>)' title='Add <?= $item->reference ?> to cart'><i class='fa fa-cart-plus'></i></button>
		<button type='button' class='button button_danger_dark' onclick='add_to_cart_as_bonus(<?= $item->id ?>)' title='Add <?= $item->reference ?> to cart as bonus'><i class='fa fa-gift'></i></button></td>
	</tr>
<?php
	}
?>
</table>
<?php
	} else {
?>
<p style='font-family:museo'>Item not found</p>
<?php
	}
?>
<select class='form-control' id='page' style='width:100px'>
<?php
	for($i = 1; $i <= $pages; $i++){
?>
	<option value='<?= $i ?>' <?php if($page == $i){ echo 'selected'; } ?>><?= $i ?></option>
<?php
	}
?>
</select>