<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /Return</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<input type='text' class='form-control' id='search_bar'><br>
		
		<table class='table table-bordered'>
			<tr>
				<th>Date</th>
				<th>Name</th>
				<th>Customer</th>
				<th>Action</th>
			</tr>
		</table>
		
		<select class='form-control' id='page' style='width:100px'>
			<option value='1'>1</option>
		</select>
	</div>
</div>