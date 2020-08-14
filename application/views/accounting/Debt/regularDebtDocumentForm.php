<form id='debtDocumentForm'>
	<label>Supplier</label>
	<select class='form-control' name='supplier' id='supplier' required></select>
	<br>
	<input type='text' class='form-control' id='search_bar' placeholder='Search'>
	<br>
	<div id='goodReceiptTable' style='display:none'>
		<table class='table table-bordered'>
			<tr>
				<th>Date</th>
				<th>Document</th>
				<th>Informaction</th>
				<th>Action</th>
			</tr>
			<tbody id='goodReceiptTableContent'></tbody>
		</table>
	
		<select class='form-control' id='page' style='width:100px'>
			<option value='1'>1</option>
		</select>
	</div>
	<p id='goodReceiptTableText' style='display:none' style='display:none'>There is no uninvoiced good receipts found.</p>
	
	<div id='document_input'></div><br>

	<input type='hidden' id='documentCount' min='1'><br>
	<button type='button' class='button button_default_dark' id='submit_button' style='display:none'><i class='fa fa-long-arrow-right'></i></button>
</form>

<div class='alert_wrapper' id='debtDocumentFormValidation'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<form id='debtDocumentValidationForm'>
			<h3 style='font-family:bebasneue'>Create debt document</h3>
			<hr>
			<label>Invoice</label><br>
			<label>Date</label>
			<input type='date' class='form-control' name='date' required>
			
			<label>Document</label>
			<input type='text' class='form-control' name='invoiceName' required>

			<label>Tax document</label>
			<input type='text' class='form-control' id='taxInvoiceName' name='taxInvoiceName'>
			<script>
				$("#taxInvoiceName").inputmask("999.999-99.99999999");
			</script>
			<hr>

			<label>Supplier</label>
			<p id='supplierName_p'></p>
			<p id='supplierAddress_p'></p>
			<p id='supplierCity_p'></p>
			<hr>

			<label>Good receipts</label>
			<div id='goodReceiptsTable'></div>

			<div id='documentInputValidate'></div>

			<button type='button' id='submitDebtButton' class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button><br>
			<div class='notificationText danger' id='insertItemFailed'><p>Failed to insert debt document.</p></div>
		</form>
	</div>
</div>

<script>
	var documentCount = 0;
	var documentArray;

	$('#debtDocumentForm').validate({
		ignore: '',
		rules: {"hidden_field": {required: true}}
	});

	$('#debtDocumentValidationForm').validate();

	$('#debtDocumentForm input').on('keypress', function(e) {
		return e.which !== 13;
	});

	$('#debtDocumentValidationForm input').on('keypress', function(e) {
		return e.which !== 13;
	});

	$(document).ready(function(){
		fetchUninvoicedSuppliers();
	});
	$('#supplier').change(function(){
		getUninvoicedDebtDocument(1, "");
		documentCount = 0;
		$('#document_input').html("");
		$('#documentCount').val(0);
	})
	$('#page').change(function(){
		getUninvoicedDebtDocument();
	});
	$('#search_bar').change(function(){
		getUninvoicedDebtDocument(1);
	});

	function fetchUninvoicedSuppliers(){
		$.ajax({
			url:"<?= site_url('Debt/getUninvoicedSupplierIds') ?>",
			beforeSend:function(){
				$('#supplier').attr('disabled', true);
			},
			success:function(response){
				$('#supplier').attr('disabled', false);
				$.each(response, function(index, supplier){
					var supplierId = supplier.id;
					var supplierName = supplier.name;
					$('#supplier').append("<option value='" + supplierId + "'>" + supplierName + "</option>")
				});

				getUninvoicedDebtDocument(1, "");
			}
		})
	}
	
	function getUninvoicedDebtDocument(page = $('#page').val(), term = $('#search_bar').val()){
		$.ajax({
			url:'<?= site_url('Debt/getUninvoicedDocumentsBySupplierId') ?>',
			data:{
				supplier_id:$('#supplier').val(),
				page:page,
				term:term
			},
			type:'GET',
			success:function(response){
				var goodReceiptCount = 0;
				$('#goodReceiptTableContent').html('');
				var bills		= response.bills;
				$.each(bills, function(index, value){
					var code_good_receipt_id	= value.id;
					var purchase_order_name		= value.purchase_order_name;
					var purchase_order_date		= value.purchase_order_date;
					
					var supplier_name			= value.supplier_name;
					var supplier_address		= value.supplier_address;
					var supplier_city			= value.supplier_city;
					
					var document				= value.name;
					var date					= value.date;
					var received_date			= value.received_date;
					goodReceiptCount++;
					
					if($('#document-' + code_good_receipt_id).length == 0){
						$('#goodReceiptTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><p style='font-family:museo'>" + document + "</p><p sytle='font-family:museo'>Received on " + my_date_format(received_date) + "</p></td>" + 
							"<td><label>Purchase order</label><p style='font-family:museo'>" + purchase_order_name + "</p><p style='font-family:museo'>" + my_date_format(purchase_order_date) + "</p></td>" + 
							"<td><button type='button' class='button button_default_light' onclick='addGoodReceipt(" + code_good_receipt_id + ")' id='plus_button-" + code_good_receipt_id + "'><i class='fa fa-plus'></i></button>" + 
							"<button type='button' class='button button_danger_dark' onclick='removeGoodReceipt(" + code_good_receipt_id + ")' id='minus_button-" + code_good_receipt_id + "' style='display:none'><i class='fa fa-minus'></i></button></td></tr>");
					} else {
						$('#goodReceiptTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><p style='font-family:museo'>" + document + "</p><p sytle='font-family:museo'>Received on " + my_date_format(received_date) + "</p></td>" + 
							"<td><label>Purchase order</label><p style='font-family:museo'>" + purchase_order_name + "</p><p style='font-family:museo'>" + my_date_format(purchase_order_date) + "</p></td>" + 
							"<td><button type='button' class='button button_default_light' onclick='addGoodReceipt(" + code_good_receipt_id + ")' id='plus_button-" + code_good_receipt_id + "' style='display:none'><i class='fa fa-plus'></i></button>" + 
							"<button type='button' class='button button_danger_dark' onclick='removeGoodReceipt(" + code_good_receipt_id + ")' id='minus_button-" + code_good_receipt_id + "'><i class='fa fa-minus'></i></button></td></tr>");
					}
				});

				var selected_page = $('#page').val();
				var pages			= response.pages;
				$('#page').html('');
				for(i = 1; i <= pages; i++){
					if(i == selected_page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}

				if(goodReceiptCount > 0){
					$('#goodReceiptTable').show();
					$('#goodReceiptTableText').hide();	
				} else {
					$('#goodReceiptTable').hide();
					$('#goodReceiptTableText').show();	
				}
			}
		});
	}
	
	function addGoodReceipt(code_good_receipt_id){
		if($('#document-' + code_good_receipt_id).length == 0){
			$('#document_input').append("<input type='hidden' name='document[" + documentCount + "]' class='form-control' id='document-" + code_good_receipt_id + "' value='" + code_good_receipt_id + "'>");
			$('#plus_button-' + code_good_receipt_id).hide();
			$('#minus_button-' + code_good_receipt_id).show();
			
			$('#submit_button').show();
			documentCount++;
			$('#documentCount').val(documentCount);
		};
	}	
	function removeGoodReceipt(code_good_receipt_id){
		$('#document-' + code_good_receipt_id).remove();
		$('#plus_button-' + code_good_receipt_id).show();
		$('#minus_button-' + code_good_receipt_id).hide();
		if($('#document_input input').length == 0){
			$('#submit_button').hide();
			$('#sumbit_button').attr('disabled', true);
		};
		documentCount--;
		$('#documentCount').val(documentCount);
	}

	$('#submit_button').click(function(){
		documentArray = [];
		if($('#debtDocumentForm').valid()){
			$('input[id^="document-"]').each(function(){
				var goodReceiptId = $(this).val();
				documentArray.push(goodReceiptId)
			});

			$.ajax({
				url:"<?= site_url('Good_receipt/getByIdArray') ?>",
				data:{
					goodReceiptArray: JSON.stringify(documentArray)
				},
				type:'GET',
				success:function(response){
					var goodReceipts 		= response.goodReceipts;
					var quantityArray		= new Array();
					$.each(goodReceipts, function(index, goodReceipt){
						var id		= goodReceipt.id;
						var date 	= goodReceipt.date;
						var name	= goodReceipt.name;
						var purchaseOrderName = goodReceipt.purchase_order_name;
						var receivedDate = goodReceipt.received_date;
						var goodReceiptValue 	= 0;
						
						$("<label>Date</label><p>" + my_date_format(date) + "</p><label>Name</label><p>" + name + "</p><label>Purchase order</label><p>" + purchaseOrderName + "</p><label>Received date</label><p>" + my_date_format(receivedDate) + "</p><div class='table-responsive-sm'><table class='table table-bordered'><tr><th>Reference</th><th>Name</th><th>Price</th><th>Quantity</th><th>Total price</th><tbody id='goodReceiptTable-" + id + "' required min='0'></tbody></table></div>").appendTo(goodReceiptsTable);
						
						var items = goodReceipt.detail;
						$.each(items, function(index, item){
							var goodReceiptId = item.id;
							var reference = item.reference;
							var name = item.name;
							var net_price = parseFloat(item.net_price);
							var quantity = parseInt(item.quantity);
							var total_price = net_price * quantity;
							quantityArray[goodReceiptId] = quantity;
							goodReceiptValue += total_price;

							$('#goodReceiptTable-' + id).append("<tr><td>" + reference + "</td><td>" + name + "</td><td><input type='number' class='form-control form-" + id + "' id='price-" + goodReceiptId + "' name='price[" + goodReceiptId + "]' value='" + net_price + "' required min='0'></td><td>" + numeral(quantity).format('0,0.00') + "</td><td id='totalPrice-" + goodReceiptId + "'>Rp. " + numeral(total_price).format('0,0.00') + "</td></tr>");

							$('#price-' + goodReceiptId).on('change', function(){
								var itemEditValue = $(this).val() * quantity;
								$('#totalPrice-' + goodReceiptId).html('Rp. ' + numeral(itemEditValue).format('0,0.00'));

								var goodReceiptEditValue = 0;

								$.each($('.form-' + id), function(index, form){
									var goodReceiptEditId = parseInt($(form).attr('id').substring(6, 261));

									var itemEditValueForm = quantityArray[goodReceiptEditId] * parseFloat($(form).val());
									goodReceiptEditValue += itemEditValueForm;
								});

								$('#totalGoodReceiptPrice-' + id).html("Rp. " + numeral(goodReceiptEditValue).format('0,0.00'));
							});
						});

						$('#goodReceiptTable-' + id).append("<tr><td colspan='2'></td><td colspan='2'>Total</td><td id='totalGoodReceiptPrice-" + id + "'>Rp. " + numeral(goodReceiptValue).format('0,0.00') + "</td>")
					})

					var supplier 				= response.supplier;
					var supplierName 			= supplier.name;
					var complete_address		= '';
					complete_address			+= supplier.address;
					var supplier_city			= supplier.city;
					var supplier_number			= supplier.number;
					var supplier_rt				= supplier.rt;
					var supplier_rw				= supplier.rw;
					var supplier_postal			= supplier.postal_code;
					var supplier_block			= supplier.block;
		
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
					
					if(supplier_postal != null){
						complete_address	+= ', ' + supplier_postal;
					}

					$('#supplierName_p').html(supplierName);
					$('#supplierAddress_p').html(complete_address);
					$('#supplierCity_p').html(supplier_city);
				}
			})

			$('#debtDocumentFormValidation').fadeIn(300, function(){
				$('#debtDocumentFormValidation .alert_box_slide').show("slide", { direction: "right" }, 250);
			});	
		}
	})

	$('#submitDebtButton').click(function(){
		$('#debtDocumentValidationForm').validate();
		$('#documentInputValidate').html($('#document_input').html());
		var data = $('#debtDocumentValidationForm').serializeArray();
		if($('#debtDocumentValidationForm').valid()){
			$.ajax({
				url:"<?= site_url('Debt/insertItem') ?>",
				data:data,
				type:'POST',
				beforeSend:function(){
					$('button').attr('disabled', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					if(response == 1){
						$('#documentInputValidate').html("");
						getUninvoicedDebtDocument(1, "");
						documentCount = 0;
						$('#document_input').html("");
						$('#documentCount').val(0);

						$('#debtDocumentFormValidation .slide_alert_close_button').click();
						
						$('#debtDocumentValidationForm').trigger("reset");
					} else {
						$('#insertItemFailed').fadeIn(250);
						setTimeout(function(){
							$('#insertItemFailed').fadeOut(250);
						}, 1000);
					}
				}
			})
		}
	})

	$('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	})	
</script>