<!DOCTYPE html>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<html lang="en">
<head>
	<script src='<?= base_url('third_party/Jquery/jquery-3.3.0.min.js') ?>'></script>
	<script src='<?= base_url('third_party/bootstrap/4.1.3/js/bootstrap.min.js') ?>'></script>
	<link rel='stylesheet' href='<?= base_url('third_party/bootstrap/4.1.3/css/bootstrap.min.css') ?>'>
	<link rel='stylesheet' href='<?= base_url('third_party/bootstrap/3.3.7/css/bootstrap.min.css') ?>'>
	<link rel='stylesheet' href='<?= base_url('third_party/fontawesome/css/font-awesome.min.css') ?>'>
	<script src='<?= base_url('third_party/Jquery/jquery-ui.js') ?>'></script>
	<link rel='stylesheet' href='<?= base_url('third_party/Jquery/jquery-ui.css') ?>'>
	<script src='<?= base_url('third_party/numeral/numeral.js') ?>'></script>
	<script src='<?= base_url('third_party/Jquery/jquery.inputmask.bundle.js') ?>'></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<link rel="stylesheet" href='<?= base_url('css/style.css') ?>'>
	<link rel="stylesheet" href='<?= base_url('css/button_style.css') ?>'>
	<link rel="stylesheet" href='<?= base_url('css/alert_style.css') ?>'>
	<link rel="stylesheet" href='<?= base_url('css/input_style.css') ?>'>
	<link rel="stylesheet" href='<?= base_url('css/loader_style.css') ?>'>
	<script src='<?= base_url('js/header.js') ?>'></script>
	<script src='<?= base_url('third_party/validation/dist/jquery.validate.min.js') ?>'></script>
	<style>
		.selfCheckIn{
			width:50px;
			height:50px;
			border-radius:50%;
			background-color:#ccc;
			position:fixed;
			bottom:20px;
			right:20px;
			box-shadow:3px 3px 3px 3px rgba(50, 50, 50, 0.8);
			color:black;
			font-size:30px;
			padding:10px;
			cursor:not-allowed;
			display:none;
		}
		
		.selfCheckIn.active{
			background-color:#E19B3C;
			cursor:pointer;
			display:block;
		}

		.selfCheckIn.active:hover{
			animation-name:bounce;
			animation-duration:0.5s;
			animation-fill-mode:forward;
		}

		@keyframes bounce{
			0%{bottom:20px;}
			50% {bottom:50px;}
			100%{bottom:20px;}
		}
	</style>
</head>
<div class='loader_wrapper'>
	<div class='loader_screen_wrapper'>
		<div class='loader_dot_wrapper'>
			<div class='loader_dot' id='loader_dot_1'></div>
			<div class='loader_dot' id='loader_dot_2'></div>
			<div class='loader_dot' id='loader_dot_3'></div>
			<div class='loader_dot' id='loader_dot_4'></div>
		</div>
	</div>
</div>