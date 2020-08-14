<head>
	<title>Sales order - Archive</title>
	<style>
		.archive_row{
			padding:10px;
			border-bottom:1px solid #e2e2e2;
		}
	</style>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Sales order') ?>'>Sales order</a> / Archive</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='row'>
			<div class='col-md-2 col-sm-3 col-xs-4'>
				<select class='form-control' id='month'>
<?php
	for($i = 1; $i <= 12; $i++){
?>
					<option value='<?= $i ?>' <?php if($i == date('m')){ echo('selected');} ?>><?= date('F', mktime(0,0,0,$i, 1)) ?></option>
<?php
	}
?>
				</select>
			</div>
			<div class='col-md-2 col-sm-3 col-xs-4'>
				<select class='form-control' id='year'>
<?php
	foreach($years as $year){
?>
					<option value='<?= $year->year ?>' <?php if($year->year == date('Y')){ echo('selected');} ?>><?= $year->year ?></option>
<?php
	}
?>
				</select>
			</div>
			<div class='col-md-6 col-sm-4 col-xs-4 col-md-offset-2 col-sm-offset-2 col-xs-offset-0'>
				<div class='input_group'>
					<input type='text' class='form-control-text-custom' id='search_bar' placeholder='Search'>
					<div class='input_group_append'>
						<button type='button' class='button button_default_dark' id='search_button'><i class='fa fa-search'></i></button>
					</div>
				</div>
			</div>
		</div>
		<br><br>
		<div id='archive_table'>
		</div><br>
		
		<select class='form-control' id='page' style='width:100px'>
			<option value='1'>1</option>
		</select>
	</div>
</div>

<div class='alert_wrapper' id='view_good_receipt_wrapper'>
	<button type='button' class='slide_alert_close_button'>&times </button>
	<div class='alert_box_slide'>
		<label>Sales order</label>
		<p style='font-family:museo' id='sales_order_name_p'></p>
		<p style='font-family:museo' id='sales_order_date_p'></p>
		<p style='font-family:museo' id='sales_order_seller_p'></p>
		
		<label>Taxing</label>
		<p style='font-family:museo' id='taxing_name_p'></p>
		
		<label>Invoicing method</label>
		<p style='font-family:museo' id='invoicing_method_p'></p>
		
		<label>Customer</label>
		<p style='font-family:museo' id='customer_name_p'></p>
		<p style='font-family:museo' id='customer_address_p'></p>
		<p style='font-family:museo' id='customer_city_p'></p>
		
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Price list</th>
				<th>Discount</th>
				<th>Net price</th>
				<th>Quantity</th>
				<th>Total price</th>
			</tr>
			<tbody id='sales_order_table'></tbody>
		</table>
	</div>
</div>

<script>
	$('#search_button').click(function(){
		refresh_view(1);
	});
	
	$('#page').change(function(){
		refresh_view();
	});
	
	refresh_view();
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Sales_order/archiveView') ?>',
			data:{
				year: $('#year').val(),
				month: $('#month').val(),
				page:page,
				term:$('#search_bar').val()
			},
			success:function(response){
				$('#page').html('');
				var pages		= response.pages;
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
				
				$('#archive_table').html('');
				
				var sales_orders		= response.sales_orders;
				
				$.each(sales_orders, function(index, sales_order){
					var seller					= sales_order.seller;
					var is_approved				= sales_order.is_approved;
					var approved_date			= sales_order.approved_date;
					
					if(seller	== null){
						seller		= "<i>Not available</i>";
					}
					
					var sales_order_name		= sales_order.name;
					var sales_order_id			= sales_order.id;
					var sales_order_date		= sales_order.date;
					var complete_address		= '';
					var customer_name			= sales_order.customer_name;
					complete_address			+= sales_order.address;
					var customer_city			= sales_order.city;
					var customer_number			= sales_order.number;
					var customer_rt				= sales_order.rt;
					var customer_rw				= sales_order.rw;
					var customer_postal			= sales_order.postal_code;
					var customer_block			= sales_order.block;
		
					if(customer_number != ''){
						complete_address	+= ' No. ' + customer_number;
					}
					
					if(customer_block != ''){
						complete_address	+= ' Blok ' + customer_block;
					}
				
					if(customer_rt != '000'){
						complete_address	+= ' RT ' + customer_rt;
					}
					
					if(customer_rw != '000' && customer_rt != '000'){
						complete_address	+= ' /RW ' + customer_rw;
					}
					
					if(customer_postal != null){
						complete_address	+= ', ' + customer_postal;
					}
					var is_confirm		= sales_order.is_confirm;
					if(is_confirm == 0){
						$('#archive_table').append("<div class='row archive_row'><div class='col-md-3 col-sm-3 col-xs-4'><p><strong>" + sales_order_name + "</strong></p><p>" + seller + "</p></div><div class='col-md-3 col-sm-3 col-xs-3'><p><strong>" + customer_name + "</strong></p><p>" + complete_address + "</p><p>" + customer_city + "</p></div><div class='col-md-4 col-sm-5 col-xs-5 col-md-offset-2 col-sm-offset-1 col-xs-offset-2'><p style='display:inline-block'>" + my_date_format(sales_order_date) + " <strong>|</strong> </p> <button type='button' class='button button_transparent' onclick='open_view(" + sales_order_id + ")' title='View " + sales_order_name + "'><i class='fa fa-eye'></i></button></div>");
					} else {
						if(is_approved == 1){
							$('#archive_table').append("<div class='row archive_row'><div class='col-md-3 col-sm-3 col-xs-4'><p><strong>" + sales_order_name + "</strong></p><p>" + seller + "</p></div><div class='col-md-3 col-sm-3 col-xs-3'><p><strong>" + customer_name + "</strong></p><p>" + complete_address + "</p><p>" + customer_city + "</p></div><div class='col-md-4 col-sm-5 col-xs-5 col-md-offset-2 col-sm-offset-1 col-xs-offset-2'><p style='display:inline-block'>" + my_date_format(sales_order_date) + " <strong>|</strong> </p> <button type='button' class='button button_transparent' onclick='open_view(" + sales_order_id + ")' title='View " + sales_order_name + "'><i class='fa fa-eye'></i></button> <button type='button' class='button button_verified' title='Confirmed'><i class='fa fa-check'></i></button> <button class='button button_verified_false' title='Closed on " + my_date_format(approved_date) + "'><i class='fa fa-times'></i></button></div>");
						} else {
							$('#archive_table').append("<div class='row archive_row'><div class='col-md-3 col-sm-3 col-xs-4'><p><strong>" + sales_order_name + "</strong></p><p>" + seller + "</p></div><div class='col-md-3 col-sm-3 col-xs-3'><p><strong>" + customer_name + "</strong></p><p>" + complete_address + "</p><p>" + customer_city + "</p></div><div class='col-md-4 col-sm-5 col-xs-5 col-md-offset-2 col-sm-offset-1 col-xs-offset-2'><p style='display:inline-block'>" + my_date_format(sales_order_date) + " <strong>|</strong> </p> <button type='button' class='button button_transparent' onclick='open_view(" + sales_order_id + ")' title='View " + sales_order_name + "'><i class='fa fa-eye'></i></button> <button type='button' class='button button_verified' title='Confirmed'><i class='fa fa-check'></i></button></div>");
						}
					}
				});
				
				var button_width		= $('.button_verified').height();
				$('.button_verified').width(button_width);
			}
		});
	}
	
	function open_view(n){
		$.ajax({
			url:'<?= site_url('Sales_order/showById') ?>',
			data:{
				id:n
			},
			success:function(response){
				var general					= response.general;
				var sales_order_date		= general.date;
				var sales_order_name		= general.name;
				var complete_address		= '';

				var customer				= response.customer;
				var customer_name			= customer.name;
				complete_address			+= customer.address;
				var customer_city			= customer.city;
				var customer_number			= customer.number;
				var customer_rt				= customer.rt;
				var customer_rw				= customer.rw;
				var customer_postal			= customer.postal_code;
				var customer_block			= customer.block;
				var taxing					= customer.taxing;
				var invoicing_method		= customer.invoicing_method;
				
				if(taxing		== 0){
					var taxing_p	= 'Non taxable sales';
				} else {
					var taxing_p	= 'Taxable sales';
				}
				
				var invoicing_method		= general.invoicing_method;
				if(invoicing_method	== 1){
					var invoicing_method_p	= 'Retail type';
				} else {
					var invoicing_method_p	= 'Coorporate type';
				}
				
				$('#taxing_name_p').html(taxing_p);
				$('#invoicing_method_p').html(invoicing_method_p);
	
				if(customer_number != ''){
					complete_address	+= ' No. ' + customer_number;
				}
				
				if(customer_block != ''){
					complete_address	+= ' Blok ' + customer_block;
				}
			
				if(customer_rt != '000'){
					complete_address	+= ' RT ' + customer_rt;
				}
				
				if(customer_rw != '000' && customer_rt != '000'){
					complete_address	+= ' /RW ' + customer_rw;
				}
				
				if(customer_postal != null){
					complete_address	+= ', ' + customer_postal;
				}

				$('#sales_order_name_p').html(sales_order_name);
				$('#sales_order_date_p').html(my_date_format(sales_order_date));
				
				$('#customer_name_p').html(customer_name);
				$('#customer_address_p').html(complete_address);
				$('#customer_city_p').html(customer_city);
				
				$('#sales_order_table').html('');
				
				var items		= response.detail;
				var sales_order_value		= 0;
				$.each(items, function(index, item){
					var reference		= item.reference;
					var name				= item.name;
					var quantity			= parseFloat(item.quantity);
					var price_list			= parseFloat(item.price_list);
					var discount			= parseFloat(item.discount);
					var net_price			= (100 - discount) * price_list / 100;
					var total_price			= quantity * net_price;
					sales_order_value	+= total_price;
					
					$('#sales_order_table').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>Rp. " + numeral(price_list).format('0,0.00') + "</td><td>" + numeral(discount).format('0,0.00') + "%</td><td>Rp. " + numeral(net_price).format('0,0.00') + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(total_price).format('0,0.00') + "</td></tr>");
				});
				
				$('#sales_order_table').append("<tr><td colspan='4'></td><td colspan='2'>Total</td><td>Rp. " + numeral(sales_order_value).format('0,0.00') + "</td></tr>");
				
				$('#view_good_receipt_wrapper').fadeIn(300, function(){
					$('#view_good_receipt_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}
</script>