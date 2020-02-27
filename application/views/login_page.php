<!DOCTYPE html>
<head>
	<script src='<?= base_url('third_party/Jquery/jquery-3.3.0.min.js') ?>'></script>
	<script src='<?= base_url('third_party/bootstrap/4.1.3/js/bootstrap.min.js') ?>'></script>
	<link rel='stylesheet' href='<?= base_url('third_party/bootstrap/4.1.3/css/bootstrap.min.css') ?>'>
	<link rel='stylesheet' href='<?= base_url('third_party/bootstrap/3.3.7/css/bootstrap.min.css') ?>'>
	<link rel='stylesheet' href='<?= base_url('third_party/fontawesome/css/font-awesome.min.css') ?>'>
	<script src='<?= base_url('third_party/Jquery/jquery-ui.js') ?>'></script>
	<link rel='stylesheet' href='<?= base_url('third_party/Jquery/jquery-ui.css') ?>'>
	<link rel="stylesheet" href='<?= base_url('css/button_style.css') ?>'>
</head>
<style>
	@font-face {
	  font-family: Bebasneue;
	  src: url(../third_party/font/BebasNeue.woff);
	}
	body{
		background-color:#333;
	}
	
	.login_box{
		width:350px;
		background-color:transparent;
		height:80%;
		position:absolute;
		padding: 10px;
		top:10%;
	}
	
	.login_box_header h1{
		color: #fff;
		font-family: bebasneue;
		letter-spacing: 10px;
	}
	
	.button_login{
		border:2px solid #f6a821;
		width:100%;
		color:white;
	}
	
	.button_login:hover{
		color:white;
		background-color: #f6a821;
		transition:0.3s all ease;
	}
	
	.sign_in_text{
		color:white;
		font-family:bebasneue;
		text-align:center;
	}
</style>
<body>
	<div class='login_box'>
		<form action='<?= site_url('Login/member_login') ?>' method='POST'>
		<h1 class='sign_in_text'>Sign In</h1>
		<label>Email</label>
		<input type='text' class='form-control' name='email'>
		
		<label>Password</label>
		<input type='password' class='form-control' name='password'>
		
		<br>
		<button type='submit' class='button button_login'>Log in</button>
		</form>
	</div>
</body>
<script>
	function adjust_login_position(){
		var window_width	= $(window).width();
		var login_box_width	= $('.login_box').width();
		var difference		= window_width - login_box_width;
		
		$('.login_box').css('right',difference / 2);
	}
	
	$(document).ready(function(){
		adjust_login_position();
	});
	
	$(window).resize(function(){
		adjust_login_position();
	});
</script>