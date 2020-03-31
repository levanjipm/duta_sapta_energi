<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /Item classes</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<input type='text' class='form-control' id='search_bar'>
			<div class='input_group_append'>
				<button type='button' class='button button_default_dark' id='add_item_class_button'>Add item class</button>
			</div>
		</div>
		<br>
		<table class='table table-bordered'>
			<tr>
				<th>Name</th>
				<th>Description</th>
				<th>Action</th>
			</tr>
<?php
	foreach($classes as $class){
		$class_id		= $class->id;
		$class_name		= $class->name;		
		$description	= $class->description;		
?>
			<tr>
				<td id='name-<?= $class_id ?>'><?= $class_name ?></td>
				<td id='description-<?= $class_id ?>'><?= $description ?></td>
				<td>
					<button type='button' class='button button_success_dark' onclick='open_edit_form(<?= $class_id ?>)'><i class='fa fa-pencil'></i></button>
					<button type='button' class='button button_danger_dark' onclick='open_delete_confirmation(<?= $class_id ?>)'><i class='fa fa-trash'></i></button>
					<button type='button' class='button button_default_light'><i class='fa fa-eye'></i></button>
				</td>
			</tr>
<?php
	}
?>
		</table>
	</div>
</div>

<div class='alert_wrapper' id='add_item_class_wrapper'>
	<button class='alert_close_button'>&times</button>
	<div class='alert_box_default'>
		<form action='<?= site_url('Item_class/insert_new_class/') ?>' method='POST'>
		<h2 style='font-family:bebasneue'>Add item class form</h2>
		<hr>
		
		<label>Name</label>
		<input type='text' class='form-control' name='item_class_name' required>
		
		<label>Description</label>
		<textarea class='form-control' name='item_class_description' rows='3' style='resize:none' required></textarea>
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
		
		<input type='hidden' id='item_class_id'>
	</div>
</div>

<div class='alert_wrapper' id='edit_item_class_wrapper'>
	<button type='button' class='alert_close_button'>&times </button>
	<div class='alert_box_default'>
		<h2 style='font-family:bebasneue'>Edit item class</h2>
		<hr>
		<form action='<?= site_url('Item_class/update_item_class') ?>' method='POST'>
			<input type='hidden' id='item_class_edit_id' name='id'>
			<label>Name</label>
			<input type='text' class='form-control' id='item_class_name' name='name'>
			
			<label>Description</label>
			<textarea class='form-control' id='item_class_description' name='description'></textarea>
			<br>
			
			<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<script>
	$('#add_item_class_button').click(function(){
		$('#add_item_class_wrapper').fadeIn();
	});
	
	function open_delete_confirmation(n){
		$('#delete_confirmation_wrapper').fadeIn();
		$('#item_class_id').val(n);
	};
	
	function confirm_delete(){
		$.ajax({
			url:'<?= site_url('Item_class/delete_item_class') ?>',
			type:'POST',
			data:{
				item_class_id: $('#item_class_id').val()
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
		var item_name_existing			= $('#name-' + n).text();
		var item_description_existing	= $('#description-' + n).text();
		$('#item_class_edit_id').val(n);
		$('#item_class_name').val(item_name_existing);
		$('#item_class_description').val(item_description_existing);
		
		$('#edit_item_class_wrapper').fadeIn();
	};
	
	$('.alert_close_button').click(function(){
		$(this).parent().fadeOut();
	});
</script>