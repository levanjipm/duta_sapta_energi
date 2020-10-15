<title>Pending Sales Orders</title>
<style>
	.progressBarWrapper{
		width:100%;
		height:30px;
		background-color:white;
		border-radius:10px;
		padding:5px;
		position:relative;
	}

	.progressBar{
		width:0;
		height:20px;
		background-color:#01bb00;
		position:relative;
		border-radius:10px;
		cursor:pointer;
		opacity:0.4;
		transition:0.3s all ease;
	}

	.progressBar:hover{
		opacity:1;
		transition:0.3s all ease;
	}

	.progressBarWrapper p{
		font-family:museo;
		color:black;
		font-weight:700;
		z-index:50;
		position:absolute;
		right:10px;
	}
</style>
<div class="dashboard">
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /Pending Sales Orders</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div id='salesOrderTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Customer</th>
					<th>Pending Sales Orders</th>
					<th>Action</th>
				</tr>
				<tbody id='salesOrderTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>

		<p id='salesOrderTableText'>There is no pending sales orders found.</p>
	</div>
</div>

<div class='alert_wrapper' id='pendingSalesOrderWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>View Pending Sales Order</h3>
		<hr>
		<label>Customer</label>
		<p id='customerName_p'></p>
		<p id='customerAddress_p'></p>
		<p id='customerCity_p'></p>

		<label>Pending Sales Orders</label>
		<div id='pendingSalesOrderTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Action</th>
				</tr>
				<tbody id='pendingSalesOrderTableContent'></tbody>
			</table>
		</div>
		<p id='pendingSalesOrderTableText'>There is no pending sales order found.</p>
	</div>
</div>

<form action='<?= site_url('Inventory/viewPendingSalesOrderById') ?>' method="GET" id="salesOrderForm">
	<input type='hidden' id='id' name='id'>
</form>

<script>
	$(document).ready(function(){
		refreshView();
	})

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Sales_order/getIncompletedSalesOrdersByCustomer') ?>',
			data:{
				page: page
			},
			success:function(response){
				var items			= response.items;
				$('#salesOrderTableContent').html("");
				var itemCount = 0;
				$.each(items, function(index, customer){
					var complete_address		= '';
					var customer_name			= customer.name;
					complete_address		+= customer.address;
					var customer_city			= customer.city;
					var customer_number			= customer.number;
					var customer_rt				= customer.rt;
					var customer_rw				= customer.rw;
					var customer_postal			= customer.postal_code;
					var customer_block			= customer.block;
					var customer_id				= customer.id;
					var count					= customer.count;
		
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

					$('#salesOrderTableContent').append("<tr><td><p>" + customer_name + "</p><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td>" + numeral(count).format('0,0') + "</td><td><button class='button button_default_dark' id='viewPendingSalesOrderButton-" + customer_id + "'><i class='fa fa-eye'></i></button></td></tr>");

					$('#viewPendingSalesOrderButton-' + customer_id).click(function(){
						$('#customerName_p').html(customer_name);
						$('#customerAddress_p').html(complete_address);
						$('#customerCity_p').html(customer_city);

						viewPendingSalesOrder(customer_id);
					})
					itemCount++;
					
				});

				if(itemCount > 0){
					$('#salesOrderTable').show();
					$('#salesOrderTableText').hide();
				} else {
					$('#salesOrderTable').hide();
					$('#salesOrderTableText').show();
				}

				var pages			= response.pages;
				$('#page').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>'")
					}
				}
			}
		})
	}

	$('#page').change(function(){
		refreshVIew();
	})

	function viewPendingSalesOrder(customerId)
	{
		$.ajax({
			url:'<?= site_url('Sales_order/getCompletePendingSalesOrdersByCustomerId') ?>',
			data:{
				customerId: customerId
			},
			success:function(response){
				$('#pendingSalesOrderTableContent').html("");
				var itemCount = 0;
				$.each(response, function(index, value){
					var id			= value.id;
					var date		= value.date;
					var name		= value.name;
					var sent		= parseInt(value.sent);
					var quantity	= parseInt(value.quantity);
					var progress	= (sent / quantity) * 100;

					$('#pendingSalesOrderTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><p>" + name + "</p><div class='progressBarWrapper'><p id='progressText-" + id + "'></p><div class='progressBar' id='progressBar-" + id + "'></div></div></td><td><button class='button button_default_dark' onclick='viewSalesOrder(" + id + ")'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
					$('#progressBar-' + id).animate({
						width: progress + "%"
					}, "slow");

					$('#progressText-' + id).html(numeral(progress).format('0,0.00') + "%");
					itemCount++;
				})

				if(itemCount > 0){
					$('#pendingSalesOrderTable').show();
					$('#pendingSalesOrderTableText').hide();
				} else {
					$('#pendingSalesOrderTable').hide();
					$('#pendingSalesOrderTable').show();
				}
			},
			complete:function(){
				$('#pendingSalesOrderWrapper').fadeIn(300, function(){
					$('#pendingSalesOrderWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function viewSalesOrder(n){
		$('#id').val(n);
		$('#salesOrderForm').submit();
	}
</script>
