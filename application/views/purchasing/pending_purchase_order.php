<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Purchasing') ?>' title='Purchasing'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Purchase_order') ?>'>Purchase order</a> / Pending</p>
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
		
		<div id='purchaseOrderTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Action</th>
				</tr>
				<tbody id='purchaseOrderTableContent'></tbody>
			</table>
			
			<select class='form-control' style='width:100px' id='page'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='purchaseOrderTableText'>There is no pending purchase order.</p>
	</div>
</div>
<script>
	$(document).ready(function(){
		refresh_view();
	})

	$('#page').change(function(){
		refresh_view();
	})

	$('#supplier_id').change(function(){
		refresh_view(1);
	})

	function refresh_view(page = $('#page').val(), supplier_id = $('#supplier_id').val()){
		$.ajax({
			url:'<?= site_url('Purchase_order/getPendingPurchaseOrder') ?>',
			data:{
				page: page,
				supplier_id: supplier_id
			},
			type:'GET',
			success:function(response){
				var pages = response.pages;
				$('#page').html('');
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>")
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>")
					}
				}

				var countPurchaseOrder = 0;
				$('#purchaseOrderTableContent').html('');
				var items = response.items;
				$.each(items, function(index, item){
					var id = item.id;
					var date = item.date;
					var name = item.name;
					var is_confirm = item.is_confirm;
					var is_delete = item.is_delete;

					if(is_confirm == 1 && is_delete == 0){
						$('#purchaseOrderTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td><button class='button button_default_dark'><i class='fa fa-eye'></i></button>")
						countPurchaseOrder++;
					}
				})

				if(countPurchaseOrder > 0){
					$('#purchaseOrderTable').show();
					$('#purchaseOrderTableText').hide();
				} else {
					$('#purchaseOrderTable').hide();
					$('#purchaseOrerTableText').show();
				}
			}
		});
	}
</script>