<style>
	#page{
		width:100px;
	}
</style>
<div class='dashboard'>
	<h2 style='font-family:bebasneue'>Customer</h2>
	<hr>
	<button type='button' class='button button_default_light' id='add_customer_button'>Add customer</button>
	<br><br>
	<input type='text' class='form_control' id='search_bar'>
	<br><br>
	<div id='customer_table_view_pane'>
	<table class='table table-bordered'>
		<tr>
			<th>Customer name</th>
			<th>Address</th>
			<th>City</th>
			<th>Action</th>
		</tr>
<?php
	foreach($customers as $customer){
		$complete_address		= '';
		$customer_name			= $customer->name;
		$complete_address		.= $customer->address;
		$customer_city			= $customer->city;
		$customer_number		= $customer->number;
		$customer_rt			= $customer->rt;
		$customer_rw			= $customer->rw;
		$customer_postal		= $customer->postal_code;
		$customer_block			= $customer->block;
		$customer_id			= $customer->id;
		
		if($customer_number != NULL){
			$complete_address	.= ' No. ' . $customer_number;
		}
		
		if($customer_block != NULL){
			$complete_address	.= ' Blok ' . $customer_block;
		}
		
		if($customer_rt != '000'){
			$complete_address	.= ' RT ' . $customer_rt;
		}
		
		if($customer_rw != '000' && $customer_rt != '000'){
			$complete_address	.= ' /RW ' . $customer_rw;
		}
		
		if($customer_postal != NULL){
			$complete_address	.= ', ' . $customer_postal;
		}
			
?>
		<tr>
			<td><?= $customer_name ?></td>
			<td><?= $complete_address ?></td>
			<td><?= $customer_city ?></td>
			<td>
				<button type='button' class='button button_success_dark' onclick='open_edit_form(<?= $customer_id ?>)'><i class='fa fa-pencil'></i></button>
				<button type='button' class='button button_danger_dark' onclick='open_delete_confirmation(<?= $customer_id ?>)'><i class='fa fa-trash'></i></button>
				<button type='button' class='button button_default_light'><i class='fa fa-eye'></i></button>
			</td>
		</tr>
<?php
	}
?>
	</table>
	<select class='form-control' id='page' onchange='update_view()'>
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
	<div class='alert_box_default'>
		<button class='alert_close_button'>&times</button>
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
	<div class='alert_box_default'>
	</div>
</div>

<script>
	$('#add_customer_button').click(function(){
		$('#add_customer_wrapper').fadeIn();
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
				$('#edit_customer_wrapper .alert_box_default').html(response);
				$('#edit_customer_wrapper').fadeIn();
			}
		})
	};
	
	$('.alert_close_button').click(function(){
		$(this).parents('.alert_wrapper').fadeOut();
	});
	
	function update_view(){
		$.ajax({
			url:'<?= site_url('Customer/update_view_page') ?>',
			data:{
				term:$('#search_bar').val(),
				page:$('#page').val()
			},
			type:'GET',
			beforeSend:function(){
				$('#customer_table_view_pane').html('');
			},
			success:function(response){
				$('#customer_table_view_pane').html(response);
			},
			
		});
	};
	
	$('#search_bar').change(function(){
		$.ajax({
			url:'<?= site_url('Customer/update_view_page') ?>',
			data:{
				term:$('#search_bar').val(),
				page:1
			},
			type:'GET',
			beforeSend:function(){
				$('#customer_table_view_pane').html('');
			},
			success:function(response){
				$('#customer_table_view_pane').html(response);
			},
			
		});
	});
</script>