<head>
	<title>Sales return - Confirm</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /Return/ Confirm</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div id='returnTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Customer</th>
					<th>Document</th>
					<th>Action</th>
				</tr>
				<tbody id='returnTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='returnTableText'>There is no sales return to be confirmed.</p>
	</div>
</div>

<div class='alert_wrapper' id='salesReturnWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Confirm return</h3>
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

		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Unit price</th>
				<th>Quantity</th>
				<th>Price</th>
			</tr>
			<tbody id='itemTableContent'></tbody>
		</table>


		<button class='button button_default_dark' onclick='confirmSalesReturn()'><i class='fa fa-long-arrow-right'></i></button>
		<button class='button button_danger_dark' onclick='deleteSalesReturn()'><i class='fa fa-trash'></i></button>

		<input type='hidden' id='salesReturnId'>
		<div class='notificationText danger' id='failedConfirmNotification'><p>Failed to confirm sales return.</p></div>
		<div class='notificationText danger' id='failedDeleteNotification'><p>Failed to delete sales return.</p></div>
	</div>
</div>

<script>
	$(document).ready(function(){
		refreshView();
	})

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Sales_return/getUnconfirmedDocuments') ?>',
			data:{
				page: page
			},
			success:function(response){
				$('#returnTableContent').html("");
				var items = response.items;
				var itemCount = 0;
				$.each(items, function(index, item){
					var customer_name = item.name;
					var complete_address = item.address;
					var customer_number = item.number;
					var customer_block = item.block;
					var customer_rt = item.rt;
					var customer_rw = item.rw;
					var customer_city = item.city;
					var customer_postal = item.postal;
					
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

					var documentName 	= item.documentName;
					var date			= item.date;
					var id				= item.id;
					$('#returnTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><label>" + customer_name + "</label><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td>" + documentName + "</td><td><button class='button button_default_dark' onclick='viewSubmission(" + id + ")'><i class='fa fa-eye'></i></button>");
					itemCount++;
				})

				if(itemCount > 0){
					$('#returnTableText').hide();
					$('#returnTable').show();
				} else {
					$('#returnTableText').show();
					$('#returnTable').hide();
				}

				$('#page').html("");
				for(i = 1; i <= page; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
 						$('#page').append("<option value='" + i + "'>" + i + "</option>")
					}					
				}
			}
		})
	}

	function viewSubmission(n){
		$.ajax({
			url:"<?= site_url('Sales_return/getById') ?>",
			data:{
				id: n
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
					returnValue			+= totalPrice;

					$('#itemTableContent').append("<tr><td>" + reference + "</td><td>" + description + "</td><td>Rp. " + numeral(unitPrice).format('0,0.00') + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(totalPrice).format('0,0.00') + "</td></tr>");
				});

				$('#itemTableContent').append("<tr><td colspan='2'></td><td colspan='2'>Total</td><td>Rp. " + numeral(returnValue).format('0,0.00') + "</td></tr>");

				$('#salesReturnId').val(n);

				$('#salesReturnWrapper').fadeIn(300, function(){
					$('#salesReturnWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function confirmSalesReturn()
	{
		$.ajax({
			url:"<?= site_url('Sales_return/confirmById') ?>",
			data:{
				id: $('#salesReturnId').val()
			},
			type:'POST',
			beforeSend:function(){
				$('button').attr('disabled', true);
			}, success:function(response){
				$('button').attr('disabled', false);
				refreshView();
				if(response == 1){
					$('#salesReturnWrapper .slide_alert_close_button').click();
				} else {
					$('#failedConfirmNotification').fadeIn(250);
					setTimeout(function(){
						$('#failedConfirmNotification').fadeOut(250);
					}, 1000);					
				}
			}
		})
	}

	function deleteSalesReturn()
	{
		$.ajax({
			url:"<?= site_url('Sales_return/deleteById') ?>",
			data:{
				id: $('#salesReturnId').val()
			},
			type:'POST',
			beforeSend:function(){
				$('button').attr('disabled', true);
			}, success:function(response){
				$('button').attr('disabled', false);
				refreshView();
				if(response == 1){
					$('#salesReturnWrapper .slide_alert_close_button').click();
				} else {
					$('#failedDeleteNotification').fadeIn(250);
					setTimeout(function(){
						$('#failedDeleteNotification').fadeOut(250);
					}, 1000);					
				}
			}
		})
	}

	$('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
</script>