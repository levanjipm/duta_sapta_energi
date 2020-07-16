<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Debt') ?>'>Debt</a> /Create blank</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<form action='<?= site_url('Debt/createDebtDocument') ?>' method='POST' id='debt_document_form'>
			<label>Date</label>
			<input type='date' class='form-control' name='date' required>
			
			<label>Supplier</label>
			<button type='button' class='form-control' onclick='refresh_view(1)'></button>
			
			<input type='hidden' id='supplier_id' name='supplier_id'>
			
			<label>Value</label>
			<input type='number' class='form-control' name='value' required min='1'>
			
			<label>Information</label>
			<textarea class='form-control' style='resize:none'></textarea>
			
			<br>
			<button class='button button_default_dark' id='submit_button'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='supplier_wrapper'>
	<div class='full_screen_box'>
		<
		<select class='form-control' id='page' style='width:100px'>
			<option value='1'>1</option>
		</select>
	</div>
</div>
<script>
	refresh_view();
	$('#debt_document_form').validate();
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
		});
	}
</script>