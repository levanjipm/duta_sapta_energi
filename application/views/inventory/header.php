<div class='topnav_bar'>
	<div class='topnav_bar_left'><a href='<?= site_url() ?>' style='text-decoration:none'><h1>DSE</h1></a></div>
	<div class='topnav_bar_right'>
		<button><i class='fa fa-bars' id='hide_side_nav_button'></i></button>
	</div>
</div>
<div class='sidenav_bar'>
	<h3 style='font-family:museo'>Main</h3>
	<a href='<?= site_url('Delivery_order') ?>'><button><p>Delivery order</p></button></a>
	<a href='<?= site_url('Good_receipt') ?>'><button class='container_button'><p style='font-family:museo'>Good receipt</p></button></a>
	<button class='container_button'><p style='font-family:museo'>Sales Order</p></button>
	<div class='container_bar'>
		<a href='<?= site_url('Sales_order') ?>'><p>Create sales order</p></a>
		<a href='<?= site_url('Sales_order/track_sales_order') ?>'><p>Track sales order</p></a>	
	</div>
	<a href='<?= site_url() ?>'><button><p>Check stock</p></button></a>
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