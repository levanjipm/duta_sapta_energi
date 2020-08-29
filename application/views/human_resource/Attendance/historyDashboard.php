<div class='dashboard'>
<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Human_resource') ?>' title='Human resource'><i class='fa fa-briefcase'></i></a> / <a href='<?= site_url('Attendance') ?>'>Attandance list</a> / History</p>
	</div>
    <br>
    <div class='dashboard_in'>
		<button class='form-control' id='userButton' style='text-align:left'></button>
		<br>

		<div id='attendanceTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Time</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
				<tbody id='attendanceTableContent'></tbody>
			</table>

			<select class='form-control' id='attendancePage'>
				<option value='1'>1</option>
			</select>
		</div>
	</div>
</div>
<script>
	var userId;
	function refreshView(page = $('#attendancePage').val()){
		$.ajax({
			url:'<?= site_url('Attendance/getByUserId') ?>',
			data:{
				page: page,
				userId: userId;
			},
			success:function(response){
				console.log(response);
			}
		})
	}
</script>
