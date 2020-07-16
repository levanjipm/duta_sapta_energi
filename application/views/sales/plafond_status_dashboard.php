<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Customer') ?>'>Customer </a>/ Customer plafond</p>
	</div>
	<br>
	<div  class='dashboard_in'>
		<input type='text' class='form-control' id='search_bar'>
		<br>
		<div  id='plafondSubmissionTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Customer</th>
					<th>Address</th>
					<th>Submitted by</th>
					<th>Action</th>
				</tr>
				<tbody id='plafond_table'></tbody>
			</table>
			
			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>

		<div id='plafondSubmissionText'>
			<p>There is no plafond submission to be confirmed.</p>
			<p><a href="<?= site_url("Plafond") ?>">Create a new one</a></p>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='plafond_confirm_wrapper'>
	<button type='button' class='slide_alert_close_button'>&times </button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>View Plafond submission</h3>
		<hr>
		<label>Customer</label>
		<a id='customer_href' target='blank' style='text-decoration:none;color:black'><p style='font-family:museo' id='customer_name_p'></p>
		<p style='font-family:museo' id='customer_address_p'></p>
		<p style='font-family:museo' id='customer_city_p'></p></a>
		
		<label>Plafond</label>
		<p style='font-family:museo' id='plafond_change_p'></p><br>
		
		<form action='<?= site_url('Plafond/confirmSubmission') ?>' method='POST'>
			<input type='hidden' id='submission_id' name='id'>
			<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
			<button type='button' class='button button_danger_dark' onclick='delete_plafond_submission()'><i class='fa fa-trash'></i></button>
		</form>
	</div>
</div>

<script>
	function delete_plafond_submission(){
		var id		= $('#submission_id').val();
		$.ajax({
			url:'<?= site_url('Plafond/deleteById') ?>',
			data:{
				id:id,
			},
			type:'POST',
			success:function(){
				window.location.reload();
			}
		});
	}
	
	$('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});

	$('#search_bar').change(function(){
		refresh_view(1);
	});
	
	$('#page').change(function(){
		refresh_view();
	});
	
	$(document).ready(function(){
		refresh_view();
	})
	
	function open_edit_form(n){
		$.ajax({
			url:'<?= site_url('Plafond/showById') ?>',
			data:{
				id:n,
			},
			success:function(response){
				var customer				= response.customer;
				var customer_name			= customer.name;
				var complete_address		= '';
				var customer_name			= customer.name;
				complete_address			+= customer.address;
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
				
				$('#customer_name_p').html(customer_name);
				$('#customer_address_p').html(complete_address);
				$('#customer_city_p').html(customer_city);
				
				var initial_plafond			= customer.plafond;
				
				var plafond					= response.plafond;
				var submitted_plafond		= plafond.submitted_plafond;
				var created_by				= plafond.created_by;
				var submission_id			= plafond.id;
				
				$('#submission_id').val(submission_id);
				
				$('#plafond_change_p').html('Rp. ' + numeral(initial_plafond).format('0,0.00') + ' - ' + numeral(submitted_plafond).format('0,0.00'));
				
				$('#customer_href').attr('href', '<?= site_url('Customer/view_detail/') ?>' + customer_id);
				
				$('#plafond_confirm_wrapper').fadeIn(300, function(){
					$('#plafond_confirm_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Plafond/showUnconfirmed') ?>',
			data:{
				page:page,
				term:$('#search_bar').val(),
			},
			success:function(response){
				var pages			= response.pages;
				var page			= $('#page').val();
				$('#page').html('');
				for(i = 1; i <= pages; i++){
					$('#page').append("<option value='" + i + "'>" + i + "</option>");
				};
				
				$('#plafond_table').html('');
				var customers = response.customers;
				var customerCount = customers.length;
				$.each(customers, function(index, customer){
					var customer_name			= customer.name;
					var complete_address		= '';
					var customer_name			= customer.name;
					complete_address			+= customer.address;
					var customer_city			= customer.city;
					var customer_number			= customer.number;
					var customer_rt				= customer.rt;
					var customer_rw				= customer.rw;
					var customer_postal			= customer.postal_code;
					var customer_block			= customer.block;
					var customer_id				= customer.id;
					var created_by				= customer.created_by;
					var submitted_date			= customer.submitted_date;
					var submission_id			= customer.submission_id;
		
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
<?php
	if($user_login->access_level > 2){
?>
					$('#plafond_table').append("<tr><td>" + customer_name + "</td><td><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><p>" + created_by + "</p><p>" + my_date_format(submitted_date) + "</p></td><td><button type='button' class='button button_default_dark' title='View plafond change submission for " + customer_name + "' onclick='open_edit_form(" + submission_id + ")'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
<?php
	} else {
?>
					$('#plafond_table').append("<tr><td>" + customer_name + "</td><td><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><button type='button' class='button button_default_dark'><i class='fa fa-long-arrow-right' title='View plafond change submission for " + customer_name + "' onclick='open_edit_form(" + customer_id + ")'></i></button></td></tr>");
<?php
	}
?>
				});

				if(customerCount > 0){
					$('#plafondSubmissionTable').show();
					$('#plafondSubmissionText').hide();
				} else {
					$('#plafondSubmissionTable').hide();
					$('#plafondSubmissionText').show();
				}
			}
		});
	}
</script>