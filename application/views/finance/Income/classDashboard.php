<head>
	<title>Income class</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-briefcase'></i></a> / Income class</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<button type='button' class='button button_default_dark' id='create_account_button'><i class='fa fa-plus'></i> Create new class</button>
		<br><br>
		<input type='text' class='form-control' id='search_bar'>
		<br>
		<table class='table table-bordered'>
			<tr>
				<th>Name</th>
				<th>Information</th>
				<th>Action</th>
			</tr>
			<tbody id='income_table'></tbody>
		</table>
		
		<select class='form-control' id='page' style='width:100px'>
			<option value='1'>1</option>
		</select>
	</div>
</div>

<div class='alert_wrapper' id='add_class_wrapper'>
	<button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Create new class form</h2>
		<hr>
		<form id='addClassForm'>
			<label>Name</label>
			<input type='text' class='form-control' name='name' required>
			
			<label>Information</label>
			<textarea class='form-control' name='information' required style='resize:none'></textarea>
			<br>
			
			<button type='button' class='button button_default_dark' id='addClassButton'><i class='fa fa-long-arrow-right'></i></button>
			<br>
			<div class='notificationText danger' id='failedInsertClass'><p>Failed to insert item.</p></div>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='edit_class_wrapper'>
	<button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Edit class form</h2>
		<hr>
		<form action='<?= site_url('Income/update_class') ?>' method='POST' id='editClassForm'>
			<input type='hidden' id='income_id' name='id'>
			
			<label>Name</label>
			<input type='text' class='form-control' id='income_name' name='name' required>
			
			<label>Information</label>
			<textarea class='form-control' name='information' id='income_information' required style='resize:none'></textarea>
			<br>
			<button type='button' class='button button_default_dark' id='editClassButton'><i class='fa fa-long-arrow-right'></i></button>
			<br>
			<div class='notificationText danger' id='failedUpdateClass'><p>Failed to update item</p></div>
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
			<button class='button button_danger_dark' onclick='deleteClass()'>Delete</button>
			
			<br><br>
			
			<p style='font-family:museo;background-color:#f63e21;width:100%;padding:5px;color:white;position:relative;bottom:0;left:0;opacity:0' id='error_delete_class'>Deletation failed.</p>
		</div>
	</div>
</div>

<script>
	$('#editClassForm').validate();
	$('#addClassForm').validate();
	
	$('#search_bar').change(function(){
		refresh_view(1);
	});
	
	$('#page').change(function(){
		refresh_view();
	});
	
	$(document).ready(function(){
		refresh_view();
	});
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Income/getItems') ?>',
			data:{
				page:page,
				term:$('#search_bar').val()
			},
			success:function(response){
				var classes		= response.classes;
				var pages		= response.pages;
				
				$('#income_table').html('');
				
				$.each(classes, function(index, value){
					var name		= value.name;
					var description	= value.description;
					var id			= value.id;
					
					$('#income_table').append("<tr><td>" + name + "</td><td>" + description + "</td><td><button type='button' class='button button_success_dark' onclick='open_edit_form(" + id + ")'><i class='fa fa-pencil'></i></button> <button class='button button_danger_dark' onclick='confirmDelete(" + id + ")'><i class='fa fa-trash'></i></button></td></tr>");
				});
				
				$('#page').html('');
				
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		});
	}

	$('#addClassButton').click(function(){
		$.ajax({
			url:"<?= site_url('Income/insertItem') ?>",
			data:$('#addClassForm').serialize(),
			type:"POST",
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				refresh_view();
				if(response == 1){
					$('#add_class_wrapper .slide_alert_close_button').click();
				} else {
					$('#failedInsertClass').fadeIn(250);
					setTimeout(function(){
						$('#failedInsertClass').fadeOut(250);
					}, 1000)
				}
			}
		})
	})
	
	function open_edit_form(income_id){
		$.ajax({
			url:'<?= site_url('Income/getById') ?>',
			data:{
				id:income_id
			},
			type:'POST',
			success:function(response){
				var id = response.id;
				var name = response.name;
				var description = response.description;
				
				$('#income_id').val(id);
				$('#income_name').val(name);
				$('#income_information').val(description);
				$('#edit_class_wrapper').fadeIn(300, function(){
					$('#edit_class_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}

	$('#editClassButton').click(function(){
		if($('#editClassForm').valid()){
			$.ajax({
				url:'<?= site_url('Income/updateById') ?>',
				data:$('#editClassForm').serialize(),
				type:"POST",
				beforeSend:function(){
					$("button").attr('disabled', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					refresh_view();
					if(response == 1){
						$('#edit_class_wrapper .slide_alert_close_button').click();
					} else {
						$('#failedUpdateClass').fadeIn(250);
						setTimeout(function(){
							$('#failedUpdateClass').fadeOut(250);
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

	function deleteClass(){
		$.ajax({
			url:'<?= site_url('Income/deleteById') ?>',
			data:{
				id:$('#delete_class_id').val()
			},
			type:"POST",
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
	}
	
	$('#create_account_button').click(function(){
		$('#add_class_wrapper').fadeIn(300, function(){
			$('#add_class_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	});
</script>
