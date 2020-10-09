<div id='returnCreateTable'>
    <table class='table table-bordered'>
        <tr>
            <th>Date</th>
            <th>Customer</th>
            <th>Action</th>
        </tr>
        <tbody id='returnCreateTableContent'></tbody>
    </table>

    <select class='form-control' id='page' style='width:100px'>
        <option value='1'>1</option>
    </select>
</div>
<p id='returnCreateTableText'>There is no sales return to be created.</p>

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

		<form id='salesReturnForm'>
			<label>Return data</label>
			<input type='date' class='form-control' id='date' name='date' required min='2020-01-01'><br>
			<input type='text' class='form-control' id='document' name='document' placeholder="Return document" required><br>

			<table class='table table-bordered'>
				<tr>
					<th>Reference</th>
					<th>Name</th>
					<th>Quantity</th>
					<th>Received</th>
					<th>Action</th>
				</tr>
				<tbody id='itemTableContent'></tbody>
			</table>


			<button type='button' class='button button_default_dark' onclick='confirmSalesReturn()'><i class='fa fa-long-arrow-right'></i></button>

			<input type='hidden' id='salesReturnQuantity' min='1' required>
			<div class='notificationText danger' id='failedConfirmNotification'><p>Failed to create sales return.</p></div>
		</form>
	</div>
</div>
<script>
	$("#salesReturnForm").validate({
		ignore:"",
		rules: {"hidden_field": {required: true}}
	});
	
    $(document).ready(function(){
        refreshView();
    });

	$('#salesReturnForm input').on('keypress', function(e) {
		return e.which !== 13;
	});

    function refreshView(page = $('#page').val()){
        $.ajax({
            url:'<?= site_url('Sales_return/getIncompletedReturn') ?>',
            data:{
                page: page,
                term: $('#search_bar').val()
            },
            success:function(response){
                $('#returnCreateTableContent').html("");
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
					var date			= item.created_date;
					var id				= item.id;

					$('#returnCreateTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><label>" + customer_name + "</label><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><button class='button button_default_dark' onclick='viewSubmission(" + id + ")'><i class='fa fa-eye'></i></button>");
					itemCount++;
				})

				if(itemCount > 0){
					$('#returnCreateTableText').hide();
					$('#returnCreateTable').show();
				} else {
					$('#returnCreateTableText').show();
					$('#returnCreateTable').hide();
				}

                $('#page').html("");
                for(i = 1; i <= response.pages; i++){
                    if(i == page){
                        $('#page').append("<option value='" + i + "' selected>" + i + "</option>");
                    } else {
                        $('#page').append("<option value='" + i + "'>" + i + "</option>");
                    }
                }
            }
        })
    }

    function viewSubmission(n){
		$("#salesReturnQuantity").val(0);
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
                    var id              = item.id;
					var reference 		= item.reference;
					var description		= item.name;
					var quantity		= parseInt(item.quantity);
                    var received        = parseInt(item.received);
                    var status          = parseInt(item.is_done);
                    var remainder       = quantity - received;

                    if(status == 0 && quantity > received){
                        $('#itemTableContent').append("<tr><td>" + reference + "</td><td>" + description + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>" + numeral(received).format('0,0') + "</td><td><input type='number' class='form-control' name='quantity[" + id + "]' id='quantity-" + id + "' max='" + remainder + "' value='0' required></td></tr>");
						
						$("#quantity-" + id).change(function(){
							var totalQuantity = 0;
							$("input[id^='quantity-']").each(function(){
								totalQuantity += parseInt($(this).val());
							})

							$('#salesReturnQuantity').val(totalQuantity);
						})
					}					
				});

				$('#salesReturnWrapper').fadeIn(300, function(){
					$('#salesReturnWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
            }
        })
    }

	function confirmSalesReturn(){
		if($('#salesReturnForm').valid()){
			$.ajax({
				url:"<?= site_url('Sales_return/receiveItem') ?>",
				data:$('#salesReturnForm').serialize(),
				type:'POST',
				beforeSend:function(){
					$("button").attr('disabled', true);
				}, 
				success:function(response){
					$("button").attr('disabled', false);
					refreshView();
					if(response == 1){
						$('#salesReturnWrapper .slide_alert_close_button').click();
						$('#itemTableContent').html("");
					} else {
						$('#failedConfirmNotification').fadeIn(250);
						setTimeout(function(){
							$('#failedConfirmNotification').fadeOut(250);
						}, 1000);
					}
				}
			})
		}
	}

    $('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	})	
</script>
