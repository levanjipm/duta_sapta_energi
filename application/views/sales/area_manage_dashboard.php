<div class='dashboard'>
	<h2 style='font-family:bebasneue'>Area</h2>
	<hr>
	<button type='button' class='button button_default_light' id='add_area_button'>Add new area</button>
<?php
	if(!empty($areas)){
?>
	<br><br>
	<table class='table table-bordered'>
		<tr>
			<th>Area name</th>
			<th>Action</th>
		</tr>
<?php
		foreach($areas as $area){
?>
		<tr>
			<td id='area_name-<?= $area->id ?>'><?= $area->name ?></td>
			<td>
				<button type='button' class='button button_success_dark' onclick='edit_area(<?= $area->id ?>)'><i class='fa fa-pencil'></i></button>
<?php
	if($area->customer == 0){
?>
				<button type='button' class='button button_danger_dark' onclick='delete_area(<?= $area->id ?>)'><i class='fa fa-trash'></i></button>
<?php
	} else {
?>
				<button type='button' class='button button_danger_dark' disabled><i class='fa fa-trash'></i></button>
<?php
	}
?>
				<button type='button' class='button button_default_light'><i class='fa fa-eye'></i></button>
			</td>
		</tr>
<?php
		}
?>
	</table>
<?php
	}
?>
</div>
<div class='alert_wrapper' id='add_area_wrapper'>
	<button class='alert_close_button'>&times </button>
	<div class='alert_box_default'>
		<h2 style='font-family:bebasneue'>Insert area</h2>
		<hr>
		<form action='<?= site_url('Area/insert_new_area') ?>' method='POST'>
			<label>Area name</label>
			<input type='text' class='form-control' name='area' required>
			
			<br>
			<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='edit_area_wrapper'>
	<button class='alert_close_button'>&times </button>
	<div class='alert_box_default'>
		<h2 style='font-family:bebasneue'>Edit area</h2>
		<hr>
		<form action='<?= site_url('Area/update_area') ?>' method='POST'>
			<input type='hidden' name='id' id='area_id'>
			<label>Area name</label>
			<input type='text' class='form-control' name='name' id='area_name' required>
			
			<br>
			<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='delete_area_wrapper'>
	<div class='alert_box_confirm'>
		<form action='<?= site_url('Area/delete') ?>' method='POST'>
			<input type='hidden' id='area_delete_id' name='id'>
			<h2 style='font-size:2em;color:red'><i class='fa fa-exclamation'></i></h2>
			<p style='font-family:museo'>Are you sure to delete this area?</p>
			<button type='button' class='button button_danger_dark' id='close_notif_button'>Check again</button>
			<button class='button button_default_dark'>Confirm</button>
		</form>
	</div>
</div>
<script>
	$('#add_area_button').click(function(){
		$('#add_area_wrapper').fadeIn();
	});
	
	$('.alert_close_button').click(function(){
		$(this).parents('.alert_wrapper').fadeOut();
	});
	
	function edit_area(n){
		var area_name	= $('#area_name-' + n).text();
		
		$('#area_id').val(n);
		$('#area_name').val(area_name);
		
		$('#edit_area_wrapper').fadeIn();
	}
	
	function delete_area(n){
		$('#area_delete_id').val(n);
		$('#delete_area_wrapper').fadeIn();
	}
	
	$('#close_notif_button').click(function(){
		$('#delete_area_wrapper').fadeOut();
	});
</script>