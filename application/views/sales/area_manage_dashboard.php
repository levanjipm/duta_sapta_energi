<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /Area</p>
	</div>
	<br>
	<div class='dashboard_in'>
<?php
	if(!empty($areas)){
?>
		<div id='area_table_wrapper'>
			<button type='button' class='button button_default_dark' id='add_area_button'>Add new area</button>
			<br><br>
			<table class='table table-bordered'>
				<tr>
					<th>Area name</th>
					<th>Action</th>
				</tr>
<?php
		foreach($areas as $area){
?>
				<tr>
					<td id='area_name-<?= $area->id ?>'><?= $area->name ?></td>
					<td>
						<button type='button' class='button button_success_dark' onclick='edit_area(<?= $area->id ?>)'><i class='fa fa-pencil'></i></button>
<?php
	if($area->customer == 0){
?>
						<button type='button' class='button button_danger_dark' onclick='delete_area(<?= $area->id ?>)'><i class='fa fa-trash'></i></button>
<?php
	} else {
?>
						<button type='button' class='button button_danger_dark' disabled><i class='fa fa-trash'></i></button>
<?php
	}
?>
						<button type='button' class='button button_default_dark' onclick='view_customer_by_area(<?= $area->id ?>, 1)'><i class='fa fa-eye'></i></button>
					</td>
				</tr>
<?php
		}
?>
			</table>
<?php
	}
?>
		</div>
		<div id='area_customer_wrapper' style='display:none'>
			<input type='hidden' id='area_id'>
			<button type='button' class='button button_danger_dark' onclick='change_view()' title='Back'><i class='fa fa-long-arrow-left'></i></button>
			<h2 style='font-family:bebasneue' id='area_name_p'></h2>
			<hr>
			<input type='text' class='form-control' id='search_bar'>
			<br>
			<table class='table table-bordered'>
				<tr>
					<th>Name</th>
					<th>Address</th>
					<th>City</th>
				</tr>
				<tbody id='customer_table'></tbody>
			</table>
			
			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
	</div>
</div>
<div class='alert_wrapper' id='add_area_wrapper'>
	<button class='alert_close_button'>&times </button>
	<div class='alert_box_default'>
		<h2 style='font-family:bebasneue'>Insert area</h2>
		<hr>
		<form action='<?= site_url('Area/insert_new_area') ?>' method='POST'>
			<label>Area name</label>
			<input type='text' class='form-control' name='area' required>
			
			<br>
			<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='edit_area_wrapper'>
	<button class='alert_close_button'>&times </button>
	<div class='alert_box_default'>
		<h2 style='font-family:bebasneue'>Edit area</h2>
		<hr>
		<form action='<?= site_url('Area/update_area') ?>' method='POST'>
			<input type='hidden' name='id' id='area_id'>
			<label>Area name</label>
			<input type='text' class='form-control' name='name' id='area_name' required>
			
			<br>
			<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='delete_area_wrapper'>
	<div class='alert_box_confirm'>
		<form action='<?= site_url('Area/delete') ?>' method='POST'>
			<input type='hidden' id='area_delete_id' name='id'>
			<img src='<?= base_url('assets/exclamation.png') ?>' style='width:40%'></img>
			<br><br>
			<h4 style='font-family:museo'>Are you sure?</h4>
			<button type='button' class='button button_danger_dark' id='close_notif_button'>Check again</button>
			<button class='button button_default_dark'>Confirm</button>
		</form>
	</div>
</div>
<script>
	function change_view(){
		$('#area_customer_wrapper').fadeOut(300);
		setTimeout(function(){
			$('#area_table_wrapper').fadeIn()
		}, 300);
	}
	
	$('#page').change(function(){
		var id	= $('#area_id').val();
		view_customer_by_area(id);
	});
	
	function view_customer_by_area(n, page = $('#page').val()){
		var id			= n;
		var area_name	= $('#area_name-' + id).text();
		$('#area_id').val(id);
		$('#area_name_p').html(area_name);
		$.ajax({
			url:'<?= site_url('Area/view_customer') ?>',
			data:{
				id:n,
				page:page,
				term:$('#search_bar').val()
			},
			success:function(response){
				$('#customer_table').html('');
				var customers		= response.customers;
				var pages			= response.pages;
				var page			= $('#page').val();
				
				$('#page').html('');
				
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
					
					$('#customer_table').append("<tr><td>" + customer_name + "</td><td>" + complete_address + "</td><td>" + customer_city + "</td></tr>");
				});
				
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option");
					}
				}
				
				$('#area_table_wrapper').fadeOut(300);
				setTimeout(function(){
					$('#area_customer_wrapper').fadeIn()
				}, 300);
				
			}
		})
	}
	
	$('#add_area_button').click(function(){
		$('#add_area_wrapper').fadeIn();
	});
	
	$('.alert_close_button').click(function(){
		$(this).parents('.alert_wrapper').fadeOut();
	});
	
	function edit_area(n){
		var area_name	= $('#area_name-' + n).text();
		
		$('#area_id').val(n);
		$('#area_name').val(area_name);
		
		$('#edit_area_wrapper').fadeIn();
	}
	
	function delete_area(n){
		$('#area_delete_id').val(n);
		$('#delete_area_wrapper').fadeIn();
	}
	
	$('#close_notif_button').click(function(){
		$('#delete_area_wrapper').fadeOut();
	});
</script>