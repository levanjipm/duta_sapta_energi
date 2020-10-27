<head>
	<title>Customer Area</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /Area</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<input type='text' class='form-control' id='search_bar' placeholder="Search Area">
			<div class='input_group_append'>
				<button class='button button_default_dark' id='add_area_button'><i class='fa fa-plus'></i> Add area</button>
			</div>
		</div>
		<br>
		<div id='areaTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Area name</th>
					<th>Action</th>
				</tr>
				<tbody id='areaTableContent'></tbody>
			</table>
			
			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1<option>
			</select>
		</div>
		<p id='areaTableText'>No area found.</p>
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
		<h3 style='font-family:bebasneue'>Edit area</h2>
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

<div class='alert_wrapper' id='delete_area_wrapper'>
	<div class='alert_box_confirm_wrapper'>
		<div class='alert_box_confirm_icon'><i class='fa fa-trash'></i></div>
		<div class='alert_box_confirm'>
			<input type='hidden' id='delete_area_id'>
			<h3>Delete confirmation</h3>
			
			<p>You are about to delete this data.</p>
			<p>Are you sure?</p>
			<button class='button button_default_dark' onclick="$('#delete_area_wrapper').fadeOut()">Cancel</button>
			<button class='button button_danger_dark' onclick='delete_area()'>Delete</button>
			
			<br><br>
			
			<p style='font-family:museo;background-color:#f63e21;width:100%;padding:5px;color:white;position:relative;bottom:0;left:0;opacity:0' id='error_delete_area'>Deletation failed.</p>
		</div>
	</div>
</div>
<form action="<?= site_url('Area/viewDetail') ?>" method="POST" id='areaForm'>
	<input type='hidden' id='areaId' name='id'>
</form>
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
			url:'<?= site_url('Area/getItems') ?>',
			data:{
				page: page,
				term: $('#search_bar').val()
			},
			success:function(response){
				var areas = response.areas;
				var areaCount = 0;
				$('#areaTableContent').html('');
				$.each(areas, function(index, area){
					var name = area.name;
					var id = area.id;
					$('#areaTableContent').append("<tr id='area-" + id + "'><td>" + name + "</td><td><button class='button button_success_dark' onclick='open_edit_view(" + id + ")'><i class='fa fa-pencil'></i></button> <button class='button button_danger_dark' onclick='confirm_delete(" + id + ")'><i class='fa fa-trash'></i></button> <button class='button button_default_dark' onclick='viewArea(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
					areaCount++;
				});

				if(areaCount > 0){
					$('#areaTable').show();
					$('#areaTableText').hide();
				} else {
					$('#areaTable').hide();
					$('#areaTableText').show();
				}
				
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
				url:'<?= site_url('Area/insertItem') ?>',
				data:{
					name: $('#area').val()
				},
				type:'POST',
				success:function(response){
					refresh_view();
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
	
	function confirm_delete(n){
		$('#delete_area_id').val(n);
		$('#delete_area_wrapper').fadeIn();
	}
	
	function delete_area(){
		$.ajax({
			url:'<?= site_url('Area/deleteById') ?>',
			data:{
				id:$('#delete_area_id').val()
			},
			type:'POST',
			success:function(response){
				if(response == 1){
					refresh_view();
					$('#delete_area_wrapper').fadeOut();
				} else {
					$('#error_delete_area').fadeTo(250, 1);
					setTimeout(function(){
						$('#error_delete_area').fadeOut(250, 0);
					}, 1000);
				}
			}
		})
	}
	
	function open_edit_view(n){
		$.ajax({
			url:'<?= site_url('Area/getItemById') ?>',
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
			url:'<?= site_url('Area/updateById') ?>',
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
	
	function viewArea(n)
	{
		$('#areaId').val(n);
		$('#areaForm').submit();
	}
</script>
