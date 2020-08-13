<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> / Bank/ Assign bank</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<form action='<?= site_url('Bank/assign_input') ?>' method='POST'>
			<label>Bank data</label>
			<p style='font-family:museo'><?= date('d M Y',strtotime($bank->date)) ?></p>
			<p style='font-family:museo'><?= $bank->name ?> ( <?= $opponent ?> )</p>
			<p style='font-family:museo'>Rp. <span id='bank_value_p'><?= number_format($bank->value,2) ?></span></p>
			<input type='hidden' value='<?= $bank->value ?>' id='bank_value'>
			<input type='hidden' value='<?= $bank->id ?>' name='bank_id' readonly>
			
			<label>Invoice</label>
<?php
	if(!empty($invoices)){
?>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Action</th>
					<th>Remaining</th>
				</tr>
<?php
	foreach($invoices as $invoice){
		$paid		= $invoice->paid;
		$value		= $invoice->value;
		$date		= $invoice->date;
		$name		= $invoice->name;
		$id			= $invoice->id;
?>
				<tr>
					<td><?= date('d M Y',strtotime($date)) ?></td>
					<td><?= $name ?></td>
					<td><input type='checkbox' name='check_box[<?= $id ?>]' onchange='process(<?= $id ?>)' id='check_box-<?= $id ?>'></td>
					<td>
						Rp. <span id='remaining_value-<?= $id ?>'><?= number_format($value - $paid,2) ?></span>
						<input type='hidden' id='remaining-<?= $id ?>' value='<?= $value - $paid ?>' name='remaining[<?= $id ?>]'>
						<input type='hidden' id='original-<?= $id ?>' value='<?= $value - $paid ?>' name='original[<?= $id ?>]'>
					</td>
				</tr>
<?php
	}
?>
			</table>
			
			<button class='button button_default_dark' id='submit_button' disabled style='display:none'><i class='fa fa-long-arrow-right'></i></button>
<?php
	} else {
?>
			<p style='font-family:museo'>There is no invoice to be assigned</p>
	
			<a href='<?= site_url('Bank/assign') ?>'><button type='button' class='button button_default_dark'><i class='fa fa-long-arrow-left'></i></button></a>
<?php
	}
?>
		</form>
	</div>
</div>
<script>
	function process(id){
		if($('#check_box-' + id).prop('checked') == true){
			var remaining_bank_value	= $('#bank_value').val();
			var remaining_invoice_value	= $('#remaining-' + id).val();
			var difference				= Math.abs(remaining_bank_value - remaining_invoice_value);
			if(remaining_bank_value < remaining_invoice_value){
				$('#bank_value').val(difference);
				$('#remaining-' + id).val(0);
				$('#bank_value_p').html(numeral(difference).format('0,0.00'));
				$('#remaining_value-' + id).html(numeral(0).format('0,0.00'));
			} else {
				$('#bank_value').val(0);
				$('#remaining-' + id).val(difference);
				$('#bank_value_p').html(numeral(0).format('0,0.00'));
				$('#remaining_value-' + id).html(numeral(difference).format('0,0.00'));
			}
		} else {
			var original_value		= parseFloat($('#original-' + id).val());
			var current_bank_value	= parseFloat($('#bank_value').val());
			var current_value		= parseFloat($('#remaining-' + id).val());
			var difference			= parseFloat(Math.abs(original_value - current_value));
			
			var bank_value			= current_bank_value + difference;
			
			$('#remaining-' + id).val(original_value);
			$('#remaining_value-' + id).html(numeral(original_value).format('0,0.00'));
			
			$('#bank_value').val(bank_value);
			$('#bank_value_p').html(numeral(bank_value).format('0,0.00'));
		}
		var len = $("[name*='check_box']:checked").length;
		if(len > 0){
			$('#submit_button').show();
			$('#submit_button').attr('disabled', false);
		} else {
			$('#submit_button').hide();
			$('#submit_button').attr('disabled', true);
		}
	}
</script>