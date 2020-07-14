<?php
	if($status == 'success'){
		$complete_address		= '';
		$customer_name			= $customer->name;
		$complete_address		= $customer->address;
		$customer_city			= $customer->city;
		$customer_number		= $customer->number;
		$customer_rt			= $customer->rt;
		$customer_rw			= $customer->rw;
		$customer_postal		= $customer->postal_code;
		$customer_block			= $customer->block;
		$customer_id			= $customer->id;
		
		if($customer_number != NULL){
			$complete_address	.= ' No. ' . $customer_number;
		}
		
		if($customer_block != NULL){
			$complete_address	.= ' Blok ' . $customer_block;
		}
		
		if($customer_rt != '000'){
			$complete_address	.= ' RT ' . $customer_rt;
		}
		
		if($customer_rw != '000' && $customer_rt != '000'){
			$complete_address	.= ' /RW ' . $customer_rw;
		}
		
		if($customer_postal != NULL){
			$complete_address	.= ', ' . $customer_postal;
		}
?>
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
		background-color:#00d478;
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
<div class='dashboard'>
	<br>
	<div class='dashboard_in'>
		<div class='logo_wrapper'>
			<div class='circle_wrapper'>
				<div class='check'><i class='fa fa-check'></i></div>
			</div>
		</div>
		<br>
		<div style='text-align:center'>
			<label>Customer</label>
			<p style='font-family:museo'><?= $customer_name ?></p>
			<p style='font-family:museo'><?= $complete_address ?></p>
			<p style='font-family:museo'><?= $customer_city ?></p>
			
			<label>Plafond</label>
			<p style='font-family:museo'>Rp. <?= number_format($customer->plafond,2) ?> - <strong>Rp. <?= number_format($submission->submitted_plafond,2) ?></strong></p>
			
			<label>Submitted by</label>
			<p style='font-family:museo'><?= $submission->created_by ?></p>
			
			<a href='<?= site_url('Plafond') ?>'><button type='button' class='button button_default_dark'><i class='fa fa-long-arrow-left'></i></button></a>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		var window_width	= $(document).width() - 100;
		var difference		= window_width * 0.5 - 200;		
		$('.logo_wrapper').css('margin-left', difference, 'important');
	});
</script>
<?php
	} else {
?>
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
<div class='dashboard'>
	<br>
	<div class='dashboard_in'>
		<div class='logo_wrapper'>
			<div class='circle_wrapper'>
				<div class='check'><i class='fa fa-times'></i></div>
			</div>
		</div>
		<br>
		<div style='text-align:center'>
			<h2>Plafond change submission failed</h2>
			<p>Please check for the last submission or try again later</p>
			<a href='<?= site_url('Plafond') ?>'><button type='button' class='button button_default_dark'><i class='fa fa-long-arrow-left'></i></button></a>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		var window_width	= $(document).width() - 100;
		var difference		= window_width * 0.5 - 200;		
		$('.logo_wrapper').css('margin-left', difference, 'important');
	});
</script>
<?php
	}
?>