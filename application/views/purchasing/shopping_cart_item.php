<div class='row' style='text-align:center'>
	<div class='col-lg-2 col-md-2 col-sm-4 col-xs-4 col-lg-offset-5 col-md-offset-5 col-sm-offset-4 col-sm-offset-4'>
		<button type='button' class='button alert_full_close_button' title='Close add item session'></button>
	</div>
</div>
<div class='row'>
	<div class='col-xs-12'>
		<h2 style='font-family:bebasneue'>Add item to cart</h2>
		<hr>
		<label>Search</label>
		<input type='text' class='form-control' id='search_bar'>
		<br>
		<div id='item_view_pane'></div>
	</div>
</div>
<script>
	$.ajax({
		url:'<?= site_url('Item/search_item_cart') ?>',
		data:{
			term:'',
			page:1,
		},
		success:function(response){
			$('#item_view_pane').html(response);
		}
	});
	
	function refresh_view(){
		$.ajax({
			url:'<?= site_url('Item/search_item_cart') ?>',
			data:{
				term:$('#search_bar').val(),
				page:$('#page').val(),
			},
			success:function(response){
				$('#item_view_pane').html(response);
			}
		});
	}
	
	$('#search_bar').change(function(){
		refresh_view();
	});
	
	$('.alert_full_close_button').click(function(){
		$('#add_item_wrapper').fadeOut();
	});
</script>