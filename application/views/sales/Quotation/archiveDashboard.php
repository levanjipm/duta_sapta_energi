<head>
	<title>Quotation - Archive</title>
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
			<div class='col-md-2 col-sm-4 col-xs-6'>
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
			<div class='col-md-2 col-sm-4 col-xs-6'>
				<select class='form-control' id='year'>
<?php
	for($i = 2020; $i <= date('Y'); $i++){
?>
					<option value='<?= $i ?>' <?php if($i == date('Y')){ echo('selected');} ?>><?= $i ?></option>
<?php
	}
?>
				</select>
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

<div class='alert_wrapper' id='viewQuotationWrapper'>
	<button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Quotation Archive</h3>
		<hr>
		<label>Sales order</label>
		<p style='font-family:museo' id='quotationName_p'></p>
		<p style='font-family:museo' id='quotationDate_p'></p>
		<p style='font-family:museo'>Created by <span id='created_by_p'></span></p>
		<p style='font-family:museo' id='quotationConfirm'></p>

		<label>Taxing</label>
		<p style='font-family:museo' id='taxing_name_p'></p>

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
			<tbody id='quotationItemTable'></tbody>
		</table>

		<label>Note</label>
		<p id='quotationNote'></p>
	</div>
</div>

<script>
	$('#page').change(function(){
		refresh_view();
	});
	
	$(document).ready(function(){
		refresh_view();
	});

	$('#month').change(function(){
		refresh_view(1);
	});

	$('#year').change(function(){
		refresh_view(1);
	})
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Quotation/archiveView') ?>',
			data:{
				year: $('#year').val(),
				month: $('#month').val(),
				page:page,
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
				
				var quotations		= response.items;
				var archiveCount = 0;
				$.each(quotations, function(index, item){					
					var quotationName			= item.name;
					var quotationId				= item.id;
					var quotationDate			= item.date;
					var complete_address		= '';
					var customer_name			= item.customerName;
					complete_address			+= item.address;
					var customer_city			= item.city;
					var customer_number			= item.number;
					var customer_rt				= item.rt;
					var customer_rw				= item.rw;
					var customer_postal			= item.postal_code;
					var customer_block			= item.block;
					var created_by				= item.created_by;
		
					if(customer_number != ''){
						complete_address	+= ' No. ' + customer_number;
					}
					
					if(customer_block != '' && customer_block != "000"){
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
					var is_confirm		= item.is_confirm;
					if(is_confirm == 0){
						$('#archive_table').append("<div class='row archive_row'><div class='col-md-3 col-sm-3 col-xs-4'><p><strong>" + quotationName + "</strong></p><p>Created by " + created_by + "</p></div><div class='col-md-3 col-sm-3 col-xs-3'><p><strong>" + customer_name + "</strong></p><p>" + complete_address + "</p><p>" + customer_city + "</p></div><div class='col-md-4 col-sm-5 col-xs-5 col-md-offset-2 col-sm-offset-1 col-xs-offset-2'><p style='display:inline-block'>" + my_date_format(quotationDate) + " <strong>|</strong> </p> <button type='button' class='button button_transparent' onclick='open_view(" + quotationId + ")' title='View " + quotationName + "'><i class='fa fa-eye'></i></button></div>");
						archiveCount++;
					} else {
						$('#archive_table').append("<div class='row archive_row'><div class='col-md-3 col-sm-3 col-xs-4'><p><strong>" + quotationName + "</strong></p><p>Created by " + created_by + "</p></div><div class='col-md-3 col-sm-3 col-xs-3'><p><strong>" + customer_name + "</strong></p><p>" + complete_address + "</p><p>" + customer_city + "</p></div><div class='col-md-4 col-sm-5 col-xs-5 col-md-offset-2 col-sm-offset-1 col-xs-offset-2'><p style='display:inline-block'>" + my_date_format(quotationDate) + " <strong>|</strong> </p> <button type='button' class='button button_transparent' onclick='open_view(" + quotationId + ")' title='View " + quotationName + "'><i class='fa fa-eye'></i></button> <button type='button' class='button button_verified' title='Confirmed'><i class='fa fa-check'></i></button></div>");
						archiveCount++;
					}
				});

				if(archiveCount == 0){
					$('#archive_table').html("<p>There is no quotation found.</p>")
				}
				
				var button_width		= $('.button_verified').height();
				$('.button_verified').width(button_width);
			}
		});
	}
	
	function open_view(n){
		$.ajax({
			url:'<?= site_url('Quotation/getById') ?>',
			data:{
				id:n
			},
			success:function(response){
				var general					= response.general;
				var quotation_date			= general.date;
				var quotation_name			= general.name;
				var creator					= general.created_by;
				var confirmed_by			= general.confirmed_by;
				var complete_address		= '';
				var note					= (general.note == null || general.note == "") ? "<i>Not available</i>" : general.note;;

				$('#quotationNote').html(note);

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
				
				if(taxing		== 0){
					var taxing_p	= 'Non taxable sales';
				} else {
					var taxing_p	= 'Taxable sales';
				}

				$('#taxing_name_p').html(taxing_p);
				if(customer_number != '' && customer_number != null){
					complete_address	+= ' No. ' + customer_number;
				}
				
				if(customer_block != null && customer_block != "000" && customer_block != ""){
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

				$('#quotationName_p').html(quotation_name);
				$('#quotationDate_p').html(my_date_format(quotation_date));

				$('#created_by_p').html((creator == null)? "<i>Not available</i>": creator);
				$('#quotationConfirm').html((confirmed_by == null)? "<i>Has not been confirmed</i>": "Confirmed by " + confirmed_by)
				
				$('#customer_name_p').html(customer_name);
				$('#customer_address_p').html(complete_address);
				$('#customer_city_p').html(customer_city);
				
				$('#quotationItemTable').html('');
				
				var items		= response.items;
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
					
					$('#quotationItemTable').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>Rp. " + numeral(price_list).format('0,0.00') + "</td><td>" + numeral(discount).format('0,0.00') + "%</td><td>Rp. " + numeral(net_price).format('0,0.00') + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(total_price).format('0,0.00') + "</td></tr>");
				});
				
				$('#quotationItemTable').append("<tr><td colspan='4'></td><td colspan='2'>Total</td><td>Rp. " + numeral(sales_order_value).format('0,0.00') + "</td></tr>");
			},
			complete:function(){				
				$('#viewQuotationWrapper').fadeIn(300, function(){
					$('#viewQuotationWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}
</script>
