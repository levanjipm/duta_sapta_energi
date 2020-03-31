<style>
	.archive_row{
		padding:10px;
		border-bottom:1px solid #e2e2e2;
	}
</style>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Inventory') ?>' title='Inventory'><i class='fa fa-briefcase'></i></a> / <a href='<?= site_url('Delivery_order') ?>'>Delivery order</a> / Archive</p>
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
				<input type='text' class='form-control-text-custom' id='search_bar' placeholder='Search'>
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

<div class='alert_wrapper' id='view_delivery_order_wrapper'>
	<button type='button' class='slide_alert_close_button'>&times </button>
	<div class='alert_box_slide'>
	</div>
</div>

<script>
	$('#page').change(function(){
		refresh_view();
	});
	
	
	refresh_view();
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Delivery_order/view_archive') ?>',
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
				
				var delivery_orders		= response.delivery_orders;
				
				$.each(delivery_orders, function(index, delivery_order){
					var sales_order_name		= delivery_order.sales_order_name;
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
					
					if(customer_block != null){
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
						$('#archive_table').append("<div class='row archive_row'><div class='col-md-3 col-sm-3 col-xs-4'><p><strong>" + delivery_order_name + "</strong></p><p>" + sales_order_name + "</p></div><div class='col-md-3 col-sm-3 col-xs-3'><p><strong>" + customer_name + "</strong></p><p>" + complete_address + "</p><p>" + customer_city + "</p></div><div class='col-md-4 col-sm-5 col-xs-5 col-md-offset-2 col-sm-offset-1 col-xs-offset-2'><p style='display:inline-block'>" + my_date_format(delivery_order_date) + " <strong>|</strong> </p> <button type='button' class='button button_transparent' onclick='open_view(" + delivery_order_id + ")' title='View " + delivery_order_name + "'><i class='fa fa-eye'></i></button></div>");
					} else {
						$('#archive_table').append("<div class='row archive_row'><div class='col-md-3 col-sm-3 col-xs-4'><p><strong>" + delivery_order_name + "</strong></p><p>" + sales_order_name + "</p></div><div class='col-md-3 col-sm-3 col-xs-3'><p><strong>" + customer_name + "</strong></p><p>" + complete_address + "</p><p>" + customer_city + "</p></div><div class='col-md-4 col-sm-5 col-xs-5 col-md-offset-2 col-sm-offset-1 col-xs-offset-2'><p style='display:inline-block'>" + my_date_format(delivery_order_date) + " <strong>|</strong> </p> <button type='button' class='button button_transparent' onclick='open_view(" + delivery_order_id + ")' title='View " + delivery_order_name + "'><i class='fa fa-eye'></i></button> <button type='button' class='button button_verified' title='Confirmed'><i class='fa fa-check'></i></button></div>");
					}
					
				});
				
				var button_width		= $('.button_verified').height();
				$('.button_verified').width(button_width);
			}
		});
	}
	
	function open_view(n){
		$('#view_delivery_order_wrapper').fadeIn(300, function(){
			$('#view_delivery_order_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	}
	
	$('.slide_alert_close_button').click(function(){
		$('#view_delivery_order_wrapper .alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$('#view_delivery_order_wrapper').fadeOut();
		});
	});
</script>