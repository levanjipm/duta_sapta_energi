<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Inventory') ?>' title='Inventory'><i class='fa fa-briefcase'></i></a> /Delivery order</p>
	</div>
	<div class='dashboard_in'>
		<div class='input_group'>
			<input type='text' class='form-control' id='search_bar'>
			<div class='input_group_append'>
				<button type='button' class='button button_default_dark' id='add_supplier_button'>Add supplier</button>
			</div>
		</div>
		<table class='table table-bordered'>
			<tr>
				<th>Supplier name</th>
				<th>Address</th>
				<th>City</th>
				<th>Action</th>
			</tr>
<?php
	foreach($suppliers as $supplier){
		$complete_address		= '';
		$supplier_name			= $supplier->name;
		$complete_address		.= $supplier->address;
		$supplier_city			= $supplier->city;
		$supplier_number		= $supplier->number;
		$supplier_rt			= $supplier->rt;
		$supplier_rw			= $supplier->rw;
		$supplier_postal		= $supplier->postal_code;
		$supplier_block			= $supplier->block;
		$supplier_id			= $supplier->id;
		
		if($supplier_number != NULL){
			$complete_address	.= ' No. ' . $supplier_number;
		}
		
		if($supplier_block != NULL){
			$complete_address	.= ' Blok ' . $supplier_block;
		}
		
		if($supplier_rt != '000'){
			$complete_address	.= ' RT ' . $supplier_rt;
		}
		
		if($supplier_rw != '000' && $supplier_rt != '000'){
			$complete_address	.= ' /RW ' . $supplier_rw;
		}
		
		if($supplier_postal != NULL){
			$complete_address	.= ', ' . $supplier_postal;
		}
			
?>
		<tr>
			<td><?= $supplier_name ?></td>
			<td><?= $complete_address ?></td>
			<td><?= $supplier_city ?></td>
			<td>
				<button type='button' class='button button_success_dark' onclick='open_edit_form(<?= $supplier_id ?>)'><i class='fa fa-pencil'></i></button>
				<button type='button' class='button button_danger_dark' onclick='open_delete_confirmation(<?= $supplier_id ?>)'><i class='fa fa-trash'></i></button>
				<button type='button' class='button button_default_light'><i class='fa fa-eye'></i></button>
			</td>
		</tr>
<?php
	}
?>
	</table>
	<select class='form-control' id='pagination' onchange='update_view()'>
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

<div class='alert_wrapper' id='add_supplier_wrapper'>
	<div class='alert_box_default'>
		<button class='alert_close_button'>&times</button>
		<form action='<?= site_url('Supplier/insert_new_supplier/') ?>' method='POST'>
		<h2 style='font-family:bebasneue'>Add supplier form</h2>
		<hr>
		
		<label>Supplier name</label>
		<input type='text' class='form-control' name='supplier_name' required>
		
		<label>Address</label>
		<textarea class='form-control' name='supplier_address' rows='3' style='resize:none' required></textarea>
		
		<label>Number</label>
		<input type='text' class='form-control' name='address_number'>
		
		<label>Block</label>
		<input type='text' class='form-control' name='address_block'>
		
		<label>Neighbourhood (RT)</label>
		<input type='text' class='form-control' name='address_rt' minlength='3' maxlength='3' required>
		
		<label>Hamlet (RW)</label>
		<input type='text' class='form-control' name='address_rw' minlength='3' maxlength='3' required>
		
		<label>Postal code</label>
		<input type='number' class='form-control' name='address_postal' minlength='3'>
		
		<label>City</label>
		<input type='text' class='form-control' name='supplier_city' required>
		
		<label>Phone number</label>
		<input type='text' class='form-control' name='supplier_phone' required>
		
		<label>PIC</label>
		<input type='text' class='form-control' name='supplier_pic'>
		
		<label>Tax identification number</label>
		<input type='text' class='form-control' name='supplier_npwp' id='supplier_npwp'>
		<script>
			$("#supplier_npwp").inputmask("99.999.999.9-999.999");
		</script>
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
		
		<input type='hidden' id='supplier_delete_id'>
	</div>
</div>

<div class='alert_wrapper' id='edit_supplier_wrapper'>
	<div class='alert_box_default'>
	</div>
</div>

<script>
	$('#add_supplier_button').click(function(){
		$('#add_supplier_wrapper').fadeIn();
	});
	
	function open_delete_confirmation(n){
		$('#delete_confirmation_wrapper').fadeIn();
		$('#supplier_delete_id').val(n);
	};
	
	function confirm_delete(){
		$.ajax({
			url:'<?= site_url('supplier/delete_supplier') ?>',
			type:'POST',
			data:{
				supplier_id: $('#supplier_delete_id').val()
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
			url:'<?= site_url('supplier/update_supplier_view') ?>',
			type:'POST',
			data:{
				supplier_id: n
			},
			beforeSend:function(){
				$('button').attr('disabled',true);
			},
			success:function(response){
				$('button').attr('disabled',false);
				$('#edit_supplier_wrapper .alert_box_default').html(response);
				$('#edit_supplier_wrapper').fadeIn();
			}
		})
	};
	
	$('.alert_close_button').click(function(){
		$(this).parents('.alert_wrapper').fadeOut();
	});
	
	function refresh_view()
	{
	}
</script>