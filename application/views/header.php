<style>
	.department_box{
		width:80%;
		background-color:black;
		border-radius:10px;
		margin-top:20px;
		cursor:pointer;
	}
	
	.department_box_head{
		background-image: linear-gradient(to right, #E19B3C,black);
		padding:10px;
		color:white;
		border-top-right-radius:10px;
		border-top-left-radius:10px;
		opacity: 0.8;
	}
	
	.department_box_body{
		background-color:black;
		padding:10px;
		border-bottom-right-radius:10px;
		border-bottom-left-radius:10px;
		text-align:right;
	}
	
	.department_box_body img{
		opacity:0.8;
		width:30%;
		min-width:50px;
		margin-top:-20px;
	}
	
	html, body{
		background-color:#2B2F38;
	}
</style>
<div class='topnav_bar' style='padding-left:8px!important;text-align:center;color:white'>
	<h4>Hello, <?= $user_login->name ?></h4>
</div>
<div class='row' style='padding:20px; padding-top:90px; margin:0;'>
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
?>
</div>
<script>
	function adjust_size(){
		var min_size	= 100000;
		$('.department_image').each(function(){
			if($(this).height() < min_size && $(this).height() > 0){
				min_size	= $(this).height();
			}
		});		
		$('.department_image').height(min_size);
	}
	
	adjust_size();
	
	$(window).resize(function(){
		adjust_size();
	});
</script>