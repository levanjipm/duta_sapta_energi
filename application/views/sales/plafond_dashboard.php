<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Customer') ?>'>Customer </a>/ Customer plafond</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<input type='text' class='form-control input-lg' id='search_bar' placeholder="Search customer">
		<br>
		<table class='table table-bordered'>
			<tr>
				<th>Name</th>
				<th>Address</th>
				<th>Plafond</th>
				<th>Action</th>
			</tr>
			<tbody id='table_plafond'></tbody>
		</table>
		
		<select class='form-control' id='page' style='width:100px;'>
			<option value='1'>1</option>
		</select>
	</div>
</div>

<div class='alert_wrapper' id='plafond_raise_wrapper'>
	<button type='button' class='slide_alert_close_button'>&times </button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Plafond change form</h3>
		<form action='<?= site_url('Customer/submit_plafond') ?>' method='POST' id='plafond_form'>
			<input type='hidden' id='customer_id' name='id'>
			
			<label>Customer</label>
			<p style='font-family:museo' id='customer_name_p'></p>
			<p style='font-family:museo' id='customer_address_p'></p>
			<p style='font-family:museo' id='customer_city_p'></p>
			
			<label>Current plafond</label>
			<p style='font-family:museo' id='customer_plafond_p'></p>
			
			<label>Submitted plafond</label>
			<input type='number' class='form-control' name='plafond' min='0' required>
			<br>
			<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<script>
	$('#plafond_form').validate();
	
	$('#page').change(function(){
		refresh_view();
	});
	
	$('#search_bar').change(function(){
		refresh_view(1);
	});
	
	refresh_view();
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Customer/show_plafonded_customers') ?>',
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
				$('#table_plafond').html('');
				
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
					var plafond					= customer.plafond;
		
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
					
					$('#table_plafond').append("<tr><td>" + customer_name + "</td><td><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td>Rp." + numeral(plafond).format('0,0.00') + "</td><td><button type='button' class='button button_default_dark' title='Plafond raise submission for " + customer_name + "' onclick='open_edit_form(" + customer_id + ")'><i class='fa fa-file-text-o'></i></button></td></tr>");
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
	}
	
	function open_edit_form(n){
		$.ajax({
			url:'<?= site_url('Customer/show_by_id') ?>',
			data:{
				id:n
			},
			success:function(response){
				var complete_address		= '';
				var customer_name			= response.name;
				complete_address			+= response.address;
				var customer_city			= response.city;
				var customer_number			= response.number;
				var customer_rt				= response.rt;
				var customer_rw				= response.rw;
				var customer_postal			= response.postal_code;
				var customer_block			= response.block;
				var customer_id				= response.id;
				var plafond					= response.plafond;
				
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
				$('#customer_plafond_p').html('Rp. ' + numeral(plafond).format('0,0.00'));
				
				$('#customer_id').val(customer_id);
				
				$('#plafond_raise_wrapper').fadeIn(300, function(){
					$('#plafond_raise_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}
	
	$('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
</script>