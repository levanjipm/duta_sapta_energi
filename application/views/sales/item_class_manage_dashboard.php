<div class='dashboard'>
	<h2 style='font-family:bebasneue'>Customer</h2>
	<hr>
	<button type='button' class='button button_default_light' id='add_item_class_button'>Add item class</button>
	<br><br>
	<input type='text' class='form_control' id='search_bar'>
	<br><br>
	<div id='customer_table_view_pane'>
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
			<td><?= $class_name ?></td>
			<td><?= $description ?></td>
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
	<div class='alert_box_default'>
		<button class='alert_close_button'>&times</button>
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
	<div class='alert_box_default'>
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
		$.ajax({
			url:'<?= site_url('Customer/update_customer_view') ?>',
			type:'POST',
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
</script>