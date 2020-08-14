<div class='topnav_bar'>
	<h3>Finance</h3>
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
		if($department->name == 'Finance'){
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
		<button class='container_button'><p style='font-family:museo'>Bank</p></button>
		<div class='container_bar'>
			<a href='<?= site_url('Bank/transactionDashboard') ?>'><button><p style='font-family:museo'>Transaction</p></button></a>
			<a href='<?= site_url('Bank/accountDashboard') ?>'><button><p style='font-family:museo'>Account</p></button></a>
			<a href='<?= site_url('Bank/mutationDashboard') ?>'><button><p style='font-family:museo'>Mutation</p></button></a>
			<a href='<?= site_url('Bank/opponent') ?>'><button><p style='font-family:museo'>Opponent</p></button></a>
		</div>
		<button class='container_button'><p style='font-family:museo'>Petty cash</p></button>
		<div class='container_bar'>
			<a href='<?= site_url('Petty_cash/transaction') ?>'><button><p style='font-family:museo'>Transaction</p></button></a>
			<a href='<?= site_url('Petty_cash/mutation') ?>'><button><p style='font-family:museo'>Mutation</p></button></a>
		</div>
		<button class='container_button'><p style='font-family:museo'>Expense</p></button>
		<div class='container_bar'>
			<a href='<?= site_url('Expense/class') ?>'><button><p style='font-family:museo'>Class</p></button></a>
			<a href='<?= site_url('Expense/report') ?>'><button><p style='font-family:museo'>Report</p></button></a>
		</div>
		<button class='container_button'><p style='font-family:museo'>Income</p></button>
		<div class='container_bar'>
			<a href='<?= site_url('Income/class') ?>'><button><p style='font-family:museo'>Class</p></button></a>
			<a href='<?= site_url('Income/report') ?>'><button><p style='font-family:museo'>Report</p></button></a>
		</div>
		<button class='container_button'><p style='font-family:museo'>Billing</p></button>
		<div class='container_bar'>
			<a href='<?= site_url('Billing/createDashboard') ?>'><button><p style='font-family:museo'>Create</p></button></a>
			<a href='<?= site_url('Billing/confirmDasbhoard') ?>'><button><p style='font-family:museo'>Confirm</p></button></a>
		</div>
	</div>
</div>
<script>
	$('.container_button').click(function(){
		$(this).next('.container_bar').toggle(300);
	});
</script>