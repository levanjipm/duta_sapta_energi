<div class='topnav_bar'>
	<div class='topnav_bar_left'><a href='<?= site_url() ?>' style='text-decoration:none'><h1>DSE</h1></a></div>
	<div class='topnav_bar_right'>
		<button><i class='fa fa-bars' id='hide_side_nav_button'></i></button>
	</div>
</div>
<div class='sidenav_bar'>
	<h3 style='font-family:museo'>Main</h3>
	<a href='<?= site_url('Purchase_order') ?>'><button><p style='font-family:museo'>Purchase order</p></button></a>
	<a href='<?= site_url('Supplier') ?>'><button><p>Supplier</p></button></a>
	<a href='<?= site_url('Item') ?>'><button><p>Item</p></button></a>
	<a href='<?= site_url('Item_class') ?>'><button><p>Item class</p></button></a>
	<button class='container_button'><p style='font-family:museo'>Purchase order</p></button>
	<div class='container_bar'>
		<a href='<?= site_url('Sales_order/create_sales_order') ?>'><p>Create purchase order</p></a>
		<a href='<?= site_url() ?>'><p>Track purchase order</p></a>	
	</div>
	<button class='container_button'><p style='font-family:museo'>Return</p></button>
	<div class='container_bar'>
		<a href='<?= site_url('Sales') ?>'><p>Create return</p></a>
		<a href='<?= site_url() ?>'><p>Confirm return</p></a>	
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