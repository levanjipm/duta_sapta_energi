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
		<option value='<?= $i ?>' <?php if($pages == $paging){ echo 'selected'; } ?>><?= $i  ?></option>
<?php
	}
?>
	</select>