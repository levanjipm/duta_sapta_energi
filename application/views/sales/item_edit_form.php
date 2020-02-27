<button class='alert_close_button'>&times</button>
<form action='<?= site_url('Item/update_item') ?>' method='POST'>
	<h2 style='font-family:bebasneue'>Edit Item form</h2>
	<hr>
	<input type='hidden' value='<?= $item->id ?>' name='item_id'>
	
	<label>Reference</label>
	<input type='text' class='form-control' name='item_reference' value='<?= $item->reference ?>' required>
	
	<label>Name</label>
	<textarea class='form-control' name='item_name' rows='3' style='resize:none' required><?= $item->name ?></textarea>
	
	<label>Recorded price list</label>
	<p>Rp. <?= number_format($item->price_list,2) ?></p>
	
	<label>Price list</label>
	<input type='number' class='form-control' name='item_price_list' value='' min='0' required>
	
	<label>Type</label>
	<select class='form-control' name='item_type'>
<?php
	foreach($classes as $class){
?>
		<option value='<?= $class->id ?>' <?php if($item->type == $class->id){ echo 'selected'; } ?>><?= $class->name ?></option>
<?php
	}
?>
	</select>
	<br>
	<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
</form>
<script>
	$('.alert_close_button').click(function(){
		$(this).parents('.alert_wrapper').fadeOut();
	});
</script>