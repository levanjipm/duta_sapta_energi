<div class='topnav_bar'>
	<div style='width:50%;display:inline-block'><h3>Finance</h3></div><div style='width:50%;display:inline-block;text-align:right'><?php if(!empty($user_login)){ ?><h4>Hello, <?= $user_login->name ?></h4><?php } else { ?><button type='button' class='button button_default_dark'>Login</button> <?php } ?></div>
</div>
<div class='sidenav_bar'>
	<a href='<?= site_url() ?>'><img src='<?= base_url('assets/Logo_light.png') ?>' style='width:80%'></a>
	<br><br>
	<button class='container_button'><p style='font-family:museo'>Bank</p></button>
	<div class='container_bar'>
		<a href='<?= site_url('Bank/transaction') ?>'><button><p style='font-family:museo'>Transaction</p></button></a>
		<a href='<?= site_url('Bank/account') ?>'><button><p style='font-family:museo'>Account</p></button></a>
		<a href='<?= site_url('Bank/mutation') ?>'><button><p style='font-family:museo'>Mutation</p></button></a>
		<a href='<?= site_url('Bank/opponent') ?>'><button><p style='font-family:museo'>Opponent</p></button></a>
	</div>
	<button class='container_button'><p style='font-family:museo'>Petty cash</p></button>
	<div class='container_bar'>
		<a href='<?= site_url('Petty_cash/transaction') ?>'><button><p style='font-family:museo'>Transaction</p></button></a>
		<a href='<?= site_url('Petty_cash/mutation') ?>'><button><p style='font-family:museo'>Mutation</p></button></a>
		<a href='<?= site_url('Petty_cash/balance') ?>'><button><p style='font-family:museo'>Balance</p></button></a>
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
</div>
<script>
	$('.container_button').click(function(){
		$(this).next('.container_bar').toggle(300);
	});
</script>