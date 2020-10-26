<head>
	<title>Attendance history</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
			<p style='font-family:museo'><a href='<?= site_url('Human_resource') ?>' title='Human resource'><i class='fa fa-briefcase'></i></a> / <a href='<?= site_url('Attendance') ?>'>Attandance list</a> / History</p>
		</div>
		<br>
		<div class='dashboard_in'>
			<button class='form-control' id='userButton' style='text-align:left'></button>
			<br>
			<button type='button' id='addAttendanceButton' class='button button_default_dark' style='display:none;margin-bottom:30px;'><i class='fa fa-plus'></i> Add attendance</button>
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
		<br>

		<label>Status</label>
		<p id='status_p'></p>
		<p id='statusDescription_p'></p>

		<button class='button button_danger_dark' onclick='deleteAttendance()'><i class='fa fa-trash'></i></button>
	</div>
</div>

<div class='alert_wrapper' id='addAttendanceWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Add Attendance</h3>
		<hr>
		<form id='addAttendanceForm'>
			<label>Date</label>
			<input type='date' class='form-control' name='date' id='addDate' required min="2020-01-01">

			<label>Time</label>
			<input type='time' id='addTime' class='form-control' value="08:00" required name='time'>

			<label>Status</label>
			<button type='button' class='form-control' id='attendanceStatusButton' style='text-align:left'></button>
			<input type='hidden' id='addAttendanceStatus' name='status' required>

			<br>
			<button type='button' id='addAttendanceSubmitButton' class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>

			<div class='notificationText danger' id='failedNotificationText'><p>Failed to insert data.</p></div>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='statusWrapper'>
	<div class='alert_box_full'>
		<button type='button' class='button alert_full_close_button' title='Close status session'>&times;</button>
		<h3 style='font-family:bebasneue'>Select Status</h3>
		<hr>
		<input type='text' class='form-control' id='statusSearchBar'><br>

		<div id='statusTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Status</th>
					<th>Information</th>
					<th>Action</th>
				</tr>
				<tbody id='statusTableContent'></tbody>
			</table>

			<select class='form-control' id='statusPage' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='statusTableText'>There is no status found.</p>
	</div>
</div>

<script>
	var userId;
	var attendanceId;

	$('#addAttendanceForm').validate({
		ignore: ""
	});

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
						$('#addAttendanceButton').show();
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
			},
			complete:function(){
				$('#attendanceWrapper').fadeIn(300, function(){
					$('#attendanceWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	$('#addAttendanceButton').click(function(){
		$('#addAttendanceForm').trigger("reset");
		$('#addAttendanceStatus').val(null);
		$('#attendanceStatusButton').html("");
		$('#addTime').val("08:00");

		$('#addAttendanceWrapper').fadeIn(300, function(){
			$('#addAttendanceWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	});

	$('#addAttendanceSubmitButton').click(function(){
		if($('#addAttendanceForm').valid()){
			var formData		= $('#addAttendanceForm').serializeArray();
			formData.push({name: "user", value: userId});

			$.ajax({
				url:"<?= site_url('Attendance/insertCompleteItem') ?>",
				data:formData,
				type:"POST",
				beforeSend:function(){
					$('button').attr('disabled', true);
					$('input').attr('readonly', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					$('input').attr('readonly', false);
					refreshView();

					if(response == 1){
						$('#addAttendanceWrapper .slide_alert_close_button').click();
					} else {
						$('#failedNotificationText').fadeIn(250);
						setTimeout(function(){
							$('#failedNotificationText').fadeOut(250);
						}, 1000)
					}
				}
			})
		}
	});

	function refreshStatus(page = $('#statusPage').val()){
		$.ajax({
			url:"<?= site_url('Attendance/getAttendanceStatus') ?>",
			data:{
				page: page,
				term: $('#statusSearchBar').val()
			},
			success:function(response){
				var items	= response.items;
				var itemCount	= 0;
				$('#statusTableContent').html("");
				$.each(items, function(index, item){
					var name		= item.name;
					var description	= item.description;
					var id			= item.id;

					$('#statusTableContent').append("<tr><td>" + name + "</td><td>" + description + "</td><td><button class='button button_default_dark' id='selectStatusButton-" + id + "'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
					itemCount++;

					$('#selectStatusButton-' + id).click(function(){
						$('#attendanceStatusButton').html(name);
						$('#addAttendanceStatus').val(id);
						$('#statusWrapper').fadeOut(250);
					})
				});

				var pages	= response.pages;
				$('#statusPage').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#statusPage').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#statusPage').append("<option value='" + i + "'>" + i + "</option>");	
					}
				}

				if(itemCount > 0){
					$('#statusTable').show();
					$('#statusTableText').hide();
				} else {
					$('#statusTable').hide();
					$('#statusTableText').show();
				}
			}
		})
	};

	$('#statusSearchBar').change(function(){
		refreshStatus(1);
	});

	$('#statusPage').change(function(){
		refreshStatus();
	});

	$('#attendanceStatusButton').click(function(){
		$('#statusSearchBar').val("");
		refreshStatus(1);
		$('#statusWrapper').fadeIn(250);
	});

	function deleteAttendance(){
		alert(attendanceId);
	}

	$('.alert_full_close_button').click(function(){
		$(this).parent().parent().fadeOut();
	});
</script>
