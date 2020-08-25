<head>
	<title>Sales return - Archive</title>
	<style>
		.archive_row{
			padding:10px;
			border-bottom:1px solid #e2e2e2;
		}
	</style>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /Return / Archive</p>
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

<div class='alert_wrapper' id='archiveWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Sales return archive</h3>
		<hr>
		<label>Customer</label>
		<p id='customerName_p'></p>
		<p id='customerAddress_p'></p>
		<p id='customerCity_p'></p>

		<label>Delivery order</label>
		<p id='deliveryOrderName_p'></p>
		<p id='deliveryOrderDate_p'></p>

		<label>Sales return</label>
		<p id='returnDocumentName_p'></p>
		<p id='returnDocumentDate_p'></p>
		<p id='returnDocumentCreatedBy_p'></p>

		<div class='table-responsive-md'>
			<table class='table table-bordered'>
				<tr>
					<th>Reference</th>
					<th>Name</th>
					<th>Unit price</th>
					<th>Quantity</th>
					<th>Received</th>
					<th>Price</th>
				</tr>
				<tbody id='itemTableContent'></tbody>
			</table>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		refreshView();
	});

	$('#month').change(function(){
		refreshView(1);
	})

	$('#year').change(function(){
		refreshView(1);
	})

	$('#page').change(function(){
		refreshView();
	});

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Sales_return/getItems') ?>",
			data:{
				month: $('#month').val(),
				year: $('#year').val(),
				page: page
			},
			success:function(response){
				var items = response.items;
				$('#archiveTableContent').html("");
				var itemCount = 0;
				$.each(items, function(index, item){
					var name = item.name;
					var date = item.created_date;
					var customerName = item.customerName;
					var customerCity = item.customerCity;
					var id = item.id;
					var isConfirm = item.is_confirm;
					if(isConfirm == 1){
						$('#archiveTableContent').append("<div class='row archive_row'><div class='col-md-3 col-sm-3 col-xs-4'><p><strong>" + name + "</strong></p><p>" + my_date_format(date) + "</p></div><div class='col-md-3 col-sm-3 col-xs-3'><p><strong>" + customerName + "</strong></p><p>" + customerCity + "</p></div><div class='col-md-4 col-sm-5 col-xs-5 col-md-offset-2 col-sm-offset-1 col-xs-offset-2'><button type='button' class='button button_transparent' onclick='openView(" + id + ")' title='View " + name + "'><i class='fa fa-eye'></i></button> <button type='button' class='button button_verified' title='Confirmed'><i class='fa fa-check'></i></button></div>");
					} else {
						$('#archiveTableContent').append("<div class='row archive_row'><div class='col-md-3 col-sm-3 col-xs-4'><p><strong>" + name + "</strong></p><p>" + my_date_format(date) + "</p></div><div class='col-md-3 col-sm-3 col-xs-3'><p><strong>" + customerName + "</strong></p><p>" + customerCity + "</p></div><div class='col-md-4 col-sm-5 col-xs-5 col-md-offset-2 col-sm-offset-1 col-xs-offset-2'><button type='button' class='button button_transparent' onclick='openView(" + id + ")' title='View " + name + "'><i class='fa fa-eye'></i></button></div>");
					}
					itemCount++;
					
				})

				if(itemCount > 0){
					$('#archiveTable').show();
					$('#archiveTableText').hide();
				} else {
					$('#archiveTable').hide();
					$('#archiveTableText').show();
				}
				var pages = response.pages;
				$('#page').html("");
				for(i = 1; i<= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		})
	}

	function openView(n){
		$.ajax({
			url:"<?= site_url('Sales_return/getById') ?>",
			data:{
				id:n
			},
			success:function(response){
				var customer = response.customer;
				var customer_name = customer.name;
				var complete_address = customer.address;
				var customer_number = customer.number;
				var customer_block = customer.block;
				var customer_rt = customer.rt;
				var customer_rw = customer.rw;
				var customer_city = customer.city;
				var customer_postal = customer.postal;
				
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

				$('#customerName_p').html(customer_name);
				$('#customerAddress_p').html(complete_address);
				$('#customerCity_p').html(customer_city);

				var deliveryOrder			= response.deliveryOrder;
				var deliveryOrderName		= deliveryOrder.name;
				var deliveryOrderDate		= deliveryOrder.date;
				
				$('#deliveryOrderName_p').html(deliveryOrderName);
				$('#deliveryOrderDate_p').html(my_date_format(deliveryOrderDate));

				var salesReturn		= response.salesReturn;
				var documentName		= salesReturn.documentName;
				var date				= salesReturn.date;
				var createdBy			= salesReturn.created_by;

				$('#returnDocumentName_p').html(documentName);
				$('#returnDocumentDate_p').html(my_date_format(date));
				$('#returnDocumentCreatedBy_p').html(createdBy);

				$('#itemTableContent').html("");
				var items = response.items;
				var returnValue = 0;
				$.each(items, function(index, item){
					var reference 		= item.reference;
					var description		= item.name;
					var priceList		= parseFloat(item.price_list);
					var discount		= parseFloat(item.discount);
					var unitPrice		= priceList * (100 - discount) / 100;
					var quantity		= parseInt(item.quantity);
					var totalPrice		= unitPrice * quantity;
					var received		= parseInt(item.received);
					returnValue			+= totalPrice;

					$('#itemTableContent').append("<tr><td>" + reference + "</td><td>" + description + "</td><td>Rp. " + numeral(unitPrice).format('0,0.00') + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>" + numeral(received).format('0,0') + "</td><td>Rp. " + numeral(totalPrice).format('0,0.00') + "</td></tr>");
				});

				$('#itemTableContent').append("<tr><td colspan='3'></td><td colspan='2'>Total</td><td>Rp. " + numeral(returnValue).format('0,0.00') + "</td></tr>");

				$('#archiveWrapper').fadeIn(300, function(){
					$('#archiveWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}
</script>
