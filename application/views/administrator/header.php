<div class='topnav_bar'>
	<div style='width:50%;display:inline-block'><h3>Administrators</h3></div><div style='width:50%;display:inline-block;text-align:right;color:white'><?php if(!empty($user_login)){ ?><h4>Hello, <a href='<?= site_url('Profile') ?>' style='text-decoration:none;color:#fff'><?= $user_login->name ?> <img src='<?= $user_login->image_url == null ? base_url('assets/ProfileImages/defaultImage.png') : base_url('assets/ProfileImages/') . $user_login->image_url ?>' style='height:30px;border-radius:50%;width:30px'></img></a></h4><?php } else { ?><button type='button' class='button button_default_dark'>Login</button> <?php } ?></div>
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
	$departmentArray		= array();
	foreach($departments as $department){
		array_push($departmentArray, (int)$department->department_id);
?>
		<button class='button_departments' onclick='window.location.href="<?= site_url($department->index_url) ?>"' title='<?= $department->name ?>'><img src='<?= base_url() . 'assets/' . $department->icon . '.png' ?>' style='width:100%;filter: brightness(0) invert(1);'></button>
		<br><br>
<?php
	}
?>
	</div>
	<div class='sidenav_bar_functions'>
		<?php if(in_array(1, $departmentArray)){ ?>
		<button class='container_button'><p>Accounting</p></button>
		<div class='container_bar'>
			<a href='<?= site_url('Invoice/deleteDashboard') ?>'><button><p style='font-family:museo'>Delete Invoice</p></button></a>
			<a href='<?= site_url('Debt/deleteDashboard') ?>'><button><p style='font-family:museo'>Delete Debt</p></button></a>
		</div>
		<?php } ?>
		<?php if(in_array(5, $departmentArray)){ ?>
		<button class='container_button'><p>Finance</p></button>
		<div class='container_bar'>
			<a href='<?= site_url('Bank/deleteDashboard') ?>'><button><p style='font-family:museo'>Delete Bank Data</p></button></a>
			<a href='<?= site_url('Petty_cash/deleteDashboard') ?>'><button><p style='font-family:museo'>Delete Petty Cash Data</p></button></a>
		</div>
		<?php } ?>
		<?php if(in_array(4, $departmentArray)){ ?>
		<button class='container_button'><p>Inventory</p></button>
		<div class='container_bar'>
			<a href='<?= site_url('Delivery_order/deleteDashboard') ?>'><button><p style='font-family:museo'>Delete Delivery Order</p></button></a>
			<a href='<?= site_url('Good_receipt/deleteDashboard') ?>'><button><p style='font-family:museo'>Delete Good Receipt</p></button></a>
		</div>
		<?php } ?>
		<?php if(in_array(3, $departmentArray)){ ?>
		<button class='container_button'><p>Purchasing</p></button>
		<div class='container_bar'>
			<a href='<?= site_url('Purchase_order/editDashboard') ?>'><button><p style='font-family:museo'>Edit Purchase Order</p></button></a>
			<a href='<?= site_url('Purchase_order/closeDashboard') ?>'><button><p style='font-family:museo'>Close Purchase Order</p></button></a>
		</div>
		<?php } ?>
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
