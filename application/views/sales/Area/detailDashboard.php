<head>
	<title>Customer Area</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /Area</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Area</label>
		<p><?= $area->name ?></p>

		<label>Customers</label>
		<table class='table table-bordered'>
			<tr>
				<th>Customer</th>
				<th>Information</th>
			</tr>
		</table>
	</div>
</div>
