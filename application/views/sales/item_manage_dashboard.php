<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /Items</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<input type='text' class='form-control' id='search_bar'>
			<div class='input_group_append'>
				<button type='button' class='button button_default_dark' id='add_item_button'>Add item</button>
			</div>
		</div>
		<br>
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Action</th>
			</tr>
			<tbody id='item_table'></tbody>
		</table>
		<select class='form-control' id='page' style='width:100px'>
			<option value='1' selected>1</option>
		</select>
	</div>
</div>

<div class='alert_wrapper' id='add_item_wrapper'>
	<button class='slide_alert_close_button'>&times</button>
	<div class='alert_box_slide'>
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
	<button type='button' class='slide_alert_close_button'>&times </button>
	<div class='alert_box_slide'>
		<form id='update_item_form'>
			<h3 style='font-family:bebasneue'>Edit item</h3>
			<hr>
			<input type='hidden' id='item_id' name='id'>
			<label>Reference</label>
			<input type='text' class='form-control' id='reference_edit' name='reference' required>
			
			<label>Description</label>
			<textarea class='form-control' id='description_edit' name='name' required></textarea>
			
			<label>Price list</label>
			<input type='number' class='form-control' id='price_list_edit' name='price_list' min='1'>
			
			<label>Type</label>
			<select class='form-control' id='item_type' name='type' required>
<?php
	foreach($classes as $class){
?>
				<option value='<?= $class->id ?>'><?= $class->name ?></option>
<?php
	}
?>
			</select>
			
			<input type='checkbox' id='is_notified'>
			<label>Notify if stock reaches minimum</label>
			
			<br>
			
			<label>Confidence level</label>
			<input type='number' class='form-control' min='0' max='99' id='confidence_level'>
			<br>
			<button class='button button_default_dark' type='button' id='submit_update_item_button'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<script>
	$(document).ready(function(){
		refresh_view();
	});
	
	$('#page').change(function(){
		refresh_view();
	});
	
	$('#search_bar').change(function(){
		refresh_view(1);
	});
	
	$('#add_item_button').click(function(){
		$('#add_item_wrapper').fadeIn(300, function(){
			$('#add_item_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	});
	
	$('#update_item_form').validate();
	
	$("#update_item_form").submit(function(e){
		return false;
	});
	
	$('#submit_update_item_button').click(function(){
		if($('#update_item_form').valid()){
			if($('#is_notified').attr('checked', true)){
				var is_notified = 1;
			} else {
				var is_notified = 0;
			}
			$.ajax({
				url:'<?= site_url('Item/update_item') ?>',
				data:{
					id: $('#item_id').val(),
					reference: $('#reference_edit').val(),
					name: $('#description_edit').val(),
					price_list: $('#price_list_edit').val(),
					type: $('#item_type').val(),
					is_notified: is_notified,
					confidence_level: $('#confidence_level').val()					
				},
				type:'POST',
				success:function(response){
					refresh_view();
					$('#edit_item_wrapper .slide_alert_close_button').click();
				}
			});
		};
	});
	
	function open_edit_form(item_id){
		$.ajax({
			url:'<?= site_url('Item/get_item_by_id') ?>',
			type:'GET',
			data:{
				id: item_id
			},
			beforeSend:function(){
				$('button').attr('disabled',true);
			},
			success:function(response){
				$('button').attr('disabled',false);
				$('#item_id').val(item_id);
				var reference	= response.reference;
				var name		= response.name;
				var price_list	= response.price_list;
				var type		= response.type;
				var confidence_level = response.confidence_level;
				var is_notified_stock = response.is_notified_stock;
				
				if(is_notified_stock == 1){
					$('#is_notified').attr("checked", true);
				} else {
					$('#is_notified').attr('checked', false);
				}
				
				$('#confidence_level').val(confidence_level);
				
				$('#reference_edit').val(reference);
				$('#description_edit').val(name);
				$('#price_list_edit').val(price_list);
				$('#item_type').val(type);
				
				$('#edit_item_wrapper').fadeIn(300, function(){
					$('#edit_item_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	};
	
	$('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Item/search_item_cart') ?>',
			data:{
				term:$('#search_bar').val(),
				page:page
			},
			type:'GET',
			beforeSend:function(){
				$('#item_table').html('');
			},
			success:function(response){
				var item_array	= response.items;
				$.each(item_array, function(index, item){
					var reference	= item.reference;
					var description	= item.name;
					var id			= item.item_id;
					$('#item_table').append("<tr><td>" + reference + "</td><td>" + description + "</td><td><button type='button' class='button button_success_dark' onclick='open_edit_form(" + id + ")' title='Edit " + reference + "'><i class='fa fa-pencil'></i></button> <button type='button' class='button button_danger_dark' onclick='open_delete_confirmation(" + id + ")' title='Delete " + reference + "'><i class='fa fa-trash'></i></button> <button type='button' class='button button_default_dark' title='View " + reference + "'><i class='fa fa-eye'></i></button>");
				});
				
				
				$('#page').html('');
				var pages		= response.pages;
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			},
			
		});
	};
</script>