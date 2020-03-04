<div class='dashboard'>
	<h2 style='font-family:bebasneue'>Debt document</h2>
	<hr>
	<button type='button' class='button button_default_light'>Create debt document</button>
	<h3 style='font-family:bebasneue'>Pending bills</h3>
<?php
	if(!empty($bills)){
?>
	<table class='table table-bordered'>
		<tr>
			<th>Date</th>
			<th>Document</th>
		</tr>
<?php
	foreach($bills as $bill){
?>
		<tr>
			<td><?= date('d M Y',strtotime($bill->date)) ?></td>
			<td><?= $bill->name ?></td>
		</tr>
<?php
	}
?>
	</table>
	
	<select class='form-control' id='page' style='width:100px'>
<?php
	for($i = 1; $i <= $pages; $i++){
?>
		<option value='<?= $i ?>'><?= $i ?></option>
<?php
	}
?>
	</select>
<?php
	}
?>
</div>