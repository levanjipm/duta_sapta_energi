<head>
	<title>Expense class</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-briefcase'></i></a> / Expense class</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<button type='button' class='button button_default_dark' id='create_account_button'>Create new class</button>
		<br><br>
		<div id='pettyTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Name</th>
					<th>Information</th>
					<th>Type</th>
					<th>Action</th>
				</tr>
				<tbody id='pettyTableContent'></tbody>
			</table>
		</div>
		<p id='pettyTableText'>There is no class found.</p>
	</div>
</div>

<div class='alert_wrapper' id='addClassWrapper'>
	<button type='button' class='slide_alert_close_button'>&times </button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Add new class</h3>
		<hr>
		<form method='POST' id='addClassForm'>
			<label>Name</label>
			<input type='text' class='form-control' name='name' required>
			
			<label>Information</label>
			<textarea class='form-control' name='information' required style='resize:none'></textarea>
			
			<label>Parent class</label>
			<select class='form-control' name='parent_id' id='parent_id' readonly>
			</select>

			<label>Type</label>
			<select class='form-control' name='type' id='type'>
				<option value='1'>Operational</option>
				<option value='2'>Non operational</option>
			</select>
			
			<label><input type='checkbox' name='null_check' id='null_check' checked> No parent</label>
			<br>
			<button type='button' id='submitAddButton' class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>

			<div class='notificationText danger' id='failedInsertNotification'><p>Failed to insert item.</p></div>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='editClassWrapper'>
	<button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Edit class form</h3>
		<hr>
		<form method='POST' id='editClassForm'>
			<input type='hidden' id='update_id' name='id'>
			
			<label>Name</label>
			<input type='text' class='form-control' name='name' id='class_name' required>
			
			<label>Information</label>
			<textarea class='form-control' name='information' id='class_information' required style='resize:none'></textarea>
			
			<label>Parent class</label>
			<select class='form-control' name='parent_id' id='parent_update_id'>
			</select>

			<label>Type</label>
			<select class='form-control' name='type' id='type_update'>
				<option value='1'>Operational</option>
				<option value='2'>Non operational</option>
			</select>
			
			<label><input type='checkbox' name='null_check' id='null_check_update' checked> No parent</label>
			<br>
			<button type='button' class='button button_default_dark' id='submitUpdateButton'><i class='fa fa-long-arrow-right'></i></button>

			<div class='notificationText danger' id='failedUpdateNotification'><p>Failed to update item.</p></div>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='delete_class_wrapper'>
	<div class='alert_box_confirm_wrapper'>
		<div class='alert_box_confirm_icon'><i class='fa fa-trash'></i></div>
		<div class='alert_box_confirm'>
			<input type='hidden' id='delete_class_id'>
			<h3>Delete confirmation</h3>
			
			<p>You are about to delete this data.</p>
			<p>Are you sure?</p>
			<button class='button button_default_dark' onclick="$('#delete_class_wrapper').fadeOut()">Cancel</button>
			<button class='button button_danger_dark' onclick='delete_class()'>Delete</button>
			
			<br><br>
			
			<p style='font-family:museo;background-color:#f63e21;width:100%;padding:5px;color:white;position:relative;bottom:0;left:0;opacity:0' id='error_delete_class'>Deletation failed.</p>
		</div>
	</div>
</div>

<script>
	$('#create_account_button').click(function(){
		$('#addClassWrapper').fadeIn(300, function(){
			$('#addClassWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	});
	
	$('#addClassForm').validate();

	$('#submitAddButton').click(function(){
		if($('#addClassForm').valid()){
			$.ajax({
				url:'<?= site_url('Expense/insertItem') ?>',
				data:$('#addClassForm').serialize(),
				type:'POST',
				beforeSend:function(){
					$('button').attr('disabled', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					refresh_view();
					if(response == 1){
						$('#addClassWrapper .slide_alert_close_button').click();
					} else {
						$('#failedInsertItem').fadeIn(250);
						setTimeout(function(){
							$('#failedInsertItem').fadeOut(250);
						}, 1000);
					}
				}
			})
		}
	})
	
	$(document).ready(function(){
		refresh_view();
	})

	function refresh_view(){
		$.ajax({
			url:'<?= site_url('Expense/getItems') ?>',
			success:function(response){
				$('#pettyTableContent').html('');
				var classes	= response.classes;
				var countClass = 0;
				$.each(classes, function(index, value){
					var name			= value.name;
					var id				= value.id;
					var parent_id		= value.parent_id;
					var description		= value.description;
					var type			= value.type;
					if(type == 1){
						var typeText = "Operational";
					} else if(type == 2) {
						var typeText = "Non-operational";
					} else {
						var typeText = "<i>Not available</i>";
					}
					
					if(parent_id == null){
						$('#pettyTableContent').append("<tr id='parent_tr-" + id + "'><td><strong>" + name + "</strong></td><td>" + description + "</td><td>" + typeText + "</td><td><button type='button' class='button button_success_dark' title='Edit " + name + " class' onclick='open_edit_form(" + id + ")'><i class='fa fa-pencil'></i></button> <button class='button button_danger_dark' onclick='confirmDelete(" + id + ")'><i class='fa fa-trash'></i></button></td></tr>");
						
						$('#parent_id').append("<option value='" + id + "'>" + name + "</option>");
						$('#parent_update_id').append("<option value='" + id + "'>" + name + "</option>");
					} else {
						$('#parent_tr-' + parent_id).after("<tr><td style='padding-left:25px'>" + name + "</td><td>" + description + "</td><td>" + typeText + "</td><td><button type='button' class='button button_success_dark' title='Edit " + name + " class' onclick='open_edit_form(" + id + ")'><i class='fa fa-pencil'></i></button> <button class='button button_danger_dark' onclick='confirmDelete(" + id + ")'><i class='fa fa-trash'></i></button></td></tr>");
					}

					countClass++;
				});

				if(countClass > 0){
					$('#pettyTable').show();
					$('#pettyTableText').hide();
				} else {
					$('#pettyTable').hide();
					$('#pettyTableText').show();
				}
			}
		});
	}
	
	$('#null_check').change(function(){
		if($(this).prop('checked') == true){
			$('#parent_id').attr('readonly', true);
			$('#parent_id').attr('required', false);
			$('#type').attr('readonly', false);
		} else {
			$('#parent_id').attr('readonly', false);
			$('#parent_id').attr('required', true);
			$('#type').attr('readonly', true);
		}
	});
	
	$('#null_check_update').change(function(){
		if($(this).prop('checked') == true){
			$('#parent_update_id').attr('readonly', true);
			$('#parent_update_id').attr('required', false);
			$('#type_update').attr('readonly', false);
		} else {
			$('#parent_update_id').attr('readonly', false);
			$('#parent_update_id').attr('required', true);
			$('#type_update').attr('readonly', true);
		}
	});
	
	function open_edit_form(n){
		$.ajax({
			url:'<?= site_url('Expense/getById') ?>',
			data:{
				id:n
			},
			success:function(response){
				$('#update_id').val(n);
				
				var description	= response.description;
				$('#class_information').val(description);
				
				var name		= response.name;
				$('#class_name').val(name);
				
				var parent_id	= response.parent_id;
				var type		= response.type;
				if(parent_id == null){
					$('#null_check_update').attr('checked', true);
					$('#parent_update_id').attr('disabled', true);
					$('#parent_update_id').attr('required', false);
					$('#type_update').attr('readonly', true);
					$('#null_check_update').attr('disabled', true);

				} else {
					$('#null_check_update').attr('checked', false);
					$('#parent_update_id').attr('readonly', false);
					$('#parent_update_id').attr('required', true);
					$('#type_update').attr('readonly', false);
					$('#null_check_update').attr('disabled', false);
				}
				
				$('#editClassWrapper').fadeIn(300, function(){
					$('#editClassWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}
	
	$('#submitUpdateButton').click(function(){
		if($('#editClassForm').valid()){
			$.ajax({
				url:'<?= site_url('Expense/updateById') ?>',
				data:$('#editClassForm').serialize(),
				type:'POST',
				beforeSend:function(){
					$('button').attr('disabled', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					refresh_view();
					if(response == 1){
						$('#editClassWrapper .slide_alert_close_button').click();
					} else {
						$('#failedUpdateNotification').fadeIn(250);
						setTimeout(function(){
							$('#failedUpdateNotification').fadeOut(250);
						}, 1000)
					}
				}
			})
		}
	});

	function confirmDelete(n){
		$('#delete_class_id').val(n);
		$('#delete_class_wrapper').fadeIn();
	}

	function delete_class(){
		$.ajax({
			url:"<?= site_urul('Expense/deleteClassById') ?>",
			data:{
				id: $('#delete_class_id').val()
			},
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				refresh_view();
				if(response == 1){
					$('#delete_class_wrapper').fadeOut();
				} else {
					$('#error_delete_class').fadeTo(250, 1);
					setTimeout(function(){
						$('#error_delete_class').fadeTo(250, 0);
					}, 1000);
				}
			}
		})
	};
</script>