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
			<td><?= $area->name ?></td>
			<td>
				<button type='button' class='button button_success_dark'><i class='fa fa-pencil'></i></button>
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
	<div class='alert_box_default'>
		<button class='alert_close_button'>&times</button>
		<h2 style='font-family:bebasneue'>Insert area form</h2>
		<hr>
		<form action='<?= site_url('Area/insert_new_area') ?>' method='POST'>
			<label>Area name</label>
			<input type='text' class='form-control' name='area' required>
			
			<br>
			<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
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
</script>