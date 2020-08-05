<form id='debtDocumentForm'>
	<label>Date</label>
	<input type='date' class='form-control' name='date' required>
	
	<label>Supplier</label>
	<select class='form-control' name='supplier' id='supplier' required></select>
	<br>
	<input type='text' class='form-control' id='search_bar' placeholder='Search'>
	<br>
	<table class='table table-bordered'>
		<tr>
			<th>Date</th>
			<th>Document</th>
			<th>Informaction</th>
			<th>Action</th>
		</tr>
		<tbody id='debt_document_by_supplier_tbody'></tbody>
	</table>
	
	<select class='form-control' id='page' style='width:100px'>
		<option value='1'>1</option>
	</select>
	
	<div id='document_input'></div>
	<br>
	<button type='button' class='button button_default_dark' id='submit_button' style='display:none'><i class='fa fa-long-arrow-right'></i></button>
</form>

<div class='alert_wrapper' id='debtDocumentFormValidation'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='box_alert_slide'>
	</div>
</div>

<script>
	$('#debtDocumentForm').validate();

	$('#debtDocumentForm').on('submit', function(){
		return false;
	})

	$('#debtDocumentForm input').on('keypress', function(e) {
		return e.which !== 13;
	});

	$(document).ready(function(){
		fetchUninvoicedSuppliers();
	});
	$('#supplier').change(function(){
		getUninvoicedDebtDocument(1, "");
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
				$('#debt_document_by_supplier_tbody').html('');
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
					
					if($('#document-' + code_good_receipt_id).length == 0){
						$('#debt_document_by_supplier_tbody').append("<tr><td>" + my_date_format(date) + "</td><td><p style='font-family:museo'>" + document + "</p><p sytle='font-family:museo'>Received on " + my_date_format(received_date) + "</p></td>" + 
							"<td><label>Purchase order</label><p style='font-family:museo'>" + purchase_order_name + "</p><p style='font-family:museo'>" + my_date_format(purchase_order_date) + "</p></td>" + 
							"<td><button type='button' class='button button_default_light' onclick='add_code_good_receipt(" + code_good_receipt_id + ")' id='plus_button-" + code_good_receipt_id + "'><i class='fa fa-plus'></i></button>" + 
							"<button type='button' class='button button_danger_dark' onclick='remove_code_good_receipt(" + code_good_receipt_id + ")' id='minus_button-" + code_good_receipt_id + "' style='display:none'><i class='fa fa-minus'></i></button></td></tr>");
					} else {
						$('#debt_document_by_supplier_tbody').append("<tr><td>" + my_date_format(date) + "</td><td><p style='font-family:museo'>" + document + "</p><p sytle='font-family:museo'>Received on " + my_date_format(received_date) + "</p></td>" + 
							"<td><label>Purchase order</label><p style='font-family:museo'>" + purchase_order_name + "</p><p style='font-family:museo'>" + my_date_format(purchase_order_date) + "</p></td>" + 
							"<td><button type='button' class='button button_default_light' onclick='add_code_good_receipt(" + code_good_receipt_id + ")' id='plus_button-" + code_good_receipt_id + "' style='display:none'><i class='fa fa-plus'></i></button>" + 
							"<button type='button' class='button button_danger_dark' onclick='remove_code_good_receipt(" + code_good_receipt_id + ")' id='minus_button-" + code_good_receipt_id + "'><i class='fa fa-minus'></i></button></td></tr>");
					}
					
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
				});
			}
		});
	}
	
	function add_code_good_receipt(code_good_receipt_id){
		if($('#document-' + code_good_receipt_id).length == 0){
			$('#document_input').append("<input type='hidden' class='form-control' name='document[" + code_good_receipt_id + "]' id='document-" + code_good_receipt_id + "'>");
			$('#plus_button-' + code_good_receipt_id).hide();
			$('#minus_button-' + code_good_receipt_id).show();
			
			$('#submit_button').show();
		};
	}
	
	function remove_code_good_receipt(code_good_receipt_id){
		$('#document-' + code_good_receipt_id).remove();
		$('#plus_button-' + code_good_receipt_id).show();
		$('#minus_button-' + code_good_receipt_id).hide();
		if($('#document_input input').length == 0){
			$('#submit_button').hide();
			$('#sumbit_button').attr('disabled', true);
		};
	}

	$('#submit_button').click(function(){
		if($('#debtDocumentForm').valid()){

		}
	})
</script>