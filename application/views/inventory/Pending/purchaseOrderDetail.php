<title>Pending Purchase Order</title>
<?php
	$supplier_name			= $supplier->name;
	$complete_address		= '';
	$complete_address		.= $supplier->address;
	$supplier_number		= $supplier->number;
	$supplier_block			= $supplier->block;
	$supplier_rt			= $supplier->rt;
	$supplier_rw			= $supplier->rw;
	$supplier_postal_code	= $supplier->postal_code;
	$complete_address		.= 'No. ' . $supplier_number;
	$supplier_city			= $supplier->city;
	
	if($supplier_block		== '' && $supplier_block == '000'){
		$complete_address	.= 'Block ' . $supplier_block;
	};
	
	if($supplier_rt != '' && $supplier_rt != '000'){
		$complete_address	.= 'RT ' . $supplier_rt . '/ RW ' . $supplier_rw;
	}
	
	if($supplier_postal_code != ''){
		$complete_address	.= ', ' . $supplier_postal_code;
	}
	
	$delivery_address			= $purchaseOrder->dropship_address;
	$delivery_city				= $purchaseOrder->dropship_city;
	$delivery_contact_person	= $purchaseOrder->dropship_contact_person;
	$delivery_contact			= $purchaseOrder->dropship_contact;
?>
<div class="dashboard">
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Inventory') ?>' title='Inventory'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Inventory/pendingPurchaseOrderDashboard') ?>'>Pending Purchase Orders</a> / Detail</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Supplier</label>
		<p><?= $supplier_name ?></p>
		<p><?= $complete_address ?></p>
		<p><?= $supplier_city ?></p>

		<label>Purchase Order</label>
		<p><?= $purchaseOrder->name ?></p>
		<p><?= date('d M Y', strtotime($purchaseOrder->date)); ?></p>

		<label>Items</label>
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
				<th>Received</th>
			</tr>
<?php foreach($items as $item){ ?>
			<tr>
				<td><?= $item->reference ?></td>
				<td><?= $item->name ?></td>
				<td><?= number_format($item->quantity, 0) ?></td>
				<td><?= number_format($item->received, 0) ?></td>
			</tr>
<?php } ?>
		</table>
		
		<label>Good Receipts</label>
<?php if(count($goodReceipt) > 0){ ?>
		<table class='table table-bordered'>
			<tr>
				<th>Date</th>
				<th>Name</th>
				<th>Action</th>
			</tr>
<?php foreach($goodReceipt as $item){ ?>
			<tr>
				<td><?= date('d M Y', strtotime($item->date)) ?></td>
				<td><?= $item->name ?></td>
				<td><button class='button button_default_dark' onclick='viewGoodReceipt(<?= $item->id ?>)'><i class='fa fa-eye'></i></button>
			</tr>
<?php } ?>
		</table>
<?php } else { ?>
		<p>There is no good receipt history found.</p>
<?php } ?>
	</div>
</div>

<div class='alert_wrapper' id='goodReceiptWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>View Good Receipt</h3>
		<hr>
		<label>Good Receipt</label>
		<p id='goodReceiptDateP'></p>
		<p id='goodReceiptNameP'></p>
		
		<label>Items</label>
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
			</tr>
			<tbody id='goodReceiptTableContent'></tbody>
		</table>

		<label>Status</label>
		<p id='goodReceiptStatusP'></p>
	</div>
</div>

<script>
	function viewGoodReceipt(n){
		$.ajax({
			url:"<?= site_url('Good_receipt/showById') ?>",
			data:{
				id: n
			},
			success:function(response){
				var general		= response.general;
				var name		= general.name;
				var date		= general.date;
				var is_confirm	= general.is_confirm;

				if(is_confirm == 1){
					$('#goodReceiptStatusP').html("Confirmed.");
				} else {
					$('#goodReceiptStatusP').html("Not confirmed.");
				}
				
				$('#goodReceiptNameP').html(name);
				$('#goodReceiptDateP').html(my_date_format(date));

				$('#goodReceiptTableContent').html("");

				var items		= response.items;
				$.each(items, function(index, value){
					var name		= value.name;
					var reference	= value.reference;
					var quantity	= value.quantity;

					$('#goodReceiptTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td></tr>");
				})
			},
			complete:function(){
				$('#goodReceiptWrapper').fadeIn(300, function(){
					$('#goodReceiptWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}
</script>
