<head>
	<title>Customer</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-line-chart'></i></a> /Customer</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<input for="customer" type='text' class='form-control' id='search_bar' style='border-radius:0' placeholder="Search customer">
			<div class='input_group_append'>
				<button type='button' class='button button_default_dark' id='add_customer_button'><i class='fa fa-plus'></i> Add customer</button>
			</div>
		</div>
		<br><br>
		<div id='customerTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Name</th>
					<th>Address</th>
					<th>Action</th>
				</tr>
				<tbody id='customerTableContent'></tbody>
			</table>
			
			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='customerTableText'>No customer found.</p>
	</div>
</div>

<div class='alert_wrapper' id='add_customer_wrapper'>
	<button class='slide_alert_close_button'>&times</button>
	<div class='alert_box_slide'>
		<form id='add_customer_form'>
			<h3 style='font-family:bebasneue'>Add customer form</h3>
			<hr>
			
			<label>Customer name</label>
			<input type='text' class='form-control' id='customer_name' required>
			
			<label>Address</label>
			<textarea class='form-control' id='customer_address' rows='3' style='resize:none' required></textarea>
			
			<label>Number</label>
			<input type='text' class='form-control' id='address_number'>
			
			<label>Block</label>
			<input type='text' class='form-control' id='address_block'>
			
			<label>Neighbourhood (RT)</label>
			<input type='text' class='form-control' id='address_rt' minlength='3' maxlength='3' required>
			
			<label>Hamlet (RW)</label>
			<input type='text' class='form-control' id='address_rw' minlength='3' maxlength='3' required>
			
			<label>Postal code</label>
			<input type='number' class='form-control' id='address_postal'>
			
			<label>City</label>
			<input type='text' class='form-control' id='customer_city' required>
			
			<label>Phone number</label>
			<input type='text' class='form-control' id='customer_phone' required>
			
			<label>PIC</label>
			<input type='text' class='form-control' id='customer_pic'>
			
			<label>Tax identification number</label>
			<input type='text' class='form-control' id='customer_npwp'>
			<script>
				$("#customer_npwp").inputmask("99.999.999.9-999.999");
			</script>
			
			<label>Area</label>
			<select class='form-control' id='area_id'>
<?php foreach($areas as $area){ ?>
				<option value='<?= $area->id ?>'><?= $area->name ?></option>
<?php } ?>
			</select>
			
			<label>Location</label>
			<div class='input_group'>
				<input type='number' class='form-control' id='latitude' placeholder='Latitude'>
				<input type='number' class='form-control' id='longitude' placeholder='Longitude'>
			</div>

			<label>Visit Frequency</label>
			<input type='number' class='form-control' id='visit' required>
			<br>
			<button class='button button_default_dark' type='button' id='submit_add_customer_button'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='edit_customer_wrapper'>
	<button class='slide_alert_close_button'>&times</button>
	<div class='alert_box_slide'>
		<form id='edit_customer_form'>
			<h3 style='font-family:bebasneue'>Update customer form</h3>
			<hr>
			<input type='hidden' id='customer_id_edit'>
			
			<label>Customer name</label>
			<input type='text' class='form-control' id='name_edit' required>
			
			<label>Address</label>
			<textarea class='form-control' id='address_edit' rows='3' style='resize:none' required></textarea>
			
			<label>Number</label>
			<input type='text' class='form-control' id='number_edit'>
			
			<label>Block</label>
			<input type='text' class='form-control' id='block_edit'>
			
			<label>Neighbourhood (RT)</label>
			<input type='text' class='form-control' id='rt_edit' minlength='3' maxlength='3' required>
			
			<label>Hamlet (RW)</label>
			<input type='text' class='form-control' id='rw_edit' minlength='3' maxlength='3' required>
			
			<label>Postal code</label>
			<input type='number' class='form-control' id='postal_edit'>
			
			<label>City</label>
			<input type='text' class='form-control' id='city_edit' required>
			
			<label>Phone number</label>
			<input type='text' class='form-control' id='phone_edit' required>
			
			<label>PIC</label>
			<input type='text' class='form-control' id='pic_edit'>
			
			<label>Tax identification number</label>
			<input type='text' class='form-control' id='npwp_edit'>
			<script>
				$("#npwp_edit").inputmask("99.999.999.9-999.999");
			</script>
			
			<label>Area</label>
			<select class='form-control' id='area_id_edit'>
<?php foreach($areas as $area){ ?>
				<option value='<?= $area->id ?>'><?= $area->name ?></option>
<?php } ?>
			</select>
			
			<label>Location</label>
			<div class='input_group'>
				<input type='number' class='form-control' id='latitude_edit' placeholder='Latitude'>
				<input type='number' class='form-control' id='longitude_edit' placeholder='Longitude'>
			</div>

			<label>Visit Frequency</label>
			<input type='number' class='form-control' id='visit_edit' placeholder='Visit Frequency'>
			<br>
			<button class='button button_default_dark' type='button' id='submit_edit_customer_button'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='delete_customer_wrapper'>
	<div class='alert_box_confirm_wrapper'>
		<div class='alert_box_confirm_icon'><i class='fa fa-trash'></i></div>
		<div class='alert_box_confirm'>
			<input type='hidden' id='delete_customer_id'>
			<h3>Delete confirmation</h3>
			
			<p>You are about to delete this data.</p>
			<p>Are you sure?</p>
			<button class='button button_default_dark' onclick="$('#delete_customer_wrapper').fadeOut()">Cancel</button>
			<button class='button button_danger_dark' onclick='delete_customer()'>Delete</button>
			
			<br><br>
			
			<p style='font-family:museo;background-color:#f63e21;width:100%;padding:5px;color:white;position:relative;bottom:0;left:0;opacity:0' id='error_delete_customer'>Deletation failed.</p>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='reset_customer_wrapper'>
	<div class='alert_box_confirm_wrapper'>
		<div class='alert_box_confirm_icon'><i class='fa fa-undo'></i></div>
		<div class='alert_box_confirm'>
			<input type='hidden' id='reset_customer_id'>
			<h3>Reset Password confirmation</h3>
			
			<p>You are about to reset this password.</p>
			<p>Are you sure?</p>
			<button class='button button_default_dark' onclick="$('#reset_customer_wrapper').fadeOut()">Cancel</button>
			<button class='button button_danger_dark' onclick='reset_password()'>Reset</button>
			
			<br><br>
			
			<p style='font-family:museo;background-color:#f63e21;width:100%;padding:5px;color:white;position:relative;bottom:0;left:0;opacity:0' id='error_reset_customer'>Deletation failed.</p>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		refresh_view();
	});
	
	$('#search_bar').change(function(){
		refresh_view(1);
	});
	
	$('#page').change(function(){
		refresh_view();
	});	
	
	$('#add_customer_button').click(function(){
		$('#visit').val(28);
		$('#add_customer_wrapper').fadeIn(300, function(){
			$('#add_customer_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	});
	
	$('#add_customer_form').validate();
	
	$('#edit_customer_form').validate();
	
	$('#add_customer_form').on('submit', function(){
		return false;
	});
	
	$('#submit_add_customer_button').click(function(){
		if($('#submit_add_customer_button').valid()){
			$.ajax({
				url:'<?= site_url('Customer/insertItem') ?>',
				data:{
					customer_name: $('#customer_name').val(),
					customer_address: $('#customer_address').val(),
					address_number: $('#address_number').val(),
					address_block: $('#address_block').val(),
					address_postal: $('#address_postal').val(),
					address_rt: $('#address_rt').val(),
					address_rw: $('#address_rw').val(),
					customer_city: $('#customer_city').val(),
					area_id: $('#area_id').val(),
					customer_npwp: $('#customer_npwp').val(),
					customer_phone: $('#customer_phone').val(),
					customer_pic: $('#customer_pic').val(),
					term_of_payment: $('#term_of_payment').val(),
					latitude: $('#latitude').val(),
					longitude: $("#longitude").val(),
					visit: $('#visit').val()
				},
				type:'POST',
				beforeSend:function(){
					$('button').attr('disabled', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					refresh_view();
					if(response == 1){
						$('#add_customer_form').trigger("reset");
						refresh_view();
						$('#add_customer_wrapper .slide_alert_close_button').click();
					}
				}
			});
		}
	});
	
	function confirm_delete(n){
		$('#delete_customer_id').val(n);
		$('#delete_customer_wrapper').fadeIn();
	}
	
	function delete_customer(){
		$.ajax({
			url:'<?= site_url('Customer/deleteById') ?>',
			type:'POST',
			data:{
				id: $('#delete_customer_id').val()
			},
			beforeSend:function(){
				$('button').attr('disabled',true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				refresh_view();
				if(response == 1){
					$('#delete_customer_wrapper').fadeOut();
				} else {
					$('#error_delete_customer').fadeTo(250, 1);
					setTimeout(function(){
						$('#error_delete_customer').fadeTo(250, 0);
					}, 1000);
				}				
			}
		})
	};
	
	function open_edit_form(n){
		$.ajax({
			url:'<?= site_url('Customer/getItemById') ?>',
			type:'GET',
			data:{
				id: n
			},
			beforeSend:function(){
				$('button').attr('disabled',true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				var name 			= response.name;
				var id 				= response.id;
				var address 		= response.address;
				var block 			= response.block;
				var postal 			= response.postal_code;
				var area_id 		= response.area_id;
				var npwp 			= response.npwp;
				var latitude 		= response.latitude;
				var longitude 		= response.longitude;
				var number 			= response.number;
				var pic 			= response.pic_name;
				var phone 			= response.phone_number;
				var rt 				= response.rt;
				var rw 				= response.rw;
				var term_of_payment = response.term_of_payment;
				var city			= response.city;
				var visit			= response.visiting_frequency;
				
				$('#name_edit').val(name)			
				$('#customer_id_edit').val(id)			
				$('#address_edit').val(address) 		
				$('#block_edit').val(block) 			
				$('#postal_edit').val(postal) 			
				$('#area_id_edit').val(area_id) 		
				$('#npwp_edit').val(npwp) 			
				$('#latitude_edit').val(latitude) 		
				$('#longitude_edit').val(longitude) 		
				$('#number_edit').val(number) 			
				$('#pic_edit').val(pic) 			
				$('#phone_edit').val(phone) 			
				$('#rt_edit').val(rt) 				
				$('#rw_edit').val(rw) 				
				$('#term_of_payment_edit').val(term_of_payment);
				$('#city_edit').val(city);
				$('#visit_edit').val(visit);
			},
			complete:function(){
				$('#edit_customer_wrapper').fadeIn(300, function(){
					$('#edit_customer_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	};
	
	$('#submit_edit_customer_button').click(function(){
		$.ajax({
			url:'<?= site_url('Customer/updateItemById') ?>',
			data:{
				name 			: $('#name_edit').val(),
				id 				: $('#customer_id_edit').val(),		
				address 		: $('#address_edit').val(), 		
				block 			: $('#block_edit').val(), 			
				postal 			: $('#postal_edit').val(), 			
				area_id 		: $('#area_id_edit').val(), 		
				npwp 			: $('#npwp_edit').val(), 				
				latitude 		: $('#latitude_edit').val(), 		
				longitude 		: $('#longitude_edit').val(),	
				number 			: $('#number_edit').val(),				
				pic 			: $('#pic_edit').val(), 				
				phone 			: $('#phone_edit').val(), 				
				rt 				: $('#rt_edit').val(), 					
				rw 				: $('#rw_edit').val(), 					
				term_of_payment : $('#term_of_payment_edit').val(),
				city			: $('#city_edit').val(),
				visit			: $('#visit_edit').val()
			},
			type:'POST',
			beforeSend: function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				refresh_view();
				$('#edit_customer_wrapper .slide_alert_close_button').click();
			}
		});
	});
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Customer/showItems') ?>',
			data:{
				term:$('#search_bar').val(),
				page:page
			},
			type:'GET',
			beforeSend:function(){
				$('#customerTableContent_view_pane').html('');
			},
			success:function(response){
				var customerCount = 0;
				var customers	= response.customers;
				var pages		= response.pages;
				$('#page').html('');
				$('#customerTableContent').html('');
				
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
					
					if(customer_block != null && customer_block != "000"){
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
					
					if(customer.password != null){
						$('#customerTableContent').append("<tr><td>" + customer_name + "</td><td><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><button type='button' class='button button_success_dark' onclick='open_edit_form(" + customer_id + ")'><i class='fa fa-pencil'></i></button> <button type='button' class='button button_danger_dark' onclick='confirm_delete(" + customer_id + ")'><i class='fa fa-trash'></i></button> <button type='button' onclick='viewCustomerDetail(" + customer_id + ")' class='button button_default_dark'><i class='fa fa-eye'></i></button> <button type='button' class='button button_default_dark' onclick='resetPassword(" + customer_id + ")'><i class='fa fa-undo'></i></button></td></tr>");
					} else {
						$('#customerTableContent').append("<tr><td>" + customer_name + "</td><td><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><button type='button' class='button button_success_dark' onclick='open_edit_form(" + customer_id + ")'><i class='fa fa-pencil'></i></button> <button type='button' class='button button_danger_dark' onclick='confirm_delete(" + customer_id + ")'><i class='fa fa-trash'></i></button> <button type='button' onclick='viewCustomerDetail(" + customer_id + ")' class='button button_default_dark'><i class='fa fa-eye'></i></button></td></tr>");
					}
					
					customerCount++;
				});

				if(customerCount > 0){
					$('#customerTableText').hide();
					$('#customerTable').show();
				} else {
					$('#customerTableText').show();
					$('#customerTable').hide();
				}
				
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

	function resetPassword(customerId)
	{
		$('#reset_customer_id').val(customerId);
		$('#reset_customer_wrapper').fadeIn();
	}

	function reset_password()
	{
		var customerId = $('#reset_customer_id').val();
		$.ajax({
			url:"<?= site_url('Customer/resetPassword') ?>",
			data:{
				id: customerId
			},
			success:function(response){
				refresh_view();
				if(response == 1){
					$("#reset_customer_id").val("");
					$('#reset_customer_wrapper').fadeOut();
				} else {
					$('#error_reset_customer').fadeTo(1, 500);
					setTimeout(function(){
						$('#error_reset_customer').fadeTo(0, 500);
					}, 1000)
				}
			}
		})
	}

	function viewCustomerDetail(n){
		window.location.href='<?= site_url('Customer/viewCustomerDetail/') ?>' + n;
	};
</script>
