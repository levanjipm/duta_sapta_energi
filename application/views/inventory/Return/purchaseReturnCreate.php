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
<p id='returnCreateTableText'>There is no purchase return to be created.</p>

<div class='alert_wrapper' id='purchaseReturnWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Confirm return</h3>
		<hr>
		<label>Supplier</label>
		<p id='supplierName_p'></p>
		<p id='supplierAddress_p'></p>
		<p id='supplierCity_p'></p>

		<label>Purchase Return</label>
		<p id='purchaseReturnName_p'></p>
		<p>Created by <span id='purchaseReturnCreator_p'></span></p>
		<p>Created on <span id='purchaseReturnCreateDate_p'></span></p>

		<form id='purchaseReturnForm'>
			<label>Return data</label>
			<input type='date' class='form-control' id='date' name='date' required min='2020-01-01'><br>
			<input type='text' class='form-control' id='document' name='document' placeholder="Return document" required><br>

			<table class='table table-bordered'>
				<tr>
					<th>Reference</th>
					<th>Name</th>
					<th>Quantity</th>
					<th>Sent</th>
					<th>Action</th>
				</tr>
				<tbody id='itemTableContent'></tbody>
			</table>


			<button type='button' class='button button_default_dark' onclick='createPurchaseReturn()'><i class='fa fa-long-arrow-right'></i></button>

			<input type='hidden' id='purchaseReturnQuantity' min='1' value='0' required>
			<div class='notificationText danger' id='failedConfirmNotification'><p>Failed to create purchase return.</p></div>
		</form>
	</div>
</div>
<script>
	$("#purchaseReturnForm").validate({
		ignore:"",
		rules: {"hidden_field": {required: true, "min": 1}}
	});
	
    $(document).ready(function(){
        refreshView();
    });

	$('#purchaseReturnForm input').on('keypress', function(e) {
		return e.which !== 13;
	});

    function refreshView(page = $('#page').val()){
        $.ajax({
            url:'<?= site_url('Purchase_return/getIncompletedReturn') ?>',
            data:{
                page: page,
                term: $('#search_bar').val()
            },
            success:function(response){
                $('#returnCreateTableContent').html("");
				var items = response.items;
				var itemCount = 0;
				$.each(items, function(index, item){
					var name = item.name;

					var supplier = item.supplier;

					var supplierName = supplier.name;

					var complete_address = supplier.address;
					var supplier_number = supplier.number;
					var supplier_block = supplier.block;
					var supplier_rt = supplier.rt;
					var supplier_rw = supplier.rw;
					var supplier_city = supplier.city;
					var supplier_postal = supplier.postal;
					
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

					var documentName 	= item.name;
					var date			= item.created_date;
					var id				= item.id;

					$('#returnCreateTableContent').append("<tr><td><label>" + documentName + "</label><p>" + my_date_format(date) + "</p></td><td><label>" + supplierName + "</label><p>" + complete_address + "</p><p>" + supplier_city + "</p></td><td><button class='button button_default_dark' onclick='viewSubmission(" + id + ")'><i class='fa fa-eye'></i></button>");
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
        $.ajax({
            url:"<?= site_url('Purchase_return/getById') ?>",
            data:{
                id: n
            },
            success:function(response){
				var supplier = response.supplier;
				var supplierName = supplier.name;

				var complete_address = supplier.address;
				var supplier_number = supplier.number;
				var supplier_block = supplier.block;
				var supplier_rt = supplier.rt;
				var supplier_rw = supplier.rw;
				var supplier_city = supplier.city;
				var supplier_postal = supplier.postal;
					
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

				$('#supplierName_p').html(supplierName);
				$('#supplierAddress_p').html(complete_address);
				$('#supplierCity_p').html(supplier_city);

				var general = response.general;
				var name = general.name;
				var created_by = general.created_by;
				var created_date = general.created_date;

				$('#purchaseReturnName_p').html(name);
				$('#purchaseReturnCreator_p').html(created_by);
				$('#purchaseReturnCreateDate_p').html(my_date_format(created_date));

				var items = response.items;
				$('#itemTableContent').html("");
				$.each(items, function(index, item){
					var reference = item.reference;
					var name = item.name;
					var id = item.id;
					var sent = parseInt(item.sent);
					var quantity = parseInt(item.quantity);
					var status = item.status;

					if(status == 0){
						$('#itemTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>" + numeral(sent).format('0,0') + "</td><td><input type='number' class='form-control' id='sentQuantity-" + id + "' name='sentQuantity[" + id + "]' max='" + (quantity - sent) + "' min='0'></td></tr>");
						$('#sentQuantity-' + id).change(function(){
							var totalQuantity = 0;
							$('input[id^="sentQuantity-"]').each(function(){
								totalQuantity += parseInt($(this).val());
							});
							$('#purchaseReturnQuantity').val(totalQuantity);						
						});
					}
				});

				$('#purchaseReturnQuantity').val(0);
				$('#purchaseReturnWrapper').fadeIn(300, function(){
					$('#purchaseReturnWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
            }
        })
    }

	function createPurchaseReturn(){
		if($('#purchaseReturnForm').valid()){
			$.ajax({
				url:"<?= site_url('Purchase_return/sendItem') ?>",
				data:$('#purchaseReturnForm').serialize(),
				type:'POST',
				beforeSend:function(){
					$("button").attr('disabled', true);
				}, 
				success:function(response){
					$("button").attr('disabled', false);
					refreshView();
					if(response == 1){
						$('#purchaseReturnWrapper .slide_alert_close_button').click();
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
