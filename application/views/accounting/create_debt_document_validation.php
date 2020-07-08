<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Sales'><i class='fa fa-bar-chart'></i></a> /<a href='<?= site_url('Debt') ?>'>Debt document</a> /Create</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<form action='<?= site_url('Debt/input') ?>' method='POST' id='debt_document_form'>
			<label>Date</label>
			<p style='font-family:museo' id='document_date'><?= date('d M Y',strtotime($date)) ?></p>
			<input type='hidden' class='form-control' name='date' value='<?= $date ?>' required>
			
			<label>Supplier</label>
			<p style='font-family:museo'><?= $supplier->name ?></p>
			<p style='font-family:museo'><?= $supplier->address ?></p>
			<p style='font-family:museo'><?= $supplier->city ?></p>
			
			<label>Tax document</label>
			<input type='text' class='form-control' id='tax_document' name='tax_document'>
			
			<label>Invoice name</label>
			<input type='text' class='form-control' id='invoice_document' name='invoice_document' required>
			<br>
			<script>
				$("#tax_document").inputmask("999.999-99.99999999");
			</script>
<?php
	foreach($documents as $document){
		$document_value		= 0;
?>
			<label>Good receipt</label>
			<p style='font-family:museo'><?= date('d M Y',strtotime($document->date)) ?></p>
			<p style='font-family:museo'><?= $document->name ?></p>
			<p style='font-family:museo'>Received on <?= date('d M Y',strtotime($document->received_date)) ?></p>
			
			<input type='hidden' name='document[<?= $document->id ?>]'>
			
			<label>Purchase order</label>
			<p style='font-family:museo'><?= $document->purchase_order_name ?></p>
			
			<table class='table table-bordered'>
				<tr>
					<th>Reference</th>
					<th>Name</th>
					<th>Quantity</th>
					<th>Net price</th>
					<th>Total price</th>
				</tr>
				<tbody id='documents-<?= $document->id ?>'>
<?php
		foreach($details as $detail){
			if($detail->code_good_receipt_id == $document->id){
				$item_value			= $detail->net_price * $detail->quantity;
				$document_value		+= $item_value;
?>
					<tr>
						<td><?= $detail->reference ?></td>
						<td><?= $detail->name ?></td>
						<td><?= number_format($detail->quantity) ?><input type='hidden' value='<?= $detail->quantity ?>' id='quantity-<?= $detail->id ?>'></td>
						<td><input type='number' class='form-control' id='net_price-<?= $detail->id ?>' name='price[<?= $detail->id ?>]' value='<?= $detail->net_price ?>' onchange='update_price(<?= $document->id ?>)' min='0'></td>
						<td id='total_value-<?= $detail->id ?>'>Rp. <?= number_format($item_value,2) ?></td>
					</tr>
<?php
			}
			
			next($details);
		}
?>
				</tbody>
				<tr>
					<td colspan='2'></td>
					<td colspan='2'>Total</td>
					<td id='total_debt_document-<?= $document->id ?>'>Rp. <?= number_format($document_value,2) ?></td>
				</tr>
			</table>
<?php
	}
?>
			<button type='button' class='button button_default_dark' onclick='validate_debt_document()'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>
<div class='alert_wrapper' id='debt_document_validation_wrapper'>
	<button type='button' class='slide_alert_close_button'>&times </button>
	<div class='alert_box_slide'>
		<h2 style='font-family:bebasneue'>Debt document</h2>
		<hr>
		<label>Date</label>
		<p style='font-family:museo' id='date_p'><?= date('d M Y',strtotime($date)) ?></p>
		
		<label>Supplier</label>
		<p style='font-family:museo'><?= $supplier->name ?></p>
		<p style='font-family:museo'><?= $supplier->address ?></p>
		<p style='font-family:museo'><?= $supplier->city ?></p>
		
		<label>Invoice</label>
		<p style='font-family:museo' id='invoice_name_p'></p>
		<p style='font-family:museo' id='tax_document_name_p'></p>
		
		<label>Good receipts</label>
		<table class='table table-bordered'>
			<tr>
				<th>Date</th>
				<th>Name</th>
				<th>Purchase order</th>
				<th>Value</th>
			</tr>
<?php
	foreach($documents as $document){
?>
			<tr>
				<td><?= date('d M Y',strtotime($document->date)) ?></td>
				<td><?= $document->name ?></td>
				<td>
					<p style='font-family:museo'><b><?= $document->purchase_order_name ?></b></p>
					<p style='font-family:museo'><?= date('d M Y',strtotime($document->purchase_order_date)) ?></p>
				</td>
				<td id='total_good_receipt_value_p-<?= $document->id ?>'></td>
			</tr>
<?php
	}
?>
			<tr>
				<td colspan='2'>
				<td>Total</td>
				<td id='grand_total_debt_p'></td>
		</table>
		
		<button type='button' class='button button_default_dark' title='Confirm debt document' id='submit_button'><i class='fa fa-long-arrow-right'></i></button>
	</div>
</div>
<script>	
	function update_price(code_good_receipt_id){
		var debt_document_value		= 0;
		$('#documents-' + code_good_receipt_id + ' input[id^="net_price-"]').each(function(){
			var id			= $(this).attr('id');
			var uid			= id.substring(10, 264);
			var net_price	= $(this).val();
			var quantity	= $('#quantity-' + uid).val();
			var total_price	= net_price * quantity;
			$('#total_value-' + uid).html('Rp. ' + numeral(total_price).format('0,0.00'));
			debt_document_value += total_price;
		});
		$('#total_debt_document-' + code_good_receipt_id).html('Rp. ' + numeral(debt_document_value).format('0,0.00'));
	}
	
	function validate_debt_document(){
		$('#debt_document_form').validate();
		
		if($('#debt_document_form').valid()){
			show_validation_page();
		}
	}
	
	function show_validation_page(){
		var invoice_name		= $('#invoice_document').val();
		var tax_invoice_name	= $('#tax_document').val();
		var total_price			= 0;
		
		var invoice_document	= $('#invoice_document').val();
		var tax_document		= $('#tax_document').val();
		
		$('input[id^="net_price-"]').each(function(){
			var id			= $(this).attr('id');
			var uid			= id.substring(10, 264);
			var net_price	= $(this).val();
			var quantity	= $('#quantity-' + uid).val();
			total_price		+= quantity * net_price;
		});
		
		$('td[id^="total_debt_document-"]').each(function(){
			var id			= $(this).attr('id');
			var uid			= parseInt(id.substring(20, 267));
			$('#total_good_receipt_value_p-' + uid).html($(this).html());
		});
		
		$('#invoice_name_p').html(invoice_document);
		$('#tax_document_name_p').html(tax_document);
		$('#grand_total_debt_p').html('Rp. ' + numeral(total_price).format('0,0.00'));
		
		$('#debt_document_validation_wrapper').fadeIn(300, function(){
			$('#debt_document_validation_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	}
	
	$('.alert_close_button').click(function(){
		$(this).parent().fadeOut();
	});
	
	$('#submit_button').click(function(){
		$('#debt_document_form').submit();
	});
	
	$('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
</script>