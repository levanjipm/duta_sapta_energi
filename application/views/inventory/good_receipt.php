<div class='dashboard'>
	<h2 style='font-family:bebasneue'>Good receipt</h2>
	<a href='<?= site_url('Good_receipt/create_dashboard') ?>' style='text-decoration:none'>
		<button type='button' class='button button_default_light'>Create good receipt</button>
	</a>
	<br><br>
<?php
	if(!empty($good_receipts)){
?>
	<table class='table table-bordered'>
		<tr>
			<th>Name</th>
			<th>Date</th>
			<th>Action</th>
		</tr>
<?php
	foreach($good_receipts as $good_receipt){
		$id				= $good_receipt->id;
		$name			= $good_receipt->name;
		$date			= $good_receipt->date;
?>
		<tr>
			<td><?= $name ?></td>
			<td><?= date('d M Y',strtotime($date)) ?></td>
			<td>
				<button type='button' class='button button_default_light' id='button_<?= $id ?>' onclick='validate_good_receipt(<?= $id ?>)'><i class='fa fa-long-arrow-right'></i></button>
			</td>
		</tr>
<?php
	}
?>
	</table>
	
	<div class='alert_wrapper' id='good_receipt_validation_wrapper'>
		<div class='alert_box_default'>
			<h3 style='font-family:bebasneue'>Confirm good receipt</h3>
			<hr>
			<label>Good receipt</label>
			<p style='font-family:museo' id='good_receipt_date'></p>
			<p style='font-family:museo' id='good_receipt_document'></p>
			<p style='font-family:museo'>Received on <span id='good_receipt_received_date'></span></p>
			
			<label>Supplier</label>
			<p style='font-family:museo' id='supplier_name_p'></p>
			<p style='font-family:museo' id='supplier_address_p'></p>
			<p style='font-family:museo' id='supplier_city_p'></p>
			
			<label>Purchase order</label>
			<p style='font-family:museo' id='purchase_order_name'></p>
			<p style='font-family:museo' id='purchase_order_date'></p>
			
			<table class='table table-bordered'>
				<tr>
					<th>Reference</th>
					<th>Name</th>
					<th>Quantity</th>
				</tr>
				<tbody id='good_receipt_table'></tbody>
			</table>
			
			<form action='<?= site_url('Good_receipt/confirm') ?>' method='POST'>
				<input type='hidden' id='good_receipt_id' name='id'>
				<button class='button button_default_dark'>Submit</button>
			</form>
		</div>
	</div>
	
	<script>
		function validate_good_receipt(n){
			$('#good_receipt_id').val(n);
			$.ajax({
				url:'<?= site_url('Good_receipt/view_complete_good_receipt') ?>',
				data:{
					id:n
				},
				dataType:'json',
				success:function(response){
					var date	= response[0].date;
					var document = response[0].name;
					var received_date = response[0].received_date;
					
					$('#good_receipt_date').html(date);
					$('#good_receipt_document').html(document);
					$('#good_receipt_received_date').html(received_date);
					
					var supplier_name		= response[0].supplier_name;
					var supplier_address	= response[0].address;
					var supplier_city		= response[0].city;
					
					$('#supplier_name_p').html(supplier_name);
					$('#supplier_address_p').html(supplier_address);
					$('#supplier_city_p').html(supplier_city);
					
					$('#good_receipt_table').html('');
					$.each(response, function(index, value){
						$('#good_receipt_table').append("<tr><td>" + value.reference + "</td><td>" + value.name + "</td><td>" + numeral(value.quantity).format('0,0.00') + "</td></tr>");
					});
					$('#good_receipt_validation_wrapper').fadeIn();
				}
			});
		};
	</script>
<?php
	}
?>
</div>