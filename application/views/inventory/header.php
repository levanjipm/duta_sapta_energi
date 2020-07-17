<div class='topnav_bar'>
	<div style='width:50%;display:inline-block'><h3>Inventory</h3></div><div style='width:50%;display:inline-block;text-align:right'><?php if(!empty($user_login)){ ?><h4>Hello, <?= $user_login->name ?></h4><?php } else { ?><button type='button' class='button button_default_dark'>Login</button> <?php } ?></div>
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
		if($department->name == 'Inventory'){
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
		<button class='container_button'><p>Delivery order</p></button>
		<div class='container_bar'>
			<a href='<?= site_url('Delivery_order/createDashboard') ?>'><p>Create</p></a>
			<a href='<?= site_url('Delivery_order/confirmDashboard') ?>'><p>Confirm</p></a>
			<a href='<?= site_url('Delivery_order/archive') ?>'><p>Archive</p></a>	
		</div>
		<button class='container_button'><p style='font-family:museo'>Good receipt</p></button>
		<div class='container_bar'>
			<a href='<?= site_url('Good_receipt') ?>'><p>Create</p></a>	
			<a href='<?= site_url('Good_receipt/archive') ?>'><p>Archive</p></a>	
		</div>
		<a href='<?= site_url('Stock/view/Inventory') ?>'><button><p>Check stock</p></button></a>
		<a href='<?= site_url('Inventory_case') ?>'><button><p>Cases</p></button></a>
	</div>
</div>
<script>
	$('.container_button').click(function(){
		$('.container_bar').hide(300);
		$(this).next('.container_bar').toggle(300);
		$('.active').removeClass('active');
		$(this).addClass('active');
	});
	
	$('#hide_side_nav_button').click(function(){
		$('.sidenav_bar').toggle(300);
		$('.dashboard').css('margin-left',0);
	});
</script>