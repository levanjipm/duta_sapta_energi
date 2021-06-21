<head>
	<title>Purchase order - Pending</title>
	<style>
		@media print {
		body * {
			visibility: hidden;
		}
		
		#printable, #printable *{
			visibility:visible!important;
		}
		
		#printable{
			position: absolute;
			left: 0;
			top: 0;
		}
		
		@page {
		size: 29.7cm, 21cm;
		}
	}
	</style>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Purchasing') ?>' title='Purchasing'><i class='fa fa-shopping-cart'></i></a> /Purchase Order / Pending</p>
	</div>
	<br>
	<div class='dashboard_in' id='printable'>
		<label>Supplier</label>
		<p><?= $supplier->name ?></p>
		<br>
		
		<div id='purchaseOrderTable'>
			<table class='table table-bordered'>
				<tr>
					<th>PO Date</th>
					<th>PO Name</th>
					<th>Reference</th>
					<th>Name</th>
					<th>Ordered</th>
					<th>Received</th>
				</tr>
			<?php foreach($items as $item){ ?>
				<tr>
					<td><?= date('d M Y', strtotime($item->purchase_order_date)) ?></td>
					<td><?= $item->purchase_order_name ?></td>
					<td><?= $item->reference ?></td>
					<td><?= $item->name ?></td>
					<td><?= number_format($item->quantity, 0) ?></td>
					<td><?= number_format(($item->quantity - $item->received), 0) ?></td>
				</tr>
			<?php } ?>
			</table>
			
			<br>
			<button onclick='window.print()' class='button button_default_dark'><i class='fa fa-print'></i></button>
		</div>
	</div>
</div>
<script>
</script>
