<div class='topnav_bar'>
	<div class='topnav_bar_left'><a href='<?= site_url() ?>' style='text-decoration:none'><h1>DSE</h1></a></div>
	<div class='topnav_bar_right'>
		<button><i class='fa fa-bars' id='hide_side_nav_button'></i></button>
	</div>
</div>
<div class='sidenav_bar'>
	<h3 style='font-family:museo'>Main</h3>
	<button class='container_button'><p style='font-family:museo'>Bank</p></button>
	<div class='container_bar'>
		<a href='<?= site_url('Bank/transaction') ?>'><button><p style='font-family:museo'>Transaction</p></button></a>
		<a href='<?= site_url('Bank/account') ?>'><button><p style='font-family:museo'>Account</p></button></a>
	</div>
</div>
<script>
	$('.container_button').click(function(){
		$(this).next('.container_bar').toggle(300);
	});
</script>