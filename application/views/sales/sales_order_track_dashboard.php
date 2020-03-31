<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Sales_order') ?>'>Sales order</a>/ Track</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<input type='text' class='form-control' id='search_bar'>
		<br>
		<table class='table table-bordered'>
			<tr>
				<th>Date</th>
				<th>Name</th>
				<th>Seller</th>
				<th>Customer</th>
				<th>Action</th>
			</tr>
			<tbody id='sales_order_table'></tbody>
		</table>
		
		<select class='form-control' id='page' style='width:100px'>
			<option value='1'>1</option>
		</select>
	</div>
</div>

<div class='alert_wrapper' id='track_sales_order_wrapper'>
	<button type='button' class='alert_close_button'>&times </button>
	<div class='alert_box_default'>
		<label>Sales order</label>
		<p style='font-family:museo' id='sales_order_name_p'></p>
		<p style='font-family:museo' id='sales_order_date_p'></p>
		
		<label>Customer</label>
		<div id='customer_p'></div>
		
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
				<th>Sent</th>
				<th>Pending</th>
			</tr>
			<tbody id='sales_order_detail_table'></tbody>
		</table>
		
		<table class='table table-bordered' id='delivery_order_history_table'>
			<tr>
				<th>Date</th>
				<th>Name</th>
				<th>Status</th>
			</tr>
			<tbody id='delivery_order_detail_table'></tbody>
		</table>
		<p style='font-family:museo' id='delivery_order_notice' style='display:none'>There is no delivery history found</p>
		<form action='<?= site_url('Sales_order/close') ?>' method='POST'>
			<input type='hidden' id='sales_order_id' name='id'>
			<button class='button button_danger_dark' title='Request to close sales order' id='close_sales_order_button'><i class='fa fa-file-text-o'></i></button>
		</form>
	</div>
</div>
<script>
	$('#close_sales_order_button').click(function(){
		
	});
	
	function track(n){
		var customer_data		= $('#customer_table_p-' + n).html();
		var sales_order_date	= $('#date_table_p-' + n).html();
		var sales_order_name	= $('#name_table_p-' + n).html();
		
		$('#sales_order_date_p').html(sales_order_date);
		$('#sales_order_name_p').html(sales_order_name);
		$('#customer_p').html(customer_data);
		$('#sales_order_id').val(n);
		
		$.ajax({
			url:'<?= site_url('Sales_order/view_track_detail') ?>',
			data:{
				id:n
			},
			success:function(response){
				$('#sales_order_detail_table').html('');
				$.each(response, function(index, sales_order){
					var name		= sales_order.name;
					var reference	= sales_order.reference;
					var sent		= sales_order.sent;
					var quantity	= sales_order.quantity;
					var pending		= quantity - sent;
					
					$('#sales_order_detail_table').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>" + numeral(sent).format('0,0') + "</td><td>" + numeral(pending).format('0,0') + "</td></tr>");
				});
			}
		});
		
		$.ajax({
			url:'<?= site_url('Delivery_order/show_by_sales_order') ?>',
			data:{
				sales_order_id:n
			},
			success:function(response){
				if(response.length == 0){
					$('#delivery_order_history_table').hide();
					$('#delivery_order_notice').show();
				} else {
					console.log(response);
					$('#delivery_order_detail_table').html('');
					$.each(response, function(index, value){
						var date		= value.date;
						var invoice_id	= value.invoice_id;
						var is_confirm	= value.is_confirm;
						var is_sent		= value.is_sent;
						var name		= value.name;
						if(is_confirm == '1' && is_sent == '0'){
							var text	= 'On delivery process';
						} else if(is_sent == '1' && invoice_id == null){
							var text	= 'On invoicing process';
						} else {
							var text	= 'Pending';
						}
						
						$('#delivery_order_detail_table').append("<tr><td>" + date + "</td><td>" + name + "</td><td>" + text + "</td></tr>");
					});
					
					$('#delivery_order_history_table').show();
					$('#delivery_order_notice').hide();
				}
			}
		});
		
		
		
		$('#track_sales_order_wrapper').fadeIn();
	}
	
	$('.alert_close_button').click(function(){
		$(this).parent().fadeOut();
	});
	
	refresh_view();
	$('#page').change(function(){
		refresh_view();
	});
	
	$('#search_bar').change(function(){
		refresh_view(1);
	});
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Sales_order/view_track') ?>',
			data:{
				term:$('#search_bar').val(),
				page: page
			},
			success:function(response){
				var pages			= response.pages;
				var page			= $('#page').val();
				var sales_orders = response.sales_orders;
				$('#sales_order_table').html('');
				$.each(sales_orders, function(index, sales_order){
					var id					= sales_order.id;
					var date				= sales_order.date;
					var name				= sales_order.name;
					var customer_name		= sales_order.customer_name;
					var customer_address	= sales_order.customer_address;
					var customer_city		= sales_order.customer_city;
					var seller				= sales_order.seller;
					if(seller == null){
						seller				= "<i>Not available</i>";
					}
					
					$('#sales_order_table').append("<tr><td id='date_table_p-" + id + "'>" + date + "</td><td id='name_table_p-" + id + "'>" + name + "<td>" + seller + "</p></td><td id='customer_table_p-" + id + "'><p>" + customer_name +"</p><p>" + customer_address + "</p><p>" + customer_city + "</p></td><td><button type='button' class='button button_default_dark' onclick='track(" + id + ")'><i class='fa fa-search-plus'></i></button></td></tr>");
				});
				$('#page').html('');
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
</script>