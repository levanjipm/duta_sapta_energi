<head>
	<title>Profile</title>
	<style>
		body{
			overflow-y:scroll;
		}
		[hidden] {
			display: none !important;
		}

		.dashboard{
			margin-left:0!important;
			padding-top:90px;
			overflow-x:hidden;
		}

		.row{
			margin:0;
			color:white;
		}

		.col{
			display:flex;
		}

		.row p{
			margin-bottom:0;
			font-family:museo;
		}

		.card{
			padding:20px;
			font-family:museo;
			color:black;
			border-radius:0px;
			box-shadow:3px 3px 3px 3px rgba(50,50,50,0.8);
			margin-bottom:20px;
			cursor:pointer;
			transition: transform .2s;
			flex: 1 0 auto;
		}
		
		.card:hover{
			transform: scale(1.1);
		}

		.button_slide{
			position:absolute;
			top:0;
			margin:auto;
			bottom:0;
			height:100%;
			background-color:transparent;
			color:#ddd;
			border:none;
			font-weight:normal;
			outline:none!important;
		}

		.button_slide:hover{
			color:#E19B3C;
			transition:0.3s all ease;
			font-weight:bold;
		}

		.left{
			left:0;
		}

		.right{
			right:0;
		}
	</style>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<div class='topnav_bar' style='padding-left:8px!important;text-align:center;color:white;height:70px'>
	<a href='<?= site_url('Welcome') ?>'><img src='<?= base_url('assets/Logo_dark.png') ?>' style='width:100px'></a>
</div>
<div class='dashboard'>
	<div class='row'>
		<div class='col-xs-12'>
			<h4>Dashboard</h4>
			<hr>
		</div>
		<div class='col-sm-4 col-xs-6 col'>
			<div class='card'>
				<label>User</label>
				<div style='text-align:center'>
					<img src="<?= ($user_login->image_url == null) ? base_url('assets/ProfileImages/defaultImage.png') : base_url('assets/ProfileImages/') . $user_login->image_url; ?>" style='height:50px;width:50px;border-radius:50%' />
					<p><strong><?= $user_login->name ?></strong></p>
				</div>
				<hr>
				<p><?= $user_login->email ?></p>
				<p><?= $user_login->address ?></p>
				<p><?= $user_login->bank_account ?></p><br>
				<button class='button button_default_dark' onclick='openEditProfileForm()'><i class='fa fa-pencil'></i></button>
			</div>
		</div>
		<div class='col-sm-4 col-xs-6 col'>
			<div class='card'>
				<label>Attendance</label>
				<p id='attendanceMonthYear'></p>
				<label>Count</label>
				<p id='attendanceCount'></p>
				<div id='donutchart' style='height:150px'></div>
				<button class='button_slide left' onclick='selectPrevious()'><i class='fa fa-chevron-left'></i></button><button class='button_slide right' onclick='selectNext()'><i class='fa fa-chevron-right'></i></button>
			</div>
		</div>
		<div class='col-sm-4 col-xs-6 col'>
			<div class='card'>
				<label>Authorization</label>
				<ul>
<?php foreach($departments as $department){ ?>
					<li><?= $department->name ?></li>
<?php } ?>
				</ul>
			</div>
		</div>
		<div class='col-sm-8 col-xs-8 col'>
			<div class='card'>
				<label>Salary Slip</label>
<?php if(count($salary) > 0){ ?>
				<table class='table table-bordered'>
					<tr>
						<th>Period</th>
						<th>Action</th>
					</tr>
<?php foreach($salary as $item){ ?>
					<tr>
						<td><?= date('F Y', mktime(0,0,0,$item->month, 1, $item->year)) ?></td>
						<td><button class='button button_default_dark'><i class='fa fa-eye'></i></button></td>
					</tr>
<?php } ?>
				</table>
<?php } else { ?>
				<p>There is no salary slip found.</p>
<?php } ?>
			</div>
		</div>
		<div class='col-sm-4 col-xs-4 col'>
			<div class='card'>
				<button class='button button_danger_dark' onclick="logout()"><i class="fa fa-sign-out"></i> Logout</button>
			</div>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='profileWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Update user data</h3>
		<hr>
		<form id='profileImageForm'>
			<label>Profile picture</label>
			<label style='width:100%'>
				<div class='input_group'>
					<span class='form-control' id='upload-file-info'></span>
					<div class='input_group_append'>
						<span class='button button_default_dark' style='display:inline-block;height:100%;cursor:pointer'>Browse <input type='file' name='image' id='image' accept="image/png, image/jpeg" hidden onchange="uploadFile()"></span>
					</div>
				</div>
			</label>
			<div class='notificationText success' id='successImage'><p>Successfuly uploaded image</p></div>
		</form>
		<form id='profileForm'>
			<label>Name</label>
			<input type='text' class='form-control' id='name' name='name' value='<?= $user_login->name ?>' required>

			<label>Email</label>
			<input type='email' class='form-control' id='email' name='email' value='<?= $user_login->email ?>'>
			
			<label>Address</label>
			<textarea class='form-control' name='address' id='address' required minlength='25' rows='3' style='resize:none'><?= $user_login->address ?></textarea>

			<label>Bank</label>
			<input type='text' class='form-control' id='bank' name='bank' value='<?= $user_login->bank_account ?>'>

			<label>Password</label>
			<input type='password' class='form-control' id='password' name='password'>

			<button type='button' class='button button_default_dark' type='button' id='updateUserButton'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<script>
	var month		= <?= date('m') - 1 ?>;
	var year		= <?= date('Y') ?>;
	adjustLabel();
	google.charts.load('current', {packages: ['corechart']});
	google.charts.setOnLoadCallback(drawBasic);

	$('#profileForm').validate();
	
	function drawBasic(){
		$.ajax({
			url:'<?= site_url('Profile/getUserAttendance') ?>',
			data:{
				id:<?= $user_login->id ?>,
				month: month + 1,
				year: year
			},
			success:function(response){
				if(response.length == 0){
					$('#donutchart').html("<p>There is no attendance history found.</p>");
					$('#attendanceCount').html(0);
				} else {
					var attendanceArray = [];
					var count		= 0;
					attendanceArray.push(["Status", "Count"]);
					$.each(response, function(index, value){
						attendanceArray.push([value.name, value.count]);
						count += parseInt(value.count);
					});

					$('#attendanceCount').html(count);

					var data = google.visualization.arrayToDataTable(attendanceArray);
					var options = {
						pieHole: 0.4,
						legend:'none'
					};

					var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
					chart.draw(data, options);
				}
				
			}
		})
	}

	function selectPrevious(){
		var date			= new Date(year, month, 1);
		var previousDate	= new Date(date.setMonth(date.getMonth() - 1));
		month				= previousDate.getMonth();
		year				= previousDate.getFullYear();
		drawBasic();
		adjustLabel();
	}

	function selectNext(){
		if(year == new Date().getFullYear() && month == new Date().getMonth()){
			return false;
		} else {
			var date			= new Date(year, month, 1);
			var nextDate		= new Date(date.setMonth(date.getMonth() + 1));
			month				= nextDate.getMonth();
			year				= nextDate.getFullYear();
			drawBasic();
			adjustLabel();
		}
	}

	function adjustLabel(){
		var monthArray		= ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
		$('#attendanceMonthYear').html(monthArray[month] + " " + year);
	}

	function openEditProfileForm(){
		$('#profileWrapper').fadeIn(300, function(){
			$('#profileWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	}

	$('#updateUserButton').click(function(){
		if($('#profileForm').valid()){
			var data		= $('#profileForm').serializeArray();
			data.push({name: "image", value: $('#image')});
			var formData	= JSON.stringify(data);
			$.ajax({
				url:'<?= site_url('Profile/editUserProfileById') ?>',
				type:"POST",
				data:formData,
				processData:false,
				contentType: 'application/json',
				cache:false,
				success:function(response){
					console.log(response);
				}
			})
		}
	})

	function uploadFile(){
		var fd = new FormData();
		var files = $('#image')[0].files;
		if(files.length > 0 ){
			fd.append('file',files[0]);
			$.ajax({
				url:"<?= site_url('Profile/updateProfileImage') ?>",
				type:"POST",
				data:fd,
				contentType: false,
				processData: false,
				beforeSend:function(){
					$('#upload-file-info').html(files[0].name);
					$('#profileImageForm button').attr('disabled', true);
				},
				success:function(){
					$('#profileImageForm button').attr('disabled', false);
				},
				complete:function(){
					$('#successImage').fadeIn(250);
					setTimeout(function(){
						$('#successImage').fadeOut(250);
					}, 1000)
				}
			});
		}
	}

	function logout(){
		window.location.href='<?= site_url('Users/Logout') ?>';
	}
</script>