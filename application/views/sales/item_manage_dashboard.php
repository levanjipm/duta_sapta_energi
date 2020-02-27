<div class='dashboard'>
	<h2 style='font-family:bebasneue'>Item</h2>
	<hr>
	<button type='button' class='button button_default_light' id='add_item_button'>Add item</button>
	<br><br>
	<input type='text' class='form_control' id='search_bar'>
	<br><br>
	<div id='customer_table_view_pane'>
	<table class='table table-bordered'>
		<tr>
			<th>Reference</th>
			<th>Name</th>
			<th>Action</th>
		</tr>
<?php
	foreach($items as $item){
		$item_id		= $item->id;
		$reference		= $item->reference;
		$item_name		= $item->name;
		$item_type		= $item->type;			
?>
		<tr>
			<td><?= $reference ?></td>
			<td><?= $item_name ?></td>
			<td>
				<button type='button' class='button button_success_dark' onclick='open_edit_form(<?= $item_id ?>)'><i class='fa fa-pencil'></i></button>
				<button type='button' class='button button_danger_dark' onclick='open_delete_confirmation(<?= $item_id ?>)'><i class='fa fa-trash'></i></button>
				<button type='button' class='button button_default_light'><i class='fa fa-eye'></i></button>
			</td>
		</tr>
<?php
	}
?>
	</table>
	<select class='form-control' id='page' onchange='update_view()' style='width:100px'>
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

<div class='alert_wrapper' id='add_item_wrapper'>
	<div class='alert_box_default'>
		<button class='alert_close_button'>&times</button>
		<form action='<?= site_url('Item/insert_new_item/') ?>' method='POST'>
		<h2 style='font-family:bebasneue'>Add item form</h2>
		<hr>
		
		<label>Reference</label>
		<input type='text' class='form-control' name='item_reference' required>
		
		<label>Name</label>
		<textarea class='form-control' name='item_name' rows='3' style='resize:none' required></textarea>
		
		<label>Type</label>
		<select class='form-control' name='class_id'>
<?php
	foreach($classes as $class){
?>
			<option value='<?= $class->id ?>'><?= $class->name ?></option>
<?php
	}
?>
		</select>
		
		<label>Pricelist</label>
		<input type='number' class='form-control' name='price_list'>
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

<div class='alert_wrapper' id='edit_item_wrapper'>
	<div class='alert_box_default'>
	</div>
</div>

<script>
	$('#add_item_button').click(function(){
		$('#add_item_wrapper').fadeIn();
	});
	
	function open_edit_form(n){
		$.ajax({
			url:'<?= site_url('Item/update_item_view') ?>',
			type:'POST',
			data:{
				item_id: n
			},
			beforeSend:function(){
				$('button').attr('disabled',true);
			},
			success:function(response){
				$('button').attr('disabled',false);
				$('#edit_item_wrapper .alert_box_default').html(response);
				$('#edit_item_wrapper').fadeIn();
			}
		})
	};
	
	$('.alert_close_button').click(function(){
		$(this).parents('.alert_wrapper').fadeOut();
	});
	
	function update_view(){
		$.ajax({
			url:'<?= site_url('Item/update_view_page') ?>',
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
			url:'<?= site_url('Item/update_view_page') ?>',
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