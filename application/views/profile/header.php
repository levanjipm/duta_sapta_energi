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
			transform: scale(1.05);
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