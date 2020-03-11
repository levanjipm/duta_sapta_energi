<div class='dashboard'>
	<h2 style='font-family:bebasneue'>Debt document</h2>
	<hr>
	<a href='<?= site_url('Debt/create_dashboard') ?>'><button type='button' class='button button_default_light'>Create debt document</button></a>
	<br><br>
	<input type='text' class='form_control' placeholder='Search' id='search_bar'>
	<br><br>
	<table class='table table-bordered' id='debt_document_table'>
		<tr>
			<th>Date</th>
			<th>Document</th>
			<th>Supplier</th>
			<th>Action</th>
		</tr>
		<tbody id='debt_document_tbody'></tbody>
	</table>
	
	<select class='form-control' id='page' style='width:100px' value='1'></select>
</div>

<div class='alert_wrapper' id='view_debt_wrapper'>
	<button type='button' class='alert_close_button'>&times </button>
	<div class='alert_box_default'>
		<form action='<?= site_url('Debt/confirm') ?>' method='POST'>
			<input type='hidden' id='purchase_invoice_id' name='id'>
			<h2 style='font-family:bebasneue'>Debt document</h2>
			<hr>
			<label>Purchase invoice</label>
			<p style='font-family:museo' id='invoice_date_p'></p>
			<p style='font-family:museo' id='invoice_document_p'></p>
			<p style='font-family:museo' id='invoice_tax_document_p'></p>
			
			<label>Supplier</label>
			<p style='font-family:museo' id='supplier_name_p'></p>
			<p style='font-family:museo' id='supplier_address_p'></p>
			<p style='font-family:museo' id='supplier_city_p'></p>
			
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Document</th>
					<th>Reference</th>
					<th>Name</th>
					<th>Unit price</th>
					<th>Quantity</th>
					<th>Total price</th>
				</tr>
				<tbody id='good_receipt_table'></tbody>
			</table>
			
			<button class='button button_default_dark'>Submit</button>
			<button type='button' class='button button_danger_dark' id='delete_purchase_invoice_button'>Delete</button>
		</form>
	</div>
</div>
<script>
	$(document).ready(function(){
		$.ajax({
			url:'<?= site_url('Debt/view_unconfirmed_documents') ?>',
			data:{
				page:1,
				term:''
			},
			success:function(response){
				var page	= $('#page').val();
				array	= response.invoices;
				$('#debt_document_tbody').html('');
				$.each(array, function(index, value){
					var id					= value.id;
					var date				= value.date;
					var tax_document		= value.tax_document;
					var invoice_document	= value.invoice_document;
					var supplier_name		= value.name;
					var supplier_address	= value.address;
					var supplier_city		= value.city;
					
					$('#debt_document_tbody').append("<tr><td>" + date + "</td><td><p>" + supplier_name + "</p><p>" + supplier_address + "</p><p>" + supplier_city + "</p></td><td><p>" + invoice_document + "</p><p>" + tax_document + "</p></td><td><button type='button' class='button button_default_light' onclick='view_debt_document(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
				});
				
				$('#page').html('');
				var pages		= response.pages;
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		});
	});
	
	$('#page').change(function(){
		refresh_view();
	});
	
	$('#search_bar').change(function(){
		refresh_view();
	});
	
	function refresh_view()
	{
		$.ajax({
			url:'<?= site_url('Debt/view_unconfirmed_documents') ?>',
			data:{
				page:$('#page').val(),
				term:$('#search_bar').val()
			},
			success:function(response){
				var page	= $('#page').val();
				array	= response.invoices;
				$('#debt_document_tbody').html('');
				$.each(array, function(index, value){
					var id					= value.id;
					var date				= value.date;
					var tax_document		= value.tax_document;
					var invoice_document	= value.invoice_document;
					var supplier_name		= value.name;
					var supplier_address	= value.address;
					var supplier_city		= value.city;
					
					$('#debt_document_tbody').append("<tr><td>" + date + "</td><td><p>" + supplier_name + "</p><p>" + supplier_address + "</p><p>" + supplier_city + "</p></td><td><p>" + invoice_document + "</p><p>" + tax_document + "</p></td><td><button type='button' class='button button_default_light' onclick='view_debt_document(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
				});
				
				$('#page').html('');
				var pages		= response.pages;
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		});
	}
	
	function view_debt_document(n){
		var code_purchase_invoice_id	= n;
		$.ajax({
			url:'<?= site_url('Debt/view_by_id') ?>',
			data:{
				id:n
			},
			success:function(response){
				$('#purchase_invoice_id').val(n);
				debt_array		= response.debt;
				document_array	= response.documents;
				detail_array	= response.details;
				
				var invoice_date		= debt_array.date;
				var invoice_name		= debt_array.invoice_document;
				var invoice_tax_name	= debt_array.tax_document;
				
				var supplier_name		= debt_array.name;
				var supplier_address	= debt_array.address;
				var supplier_city		= debt_array.city;
				
				$('#invoice_date_p').html(invoice_date);
				$('#invoice_document_p').html(invoice_name);
				$('#invoice_tax_document_p').html(invoice_tax_name);
				
				$('#supplier_name_p').html(supplier_name);
				$('#supplier_address_p').html(supplier_address);
				$('#supplier_city_p').html(supplier_city);
				
				$.each(document_array, function(index, value){
					var document_date			= value.date;
					var document_name			= value.name;
					var document_received_date	= value.received_date;
					var document_id				= value.id;
					$('#good_receipt_table').append("<tr><td>" + document_date + "</td><td>" + document_name + "</td><td colspan='5'></td></tr>");
					$.each(detail_array, function (index_a, value_a){
						if(value_a.code_good_receipt_id = document_id){
							var reference			= value_a.reference;
							var name				= value_a.name;
							var quantity			= value_a.quantity;
							var billed_price		= value_a.billed_price;
							var total_price			= billed_price * quantity;
							$('#good_receipt_table').append("<tr><td colspan='2'></td><td>" + reference + "</td><td>" + name + "</td><td>Rp. " + numeral(billed_price).format('0,0.00') + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(total_price).format('0,0.00') + "</td></tr>");
						};
					});
				});
				
				$('#view_debt_wrapper').fadeIn();
			}
		});
	}

	$('#delete_purchase_invoice_button').click(function(){
		$.ajax({
			url:'<?= site_url('Debt/delete') ?>',
			data:{
				id:$('#purchase_invoice_id').val()
			},
			type:'POST',
			beforeSend:function(){
				$('button').attr('disabled', true);
			}, success:function(){
				location.reload();
			}
		});
	});
	
	$('.alert_close_button').click(function(){
		$(this).parent().fadeOut();
	});
</script>	