<div class='dashboard'>
	<h2 style='font-family:bebasneue'>Invoice</h2>
	<p style='font-family:museo'>Create invoice</p>
	<hr>
	<a href='<?= site_url('Invoice/create') ?>'><button type='button' class='button button_default_light'>Create invoice</button></a>
	
	<input type='text' class='form-control' id='search_bar'>
	<br>
	<table class='table table-bordered' id='delivery_order_table'>
		<tr>
			<th>Date</th>
			<th>Name</th>
			<th>Customer</th>
			<th>Action</th>
		</tr>
		<tbody id='delivery_order_tbody'></tbody>
	</table>
	
	<select class='form-control' id='page' style='width:100px'>
		<option value='1'>1</option>
	</select>
</div>
<form action='<?= site_url('Invoice/create_retail') ?>' method='POST' id='invoice_form'>
	<input type='hidden' id='delivery_order_id' name='id'>
</form>
<script>
	refresh_view();
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Invoice/view_uninvoiced_retail_delivery_orders') ?>',
			data:{
				page:page,
				term:$('#search_bar').val()
			},
			success:function(response){
				var delivery_order_array	= response.delivery_orders;
				$.each(delivery_order_array, function(i, delivery_order){
					var date					= delivery_order.date;
					var delivery_order_id		= delivery_order.id;
					var delivery_order_name		= delivery_order.name;
					var customer_name			= delivery_order.customer_name;
					var customer_address		= delivery_order.customer_address;
					var customer_city			= delivery_order.customer_city;
					
					$('#delivery_order_tbody').append("<tr><td>" + date + "</td><td>" + delivery_order_name + "</td><td><p>" + customer_name + "</p><p>" + customer_address + "</p><p>" + customer_city + "</p></td><td><button onclick='submit_form(" + delivery_order_id + ")'><i class='fa fa-long-arrow-right'></i></button></td>");
				});
				var pages					= response.pages;
			}
		});
	}
	
	function submit_form(n){
		$('#delivery_order_id').val(n);
		$('#invoice_form').submit();
	};
</script>