<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Inventory') ?>' title='Inventory'><i class='fa fa-briefcase'></i></a> /Good receipt/ Confirm</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div id='goodReceiptTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Name</th>
					<th>Date</th>
					<th>Action</th>
				</tr>
				<tbody id='goodReceiptTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='goodReceiptTableText'>There is no good receipt to be confirmed.</p>
	</div>
</div>
<div class='alert_wrapper' id='good_receipt_validation_wrapper'>
	<button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Confirm good receipt</h3>
		<hr>
		<label>Good receipt</label>
		<p style='font-family:museo' id='good_receipt_date'></p>
		<p style='font-family:museo' id='good_receipt_document'></p>
		<p style='font-family:museo'>Received on <span id='good_receipt_received_date'></span></p>
		
		<label>Supplier</label>
		<p style='font-family:museo' id='supplier_name_p'></p>
		<p style='font-family:museo' id='supplier_address_p'></p>
		<p style='font-family:museo' id='supplier_city_p'></p>
		
		<label>Purchase order</label>
		<p style='font-family:museo' id='purchase_order_name'></p>
		<p style='font-family:museo' id='purchase_order_date'></p>
		
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
			</tr>
			<tbody id='goodReceiptItemsTableContent'></tbody>
		</table>
		
		<input type='hidden' id='good_receipt_id' name='id'>
		<button type='button' class='button button_default_dark' onclick='confirmGoodReceipt()' title='Submit good receipt'><i class='fa fa-long-arrow-right'></i></button>
		<button type='button' class='button button_danger_dark' onclick='deleteGoodReceipt()' title='Delete good receipt'><i class='fa fa-trash'></i></button>

		<div class='notificationText danger' id='failedDeleteNotification'><p>Failed to delete good receipt.</p></div>
		<div class='notificationText danger' id='failedConfirmNotification'><p>Failed to confirm good receipt.</p></div>
	</div>
</div>
<script>
	$(document).ready(function(){
		refresh_view();
	})
	
	function refresh_view(page = $('#page').val())
	{
		$.ajax({
			url:'<?= site_url("Good_receipt/showUnconfirmedItems") ?>',
			data:{
				page: page
			},
			success:function(response){
				$('#page').html('');
				var pages = response.pages;
				for(i = 1; i<= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}

				var items = response.items;
				var goodReceiptCount = 0;
				$('#goodReceiptTableContent').html('');
				$.each(items, function(index, item){
					var id = item.id;
					var date = item.date;
					var name = item.name;

					$('#goodReceiptTableContent').append("<tr><td>" + name + "</td><td>" + my_date_format(date) + "</td><td><button class='button button_default_dark' onclick='viewGoodReceipt(" + id + ")'><i class='fa fa-eye'></i></button>");
					goodReceiptCount++;
				})

				if(goodReceiptCount > 0){
					$('#goodReceiptTable').show();
					$('#goodReceiptTableText').hide();
				} else {
					$('#goodReceiptTable').hide();
					$('#goodReceiptTableText').show();
				}
			}
		})
	}

	function viewGoodReceipt(n){
		$.ajax({
			url:'<?= site_url("Good_receipt/showById") ?>',
			data:{
				id:n
			},
			beforeSend:function(){
				$('button').attr('disabled', true);
			}, success:function(response){
				$('button').attr('disabled', false);

				var general = response.general;
				$('#good_receipt_id').val(n);

				var receivedDate = general.received_date;

				$('#good_receipt_received_date').html(my_date_format(receivedDate));

				var purchaseOrderName = general.purchase_order_name;
				var purchaseOrderDate = general.purchase_order_date;

				$('#purchase_order_name').html(purchaseOrderName);
				$('#purchase_order_date').html(my_date_format(purchaseOrderDate));

				var supplierName = general.supplier_name;
				var complete_address = general.address;

				var supplier_city			= general.city;
				var supplier_number			= general.number;
				var supplier_rt				= general.rt;
				var supplier_rw				= general.rw;
				var supplier_postal			= general.postal_code;
				var supplier_block			= general.block;
				var supplier_id				= general.id;

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
				
				if(supplier_postal != ''){
					complete_address	+= ', ' + supplier_postal;
				}

				$('#supplier_name_p').html(supplierName);
				$('#supplier_address_p').html(complete_address);
				$('#supplier_city_p').html(supplier_city);

				var items = response.items;
				$('#goodReceiptItemsTableContent').html('');
				$.each(items, function(index, item){
					var reference = item.reference;
					var name = item.name;
					var quantity = item.quantity;
					$('#goodReceiptItemsTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td></tr>");
				})


				$('#good_receipt_validation_wrapper').fadeIn(300, function(){
					$('#good_receipt_validation_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function confirmGoodReceipt()
	{
		$.ajax({
			url:'<?= site_url("Good_receipt/confirmById") ?>',
			data:{
				id: $('#good_receipt_id').val()
			},
			type:'POST',
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				if(response == 1){
					refresh_view();
					$('#good_receipt_validation_wrapper .slide_alert_close_button').click();
				} else {
					$('#failedConfirmNotification').fadeTo(250, 1);
					setTimeout(function(){
						$('#failedConfirmNotification').fadeTo(250, 0)
					}, 1000);
				}
			}
		})
	}

	function deleteGoodReceipt()
	{
		$.ajax({
			url:'<?= site_url("Good_receipt/deleteById") ?>',
			data:{
				id: $('#good_receipt_id').val()
			},
			type:'POST',
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				if(response == 1){
					refresh_view();
					$('#good_receipt_validation_wrapper .slide_alert_close_button').click();
				} else {
					$('#failedConfirmNotification').fadeTo(250, 1);
					setTimeout(function(){
						$('#failedConfirmNotification').fadeTo(250, 0)
					}, 1000);
				}
			}
		})
	}
	
	$('.slide_alert_close_button').click(function(){
		$('#good_receipt_validation_wrapper .alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$('#good_receipt_validation_wrapper').fadeOut();
		});
	});
</script>