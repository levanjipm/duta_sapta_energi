<!DOCTYPE html>
<head>
	<script src='<?= base_url('third_party/Jquery/jquery-3.3.0.min.js') ?>'></script>
	<script src='<?= base_url('third_party/bootstrap/4.1.3/js/bootstrap.min.js') ?>'></script>
	<link rel='stylesheet' href='<?= base_url('third_party/bootstrap/4.1.3/css/bootstrap.min.css') ?>'>
	<link rel='stylesheet' href='<?= base_url('third_party/bootstrap/3.3.7/css/bootstrap.min.css') ?>'>
	<link rel='stylesheet' href='<?= base_url('third_party/fontawesome/css/font-awesome.min.css') ?>'>
	<script src='<?= base_url('third_party/Jquery/jquery-ui.js') ?>'></script>
	<link rel='stylesheet' href='<?= base_url('third_party/Jquery/jquery-ui.css') ?>'>
	<link rel="stylesheet" href='<?= base_url('css/style.css') ?>'>
	<link rel="stylesheet" href='<?= base_url('css/button_style.css') ?>'>
	<title>Login</title>
</head>
<style>
	html, body{
		height:100%;
	}
	
	.row{
		height:100%;
		margin:0;
	}
	
	#left_pane{
		background-color:#E19B3C;
		background-image: url(<?= base_url('assets/Background_login.png') ?>);
		background-repeat: no-repeat;
		background-size: 120% 100%;
	}
	
	#right_pane{
		background-color:#2B2F38;
		position:relative;
	}
	
	.form-control{
		border-radius:5px;
		border:1px solid #eee;
		outline:none!important;
		padding:8px;
		background-color:transparent;
		color:white;
	}
	
	.button_login{
		border-radius:5px;
		padding:10px 40px;
		background-color:#E19B3C;
		font-family:bebasneue;
		border:none;
		outline:none!important;
		font-size:1.2em;
	}
	
	#welcome_back_wrapper{
		padding:10px;
		position:absolute;
		bottom:0;
		right:0;
		color:#111;
	}
	
	#login_form_wrapper{
		position:absolute;
		top:50%;
		left:0;
		transform: translate(0%, -50%);
	}
	
	label{
		color:white;
	}
</style>
<body>
	<div class='row'>
		<div class='col-sm-5 col-xs-4' id='left_pane'>
			<div id='welcome_back_wrapper'>
				<h1 style='font-family:museo'>WELCOME <br>BACK</h1>
			</div>
		</div>
		<div class='col-sm-7 col-xs-8' id='right_pane'>
			<div class='row'>
				<div class='col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1' id='login_form_wrapper'>
					<div style='text-align:center'>
						<img src='<?= base_url('assets/Logo_light.png') ?>' style='width:30%;min-width:150px'>
					</div>
					<br><br><br>
					
					<div style='text-align:left'>
						<form action='<?= site_url('Login/member_login') ?>' method='POST'>
							<label>Email</label>
							<input type='text' class='form-control' name='email' placeholder='Email'>
							
							<label>Password</label>
							<input type='password' class='form-control' name='password' placeholder='Password'>
							<br>
							<button class='button_login'>Login</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>