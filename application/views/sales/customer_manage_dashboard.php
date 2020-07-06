<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /Customer</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group input-group-lg'>
			<input for="customer" type='text' class='form-control' id='search_bar' style='border-radius:0' placeholder="Search customer">
			<div class='input_group_append'>
				<button type='button' class='button button_default_dark' id='add_customer_button'>Add customer</button>
			</div>
		</div>
		<br><br>
		<table class='table table-bordered'>
			<tr>
				<th>Customer name</th>
				<th>Address</th>
				<th>Action</th>
			</tr>
			<tbody id='customer_table'></tbody>
		</table>
		
		<select class='form-control' id='page' onchange='update_view()' style='width:100px'>
<?php
	for($i = 1; $i <= $pages; $i++){
?>
			<option value='<?= $i ?>'><?= $i ?></option>
<?php
	}
?>
		</select>
	</div>
</div>

<div class='alert_wrapper' id='add_customer_wrapper'>
	<button class='slide_alert_close_button'>&times</button>
	<div class='alert_box_slide'>
		<form action='<?= site_url('Customer/insert_new_customer/') ?>' method='POST'>
		<h2 style='font-family:bebasneue'>Add customer form</h2>
		<hr>
		
		<label>Customer name</label>
		<input type='text' class='form-control' name='customer_name' required>
		
		<label>Address</label>
		<textarea class='form-control' name='customer_address' rows='3' style='resize:none' required></textarea>
		
		<label>Number</label>
		<input type='text' class='form-control' name='address_number'>
		
		<label>Block</label>
		<input type='text' class='form-control' name='address_block'>
		
		<label>Neighbourhood (RT)</label>
		<input type='text' class='form-control' name='address_rt' minlength='3' maxlength='3' required>
		
		<label>Hamlet (RW)</label>
		<input type='text' class='form-control' name='address_rw' minlength='3' maxlength='3' required>
		
		<label>Postal code</label>
		<input type='number' class='form-control' name='address_postal' minlength='4'>
		
		<label>City</label>
		<input type='text' class='form-control' name='customer_city' required>
		
		<label>Phone number</label>
		<input type='text' class='form-control' name='customer_phone' required>
		
		<label>PIC</label>
		<input type='text' class='form-control' name='customer_pic'>
		
		<label>Tax identification number</label>
		<input type='text' class='form-control' name='customer_npwp' id='customer_npwp'>
		<script>
			$("#customer_npwp").inputmask("99.999.999.9-999.999");
		</script>
		
		<label>Area</label>
		<select class='form-control' name='area_id'>
<?php
	foreach($areas as $area){
?>
			<option value='<?= $area->id ?>'><?= $area->name ?></option>
<?php
	}
?>
		</select>
		
		<label>Default payment</label>
		<input type='number' class='form-control' min='0' required name='term_of_payment'>
		<br>
		<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='delete_confirmation_wrapper'>
	<div class='alert_box_confirm'>
		<img src='<?= base_url('assets/exclamation.png') ?>' style='width:40%'></img>
		<br><br>
		<h4 style='font-family:museo'>Are you sure?</h4>
		<br><br>
		<button class='button button_danger_dark' onclick='close_alert("delete_confirmation_wrapper")'>Not sure</button>
		<button class='button button_success_dark' onclick='confirm_delete()'>Yes</button>
		
		<input type='hidden' id='customer_delete_id'>
	</div>
</div>

<div class='alert_wrapper' id='edit_customer_wrapper'>
	<button class='slide_alert_close_button'>&times</button>
	<div class='alert_box_slide'>
	</div>
</div>

<script>
	update_view();
	$('#add_customer_button').click(function(){
		$('#add_customer_wrapper').fadeIn(300, function(){
			$('#add_customer_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	});
	
	function open_delete_confirmation(n){
		$('#delete_confirmation_wrapper').fadeIn();
		$('#customer_delete_id').val(n);
	};
	
	function confirm_delete(){
		$.ajax({
			url:'<?= site_url('Customer/delete_customer') ?>',
			type:'POST',
			data:{
				customer_id: $('#customer_delete_id').val()
			},
			beforeSend:function(){
				$('button').attr('disabled',true);
			},
			success:function(){
				window.location.reload();
			}
		})
	};
	
	function open_edit_form(n){
		$.ajax({
			url:'<?= site_url('Customer/update_customer_view') ?>',
			type:'GET',
			data:{
				customer_id: n
			},
			beforeSend:function(){
				$('button').attr('disabled',true);
			},
			success:function(response){
				$('button').attr('disabled',false);
				$('#edit_customer_wrapper .alert_box_slide').html(response);
				$('#edit_customer_wrapper').fadeIn(300, function(){
					$('#edit_customer_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	};
	
	$('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
	
	function update_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Customer/show_items') ?>',
			data:{
				term:$('#search_bar').val(),
				page:page
			},
			type:'GET',
			beforeSend:function(){
				$('#customer_table_view_pane').html('');
			},
			success:function(response){
				var customers	= response.customers;
				var pages		= response.pages;
				$('#page').html('');
				$('#customer_table').html('');
				
				$.each(customers, function(index, customer){
					var complete_address		= '';
					var customer_name			= customer.name;
					complete_address		+= customer.address;
					var customer_city			= customer.city;
					var customer_number			= customer.number;
					var customer_rt				= customer.rt;
					var customer_rw				= customer.rw;
					var customer_postal			= customer.postal_code;
					var customer_block			= customer.block;
					var customer_id				= customer.id;
		
					if(customer_number != null){
						complete_address	+= ' No. ' + customer_number;
					}
					
					if(customer_block != null){
						complete_address	+= ' Blok ' + customer_block;
					}
				
					if(customer_rt != '000'){
						complete_address	+= ' RT ' + customer_rt;
					}
					
					if(customer_rw != '000' && customer_rt != '000'){
						complete_address	+= ' /RW ' + customer_rw;
					}
					
					if(customer_postal != null){
						complete_address	+= ', ' + customer_postal;
					}
					
					$('#customer_table').append("<tr><td>" + customer_name + "</td><td><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><button type='button' class='button button_success_dark' onclick='open_edit_form(" + customer_id + ")'><i class='fa fa-pencil'></i></button> <button type='button' class='button button_danger_dark' onclick='open_delete_confirmation(" + customer_id + ")'><i class='fa fa-trash'></i></button> <button type='button' class='button button_default_dark'><i class='fa fa-eye'></i></button></tr>");
				});
				
				for(i = 1; i <= pages; i++){
					if(page == i){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			},
			
		});
	};
	
	$('#search_bar').change(function(){
		update_view(1);
	});
</script>