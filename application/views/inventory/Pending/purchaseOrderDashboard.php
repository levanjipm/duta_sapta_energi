<title>Pending Purchase Orders</title>
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
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /Pending Purchase Orders</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div id='purchaseOrderTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Supplier</th>
					<th>Pending Purchase Orders</th>
					<th>Action</th>
				</tr>
				<tbody id='purchaseOrderTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>

		<p id='purchaseOrderTableText'>There is no pending purchase orders found.</p>
	</div>
</div>

<div class='alert_wrapper' id='pendingPurchaseOrderWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>View Pending Sales Order</h3>
		<hr>
		<label>Supplier</label>
		<p id='supplierName_p'></p>
		<p id='supplierAddress_p'></p>
		<p id='supplierCity_p'></p>

		<label>Pending Purchase Orders</label>
		<div id='pendingSalesOrderTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Action</th>
				</tr>
				<tbody id='pendingPurcaseOrderTableContent'></tbody>
			</table>
		</div>
		<p id='pendingPurchaseOrderTableText'>There is no pending sales order found.</p>
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
			url:'<?= site_url('Purchase_order/getAllPendingSupplier') ?>',
			data:{
				page: page
			},
			success:function(response){
				$('#purchaseOrderTableContent').html("");
				var itemCount = 0;
				$.each(response, function(index, supplier){
					var complete_address		= '';
					var supplier_name			= supplier.name;
					complete_address		+= supplier.address;
					var supplier_city			= supplier.city;
					var supplier_number			= supplier.number;
					var supplier_rt				= supplier.rt;
					var supplier_rw				= supplier.rw;
					var supplier_postal			= supplier.postal_code;
					var supplier_block			= supplier.block;
					var supplier_id				= supplier.id;
					var count					= supplier.count;
		
					if(supplier_number != null){
						complete_address	+= ' No. ' + supplier_number;
					}
					
					if(supplier_block != null && supplier_block != "000"){
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

					$('#purchaseOrderTableContent').append("<tr><td><p>" + supplier_name + "</p><p>" + complete_address + "</p><p>" + supplier_city + "</p></td><td>" + numeral(count).format('0,0') + "</td><td><button class='button button_default_dark' id='viewPendingPurchaseOrderButton-" + supplier_id + "'><i class='fa fa-eye'></i></button></td></tr>");
					itemCount++;

					$('#viewPendingPurchaseOrderButton-' + supplier_id).click(function(){
						$('#supplierName_p').html(supplier_name);
						$('#supplierAddress_p').html(complete_address);
						$('#supplierCity_p').html(supplier_city);

						viewPendingPurchaseOrder(supplier_id);
					})
				});


				if(itemCount > 0){
					$('#purchaseOrderTable').show();
					$('#purchaseOrderTableText').hide();
				} else {
					$('#purchaseOrderTable').hide();
					$('#purchaseOrderTableText').show();
				}
			}
		})
	}

	function viewPendingPurchaseOrder(supplierId)
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
