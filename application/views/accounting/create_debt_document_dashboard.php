<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-bar-chart'></i></a> /<a href='<?= site_url('Debt') ?>'>Debt document</a> /Create</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<form action='<?= site_url('Debt/create_debt_document') ?>' method='POST' id='debt_document_form'>
			<label>Date</label>
			<input type='date' class='form-control' name='date' required>
			
			<label>Supplier</label>
			<select class='form-control' name='supplier' id='supplier' required>
<?php
	foreach($suppliers as $supplier){
		$supplier_id	= $supplier->id;
		$supplier_name	= $supplier->name;
?>
				<option value='<?= $supplier_id ?>'><?= $supplier_name ?></option>
<?php
	}
?>
			</select>
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
			<button class='button button_default_dark' id='submit_button' style='display:none'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>
<script>
	$('#page').change(function(){
		get_uninvoiced_debt_document();
	});
	
	$('#search_bar').change(function(){
		get_uninvoiced_debt_document();
	});
	
	$(document).ready(function(){
		$.ajax({
			url:'<?= site_url('Debt/view_uninvoiced_documents_by_supplier') ?>',
			data:{
				supplier_id:$('#supplier').val(),
				page:1,
				term:''
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
					
					$('#debt_document_by_supplier_tbody').append("<tr><td>" + date + "</td><td><p style='font-family:museo'>" + document + "</p><p sytle='font-family:museo'>Received on " + received_date + "</p></td>" + 
						"<td><label>Purchase order</label><p style='font-family:museo'>" + purchase_order_name + "</p><p style='font-family:museo'>" + purchase_order_date + "</p></td>" + 
						"<td><button type='button' class='button button_default_dark' onclick='add_code_good_receipt(" + code_good_receipt_id + ")' id='plus_button-" + code_good_receipt_id + "'><i class='fa fa-plus'></i></button>" + 
						"<button type='button' class='button button_danger_dark' onclick='remove_code_good_receipt(" + code_good_receipt_id + ")' id='minus_button-" + code_good_receipt_id + "' style='display:none'><i class='fa fa-minus'></i></button></td></tr>");
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
			}
		});
	});
	
	function get_uninvoiced_debt_document(){
		$.ajax({
			url:'<?= site_url('Debt/view_uninvoiced_documents_by_supplier') ?>',
			data:{
				supplier_id:$('#supplier').val(),
				page:$('#page').val(),
				term:$('#search_bar').val()
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
						$('#debt_document_by_supplier_tbody').append("<tr><td>" + date + "</td><td><p style='font-family:museo'>" + document + "</p><p sytle='font-family:museo'>Received on " + received_date + "</p></td>" + 
							"<td><label>Purchase order</label><p style='font-family:museo'>" + purchase_order_name + "</p><p style='font-family:museo'>" + purchase_order_date + "</p></td>" + 
							"<td><button type='button' class='button button_default_light' onclick='add_code_good_receipt(" + code_good_receipt_id + ")' id='plus_button-" + code_good_receipt_id + "'><i class='fa fa-plus'></i></button>" + 
							"<button type='button' class='button button_danger_dark' onclick='remove_code_good_receipt(" + code_good_receipt_id + ")' id='minus_button-" + code_good_receipt_id + "' style='display:none'><i class='fa fa-minus'></i></button></td></tr>");
					} else {
						$('#debt_document_by_supplier_tbody').append("<tr><td>" + date + "</td><td><p style='font-family:museo'>" + document + "</p><p sytle='font-family:museo'>Received on " + received_date + "</p></td>" + 
							"<td><label>Purchase order</label><p style='font-family:museo'>" + purchase_order_name + "</p><p style='font-family:museo'>" + purchase_order_date + "</p></td>" + 
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
	
	$('#debt_document_form').validate();
</script>