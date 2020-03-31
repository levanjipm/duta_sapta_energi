	<form action='<?= site_url('Customer/update_customer/') ?>' method='POST'>
		<h2 style='font-family:bebasneue'>Edit customer form</h2>
		<hr>
		<input type='hidden' value='<?= $customer->id ?>' name='customer_id'>
		
		<label>Customer name</label>
		<input type='text' class='form-control' name='customer_name' value='<?= $customer->name ?>' required>
		
		<label>Address</label>
		<textarea class='form-control' name='customer_address' rows='3' style='resize:none' required><?= $customer->address ?></textarea>
		
		<label>Number</label>
		<input type='text' class='form-control' name='address_number' value='<?= $customer->number ?>'>
		
		<label>Block</label>
		<input type='text' class='form-control' name='address_block' value='<?= $customer->block ?>'>
		
		<label>Neighbourhood (RT)</label>
		<input type='text' class='form-control' name='address_rt' minlength='3' maxlength='3' value='<?= $customer->rt ?>' required>
		
		<label>Hamlet (RW)</label>
		<input type='text' class='form-control' name='address_rw' minlength='3' maxlength='3' value='<?= $customer->rw ?>' required>
		
		<label>Postal code</label>
		<input type='number' class='form-control' name='address_postal' minlength='3' value='<?= $customer->postal_code ?>'>
		
		<label>City</label>
		<input type='text' class='form-control' name='customer_city' value='<?= $customer->city ?>' required>
		
		<label>Phone number</label>
		<input type='text' class='form-control' name='customer_phone' value='<?= $customer->phone_number ?>' required>
		
		<label>PIC</label>
		<input type='text' class='form-control' name='customer_pic' value='<?= $customer->pic_name ?>'>
		
		<label>Tax identification number</label>
		<input type='text' class='form-control' name='customer_npwp' id='customer_edit_npwp' value='<?= $customer->npwp ?>'>
		<script>
			$("#customer_edit_npwp").inputmask("99.999.999.9-999.999");
		</script><br>
		
		<label>Area</label>
		<select class='form-control' name='area_id'>
<?php
	foreach($areas as $area){
?>
			<option value='<?= $area->id ?>' <?php if($customer->area_id == $area->id){ echo 'selected'; } ?>><?= $area->name ?></option>
<?php
	}
?>
		</select>
		<label>Term of payment</label>
		<input type='number' class='form-control' min='0' required name='term_of_payment' value='<?= $customer->term_of_payment ?>'>
		
		<br>
		<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
	</form>
	<script>
		$('.alert_close_button').click(function(){
			$(this).parents('.alert_wrapper').fadeOut();
		});
	</script>