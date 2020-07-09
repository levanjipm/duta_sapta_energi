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
			<tbody id='item_class_table'></tbody>
		</table>
		
		<select class='form-control' id='page' style='width:100px'>
			<option value='1'>1</option>
		</select>
	</div>
</div>

<div class='alert_wrapper' id='add_item_class_wrapper'>
	<button class='slide_alert_close_button'>&times </button>
	<div class='alert_box_slide'>
		<form id='add_item_form'>
			<h3 style='font-family:bebasneue'>Add item class</h3>
			<hr>
			
			<label>Name</label>
			<input type='text' class='form-control' id='item_class_name' required>
			
			<label>Description</label>
			<textarea class='form-control' id='item_class_description' rows='3' style='resize:none' required></textarea>
			<div style='padding:2px 10px;background-color:#ffc107;width:100%;display:none;' id='error_insert_item_class'><p style='font-family:museo'><i class='fa fa-exclamation-triangle'></i> Insert data failed.</p></div><br>
			<br>
			<button class='button button_default_dark' type='button' id='add_item_button'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='edit_item_class_wrapper'>
	<button type='button' class='slide_alert_close_button'>&times </button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Edit item class</h3>
		<hr>
		<form id='edit_item_form'>
			<input type='hidden' id='item_class_edit_id'>
			<label>Name</label>
			<input type='text' class='form-control' id='item_class_name_edit'>
			
			<label>Description</label>
			<textarea class='form-control' id='item_class_description_edit' rows='3' style='resize:none'></textarea>
			<div style='padding:2px 10px;background-color:#ffc107;width:100%;display:none;' id='error_update_item_class'><p style='font-family:museo'><i class='fa fa-exclamation-triangle'></i> Insert data failed.</p></div><br>
			<br>
			
			<button class='button button_default_dark' type='button' id='edit_item_button'><i class='fa fa-long-arrow-right'></i></button>
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
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Item_class/show_items') ?>',
			data:{
				term:$('#search_bar').val(),
				page:page
			},
			success:function(response){
				console.log(response);
				$('#item_class_table').html('');
				var items		= response.items;
				$.each(items, function(index, item){
					var id 				= item.id;
					var name			= item.name;
					var description		= item.description;
					var quantity		= item.quantity;
					
					if(quantity == 0){
						$('#item_class_table').append("<tr><td id='name-" + id + "'>" + name + "</td><td id='description-" + id + "'>" + description + "</td>" +
							"<td><button type='button' class='button button_success_dark' onclick='open_edit_form(" + id + ")'><i class='fa fa-pencil'></i></button> " +
							"<button type='button' class='button button_danger_dark' onclick='open_delete_confirmation(" + id + ")'><i class='fa fa-trash'></i></button> " +
							"<button type='button' class='button button_default_dark'><i class='fa fa-eye'></i></button></td></tr>");
					} else {
						$('#item_class_table').append("<tr><td id='name-" + id + "'>" + name + "</td><td id='description-" + id + "'>" + description + "</td>" +
							"<td><button type='button' class='button button_success_dark' onclick='open_edit_form(" + id + ")'><i class='fa fa-pencil'></i></button> " +
							"<button type='button' class='button button_danger_dark' disabled><i class='fa fa-trash'></i></button> " +
							"<button type='button' class='button button_default_dark'><i class='fa fa-eye'></i></button></td></tr>");
					}
				});
				
				var pages		= response.pages;
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
	
	$('#add_item_class_button').click(function(){
		$('#add_item_class_wrapper').fadeIn(300, function(){
			$('#add_item_class_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	});
	
	$('#add_item_form').on('submit', function(){
		return false;
	});
	
	$('#edit_item_form').on('submit', function(){
		return false;
	});
	
	function open_delete_confirmation(n){
		$('#delete_confirmation_wrapper').fadeIn();
		$('#item_class_id').val(n);
	};
	
	$('#add_item_button').click(function(){
		$.ajax({
			url:'<?= site_url('Item_class/insert_from_post') ?>',
			data:{
				name: $('#item_class_name').val(),
				description: $('#item_class_description').val()
			},
			type:"POST",
			success:function(response){
				if(response == 1){
					$('#item_class_name').val('');
					$('#item_class_description').val('');
					
					refresh_view();
					$('#add_item_class_button .slide_alert_close_button').click();
				} else {
					$('#error_insert_item_class').show();
					setTimeout(function(){
						$('#error_insert_item_class').fadeOut();
					}, 300);
				}
			}
		});
	});
	
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
			url:"<?= site_url('Item_class/get_by_id') ?>",
			data:{
				id: n
			},
			success:function(response){
				var name = response.name;
				var description = response.description;
				
				$('#item_class_edit_id').val(n);
				$('#item_class_name_edit').val(name);
				$('#item_class_description_edit').val(description);
				
				$('#edit_item_class_wrapper').fadeIn(300, function(){
					$('#edit_item_class_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	};
	
	$('#edit_item_button').click(function(){
		$.ajax({
			url:'<?= site_url('Item_class/update_from_post') ?>',
			data:{
				id: $('#item_class_edit_id').val(),
				name: $('#item_class_name_edit').val(),
				description: $('#item_class_description_edit').val()
			},
			type:'POST',
			success:function(response){
				if(response == 1){
					refresh_view();
					$('#edit_item_class_wrapper .slide_alert_close_button').click();
				} else {
					$('#error_update_item_class').show();
					setTimeout(function(){
						$('#error_update_item_class').fadeOut();
					}, 1000);
				}
			}
		});
	});
	
	$('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
</script>