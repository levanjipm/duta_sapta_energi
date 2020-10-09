<div class='dashboard'>
	<div class='dashboard_head'>
			<p style='font-family:museo'><a href='<?= site_url('Human_resource') ?>' title='Human resource'><i class='fa fa-briefcase'></i></a> / <a href='<?= site_url('Attendance') ?>'>Attandance list</a> / History</p>
		</div>
		<br>
		<div class='dashboard_in'>
			<button class='form-control' id='userButton' style='text-align:left'></button>
			<br>

			<div id='attendanceTable' style='display:none'>
				<table class='table table-bordered'>
					<tr>
						<th>Date</th>
						<th>Time</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
					<tbody id='attendanceTableContent'></tbody>
				</table>

				<select class='form-control' id='attendancePage' style='width:100px'>
					<option value='1'>1</option>
				</select>
			</div>

			<p id='attendanceTableText'>There is no attendance data found.</p>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='userWrapper'>
	<div class='alert_box_full'>
		<button type='button' class='button alert_full_close_button' title='Close user session'>&times;</button>
		<h3 style='font-family:bebasneue'>Choose user</h3>
		<hr>
		<input type='text' class='form-control' id='userSearchBar'><br>

		<div id='userTable'>
			<table class='table table-bordered'>
				<tr>
					<th>User</th>
					<th>Action</th>
				</tr>
				<tbody id='userTableContent'></tbody>
			</table>

			<select class='form-control' id='userPage' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='userTableText'>There is no user found.</p>
	</div>
</div>

<div class='alert_wrapper' id='attendanceWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>View Attendance</h3>
		<hr>
		<label>Date</label>
		<p id='date_p'></p>

		<label>Time</label>
		<input type='time' id='time' class='form-control' value="08:00">

		<label>Status</label>
		<p id='status_p'></p>
		<p id='statusDescription_p'></p>

		<button class='button button_danger_dark' onclick='deleteAttendance()'><i class='fa fa-trash'></i></button>
	</div>
</div>

<script>
	var userId;
	var attendanceId;

	function refreshView(page = $('#attendancePage').val()){
		$.ajax({
			url:'<?= site_url('Attendance/getByUserId') ?>',
			data:{
				page: page,
				userId: userId
			},
			success:function(response){
				var items = response.items;
				var itemCount = 0;
				$('#attendanceTableContent').html("");
				$.each(items, function(index, item){
					var name		= item.name;
					var time		= new Date(item.time);
					var hour		= String(time.getHours());
					var minute		= String(time.getMinutes());
					var second		= String(time.getSeconds());
					var id			= item.id;
					var date		= item.date;

					var formattedTime	= hour.padStart(2, "0") + ":" + minute.padStart(2, "0") + ":" + second.padStart(2, "0");
					
					$('#attendanceTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + formattedTime + "</td><td>" + name + "</td><td><button class='button button_default_dark' onclick='viewAttendanceDetail(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
					itemCount++;
				});

				if(itemCount > 0){
					$('#attendanceTable').show();
					$('#attendanceTableText').hide();
				} else {
					$('#attendanceTable').hide();
					$('#attendanceTableText').show();
				}

				var pages = response.pages;
				$('#attendancePage').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#attendancePage').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#attendancePage').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		})
	}

	$('#userButton').click(function(){
		$('#userSearchBar').val("");
		refreshUsers(1);
		$('#userWrapper').fadeIn();
	});

	$('#userSearchBar').change(function(){
		refreshUsers(1);
	})

	$('#userPage').change(function(){
		refreshUsers();
	});

	function refreshUsers(page = $('#userPage').val()){
		$.ajax({
			url:"<?= site_url('Users/getItems') ?>",
			data:{
				page: $('#userPage').val(),
				term: $('#userSearchBar').val()
			},
			success:function(response){
				var items = response.users;
				$('#userTableContent').html("");
				var userCount = 0;
				$.each(items, function(index, item){
					var id = item.id;
					var name = item.name;
					if(item.image_url == null){
						var imageUrl = "<?= base_url() . '/assets/ProfileImages/defaultImage.png' ?>";
					} else {
						var imageUrl = "<?= base_url() . '/assets/ProfileImages/' ?>" + item.image_url;
					}

					$('#userTableContent').append("<tr><td><img src='" + imageUrl + "' style='width:30px;height:30px;border-radius:50%'> " + name + "</td><td><button class='button button_default_dark' id='userSelectButton-" + id + "'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
					$('#userSelectButton-' + id).click(function(){
						$('#userButton').html(name);
						userId = id;
						refreshView(1);
						$('#userWrapper .alert_full_close_button').click();
					});
					userCount++;
				});

				if(userCount > 0){
					$('#userTable').show();
					$('#userTableText').hide();
				} else {
					$('#userTable').hide();
					$('#userTableText').show();
				}

				var pages = response.pages;
				$('#userPage').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#userPage').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#userPage').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		})
	}

	function viewAttendanceDetail(id)
	{
		$.ajax({
			url:'<?= site_url('Attendance/getById') ?>',
			data:{
				id: id
			},
			success:function(response){
				attendanceId	= id;
				var date		= response.date;
				var time		= new Date(response.time);
				var name		= response.name;
				var description	= response.description;

				var hour		= String(time.getHours(time));
				var minutes		= String(time.getMinutes(time));

				var formattedTime	= hour.padStart(2, "0") + ":" + minutes.padStart(2, "0");

				$('#status_p').html(name);
				$('#statusDescription_p').html(description);
				$('#date_p').html(my_date_format(date));

				$('#time').val(formattedTime);

				$('#attendanceWrapper').fadeIn(300, function(){
					$('#attendanceWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function deleteAttendance(){
		alert(attendanceId);
	}

	$('.alert_full_close_button').click(function(){
		$(this).parent().parent().fadeOut();
	});
</script>
