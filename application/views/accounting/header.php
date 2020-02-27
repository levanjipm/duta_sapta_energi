<div class='topnav_bar'>
	<div class='topnav_bar_left'><a href='<?= site_url() ?>' style='text-decoration:none'><h1>DSE</h1></a></div>
	<div class='topnav_bar_right'>
		<button><i class='fa fa-bars' id='hide_side_nav_button'></i></button>
	</div>
</div>
<div class='sidenav_bar'>
	<h3 style='font-family:museo'>Main</h3>
	<a href='<?= site_url('invoice') ?>'><button><p style='font-family:museo'>Invoice</p></button></a>
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