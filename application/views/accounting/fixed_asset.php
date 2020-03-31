<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Asset') ?>'>Asset</a> / Fixed</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<input type='text' class='form-control' id='search_bar'>
			<div class='input_group_append'>
				<button type='button' class='button button_default_dark' id='add_asset_button'>Add new asset</button>
			</div>
		</div>
		<br>
		<table class='table table-bordered'>
			<tr>
				<th>Name</th>
				<th>Description</th>
				<th>Value</th>
				<th>Action</th>
			</tr>
			<tbody id='asset_table'></tbody>
		</table>
		<select class='form-control' id='page' style='width:100px'>
			<option value='1'>1</option>
		</select>
	</div>
</div>

<div class='alert_wrapper' id='add_asset_wrapper'>
	<button type='button' class='alert_close_button'>&times </button>
	<div class='alert_box_default'>
		<h2 style='font-family:bebasneue'>Add new asset form</h2>
		<form action='<?= site_url('Asset/input_from_post') ?>' method='POST' id='add_asset_form'>
			<label>Date</label>
			<input type='date' class='form-control' name='date' required min='01-01-2019'>
			
			<label>Name</label>
			<input type='text' class='form-control' name='name' required>
			
			<label>Description</label>
			<textarea class='form-control' name='description' required minlength='25'></textarea>
			
			<label>Value</label>
			<input type='number' class='form-control' min='1' required name='value'>
			
			<label>Depreciation time</label>
			<select class='form-control' name='depreciation_time'>
				<option value=''>Do not depreciate</option>
				<option value='4'>4 years</option>
				<option value='8'>8 years</option>
				<option value='16'>16 years</option>
				<option value='20'>20 year</option>
			</select>
			
			<label>Type</label>
			<select class='form-control' name='type' required>
<?php
	foreach($types as $type){
?>
				<option value='<?= $type->id ?>'><?= $type->name ?></option>
<?php
	}
?>
			</select>
			<br>
			<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='edit_asset_wrapper'>
	<button type='button' class='alert_close_button'>&times </button>
	<div class='alert_box_default'>
		<h2 style='font-family:bebasneue'>Edit asset form</h2>
		<form action='<?= site_url('Asset/update_from_post') ?>' method='POST' id='edit_asset_form'>
			<input type='hidden' id='edit_id' name='id'>
			
			<label>Date</label>
			<input type='date' class='form-control' name='date' id='edit_date' required min='01-01-2019'>
			
			<label>Name</label>
			<input type='text' class='form-control' name='name' id='edit_name' required>
			
			<label>Description</label>
			<textarea class='form-control' name='description' required minlength='25' id='edit_description'></textarea>
			
			<label>Value</label>
			<input type='number' class='form-control' min='1' required name='value' id='edit_value'>
			
			<label>Depreciation time</label>
			<select class='form-control' name='depreciation_time' id='edit_time'>
				<option value=''>Do not depreciate</option>
				<option value='4'>4 years</option>
				<option value='8'>8 years</option>
				<option value='16'>16 years</option>
				<option value='20'>20 year</option>
			</select>
			
			<label>Type</label>
			<select class='form-control' name='type' id='edit_type' required>
<?php
	foreach($types as $type){
?>
				<option value='<?= $type->id ?>'><?= $type->name ?></option>
<?php
	}
?>
			</select>
			<br>
			<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<script>
	$('#add_asset_form').validate();
	$('#edit_asset_form').validate();
	
	refresh_view();
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Asset/show_items') ?>',
			data:{
				page:page,
				term:$('#search_bar').val()
			},
			success:function(response){
				var assets	= response.assets;
				$('#asset_table').html('');
				
				$.each(assets, function(index, asset){
					var id			= asset.id;
					var name		= asset.name;
					var description	= asset.description;
					var sold_date	= asset.sold_date;
					var value		= asset.value;
					
					if(sold_date == null){
						$('#asset_table').append("<tr><td>" + name + "</td><td>" + description + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td><button type='button' class='button button_success_dark' onclick='open_edit_form(" + id + ")'><i class='fa fa-pencil'></i></button></tr>");
					}
				});
			}
		});
	}
	
	$('#add_asset_button').click(function(){
		$('#add_asset_wrapper').fadeIn();
	});
	
	$('.alert_close_button').click(function(){
		$(this).parent().fadeOut();
	});
	
	function open_edit_form(n){
		$.ajax({
			url:'<?= site_url('Asset/show_by_id') ?>',
			data:{
				id:n
			},
			success:function(response){
				var id			= response.id;
				var name		= response.name;
				var description	= response.description;
				var sold_date	= response.sold_date;
				var date		= response.date;
				var value		= response.value;
				var type		= response.type;
				var time		= response.depreciation_time;
				
				$('#edit_id').val(id);
				$('#edit_date').val(date);
				$('#edit_name').val(name);
				$('#edit_description').val(description);
				$('#edit_value').val(value);
				$('#edit_type').val(type);
				$('#edit_time').val(time);
				
				$('#edit_asset_wrapper').fadeIn();
			}
		});
	}
	
	$('#search_bar').change(function(){
		refresh_view(1);
	});
	
	$('#page').change(function(){
		refresh_view();
	});
</script>