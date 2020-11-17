<head>
</head>
<div class='dashboard' style='padding-top:100px'>
	<div class='dashboard_in'>
		<label>Period</label>
		<div class='input_group'>
			<select class='form-control' id='month'>
				<option value='0'>Select all year</option>
			<?php for($i = 1; $i <= 12; $i++){ ?>
				<option value='<?= $i ?>'><?= date("F", mktime(0,0,0,$i, 1, date("Y"))) ?></option>
			<?php } ?>
			</select>
			<select class='form-control' id='year'>
			<?php for($i = 2020; $i <= date("Y"); $i++){ ?>
				<option value='<?= $i ?>'><?= $i ?></option>
			<?php } ?>
			</select>
			<div class='input_group_append'>
				<button class='button button_default_dark' onclick='viewIncomeStatement()'><i class='fa fa-long-arrow-right'></i></button>
			</div>
		</div>
		<hr>
		<table class='table table-bordered' id='incomeStatementTable' style='display:none'>
			<tr>
				<th>Property</th>
				<th>Value</th>
				<th>Action</th>
			</tr>
			<tbody id='incomeStatementTableContent'></tbody>
		</table>
	</div>
</div>
<script>
	
</script>