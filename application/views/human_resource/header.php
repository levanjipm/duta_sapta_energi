<div class='topnav_bar'>
<div style='width:50%;display:inline-block'><h3>Human Resource</h3></div><div style='width:50%;display:inline-block;text-align:right;color:white'><?php if(!empty($user_login)){ ?><h4>Hello, <a href='<?= site_url('Profile') ?>' style='text-decoration:none;color:#fff'><?= $user_login->name ?> <img src='<?= $user_login->image_url == null ? base_url('assets/ProfileImages/defaultImage.png') : base_url("assets/ProfileImages/") . $user_login->image_url ?>' style='height:30px;border-radius:50%;width:30px'></img></a></h4><?php } else { ?><button type='button' class='button button_default_dark'>Login</button> <?php } ?></div>
</div>
<div class='sidenav_bar'>
	<button class='button_close_sidenav'>
		<div class='bar bar_1'></div>
		<div class='bar bar_2'></div>
		<div class='bar bar_3'></div>
	</button>
	<a href='<?= site_url() ?>'><img src='<?= base_url('assets/Logo_light.png') ?>' style='width:70%;vertical-align:top;height:65px;'></a>
	<div class='sidenav_bar_departments'>
<?php
	foreach($departments as $department){
		if($department->name == 'Human resource'){
?>
		<button class='button_departments' onclick='window.location.href="<?= site_url($department->index_url) ?>"' title='<?= $department->name ?>'><img src='<?= base_url() . 'assets/' . $department->icon . '.png' ?>' style='width:100%'></button>
		<br><br>
<?php
		} else {
?>
		<button class='button_departments' onclick='window.location.href="<?= site_url($department->index_url) ?>"' title='<?= $department->name ?>'><img src='<?= base_url() . 'assets/' . $department->icon . '.png' ?>' style='width:100%;filter: brightness(0) invert(1);'></button>
		<br><br>
<?php
		}
	}
?>
	</div>
	<div class='sidenav_bar_functions'>
		<button class='container_button'><p style='font-family:museo'>Users</p><i class='fa fa-caret-down'></i></button>
		<div class='container_bar'>
			<a href='<?= site_url('Users') ?>'><button><p style='font-family:museo'>Manage</p></button></a>
			<a href='<?= site_url('Users_authorization') ?>'><button><p style='font-family:museo'>Authorization</p></button></a>
		</div>
		<button class='container_button'><p style='font-family:museo'>Salary Slip</p><i class='fa fa-caret-down'></i></button>
		<div class='container_bar'>
			<a href='<?= site_url('Salary_slip') ?>'><button><p style='font-family:museo'>Create</p></button></a>
			<a href='<?= site_url('Salary_slip/ArchiveDashboard') ?>'><button><p style='font-family:museo'>Archive</p></button></a>
		</div>
		<button class='container_button'><p style='font-family:museo'>Attendance</p><i class='fa fa-caret-down'></i></button>
		<div class='container_bar'>
			<a href='<?= site_url('Attendance') ?>'><button><p style='font-family:museo'>List</p></button></a>
			<a href='<?= site_url('Attendance/statusDashboard') ?>'><button><p style='font-family:museo'>Status</p></button></a>
			<a href='<?= site_url('Attendance/historyDashboard') ?>'><button><p style='font-family:museo'>History</p></button></a>
		</div>
		<a href='<?= site_url('Benefits') ?>'><button><p style='font-family:museo'>Benefits</p></button></a>
	</div>
</div>
<div class="selfCheckIn" data-toggle="tooltip" title="Self Check In">
	<i class='fa fa-check'></i>
</div>
<script>
	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip();
		checkAttendance();
	})

	function checkAttendance(){
		$.ajax({
			url:"<?= site_url('Users/checkAttendance') ?>",
			success:function(response){
				if(response == 0){
					$('.selfCheckIn').addClass("active");
				} else {
					$('.selfCheckIn').removeClass("active");
				}
			}
		})
	}
	
	$('.container_button').click(function(){
		$('.active').removeClass('active');
		$('.container_bar').hide(400);
		if($(this).next('.container_bar').is(':hidden')){
			$(this).addClass('active');
			var containerBox = $(this).next('.container_bar');
			containerBox.toggle(400);
			containerBox.children().css("opacity", 0);
			var time = 300;
			containerBox.children().each(function(index, child){
				$(child).fadeTo(time, 1);
				time += 300;
			});
		}
	});
	
	$('#hide_side_nav_button').click(function(){
		$('.sidenav_bar').toggle(300);
		$('.dashboard').css('margin-left',0);
	});
	
	$('.selfCheckIn').click(function(){
		$.ajax({
			url:"<?= site_url('Users/selfCheckIn') ?>",
			beforeSend:function(){
				$('.selfCheckIn').attr('disabled', true);
			},
			success:function(){
				checkAttendance();
			}
		})
	})
</script>
