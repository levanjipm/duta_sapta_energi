<head>
	<title>Sales order - Close</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-line-chart'></i></a> /<a href='<?= site_url('Sales_order') ?>'>Sales order</a>/ Close</p>
	</div>
	<br>
	<div class='dashboard_in'>

		<div id='salesOrderTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Customer</th>
					<th>Action</th>
				</tr>
				<tbody id='salesOrderTableContent'></tbody>
			</table>
		</div>
		<p id='salesOrderTableText'>There is no close sales order submission to be reviewed.</p>
	</div>
</div>

<div class='alert_wrapper' id='salesOrderWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Confirm close sales order</h3>
		<hr>
		
		<label>Submission date</label>
		<p id='date_close_p'></p>

		<label>Information</label>
		<p id='information_close_p'></p>

		<label>Sales order</label>
		<p id='sales_order_name_p'></p>
		<p id='sales_order_date_p'></p>

		<label>Seller</label>
		<p id='sales_order_seller_p'></p>

		<label>Items</label>
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
				<th>Pending</th>
			</tr>
			<tbody id='salesOrderItemTable'></tbody>
		</table>

		<input type='hidden' id='salesOrderCloseRequestId'>
		<button onclick='confirmClose()' class='button button_default_dark' title='Submit close'><i class='fa fa-long-arrow-right'></i></button>
		<butotn onclick='deleteClose()' class='button button_danger_dark' title='Cancel close'><i class='fa fa-trash'></i></button>

		<div class='notificationText danger' id='createFailedNotification'><p>Failed to confirm submission.</p></div>

		<div class='notificationText danger' id='deleteFailedNotification'><p>Failed to delete submission.</p></div>
	</div>
</div>

<script>
    $(document).ready(function(){
        refresh_view();
    })
    function refresh_view(){
        $.ajax({
            url:'<?= site_url('Sales_order/getUnconfirmedCloseSubmission') ?>',
            success:function(response){
				var closeSubmissionCount = 0;
				$('#salesOrderTableContent').html('');
                $.each(response, function(index, value){
					var id = value.id;
					var date = value.date;
					var salesOrderName = value.sales_order_name;
					var salesOrderDate = value.sales_order_date;
					var customer_name	= value.name;

					var complete_address = value.address;
					var customer_number = value.number;
					var customer_block = value.block;
					var customer_rt = value.rt;
					var customer_rw = value.rw;
					var customer_city = value.city;
					var customer_postal = value.postal;
					
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

					$('#salesOrderTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><label>Name</label><p>" + salesOrderName + "</p><p>" + my_date_format(salesOrderDate) + "</p></td><td><p>" + customer_name + "</p><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><button class='button button_default_dark' onclick='viewSubmission(" + id + ")'><i class='fa fa-eye'></i></button></tr>");

					closeSubmissionCount++;
				});

				if(closeSubmissionCount > 0){
					$('#salesOrderTable').show();
					$('#salesOrderTableText').hide();
				} else {
					$('#salesOrderTable').hide();
					$('#salesOrderTableText').show();
				}
            }
        })
	}
	
	function viewSubmission(n){
		$.ajax({
			url:"<?= site_url('Sales_order/getCloseSubmissionById') ?>",
			data:{
				id: n
			},
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);

				$('#salesOrderCloseRequestId').val(n);

				var general = response.general;
				var id = general.id;
				var date = general.date;
				var information = general.information;

				$('#date_close_p').html(my_date_format(date));
				$('#information_close_p').html(information);

				var salesOrder = response.sales_order;
				var date = salesOrder.date;
				var name = salesOrder.name;
				var seller = salesOrder.seller;

				if(seller == null){
					var sellerText = "<i>Not available</i>";
				} else {
					var sellerText = seller;
				}

				$('#sales_order_date_p').html(my_date_format(date));
				$('#sales_order_name_p').html(name);
				$('#sales_order_seller_p').html(sellerText);

				var items = response.items;
				$('#salesOrderItemTable').html('');
				$.each(items, function(index, item){
					var quantity = item.quantity;
					var name = item.name;
					var reference = item.reference;
					var sent = item.sent;
					var pending = quantity - sent;

					$('#salesOrderItemTable').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>" + numeral(pending).format('0,0') + "</td></tr>");
				})

				$('#salesOrderWrapper').fadeIn(300, function(){
					$('#salesOrderWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function deleteClose()
	{
		$.ajax({
			url:'<?= site_url('Sales_order/deleteCloseSalesOrder') ?>',
			data:{
				id: $('#salesOrderCloseRequestId').val()
			},
			type:'POST',
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				if(response == 1){
					refresh_view();
					$('#salesOrderWrapper .slide_alert_close_button').click();
				} else {
					$('#createFailedNotification').fadeIn(250);
					setTimeout(function(){
						$('#createFailedNotification').fadeOut(250);
					}, 1000);
				}
			}
		})
	}

	function confirmClose()
	{
		$.ajax({
			url:'<?= site_url('Sales_order/confirmCloseSalesOrder') ?>',
			data:{
				id: $('#salesOrderCloseRequestId').val()
			},
			type:'POST',
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				if(response == 1){
					refresh_view();
					$('#salesOrderWrapper .slide_alert_close_button').click();
				} else {
					$('#createFailedNotification').fadeIn(250);
					setTimeout(function(){
						$('#createFailedNotification').fadeOut(250);
					}, 1000);
				}
			}
		})
	}

	
</script>