<div class='topnav_bar'>
<div style='width:50%;display:inline-block'><h3>Purchasing</h3></div><div style='width:50%;display:inline-block;text-align:right;color:white'><?php if(!empty($user_login)){ ?><h4>Hello, <a href='<?= site_url('Profile') ?>' style='text-decoration:none;color:#fff'><?= $user_login->name ?> <img src='<?= $user_login->image_url == null ? base_url('assets/ProfileImages/defaultImage.png') : base_url("assets/ProfileImages/") . $user_login->image_url ?>' style='height:30px;border-radius:50%'></img></a></h4><?php } else { ?><button type='button' class='button button_default_dark'>Login</button> <?php } ?></div>
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
		if($department->name == 'Purchasing'){
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
		<button class='container_button'><p style='font-family:museo'>Purchase order</p><i class='fa fa-caret-down'></i></button>
		<div class='container_bar'>
			<a href='<?= site_url('Purchase_order/createDashboard') ?>'><p>Create</p></a>
			<a href='<?= site_url('Purchase_order/confirmDashboard') ?>'><p>Confirm</p></a>
			<a href='<?= site_url('Purchase_order/pending') ?>'><p>Pending</p></a>	
			<a href='<?= site_url('Purchase_order/archiveDashboard') ?>'><p>Archive</p></a>	
		</div>
		<a href='<?= site_url('Supplier') ?>'><button><p>Supplier</p></button></a>
		<button class='container_button'><p style='font-family:museo'>Return</p><i class='fa fa-caret-down'></i></button>
		<div class='container_bar'>
			<a href='<?= site_url('Purchase_return') ?>'><p>Create</p></a>
			<?php if($user_login->access_level > 2){ ?>
			<a href='<?= site_url('Purchase_return/confirmDashboard') ?>'><p>Confirm</p></a>
			<?php } ?>
			<a href='<?= site_url('Purchase_return/archiveDashboard') ?>'><p>Archive</p></a>	
		</div>
		<a href='<?= site_url('Purchase_report') ?>'><button><p>Report</p></button></a>
	</div>
</div>
<script>
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
</script>
