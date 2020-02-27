<div class='dashboard'>
	<h2 style='font-family:bebasneue'>Purchase order</h2>
	<hr>
	<a href='<?= site_url('Purchase_order/create') ?>'>
		<button type='button' class='button button_default_light'>Create purchase order</button>
	</a>
	<br><br>
<?php
	if(!empty($purchase_orders)){
?>
	<table class='table table-bordered'>
		<tr>
			<th>Date</th>
			<th>Name</th>
			<th>Supplier</th>
			<th>Action</th>
		</tr>
	</table>
<?php
	}
?>
</div>