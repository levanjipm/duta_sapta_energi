<style>
	.archive_row{
		padding:10px;
		border-bottom:1px solid #e2e2e2;
	}
</style>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Purchasing') ?>' title='Purchasing'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Purchase_order') ?>'>Purchase order</a> / Archive</p>
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
		<label>Purchase order</label>
		<p style='font-family:museo' id=purchase_order_name_p'></p>
		<p style='font-family:museo' id=purchase_order_date_p'></p>
		
		<label>Supplier</label>
		<p style='font-family:museo' id='supplier_name_p'></p>
		<p style='font-family:museo' id='supplier_address_p'></p>
		<p style='font-family:museo' id='supplier_city_p'></p>
		
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
			<tbody id='good_receipt_table'></tbody>
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
			url:'<?= site_url('Purchase_order/showArchive') ?>',
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
				
				var purchase_orders		= response.purchase_orders;
				
				$.each(purchase_orders, function(index, purchase_order){
					var purchase_order_name		= purchase_order.name;
					var purchase_order_id		= purchase_order.id;
					var purchase_order_date		= purchase_order.date;
					var complete_address		= '';
					var supplier_name			= purchase_order.supplier_name;
					complete_address			+= purchase_order.address;
					var supplier_city			= purchase_order.city;
					var supplier_number			= purchase_order.number;
					var supplier_rt				= purchase_order.rt;
					var supplier_rw				= purchase_order.rw;
					var supplier_postal			= purchase_order.postal_code;
					var supplier_block			= purchase_order.block;
		
					if(supplier_number != null){
						complete_address	+= ' No. ' + supplier_number;
					}
					
					if(supplier_block != null){
						complete_address	+= ' Blok ' + supplier_block;
					}
				
					if(supplier_rt != '000'){
						complete_address	+= ' RT ' + supplier_rt;
					}
					
					if(supplier_rw != '000' && supplier_rt != '000'){
						complete_address	+= ' /RW ' + supplier_rw;
					}
					
					if(supplier_postal != null){
						complete_address	+= ', ' + supplier_postal;
					}
					var is_confirm		= purchase_order.is_confirm;
					if(is_confirm == 0){
						$('#archive_table').append("<div class='row archive_row'><div class='col-md-3 col-sm-3 col-xs-4'><p><strong>" + purchase_order_name + "</strong></p></div><div class='col-md-3 col-sm-3 col-xs-3'><p><strong>" + supplier_name + "</strong></p><p>" + complete_address + "</p><p>" + supplier_city + "</p></div><div class='col-md-4 col-sm-5 col-xs-5 col-md-offset-2 col-sm-offset-1 col-xs-offset-2'><p style='display:inline-block'>" + my_date_format(purchase_order_date) + " <strong>|</strong> </p> <button type='button' class='button button_transparent' onclick='open_view(" + purchase_order_id + ")' title='View " + purchase_order_name + "'><i class='fa fa-eye'></i></button></div>");
					} else {
						$('#archive_table').append("<div class='row archive_row'><div class='col-md-3 col-sm-3 col-xs-4'><p><strong>" + purchase_order_name + "</strong></p></div><div class='col-md-3 col-sm-3 col-xs-3'><p><strong>" + supplier_name + "</strong></p><p>" + complete_address + "</p><p>" + supplier_city + "</p></div><div class='col-md-4 col-sm-5 col-xs-5 col-md-offset-2 col-sm-offset-1 col-xs-offset-2'><p style='display:inline-block'>" + my_date_format(purchase_order_date) + " <strong>|</strong> </p> <button type='button' class='button button_transparent' onclick='open_view(" + purchase_order_id + ")' title='View " + purchase_order_name + "'><i class='fa fa-eye'></i></button> <button type='button' class='button button_verified' title='Confirmed'><i class='fa fa-check'></i></button></div>");
					}
				});
				
				var button_width		= $('.button_verified').height();
				$('.button_verified').width(button_width);
			}
		});
	}
	
	function open_view(n){
		$.ajax({
			url:'<?= site_url('Purchase_order/getDetailById/') ?>' + n,
			success:function(response){
				var general					= response.general;
				var good_receipt_date		= general.date;
				var good_receipt_name		= general.name;
				var complete_address		= '';
				var supplier_name			= general.supplier_name;
				var complete_address		= '';
				var supplier_name			= general.supplier_name;
				complete_address			+= general.address;
				var supplier_city			= general.city;
				var supplier_number			= general.number;
				var supplier_rt				= general.rt;
				var supplier_rw				= general.rw;
				var supplier_postal			= general.postal_code;
				var supplier_block			= general.block;
	
				if(supplier_number != null){
					complete_address	+= ' No. ' + supplier_number;
				}
				
				if(supplier_block != null){
					complete_address	+= ' Blok ' + supplier_block;
				}
			
				if(supplier_rt != '000'){
					complete_address	+= ' RT ' + supplier_rt;
				}
				
				if(supplier_rw != '000' && supplier_rt != '000'){
					complete_address	+= ' /RW ' + supplier_rw;
				}
				
				if(supplier_postal != null){
					complete_address	+= ', ' + supplier_postal;
				}
				
				$('#purchase_order_name_p').html(good_receipt_name);
				$('#purchase_order_date_p').html(my_date_format(good_receipt_date));
				
				$('#supplier_name_p').html(supplier_name);
				$('#supplier_address_p').html(complete_address);
				$('#supplier_city_p').html(supplier_city);
				
				$('#good_receipt_table').html('');
				
				var items		= response.detail;
				var purchase_order_value		= 0;
				$.each(items, function(index, item){
					var reference		= item.reference;
					var name			= item.name;
					var quantity		= parseFloat(item.quantity);
					var price_list		= parseFloat(item.price_list);
					var net_price		= parseFloat(item.net_price);
					var discount		= 100 * (1 - (net_price / price_list));
					var total_price		= net_price * quantity;
					purchase_order_value	+= total_price;
					
					$('#good_receipt_table').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>Rp. " + numeral(price_list).format('0,0.00') + "</td><td>" + numeral(discount).format('0,0.00') + "%</td><td>Rp. " + numeral(net_price).format('0,0.00') + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(total_price).format('0,0.00') + "</td></tr>");
				});
				
				$('#good_receipt_table').append("<tr><td colspan='4'></td><td colspan='2'>Total</td><td>Rp. " + numeral(purchase_order_value).format('0,0.00') + "</td></tr>");
				
				$('#view_good_receipt_wrapper').fadeIn(300, function(){
					$('#view_good_receipt_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}
	
	$('.slide_alert_close_button').click(function(){
		$('#view_good_receipt_wrapper .alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$('#view_good_receipt_wrapper').fadeOut();
		});
	});
</script>