<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-bar-chart'></i></a> /<a href='<?= site_url('Invoice') ?>'>Invoice </a> /Create invoice</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<button type='button' class='button button_mini_tab active' id='retail_button' disabled>Retail invoice</button>
		<button type='button' class='button button_mini_tab' id='coorporate_button'>Coorporate invoice</button>
		<br><br>
		
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
</div>
<form action='<?= site_url('Invoice/create_retail') ?>' method='POST' id='invoice_form'>
	<input type='hidden' id='delivery_order_id' name='id'>
</form>
<script>
	refresh_view(1);
	
	$('#retail_button').click(function(){
		$('#retail_button').attr('disabled', true);
		$('#coorporate_button').attr('disabled', false);
		$('#retail_button').addClass('active');
		$('#coorporate_button').removeClass('active');
		refresh_view(1, 1);
	});
	
	$('#coorporate_button').click(function(){
		$('#retail_button').attr('disabled', false);
		$('#coorporate_button').attr('disabled', true);
		$('#retail_button').removeClass('active');
		$('#coorporate_button').addClass('active');
		refresh_view(2,1);
	});
	
	function refresh_view(type = 1, page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Invoice/view_uninvoiced_delivery_orders') ?>',
			data:{
				type:type,
				page:page,
				term:$('#search_bar').val()
			},
			success:function(response){
				$('#delivery_order_tbody').html('');
				var delivery_order_array	= response.delivery_orders;
				$.each(delivery_order_array, function(i, delivery_order){
					var date					= delivery_order.date;
					var delivery_order_id		= delivery_order.id;
					var delivery_order_name		= delivery_order.name;
					var customer_name			= delivery_order.customer_name;
					var customer_address		= delivery_order.customer_address;
					var customer_city			= delivery_order.customer_city;
					var is_sent					= delivery_order.is_sent;
					
					if(is_sent == 1 && type == 2){
						$('#delivery_order_tbody').append("<tr><td>" + my_date_format(date) + "</td><td>" + delivery_order_name + "</td><td><p>" + customer_name + "</p><p>" + customer_address + "</p><p>" + customer_city + "</p></td><td><button class='button button_default_dark' onclick='submit_form(" + delivery_order_id + ")'><i class='fa fa-long-arrow-right'></i></button></td>");
					} else if(type == 1){
						$('#delivery_order_tbody').append("<tr><td>" + my_date_format(date) + "</td><td>" + delivery_order_name + "</td><td><p>" + customer_name + "</p><p>" + customer_address + "</p><p>" + customer_city + "</p></td><td><button class='button button_default_dark' onclick='submit_form(" + delivery_order_id + ")'><i class='fa fa-long-arrow-right'></i></button></td>");
					}
				});
				var pages					= response.pages;
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		});
	}
	
	function submit_form(n){
		$('#delivery_order_id').val(n);
		$('#invoice_form').submit();
	};
</script>