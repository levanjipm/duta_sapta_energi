<div class='topnav_bar'>
	<h3>Accounting</h3>
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
		if($department->name == 'Accounting'){
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
		<button class='container_button'><p>Invoice</p></button>
		<div class='container_bar'>
			<a href='<?= site_url('Invoice') ?>'><button><p style='font-family:museo'>Create</p></button></a>
			<?php if($user_login->access_level > 1){ ?>
			<a href='<?= site_url('Invoice/confirmDashboard') ?>'><button><p>Confirm</p></button></a>
			<?php } ?>
			<a href='<?= site_url('Invoice/archiveDashboard') ?>'><button><p style='font-family:museo'>Archive</p></button></a>
		</div>
		
		<button class='container_button'><p>Debt Document</p></button>
		<div class='container_bar'>
			<a href='<?= site_url('Debt') ?>'><button><p style='font-family:museo'>Create</p></button></a>
			<a href='<?= site_url('Debt/confirmDashboard') ?>'><button><p style='font-family:museo'>Confirm</p></button></a>
			<a href='<?= site_url('Debt/typeDashboard') ?>'><button><p style='font-family:museo'>Types</p></button></a>
			<a href='<?= site_url('Debt/archiveDashboard') ?>'><button><p style='font-family:museo'>Archive</p></button></a>
		</div>
		<a href='<?= site_url('Receivable') ?>'><button><p style='font-family:museo'>Receivable</p></button></a>
		<a href='<?= site_url('Payable') ?>'><button><p style='font-family:museo'>Payable</p></button></a>
		<a href='<?= site_url('Bank/assign') ?>'><button><p style='font-family:museo'>Assign bank</p></button></a>
		<button class='container_button'>Asset</button>
		<div class='container_bar'>
			<a href='<?= site_url('Asset') ?>'><button><p style='font-family:museo'>List</p></button></a>
			<a href='<?= site_url('Asset/calculateValue') ?>'><button><p style='font-family:museo'>Value</p></button></a>
			<a href='<?= site_url('Asset/class_dashboard') ?>'><button><p style='font-family:museo'>Class</p></button></a>
		</div>
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