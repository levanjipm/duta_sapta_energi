<head>
	<title>Index page</title>
	<style>
		.department_box{
			width:80%;
			background-color:transparent;
			border-radius:7px;
			margin-top:20px;
			cursor:pointer;
			max-height:200px;
		}
		
		.department_box_head{
			background: transparent linear-gradient(90deg, #9B713A 0%, #5C3B30 42%, #392D33 100%) 0% 0% no-repeat padding-box;
			padding:10px;
			color:white;
			border-top-right-radius:7px;
			border-top-left-radius:7px;
			opacity:0.8;
		}
		
		.department_box_body{
			background-color:#232323;
			padding:10px;
			border-bottom-right-radius:7px;
			border-bottom-left-radius:7px;
			text-align:right;
		}
		
		.department_box_body img{
			opacity:0.1;
			width:30%;
			min-width:50px;
		}
		
		html, body{
			background-color:#2B2F38;
		}
	</style>
</head>
<div class='topnav_bar' style='padding-left:8px!important;text-align:center;color:white;height:100px'>
	<img src='<?= base_url('assets/Logo_dark.png') ?>' style='width:100px'>
	<a style='text-decoration:none;color:white' href='<?= site_url('Profile') ?>'><h4>Hello, <?= $user_login->name ?></h4></a>
</div>
<div class='row' style='padding:20px; padding-top:120px; margin:0;'>
<?php
	foreach($departments as $department){
?>
	<div class='col-lg-3 col-md-3 col-sm-4 col-xs-6 department_box_wrapper' onclick='window.location.href="<?= site_url($department->index_url) ?>"'>
		<div class='department_box'>
			<div class='department_box_head'><?= $department->name ?></div>
			<div class='department_box_body'>
				<img src='<?= base_url() . 'assets/' . $department->icon . '.png'; ?>' class='department_image'>
			</div>
		</div>
	</div>
<?php
	}

	if($user_login->access_level >3){
?>
	<div class='col-lg-3 col-md-3 col-sm-4 col-xs-6 department_box_wrapper' onclick='window.location.href="<?= site_url('Administrators') ?>"'>
		<div class='department_box'>
			<div class='department_box_head'>Administrators</div>
			<div class='department_box_body'>
				<img src='<?= base_url() . 'assets/' . $department->icon . '.png'; ?>' class='department_image'>
			</div>
		</div>
	</div>
<?php } if($user_login->access_level == 5){ ?>
	<div class='col-lg-3 col-md-3 col-sm-4 col-xs-6 department_box_wrapper' onclick='window.location.href="<?= site_url('Director') ?>"'>
		<div class='department_box'>
			<div class='department_box_head'>Directors</div>
			<div class='department_box_body'>
				<img src='<?= base_url() . 'assets/director.png'; ?>' class='department_image'>
			</div>
		</div>
	</div>
<?php } ?>
</div>
<div class="selfCheckIn" data-toggle="tooltip" title="Self Check In">
	<i class='fa fa-check'></i>
</div>
<script>	
	function adjust_size(){
		var min_size	= 150;
		$('.department_image').each(function(){
			if($(this).height() < min_size && $(this).height() > 0){
				min_size	= $(this).height();
			}
		});

		$('.department_image').height(min_size);
	}

	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip();

		adjust_size();
		checkAttendance();
	})
	
	$(window).resize(function(){
		adjust_size();
	});

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
