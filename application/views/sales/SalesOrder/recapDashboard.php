<head>
	<title>Sales order - Recap</title>
	<style>
		td.actived{
			background-color:#01bb00!important;
			color:white;
			opacity:0.4;
			transition:0.3s all ease;
			cursor:pointer;
		}

		td.actived:hover{
			opacity:1;
			transition:0.3s all ease;
		}
	</style>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-line-chart'></i></a> /<a href='<?= site_url('Sales order') ?>'>Sales order</a> / Recap</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<select class='form-control' id='type' onchange='refreshView(1)'>
				<option value='1'>Sales Order</option>
				<option value='2'>Invoices</option>
			</select>
			<select class='form-control' id='month' onchange='refreshView(1)'>
			<?php for($i = 1; $i <= 12; $i++){ ?>
				<option value='<?= $i ?>' <?php if($i == date('m')){ echo('selected');} ?>><?= date('F', mktime(0,0,0,$i, 1)) ?></option>
			<?php } ?>
			</select>
			<select class='form-control' id='year' onchange='refreshView(1)'>
			<?php for($i = 2020; $i <= date('Y'); $i++){ ?>
				<option value='<?= $i ?>' <?= ($i == date('Y')) ? 'selected': '' ?>><?= $i ?></option>
			<?php } ?>
			</select>
			<div class='input_group_append'>
				<button class='button button_default_dark' id="hideEmptyButton"><i class='fa fa-eye'></i></button>
				<button class='button button_danger_dark' id='showEmptyButton' style='display:none'><i class='fa fa-eye'></i></button>
			</div>
			<div class='input_group_append'>
				<button class='button button_default_dark' onclick='viewReport()'><i class='fa fa-file'></i></button>
			</div>
		</div>
		<br>
		<div id='salesOrderTable'>
			<table class='table table-bordered'>
				<tr>
					<th id='tableHeader'>Customer</th>
				</tr>
				<?php foreach($customers as $item){ ?>
					<tr>
						<td id='tableCustomer-<?= $item->id ?>'><?= $item->name ?>, <?= $item->city ?></td>
					</tr>
				<?php } ?>
			</table>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='salesOrderReportWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Sales Order Summary</h3>
		<hr>
		<label>Period</label>
		<p id='monthP'></p>
		<p id='yearP'></p>

		<label>Sales Order</label>
		<p id='salesOrderCount'></p>
		<p>Average per sales order: <span id='salesOrderValueAverageP'></span></p>

		<label>Customer Count</label>
		<p id='customerCount'></p>

		<label>Total Value</label>
		<p id="totalValueP"></p>
	</div>
</div>

<div class='alert_wrapper' id='salesOrderWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Sales Order</h3>
		<hr>
		<label>Customer</label>
		<p id='customerNameP'></p>
		<p id='customerAddressP'></p>
		<p id='customerCityP'></p>

		<div id='salesOrdersWrapper'>
			<label>Sales Orders</label>
			<div id='salesOrders'></div>
		</div>
		<div id='invoicesWrapper'>
			<label>Invoices</label>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Invoice</th>
					<th>Value</th>
				</tr>
				<tbody id='invoices'></tbody>
			</table>
		</div>
	</div>
</div>

<script>
	var salesOrderCount = 0;
	var customerCount	= 0;
	var totalValue		= 0;

	$(document).ready(function(){
		refreshView();
	});

	function refreshView(page = $('#page').val()){
		var month	= $('#month').val();
		var year	= $('#year').val();
		var type	= $('#type').val();

		if(type == 1){
			$.ajax({
				url:"<?= site_url("Sales_order/getRecap") ?>",
				data:{
					month: month,
					year: year
				},
				beforeSend:function(){
					var monthArray		= ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

					$('#monthP').html(monthArray[month - 1]);
					$('#yearP').html(year);
					$('#tableHeader').siblings().remove();
					var lastDayDate = new Date(year, month, 0);
					var lastDay		= lastDayDate.getDay();
					var lastDate	= lastDayDate.getDate();
					
					for(i = lastDate; i >= 1; i--){
						var dayDate	= new Date(year, (month - 1), i);
						var date	= dayDate.getDay();
						if(date != 0){
							$('#tableHeader').after("<th>" + i + "</th>")
						}
					}

					var lastDayDate = new Date(year, month, 0);
					var lastDay		= lastDayDate.getDay();
					var lastDate	= lastDayDate.getDate();
					$('td[id^="tableCustomer-"]').each(function(){
						$(this).siblings().remove();
						var tableCustomerId	= $(this).attr('id');
						var tableCustomerUid	= parseInt(tableCustomerId.substr(14, 269));
						$('#tableCustomer-' + tableCustomerUid).parent().removeClass('emptyCustomer');
						$('#tableCustomer-' + tableCustomerUid).parent().addClass('emptyCustomer');
						for(i = lastDate; i >= 1; i--){
							var dayDate	= new Date(year, (month - 1), i);
							var date	= dayDate.getDay();
							if(date != 0){
								$('#tableCustomer-' + tableCustomerUid).after("<td id='customer-" + tableCustomerUid + "-" + i + "'></td>");
							}
						}
					});

					$('#hideEmptyButton').show();
					$('#showEmptyButton').hide();

					$('.emptyCustomer').show();

					salesOrderCount = 0;
					customerCount	= 0;
					totalValue		= 0;
				},
				success:function(response){
					$.each(response, function(customerId, items){
						$.each(items, function(date, item){
							var count		= parseInt(item.count);
							var value		= parseFloat(item.value);

							$('#customer-' + customerId + '-' + date).html(count);
							$('#tableCustomer-' + customerId).parent().removeClass('emptyCustomer');
							$('#customer-' + customerId + '-' + date).addClass('actived');

							$('#customer-' + customerId + '-' + date).click(function(){
								viewSalesOrdersByCustomerIdDate(customerId, date, month, year);
							})

							salesOrderCount		+= count;
							totalValue			+= value;
						})

						customerCount++;
					});

					$('#salesOrderCount').html(numeral(salesOrderCount).format('0,0'));
					$('#customerCount').html(numeral(customerCount).format('0,0'));
					$('#totalValueP').html("Rp. " + numeral(totalValue).format('0,0.00'));
					$('#salesOrderValueAverageP').html("Rp. " + numeral(totalValue / salesOrderCount).format('0,0.00'));
				}
			})
		} else {
			$.ajax({
				url:"<?= site_url("Invoice/getRecap") ?>",
				data:{
					month: month,
					year: year
				},
				beforeSend:function(){
					var monthArray		= ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

					$('#monthP').html(monthArray[month - 1]);
					$('#yearP').html(year);
					$('#tableHeader').siblings().remove();
					var lastDayDate = new Date(year, month, 0);
					var lastDay		= lastDayDate.getDay();
					var lastDate	= lastDayDate.getDate();
					
					for(i = lastDate; i >= 1; i--){
						var dayDate	= new Date(year, (month - 1), i);
						var date	= dayDate.getDay();
						if(date != 0){
							$('#tableHeader').after("<th>" + i + "</th>")
						}
					}

					var lastDayDate = new Date(year, month, 0);
					var lastDay		= lastDayDate.getDay();
					var lastDate	= lastDayDate.getDate();
					$('td[id^="tableCustomer-"]').each(function(){
						$(this).siblings().remove();
						var tableCustomerId	= $(this).attr('id');
						var tableCustomerUid	= parseInt(tableCustomerId.substr(14, 269));
						$('#tableCustomer-' + tableCustomerUid).parent().removeClass('emptyCustomer');
						$('#tableCustomer-' + tableCustomerUid).parent().addClass('emptyCustomer');
						for(i = lastDate; i >= 1; i--){
							var dayDate	= new Date(year, (month - 1), i);
							var date	= dayDate.getDay();
							if(date != 0){
								$('#tableCustomer-' + tableCustomerUid).after("<td id='customer-" + tableCustomerUid + "-" + i + "'></td>");
							}
						}
					});

					$('#hideEmptyButton').show();
					$('#showEmptyButton').hide();

					$('.emptyCustomer').show();

					salesOrderCount = 0;
					customerCount	= 0;
					totalValue		= 0;
				},
				success:function(response){
					$.each(response, function(customerId, items){
						$.each(items, function(date, item){
							var count		= parseInt(item.count);
							var value		= parseFloat(item.value);

							$('#customer-' + customerId + '-' + date).html(count);
							$('#tableCustomer-' + customerId).parent().removeClass('emptyCustomer');
							$('#customer-' + customerId + '-' + date).addClass('actived');

							$('#customer-' + customerId + '-' + date).click(function(){
								viewInvoicesByCustomerIdDate(customerId, date, month, year);
							})

							salesOrderCount		+= count;
							totalValue			+= value;
						})

						customerCount++;
					});

					$('#salesOrderCount').html(numeral(salesOrderCount).format('0,0'));
					$('#customerCount').html(numeral(customerCount).format('0,0'));
					$('#totalValueP').html("Rp. " + numeral(totalValue).format('0,0.00'));
					$('#salesOrderValueAverageP').html("Rp. " + numeral(totalValue / salesOrderCount).format('0,0.00'));
				}
			})
		}
	}

	$('#hideEmptyButton').click(function(){
		$('.emptyCustomer').hide();

		$('#showEmptyButton').show();
		$('#hideEmptyButton').hide();
	});

	$('#showEmptyButton').click(function(){
		$('.emptyCustomer').show();

		$('#showEmptyButton').hide();
		$('#hideEmptyButton').show();
	})

	function viewReport(){
		$('#salesOrderReportWrapper').fadeIn(300, function(){
			$('#salesOrderReportWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	}

	function viewInvoicesByCustomerIdDate(customerId, date, month, year){
		$.ajax({
			url:"<?= site_url('Invoice/getByCustomerIdDate') ?>",
			data:{
				date: date,
				month: month, 
				year: year,
				id: customerId
			},
			beforeSend:function(){
				$('#salesOrders').html("");
				$('#invoices').html("");
				$('#invoicesWrapper').show();
				$('#salesOrdersWrapper').hide();
			},
			success:function(response){
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

				$('#customerNameP').html(customer_name);
				$('#customerAddressP').html(complete_address);
				$('#customerCityP').html(customer_city);

				var invoices		= response.items;
				var element			= "";
				$.each(invoices, function(index, invoice){
					var name			= invoice.name;
					var date			= invoice.date;
					var value			= invoice.value;
					$('#invoices').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td></tr>");
				});
			},
			complete:function(){
				$('#salesOrderWrapper').fadeIn(300, function(){
					$('#salesOrderWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}

	function viewSalesOrdersByCustomerIdDate(customerId, date, month, year){
		$.ajax({
			url:"<?= site_url('Sales_order/getByCustomerIdDate') ?>",
			data:{
				date: date,
				month: month, 
				year: year,
				id: customerId
			},
			beforeSend:function(){
				$('#salesOrders').html("");
				$('#invoices').html("");
				$('#invoicesWrapper').hide();
				$('#salesOrdersWrapper').show();
			},
			success:function(response){
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

				$('#customerNameP').html(customer_name);
				$('#customerAddressP').html(complete_address);
				$('#customerCityP').html(customer_city);

				var salesOrders		= response.items;
				var element			= "";
				$.each(salesOrders, function(index, salesOrder){
					var name			= salesOrder.name;
					var date			= salesOrder.date;
					var seller			= (salesOrder.seller == null) ? "<i>Not available</i>" : salesOrder.seller;
					var createdBy		= salesOrder.created_by;
					element			+= "<p><strong>" + name + "</strong></p><p>" + my_date_format(date) + "</p><p>Created by " + createdBy + "</p><p>Sold by " + seller + "</p>";
					element			+= "<br><table class='table table-bordered'><tr><th>Reference</th><th>Name</th><th>Price list</th><th>Discount</th><th>Net Price</th><th>Quantity</th><th>Total price</th></tr>";

					var items			= salesOrder.items;
					var salesOrderValue			= 0;
					$.each(items, function(index, item){
						var reference			= item.reference;
						var name				= item.name;
						var price_list			= parseFloat(item.price_list);
						var discount			= parseFloat(item.discount);
						var quantity			= parseInt(item.quantity);
						var netPrice			= price_list * (100 - discount) / 100;
						var totalPrice			= netPrice * quantity;
						salesOrderValue			+= totalPrice;

						element					+= "<tr><td>" + reference + "</td><td>" + name + "</td><td>Rp. " + numeral(price_list).format('0,0.00') + "</td><td>" + numeral(discount).format('0,0.00') + "%</td><td>Rp. " + numeral(netPrice).format('0,0.00') + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(totalPrice).format('0,0.00') + "</td></tr>";
					})

					element			+= "<tr><td colspan='5'></td><td>Total</td><td>Rp. " + numeral(salesOrderValue).format('0,0.00') + "</td></tr></table>";
				});

				$('#salesOrders').html(element);
			},
			complete:function(){
				$('#salesOrderWrapper').fadeIn(300, function(){
					$('#salesOrderWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}
</script>