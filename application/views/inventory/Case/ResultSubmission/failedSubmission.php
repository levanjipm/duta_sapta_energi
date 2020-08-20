<head>
	<title>Inventory case - Failed Submission</title>
	<style>
		.logo_wrapper{
			position:relative; 
			height:200px;
		}

		.circle_wrapper {
			opacity: 1;
			transform: scale(0);
			color: white;
			border-radius:50%;
			border:2px solid #fff;
			-webkit-text-stroke: 0;
			animation: background .3s cubic-bezier(1.000, 0.008, 0.565, 1.650) .1s 1 forwards;
			width:200px;
			height:200px;
			position:absolute;
			text-align:center;
		}

		.check{
			position:relative;
			top:5%;
			opacity:0;
			color:white;
			font-size:10rem;
			animation: icon .5s cubic-bezier(0.895, 0.030, 0.685, 0.220) forwards;
		}

		@keyframes background {
			from {
				border:2px solid white;
				opacity: 0;
				transform: scale(0.3);
				background-color:transparent;
			}
			to {
				border:2px solid transparent;
				opacity: 1;
				transform: scale(1);
				background-color:red;
			}
		}

		@keyframes icon {
			from {
				opacity: 0;
				transform: scale(0.3);
			}
			to {
				opacity: 1;
				transform: scale(1)
			}
		}

		#copy_text_span{
			cursor:pointer;
		}
	</style>
</head>
<div class='dashboard'>
	<br>
	<div class='dashboard_in'>
		<div class='logo_wrapper'>
			<div class='circle_wrapper'>
				<div class='check'><i class='fa fa-times'></i></div>
			</div>
		</div>
		<br>
		<div class='row'>
			<div class='col-sm-12 col-xs-12'>
				<h3 style='font-family:bebasneue;text-align:center'>Failed to perform operation.</h3>
			</div>
		</div>
		<a href='<?= site_url('Inventory_case') ?>'><button class='button button_default_dark'><i class='fa fa-long-arrow-left'></i></button></a>
	</div>
</div>
<script>
	$(document).ready(function(){
		var window_width	= $('.dashboard_in').width() - 200;
		var difference		= window_width * 0.5;		
		$('.logo_wrapper').css('margin-left', difference, 'important');
	});
</script>