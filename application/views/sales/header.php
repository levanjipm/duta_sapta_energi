<div class='topnav_bar'>
	<div style='width:50%;display:inline-block'><h3>Sales</h3></div><div style='width:50%;display:inline-block;text-align:right;color:white'><?php if(!empty($user_login)){ ?><h4>Hello, <a href='<?= site_url('Profile') ?>'><?= $user_login->name ?></a></h4><?php } else { ?><button type='button' class='button button_default_dark'>Login</button> <?php } ?></div>
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
		if($department->name == 'Sales'){
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
		<button class='container_button'><p style='font-family:museo'>Customer <i class='fa fa-caret-down'></i></p></button>
		<div class='container_bar'>
			<a href='<?= site_url('Customer') ?>'><button><p>Manage</p></button></a>
			<a href='<?= site_url('Customer/plafond') ?>'><button><p>Plafond</p></button></a>
			<a href='<?= site_url('Customer/check_plafond_status') ?>'><button><p>Plafond Submission status</p></button></a>
		</div>
		<a href='<?= site_url('Area') ?>'><button><p>Area</p></button></a>
		<a href='<?= site_url('Item') ?>'><button><p>Item</p></button></a>
		<a href='<?= site_url('Item_class') ?>'><button><p>Item class</p></button></a>
		<button class='container_button'><p style='font-family:museo'>Sales order  <i class='fa fa-caret-down'></i></p></button>
		<div class='container_bar'>
			<a href='<?= site_url('Sales_order') ?>'><p>Create</p></a>
			<a href='<?= site_url('Sales_order/track') ?>'><p>Track</p></a>
		<?php if($user_login->access_level > 2) { ?>
			<a href='<?= site_url('Sales_order/close_confirmation') ?>'>Close</p></a>
		<?php } ?>
			<a href='<?= site_url('Sales_order/archive') ?>'>Archive</a>
		</div>
		<button class='container_button'><p style='font-family:museo'>Return  <i class='fa fa-caret-down'></i></p></button>
		<div class='container_bar'>
			<a href='<?= site_url('Sales_return/create') ?>'><p>Create</p></a>
			<a href='<?= site_url('Sales_return/confirm') ?>'><p>Confirm</p></a>	
		</div>
		<a href='<?= site_url('Stock/view/Sales') ?>'><button><p>Check stock</p></button></a>
		<a href='<?= site_url('') ?>'><button><p>Analytic</p></button></a>
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