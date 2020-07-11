<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Human_resource') ?>' title='Human resource'><i class='fa fa-briefcase'></i></a> /Salary slip</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<button class='button button_default_dark'>Create salary slip</button>
		<br><br>
		<p>Last 10 salary slips</p>
		<hr style='border-bottom:2px solid #2b2f38'>
		<table class='table table-bordered'>
			<tr>
				<th>Period</th>
				<th>User</th>
				<th>Salary</th>
				<th>Benefit</th>
				<th>Total</th>
				<th>Action</th>
			</tr>
			<tbody id='salary_table'></tbody>
		</table>
	</div>
</div>

<div class='alert_wrapper' id='create_salary_wrapper'>
</div>