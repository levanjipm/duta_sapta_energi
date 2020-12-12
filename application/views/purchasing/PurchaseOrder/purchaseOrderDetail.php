<head>
	<title>Purchase order</title>
</head>
<?php
	$complete_address		= '';
	$complete_address		.= $supplier->address;
	$supplier_number		= $supplier->number;
	$supplier_block			= $supplier->block;
	$supplier_rt			= $supplier->rt;
	$supplier_rw			= $supplier->rw;
	$supplier_postal_code	= $supplier->postal_code;
		
	$created_by				= $general->created_by;
	$confirmed_by			= $general->confirmed_by;
		
	$complete_address		.= 'No. ' . $supplier_number;
		
	if($supplier_block		== '' && $supplier_block == '000'){
		$complete_address	.= 'Block ' . $supplier_block;
	};
		
	if($supplier_rt != '' && $supplier_rt != '000'){
		$complete_address	.= 'RT ' . $supplier_rt . '/ RW ' . $supplier_rw;
	}
		
	if($supplier_postal_code != ''){
		$complete_address	.= ', ' . $supplier_postal_code;
	}
?>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Purchasing') ?>' title='Purchasing'><i class='fa fa-shopping-cart'></i></a> / Purchase order / View</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Purchase order</label>
		<p><?= $general->name ?></p>
		<p>Created by <?= $general->created_by ?></p>
		<p><?= ($general->taxing == 1) ? "Taxable purchase" : "Non-taxable purchase"; ?></p>
		<p><?= ($general->date_send_request == null) ? $general->status : ("Send date: " . date('d M Y', strtotime($general->date_send_request))); ?></p>

		<label>Payment</label>
		<p><?= $general->payment ?> days</p>

		<label>Supplier</label>
		<p><?= $supplier->name ?></p>
		<p><?= $complete_address ?></p>
		<p><?= $supplier->city ?></p>

		<label>Delivery Location</label>
		<p><?= ($general->dropship_address == null) ? "PT Duta Sapta Energi" : $general->dropship_address ?></p>
		<p><?= ($general->dropship_address == null) ? "Jalan Babakan Hantap no. 23" : $general->dropship_city ?></p>
		<p><?= ($general->dropship_address == null) ? "Bandung" : $general->dropship_contact_person ?></p>
		<p><?= ($general->dropship_address == null) ? "" : $general->dropship_contact ?></p>

		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
				<th>Received</th>
			</tr>
<?php foreach($detail as $item){ ?>
			<tr>
				<td><?= $item->reference ?></td>
				<td><?= $item->name ?></td>
				<td><?= number_format($item->quantity, 0) ?></td>
				<td><?= number_format($item->received, 0) ?></td>
			</tr>
<?php } ?>
		</table>

		<label>Good receipts</label>
<?php if(count($goodReceipt) > 0){ ?>
		<table class='table table-bordered'>
			<tr>
				<th>Date</th>
				<th>Name</th>
				<th>Action</th>
			</tr>
<?php foreach($goodReceipt as $item){ ?>
			<tr>
				<td><?= $item->date ?></td>
				<td><?= $item->name ?></td>
				<td><button class='button button_default_dark' onclick='viewGoodReceipt(<?= $item->id ?>)'><i class='fa fa-eye'></i></button>
			</tr>
<?php } ?>
		</table>
<?php } else { ?>
		<p>There is no good receipt found.
<?php } ?>
	</div>
</div>

<div class='alert_wrapper' id='goodReceiptWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Good receipt</h3>
		<hr>
		<label>Good Receipt</label>
		<p id='goodReceiptNameP'></p>
		<p id='goodReceiptDateP'></p>
		<p>Received on <span id='goodReceiptReceivedDateP'></p>

		<label>Items</label>
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
			</tr>
			<tbody id='goodReceiptItem'></tbody>
		</table>

		<label>Status</label>
		<p id="goodReceiptStatusP"></p>
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
				var general			= response.general;
				var date			= general.date;
				var name			= general.name;
				$('#goodReceiptDateP').html(my_date_format(date));
				$('#goodReceiptNameP').html(name);

				var invoice_id		= general.invoice_id;
				var is_confirm		= general.is_confirm;
				var receivedDate	= general.received_date;

				$('#goodReceiptReceivedDateP').html(my_date_format(receivedDate));

				if(is_confirm == 0){
					$('#goodReceiptStatusP').html("Good receipt has not been confirmed.");
				} else if(is_confirm == 1 && invoice_id == null){
					$('#goodReceiptStatusP').html("Good receipt has not been invoiced");
				} else if(is_confirm == 1 && invoice_id != null){
					$('#goodReceiptStatusP').html("Good receipt has been invoiced.");
				}
				var items			= response.items;
				$('#goodReceiptItem').html("");
				$.each(items, function(index, value){
					var reference		= value.reference;
					var name			= value.name;
					var quantity		= value.quantity;
					$('#goodReceiptItem').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td></tr>");
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
