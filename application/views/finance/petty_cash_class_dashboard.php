<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-briefcase'></i></a> / Expense class</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<button type='button' class='button button_default_dark' id='create_account_button'>Create new class</button>
		<br><br>
		
		<table class='table table-bordered'>
			<tr>
				<th>Name</th>
				<th>Information</th>
				<th>Action</th>
			</tr>
			<tbody id='petty_table'></tbody>
		</table>
	</div>
</div>

<div class='alert_wrapper' id='add_class_wrapper'>
	<button type='button' class='alert_close_button'>&times </button>
	<div class='alert_box_default'>
		<h2 style='font-family:bebasneue'>Create new class form</h2>
		<hr>
		<form action='<?= site_url('Petty_cash/add_class') ?>' method='POST' id='add_class_form'>
			<label>Name</label>
			<input type='text' class='form-control' name='name' required>
			
			<label>Information</label>
			<textarea class='form-control' name='information' required style='resize:none'></textarea>
			
			<label>Type</label>
			<select class='form-control' name='type'>
				<option value='1'>Expense</option>
				<option value='2'>Income</option>
			<select>
			
			<label>Parent class</label>
			<select class='form-control' name='parent_id' id='parent_id' readonly>
			</select>
			
			<label><input type='checkbox' name='null_check' id='null_check' checked> No parent</label>
			<br>
			<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>
<script>
	$('#create_account_button').click(function(){
		$('#add_class_wrapper').fadeIn();
	});
	
	$('.alert_close_button').click(function(){
		$(this).parent().fadeOut();
	});
	
	$('#add_class_form').validate();
	
	refresh_view();

	function refresh_view(){
		$.ajax({
			url:'<?= site_url('Petty_cash/view_class') ?>',
			success:function(response){
				$('#petty_table').html('');
				var classes	= response.classes;
				$.each(classes, function(index, value){
					var name			= value.name;
					var id				= value.id;
					var parent_id		= value.parent_id;
					var description		= value.description;
					
					if(parent_id == null){
						$('#petty_table').append("<tr id='parent_tr-" + id + "'><td><strong>" + name + "</strong></td><td>" + description + "</td><td><button type='button' class='button button_success_dark' title='Edit " + name + " class' onclick='open_edit_form(" + id + ")'><i class='fa fa-pencil'></i></button></td></tr>");
						$('#parent_id').append("<option value='" + id + "'>" + name + "</option>");
					} else {
						$('#parent_tr-' + parent_id).after("<tr><td style='padding-left:25px'>" + name + "</td><td>" + description + "</td><td><button type='button' class='button button_success_dark' title='Edit " + name + " class' onclick='open_edit_form(" + id + ")'><i class='fa fa-pencil'></i></button></td></tr>");
					}
					
					
				});
			}
		});
	}
	
	$('#null_check').change(function(){
		if($(this).prop('checked') == true){
			$('#parent_id').attr('readonly', true);
			$('#parent_id').attr('required', false);
		} else {
			$('#parent_id').attr('readonly', false);
			$('#parent_id').attr('required', true);
		}
	});
</script>