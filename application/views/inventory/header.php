<div class='topnav_bar'>
	<h3>Inventory</h3>
</div>
<div class='sidenav_bar'>
	<a href='<?= site_url() ?>'><img src='<?= base_url('assets/Logo_light.png') ?>' style='width:80%'></a>
	<br><br>
	<button class='container_button'><p>Delivery order</p></button>
	<div class='container_bar'>
		<a href='<?= site_url('Delivery_order') ?>'><p>Create</p></a>	
		<a href='<?= site_url('Delivery_order/check') ?>'><p>Check</p></a>	
		<a href='<?= site_url('Delivery_order/archive') ?>'><p>Archive</p></a>	
	</div>
	<button class='container_button'><p style='font-family:museo'>Good receipt</p></button>
	<div class='container_bar'>
		<a href='<?= site_url('Good_receipt') ?>'><p>Create</p></a>	
		<a href='<?= site_url('Good_receipt/archive') ?>'><p>Archive</p></a>	
	</div>
	<a href='<?= site_url('Stock/view/Inventory') ?>'><button><p>Check stock</p></button></a>
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