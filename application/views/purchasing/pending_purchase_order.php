<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Purchasing') ?>' title='Purchasing'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Purchase_order') ?>'>Purchase order</a> / Archive</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Supplier</label>
		<select class='form-control' id='supplier_id'>
<?php
	foreach($suppliers as $supplier){
?>
			<option value='<?= $supplier->id ?>'><?= $supplier->name ?></option>
<?php
	}
?>
		</select>
		<br>
		
		<table class='table table-bordered'>
			<tr>
				<th>Date</th>
				<th>Name</th>
				<th>Action</th>
			</tr>
			<tbody id='purchase_order_table'></tbody>
		</table>
		
		<select class='form-control' style='width:100px' id='page'>
			<option value='1'>1</option>
		</select>
	</div>
</div>
<script>
	function refresh_view(page = $('#page').val()){
		$.ajax({
		});
	}
</script>