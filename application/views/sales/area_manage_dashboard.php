<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /Area</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<input type='text' class='form-control input-lg' id='search_bar'>
			<div class='input_group_append'>
				<button class='button button_default_dark' id='add_area_button'>Add area</button>
			</div>
		</div>
		<br>
		<table class='table table-bordered'>
			<tr>
				<th>Area name</th>
				<th>Action</th>
			</tr>
			<tbody id='area_table'></tbody>
		</table>
		
		<select class='form-control' id='page' style='width:100px'>
			<option value='1'>1<option>
		</select>
	</div>
</div>

<div class='alert_wrapper' id='add_area_wrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Insert area</h3>
		<hr>
		<form id='insert_area_form'>
			<label>Area name</label>
			<input type='text' class='form-control' id='area' required>
			<div style='padding:2px 10px;background-color:#ffc107;width:100%;display:none;' id='error_insert_area'><p style='font-family:museo'><i class='fa fa-exclamation-triangle'></i> Insert data failed.</p></div><br>
			
			<button class='button button_default_dark' type='button' id='insert_area_button'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='edit_area_wrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h2 style='font-family:bebasneue'>Edit area</h2>
		<hr>
		<form id='edit_area_form'>
			<input type='hidden' id='area_id'>
			<label>Area name</label>
			<input type='text' class='form-control' name='name' id='area_name' required>
			<div style='padding:2px 10px;background-color:#ffc107;width:100%;display:none;' id='error_update_area'><p style='font-family:museo'><i class='fa fa-exclamation-triangle'></i> Insert data failed.</p></div><br>
			
			<button class='button button_default_dark' type='button' id='edit_area_button'><i class='fa fa-long-arrow-right'></i></button>
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
			url:'<?= site_url('Area/get_areas') ?>',
			data:{
				page: page,
				term: $('#search_bar').val()
			},
			success:function(response){
				var areas = response.areas;
				$('#area_table').html('');
				$.each(areas, function(index, area){
					var name = area.name;
					var id = area.id;
					$('#area_table').append("<tr id='area-" + id + "'><td>" + name + "</td><td><button class='button button_success_dark' onclick='open_edit_view(" + id + ")'><i class='fa fa-pencil'></i></button> <button class='button button_danger_dark' onclick='delete_area(" + id + ")'><i class='fa fa-trash'></i></button> <button class='button button_default_dark'><i class='fa fa-eye'></i></button></td></tr>");
				});
				
				var pages = response.pages;
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
	
	$('#insert_area_form').validate();
	
	$('#add_area_button').click(function(){
		$('#add_area_wrapper').fadeIn(300, function(){
			$('#add_area_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	});
	
	$("#insert_area_form").submit(function(e){
		return false;
	});
	
	$('#insert_area_button').click(function(){
		if($('#insert_area_form').valid()){
			$.ajax({
				url:'<?= site_url('Area/insert_new_area') ?>',
				data:{
					name: $('#area').val()
				},
				type:'POST',
				success:function(response){
					if(response == 1){
						$('#area').val('');
						$('#add_area_wrapper .slide_alert_close_button').click();
					} else {
						$('#error_insert_area').show();
						setTimeout(function(){
							$('#error_insert_area').fadeOut()
						}, 1000);
					}
				}
			});
		}
	});
	
	function delete_area(n){
		$.ajax({
			url:'<?= site_url('Area/delete_area') ?>',
			data:{
				id:n
			},
			type:'POST',
			success:function(response){
				if(response == 1){
					$('#area-' + n).remove();
				}
			}
		})
	}
	
	function open_edit_view(n){
		$.ajax({
			url:'<?= site_url('Area/get_area_by_id') ?>',
			data:{
				id: n
			},
			success:function(response){
				var area_name = response.name;
				$('#area_name').val(area_name);
				$('#area_id').val(n);
				$('#edit_area_wrapper').fadeIn(300, function(){
					$('#edit_area_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}
	
	$('#edit_area_button').click(function(){
		$.ajax({
			url:'<?= site_url('Area/update_area') ?>',
			data:{
				id: $('#area_id').val(),
				name: $('#area_name').val()
			},
			type:'POST',
			success:function(response){
				if(response == 1){
					refresh_view();
					$('#edit_area_wrapper .slide_alert_close_button').click();
				} else {
					$('#error_update_area').show();
					setTimeout(function(){
						$('#error_update_area').fadeOut()
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