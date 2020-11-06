<div class='topnav_bar'>
<div style='width:50%;display:inline-block'><h3>Inventory</h3></div><div style='width:50%;display:inline-block;text-align:right;color:white'><?php if(!empty($user_login)){ ?><h4>Hello, <a href='<?= site_url('Profile') ?>' style='text-decoration:none;color:#fff'><?= $user_login->name ?> <img src='<?= $user_login->image_url == null ? base_url('assets/ProfileImages/defaultImage.png') : base_url("assets/ProfileImages/") . $user_login->image_url ?>' style='height:30px;border-radius:50%;width:30px'></img></a></h4><?php } else { ?><button type='button' class='button button_default_dark'>Login</button> <?php } ?></div>
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
		<button class='container_button'><p>Delivery order</p><i class='fa fa-caret-down'></i></button>
		<div class='container_bar'>
			<a href='<?= site_url('Delivery_order/createDashboard') ?>'><p>Create</p></a>
			<a href='<?= site_url('Delivery_order/confirmDashboard') ?>'><p>Confirm</p></a>
			<a href='<?= site_url('Delivery_order/archive') ?>'><p>Archive</p></a>	
		</div>
		<button class='container_button'><p style='font-family:museo'>Good receipt</p><i class='fa fa-caret-down'></i></button>
		<div class='container_bar'>
			<a href='<?= site_url('Good_receipt/createDashboard') ?>'><p>Create</p></a>
			<?php if($user_login->access_level > 1){ ?>
			<a href='<?= site_url('Good_receipt/confirmDashboard') ?>'><p>Confirm</p></a>
			<?php } ?>
			<a href='<?= site_url('Good_receipt/archiveDashboard') ?>'><p>Archive</p></a>	
		</div>
		<button class='container_button'><p style='font-family:museo'>Return</p><i class='fa fa-caret-down'></i></button>
		<div class='container_bar'>
			<a href='<?= site_url('Sales_return/receiveDashboard') ?>'><p>Sales</p></a>
			<a href='<?= site_url('Purchase_return/sendDashboard') ?>'><p>Purchase</p></a>
		</div>
		<a href='<?= site_url('Stock/view/Inventory') ?>'><button><p>Check stock</p></button></a>
		<button class='container_button'><p style='font-family:museo'>Cases</p><i class='fa fa-caret-down'></i></button>
		<div class='container_bar'>
			<a href='<?= site_url('Inventory_case') ?>'><p>Create</p></a>
			<a href='<?= site_url('Inventory_case/archiveDashboard') ?>'><p>Archive</p></a>
		</div>
		<button class='container_button'><p style='font-family:museo'><p>Pending</p><i class='fa fa-caret-down'></i></button>
		<div class='container_bar'>
			<a href='<?= site_url('Inventory/pendingDeliveryOrderDashboard') ?>'><p>Delivery Orders</p></a>
			<a href='<?= site_url('Inventory/pendingSalesOrderDashboard') ?>'><p>Sales Orders</p></a>
			<a href='<?= site_url('Inventory/pendingPurchaseOrderDashboard') ?>'><p>Purchase Orders</p></a>
		</div>
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
