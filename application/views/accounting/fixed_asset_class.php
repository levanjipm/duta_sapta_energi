<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Asset') ?>'>Asset</a> / Class</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<input type='text' class='form-control' id='search_bar'>
			<div class='input_group_append'>
				<button type='button' class='button button_default_dark' id='add_asset_button'>Add new asset class</button>
			</div>
		</div>
		<br>
		<table class='table table-bordered'>
			<tr>
				<th>Name</th>
				<th>Description</th>
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
		<form action='<?= site_url('Asset/input_type_from_post') ?>' method='POST' id='add_asset_form'>
			<label>Name</label>
			<input type='text' class='form-control' name='name' required>
			
			<label>Description</label>
			<textarea class='form-control' name='description' required minlength='25'></textarea>
			<br>
			<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='edit_asset_wrapper'>
	<button type='button' class='alert_close_button'>&times </button>
	<div class='alert_box_default'>
		<h2 style='font-family:bebasneue'>Edit asset form</h2>
		<form action='<?= site_url('Asset/update_type') ?>' method='POST' id='edit_asset_form'>
			<input type='hidden' id='edit_id' name='id'>
			
			<label>Name</label>
			<input type='text' class='form-control' name='name' id='name_edit' required>
			
			<label>Description</label>
			<textarea class='form-control' name='description' id='description_edit' required minlength='25'></textarea>
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
			url:'<?= site_url('Asset/show_type_limited') ?>',
			data:{
				page:page,
				term:$('#search_bar').val()
			},
			success:function(response){
				$('#asset_table').html('');
				var types	= response.types;
				$.each(types, function(index, type){
					var name		= type.name;
					var description	= type.description;
					var id			= type.id;
					
					$('#asset_table').append("<tr><td>" + name + "</td><td>" + description + "</td><td><button type='button' class='button button_success_dark' onclick='open_edit_form(" + id + ")'><i class='fa fa-pencil'></i></button></tr>");
				});
				
				$('#page').html('');
				
				var pages		= response.pages;
				for(i = 1; i <= pages; i ++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		});
	}
	
	function open_edit_form(n){
		$.ajax({
			url:'<?= site_url('Asset/show_type_by_id') ?>',
			data:{
				id:n
			},
			success:function(response){
				var id		= response.id;
				var name	= response.name;
				var description	= response.description;
				$('#name_edit').val(name);
				$('#description_edit').val(description);
				$('#edit_id').val(id);
				
				$('#edit_asset_wrapper').fadeIn();
			}
		});
	}
	
	$('#add_asset_button').click(function(){
		$('#add_asset_wrapper').fadeIn();
	});
	
	$('.alert_close_button').click(function(){
		$(this).parent().fadeOut();
	});
	
	$('#page').change(function(){
		refresh_view();
	});
	
	$('#search_bar').change(function(){
		refresh_view(1);
	});
</script>