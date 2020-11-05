<head>
	<title>Delivery order - Archive</title>
	<style>
		.archive_row{
			padding:10px;
			border-bottom:1px solid #e2e2e2;
		}
	</style>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Inventory') ?>' title='Inventory'><i class='fa fa-briefcase'></i></a> / <a href='<?= site_url('Delivery_order') ?>'>Delivery order</a> / Archive</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='row'>
			<div class='col-md-2 col-sm-3 col-xs-4'>
				<select class='form-control' id='month'>
				<?php for($i = 1; $i <= 12; $i++){ ?>
					<option value='<?= $i ?>' <?php if($i == date('m')){ echo('selected');} ?>><?= date('F', mktime(0,0,0,$i, 1)) ?></option>
				<?php } ?>
				</select>
			</div>
			<div class='col-md-2 col-sm-3 col-xs-4'>
				<select class='form-control' id='year'>
				<?php for($i = 2020; $i <= date('Y'); $i++){ ?>
					<option value='<?= $i ?>'><?= $i ?></option>
				<?php } ?>
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
		<div id='archiveTable'>
			<div id='archiveTableContent'>
			</div><br>
			
			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='archiveTableText'>There is no archive found.</p>
	</div>
</div>

<div class='alert_wrapper' id='view_delivery_order_wrapper'>
	<button type='button' class='slide_alert_close_button'>&times </button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Delivery order archive</h3>
		<hr>
		<label>Delivery order</label>
		<p style='font-family:museo' id='delivery_order_name_p'></p>
		<p style='font-family:museo' id='delivery_order_date_p'></p>
		
		<label>Sales order</label>
		<p style='font-family:museo' id='sales_order_name_p'></p>
		<p style='font-family:museo' id='sales_order_date_p'></p>
		
		<label>Taxing</label>
		<p style='font-family:museo' id='taxing_name_p'></p>
		
		<label>Invoicing method</label>
		<p style='font-family:museo' id='invoicing_method_p'></p>
		
		<label>Seller</label>
		<p style='font-family:museo' id='seller_name_p'></p>
		
		<label>Customer</label>
		<p style='font-family:museo' id='customer_name_p'></p>
		<p style='font-family:museo' id='customer_address_p'></p>
		<p style='font-family:museo' id='customer_city_p'></p>
		<p style='font-family:museo' id='customer_up_p'></p>
		
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
			</tr>
			<tbody id='delivery_order_table'></tbody>
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
			url:'<?= site_url('Delivery_order/viewArchive') ?>',
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
				
				$('#archiveTableContent').html('');
				
				var delivery_orders		= response.delivery_orders;
				var deliveryOrderCount	= 0;
				
				$.each(delivery_orders, function(index, delivery_order){
					var sales_order_name		= delivery_order.salesOrderName;
					var delivery_order_name		= delivery_order.name;
					var delivery_order_id		= delivery_order.id;
					var delivery_order_date		= delivery_order.date;
					var complete_address		= '';
					var customer_name			= delivery_order.customer_name;
					complete_address			+= delivery_order.address;
					var customer_city			= delivery_order.city;
					var customer_number			= delivery_order.number;
					var customer_rt				= delivery_order.rt;
					var customer_rw				= delivery_order.rw;
					var customer_postal			= delivery_order.postal_code;
					var customer_block			= delivery_order.block;
					var customer_id				= delivery_order.id;
		
					if(customer_number != null){
						complete_address	+= ' No. ' + customer_number;
					}
					
					if(customer_block != null && customer_block != "000"){
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
					var is_confirm		= delivery_order.is_confirm;
					if(is_confirm == 0){
						$('#archiveTableContent').append("<div class='row archive_row'><div class='col-md-3 col-sm-3 col-xs-4'><p><strong>" + delivery_order_name + "</strong></p><p>" + sales_order_name + "</p></div><div class='col-md-3 col-sm-3 col-xs-3'><p><strong>" + customer_name + "</strong></p><p>" + complete_address + "</p><p>" + customer_city + "</p></div><div class='col-md-4 col-sm-5 col-xs-5 col-md-offset-2 col-sm-offset-1 col-xs-offset-2'><p style='display:inline-block'>" + my_date_format(delivery_order_date) + " <strong>|</strong> </p> <button type='button' class='button button_transparent' onclick='open_view(" + delivery_order_id + ")' title='View " + delivery_order_name + "'><i class='fa fa-eye'></i></button></div>");
					} else {
						$('#archiveTableContent').append("<div class='row archive_row'><div class='col-md-3 col-sm-3 col-xs-4'><p><strong>" + delivery_order_name + "</strong></p><p>" + sales_order_name + "</p></div><div class='col-md-3 col-sm-3 col-xs-3'><p><strong>" + customer_name + "</strong></p><p>" + complete_address + "</p><p>" + customer_city + "</p></div><div class='col-md-4 col-sm-5 col-xs-5 col-md-offset-2 col-sm-offset-1 col-xs-offset-2'><p style='display:inline-block'>" + my_date_format(delivery_order_date) + " <strong>|</strong> </p> <button type='button' class='button button_transparent' onclick='open_view(" + delivery_order_id + ")' title='View " + delivery_order_name + "'><i class='fa fa-eye'></i></button> <button type='button' class='button button_verified' title='Confirmed'><i class='fa fa-check'></i></button></div>");
					}

					deliveryOrderCount++;
					
				});
				
				var button_width		= $('.button_verified').height();
				if(button_width > 0){
					$('.button_verified').width(button_width);
				}				

				if(deliveryOrderCount > 0){
					$('#archiveTable').show();
					$('#archiveTableText').hide();
				} else {
					$('#archiveTable').hide();
					$('#archiveTableText').show();
				}
			}
		});
	}
	
	function open_view(n){
		$.ajax({
			url:'<?= site_url('Delivery_order/getById') ?>',
			data:{
				id:n
			},
			success:function(response){
				var general					= response.general;
				var delivery_order_date		= general.date;
				var delivery_order_name		= general.name;

				var customer				= response.customer;
				var complete_address		= '';
				var customer_name			= customer.name;
				complete_address			+= customer.address;
				var customer_city			= customer.city;
				var customer_number			= customer.number;
				var customer_rt				= customer.rt;
				var customer_rw				= customer.rw;
				var customer_postal			= customer.postal_code;
				var customer_block			= customer.block;
				var customer_id				= customer.id;
	
				if(customer_number != null){
					complete_address	+= ' No. ' + customer_number;
				}
				
				if(customer_block != null && customer_block != "000"){
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
				
				var customer_pic_name		= general.pic_name;
				var taxing					= general.taxing;
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
				
				var seller_name		= general.seller;
				if(seller_name	== null){
					seller_name		= '<i>Not available</i>';
				}
				
				var sales_order_name	= general.sales_order_name;
				var sales_order_date	= my_date_format(general.sales_order_date);
				
				$('#taxing_name_p').html(taxing_p);
				
				$('#sales_order_name_p').html(sales_order_name);
				$('#sales_order_date_p').html(sales_order_date);
				
				$('#invoicing_method_p').html(invoicing_method_p);
				$('#seller_name_p').html(seller_name);
				
				$('#delivery_order_name_p').html(delivery_order_name);
				$('#delivery_order_date_p').html(my_date_format(delivery_order_date));
				$('#customer_name_p').html(customer_name);
				$('#customer_address_p').html(complete_address);
				$('#customer_city_p').html(customer_city);
				$('#customer_up_p').html(customer_pic_name);
				
				$('#delivery_order_table').html('');
				
				var items		= response.items;
				$.each(items, function(index, item){
					var reference		= item.reference;
					var name			= item.name;
					var quantity		= item.quantity;
					$('#delivery_order_table').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td></tr>");
				});
				
				$('#view_delivery_order_wrapper').fadeIn(300, function(){
					$('#view_delivery_order_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}
</script>
