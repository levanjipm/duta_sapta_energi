<div class='topnav_bar'>
<div style='width:50%;display:inline-block'><h3>Sales</h3></div><div style='width:50%;display:inline-block;text-align:right;color:white'><?php if(!empty($user_login)){ ?><h4>Hello, <a href='<?= site_url('Profile') ?>' style='text-decoration:none;color:#fff'><?= $user_login->name ?> <img src='<?= $user_login->image_url == null ? base_url('assets/ProfileImages/defaultImage.png') : base_url("assets/ProfileImages/") . $user_login->image_url ?>' style='height:30px;border-radius:50%;width:30px'></img></a></h4><?php } else { ?><button type='button' class='button button_default_dark'>Login</button> <?php } ?></div>
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
		<button class='container_button'><p>Quotation</p><i class='fa fa-caret-down'></i></button>
		<div class='container_bar'>
			<a href='<?= site_url("Quotation/createDashboard") ?>'><button><p>Create</p></button></a>
			<a href='<?= site_url("Quotation/confirmDashboard") ?>'><button><p>Confirm</p></button></a>
			<a href='<?= site_url("Quotation/archiveDashboard") ?>'><button><p>Archive</p></button></a>
		</div>
		<button class='container_button'><p>Customer</p><i class='fa fa-caret-down'></i></button>
		<div class='container_bar'>
			<a href='<?= site_url('Customer') ?>'><button><p>Manage</p></button></a>
			<a href='<?= site_url('Plafond') ?>'><button><p>Plafond</p></button></a>
			<?php if($user_login->access_level > 2){ ?>
			<a href='<?= site_url('Plafond/confirmDashboard') ?>'><button><p>Plafond Submission status</p></button></a>
			<?php } ?>
		</div>
		<a href='<?= site_url('Area') ?>'><button><p>Area</p></button></a>
		<a href='<?= site_url('Item') ?>'><button><p>Item</p></button></a>
		<a href='<?= site_url('Item_class') ?>'><button><p>Item class</p></button></a>
		<button class='container_button'><p>Sales order</p><i class='fa fa-caret-down'></i></button>
		<div class='container_bar'>
			<a href='<?= site_url('Sales_order/createDashboard') ?>'><p>Create</p></a>
			<?php if($user_login->access_level > 1){ ?>
			<a href='<?= site_url('Sales_order/confirmDashboard') ?>'><p>Confirm</p></a>
			<?php } ?>
			<a href='<?= site_url('Sales_order/trackDashboard') ?>'><p>Track</p></a>
			<a href='<?= site_url('Sales_order/closeSalesOrderDashboard') ?>'>Close</p></a>
		<?php if($user_login->access_level > 1) { ?>
			<a href='<?= site_url('Sales_order/confirmCloseSalesOrderDashboard') ?>'><p>Confirm close</p></a>
		<?php } ?>
			<a href='<?= site_url('Sales_order/archiveDashboard') ?>'>Archive</a>
		</div>
		<button class='container_button'><p>Return</p><i class='fa fa-caret-down'></i></button>
		<div class='container_bar'>
			<a href='<?= site_url('Sales_return/createDashboard') ?>'><p>Create</p></a>
			<a href='<?= site_url('Sales_return/confirmDashboard') ?>'><p>Confirm</p></a>
			<a href='<?= site_url('Sales_return/archiveDashboard') ?>'><p>Archive</p></a>	
		</div>
		<a href='<?= site_url('Stock/view/Sales') ?>'><button><p>Check stock</p></button></a>
		<?php if($user_login->access_level > 1){ ?>
		<button class='container_button'><p>Visit List</p><i class='fa fa-caret-down'></i></button>
		<div class='container_bar'>
			<a href='<?= site_url('Visit_list/createDashboard') ?>'><p>Create</p></a>
			<a href='<?= site_url('Visit_list/confirmDashboard') ?>'><p>Confirm</p></a>
			<a href='<?= site_url('Visit_list/reportDashboard') ?>'><p>Report</p></a>
			<a href='<?= site_url('Visit_list/archiveDashboard') ?>'><p>Archive</p></a>	
			<a href='<?= site_url('Visit_list/recapDashboard') ?>'><p>Recap</p></a>
		</div>
		<?php } ?>
		<?php if($user_login->access_level > 2){ ?>
		<a href="<?= site_url('CustomerSales') ?>"><button><p>Assign sales</p></button></a>
		<a href='<?= site_url('SalesAnalytics') ?>'><button><p>Analytic</p></button></a>
		<?php } ?>
		<button class='container_button'><p>Promotion</p><i class='fa fa-caret-down'></i></button>
		<div class='container_bar'>
			<a href='<?= site_url('Promotion/createDashboard') ?>'><p>Create</p></a>
			<?php if($user_login->access_level > 2){ ?>
			<a href='<?= site_url('Promotion/confirmDashboard') ?>'><p>Confirm</p></a>
			<?php } ?>
			<a href='<?= site_url('Promotion/archiveDashboard') ?>'><p>Archive</p></a>
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
