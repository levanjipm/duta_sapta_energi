<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Sales'><i class='fa fa-bar-chart'></i></a> /Debt document</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<input type='text' class='form-control input-lg' id='search_bar'>
			<div class='input-group-append'>
				<a href='<?= site_url('Debt/create') ?>' role='button' class='button button_default_dark'>Create</a>
				<a href='<?= site_url('Debt/createBlank') ?>' role='button' class='button button_default_dark'>Create blank </a>
			</div>
		</div>
		<br>
		<table class='table table-bordered' id='debt_document_table'>
			<tr>
				<th>Date</th>
				<th>Document</th>
				<th>Supplier</th>
				<th>Action</th>
			</tr>
			<tbody id='debt_document_tbody'></tbody>
		</table>
	
		<select class='form-control' id='page' style='width:100px'>
			<option value='1'>1</option>
		</select>
</div>

<div class='alert_wrapper' id='view_debt_wrapper'>
	<button type='button' class='slide_alert_close_button'>&times </button>
	<div class='alert_box_slide'>
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
	refresh_view();
	
	$('#page').change(function(){
		refresh_view();
	});
	
	$('#search_bar').change(function(){
		refresh_view(1);
	});
	
	function refresh_view(page = $('#page').val())
	{
		$.ajax({
			url:'<?= site_url('Debt/view_unconfirmed_documents') ?>',
			data:{
				page:page,
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
					
					$('#debt_document_tbody').append("<tr><td>" + my_date_format(date) + "</td><td><p>" + supplier_name + "</p><p>" + supplier_address + "</p><p>" + supplier_city + "</p></td><td><p>" + invoice_document + "</p><p>" + tax_document + "</p></td><td><button type='button' class='button button_default_dark' onclick='view_debt_document(" + id + ")' title='View " + invoice_document + "'><i class='fa fa-eye'></i></button></td></tr>");
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
			url:'<?= site_url('Debt/getById') ?>',
			data:{
				id:n
			},
			success:function(response){
				$('#purchase_invoice_id').val(n);
				var total_value = 0;
				debt_array		= response.debt;
				document_array	= response.documents;
				detail_array	= response.details;
				
				var invoice_date		= debt_array.date;
				var invoice_name		= debt_array.invoice_document;
				var invoice_tax_name	= debt_array.tax_document;
				
				var supplier_name		= debt_array.name;
				var supplier_address	= debt_array.address;
				var supplier_city		= debt_array.city;
				
				$('#invoice_date_p').html(my_date_format(invoice_date));
				$('#invoice_document_p').html(invoice_name);
				$('#invoice_tax_document_p').html(invoice_tax_name);
				
				$('#supplier_name_p').html(supplier_name);
				$('#supplier_address_p').html(supplier_address);
				$('#supplier_city_p').html(supplier_city);
				$('#good_receipt_table').html('');
				$.each(document_array, function(index, value){
					var document_date			= value.date;
					var document_name			= value.name;
					var document_received_date	= value.received_date;
					var document_id				= value.id;
					$('#good_receipt_table').append("<tr><td>" + my_date_format(document_date) + "</td><td>" + document_name + "</td><td colspan='4'></td><td id='document_value-" + document_id + "'></td></tr>");
					
					var document_value			= 0;
					$.each(detail_array, function (index_a, value_a){
						if(value_a.code_good_receipt_id == document_id){
							var reference			= value_a.reference;
							var name				= value_a.name;
							var quantity			= value_a.quantity;
							var billed_price		= value_a.billed_price;
							var total_price			= billed_price * quantity;
							$('#good_receipt_table').append("<tr><td colspan='2'></td><td>" + reference + "</td><td>" + name + "</td><td>Rp. " + numeral(billed_price).format('0,0.00') + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(total_price).format('0,0.00') + "</td></tr>");
							
							document_value += total_price;
						};
					});
					
					total_value		+= document_value;
					$('#document_value-' + document_id).html('Rp. ' + numeral(document_value).format('0,0.00'));
				});
				
				$('#good_receipt_table').append("<tr><td colspan='5'></td><td>Total</td><td>Rp. " + numeral(total_value).format('0,0.00') + "</td></tr>");
				
				$('#view_debt_wrapper').fadeIn(300, function(){
					$('#view_debt_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
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
	
	$('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
</script>	