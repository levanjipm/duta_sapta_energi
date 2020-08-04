<style>
	.archive_row{
		padding:10px;
		border-bottom:1px solid #e2e2e2;
	}
</style>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Inventory') ?>' title='Inventory'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Good_receipt') ?>'>Good receipt</a> / Archive</p>
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
		<div id='archiveTableWrapper'>
			<div id='archiveTable'></div>
			<br>
			
			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='archiveTableText'>There is no good receipt found.</p>
	</div>
</div>

<div class='alert_wrapper' id='view_good_receipt_wrapper'>
	<button type='button' class='slide_alert_close_button'>&times </button>
	<div class='alert_box_slide'>
		<label>Delivery order</label>
		<p style='font-family:museo' id='good_receipt_name_p'></p>
		<p style='font-family:museo' id='good_receipt_date_p'></p>
		
		<label>Purchase order</label>
		<p style='font-family:museo' id='purchase_order_name_p'></p>
		<p style='font-family:museo' id='purchase_order_date_p'></p>
		
		<label>Supplier</label>
		<p style='font-family:museo' id='supplier_name_p'></p>
		<p style='font-family:museo' id='supplier_address_p'></p>
		<p style='font-family:museo' id='supplier_city_p'></p>
		
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
			</tr>
			<tbody id='good_receipt_table'></tbody>
		</table>

		<p id='invoice_status_done'>This good receipt has been invoiced.</p>
		<p id='invoice_status_not_done'>This good receipt has <strong>not</strong> been invoiced.</p>
	</div>
</div>

<script>
	$('#search_button').click(function(){
		refresh_view(1);
	});
	
	$('#page').change(function(){
		refresh_view();
	});
	
	$(document).ready(function(){
		refresh_view(1);
	})
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Good_receipt/view_archive') ?>',
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
				
				$('#archiveTable').html('');
				
				var good_receipts		= response.good_receipts;
				if(good_receipts.length == 0){
					$('#archiveTableWrapper').hide();
					$('#archiveTableText').show();
				} else {
					$('#archiveTableWrapper').show();
					$('#archiveTableText').hide();
				}
				
				$.each(good_receipts, function(index, good_receipt){
					var purchase_order_name		= good_receipt.purchase_order_name;
					var good_receipt_name		= good_receipt.name;
					var good_receipt_id			= good_receipt.id;
					var good_receipt_date		= good_receipt.date;
					var complete_address		= '';
					var supplier_name			= good_receipt.supplier_name;
					complete_address			+= good_receipt.address;
					var supplier_city			= good_receipt.city;
					var supplier_number			= good_receipt.number;
					var supplier_rt				= good_receipt.rt;
					var supplier_rw				= good_receipt.rw;
					var supplier_postal			= good_receipt.postal_code;
					var supplier_block			= good_receipt.block;
		
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
					var is_confirm		= good_receipt.is_confirm;
					if(is_confirm == 0){
						$('#archiveTable').append("<div class='row archive_row'><div class='col-md-3 col-sm-3 col-xs-4'><p><strong>" + good_receipt_name + "</strong></p><p>" + purchase_order_name + "</p></div><div class='col-md-3 col-sm-3 col-xs-3'><p><strong>" + supplier_name + "</strong></p><p>" + complete_address + "</p><p>" + supplier_city + "</p></div><div class='col-md-4 col-sm-5 col-xs-5 col-md-offset-2 col-sm-offset-1 col-xs-offset-2'><p style='display:inline-block'>" + my_date_format(good_receipt_date) + " <strong>|</strong> </p> <button type='button' class='button button_transparent' onclick='open_view(" + good_receipt_id + ")' title='View " + good_receipt_name + "'><i class='fa fa-eye'></i></button></div>");
					} else {
						$('#archiveTable').append("<div class='row archive_row'><div class='col-md-3 col-sm-3 col-xs-4'><p><strong>" + good_receipt_name + "</strong></p><p>" + purchase_order_name + "</p></div><div class='col-md-3 col-sm-3 col-xs-3'><p><strong>" + supplier_name + "</strong></p><p>" + complete_address + "</p><p>" + supplier_city + "</p></div><div class='col-md-4 col-sm-5 col-xs-5 col-md-offset-2 col-sm-offset-1 col-xs-offset-2'><p style='display:inline-block'>" + my_date_format(good_receipt_date) + " <strong>|</strong> </p> <button type='button' class='button button_transparent' onclick='open_view(" + good_receipt_id + ")' title='View " + good_receipt_name + "'><i class='fa fa-eye'></i></button> <button type='button' class='button button_verified' title='Confirmed'><i class='fa fa-check'></i></button></div>");
					}
				});
				
				var button_width		= $('.button_verified').height();
				$('.button_verified').width(button_width);
			}
		});
	}
	
	function open_view(n){
		$.ajax({
			url:'<?= site_url('Good_receipt/showById') ?>',
			data:{
				id:n
			},
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

				var is_invoiced				= general.invoice_id;

				if(is_invoiced == null){
					$('#invoice_status_done').hide();
					$('#invoice_status_not_done').show();
				} else {
					$('#invoice_status_done').show();
					$('#invoice_status_not_done').hide();
				}
	
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
				
				var purchase_order_name	= general.purchase_order_name;
				var purchase_order_date	= my_date_format(general.purchase_order_date);
				
				$('#purchase_order_name_p').html(purchase_order_name);
				$('#purchase_order_date_p').html(purchase_order_date);
				
				$('#good_receipt_name_p').html(good_receipt_name);
				$('#good_receipt_date_p').html(my_date_format(good_receipt_date));
				
				$('#supplier_name_p').html(supplier_name);
				$('#supplier_address_p').html(complete_address);
				$('#supplier_city_p').html(supplier_city);
				
				$('#good_receipt_table').html('');
				
				var items		= response.items;
				$.each(items, function(index, item){
					var reference		= item.reference;
					var name			= item.name;
					var quantity		= item.quantity;
					$('#good_receipt_table').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td></tr>");
				});
				
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