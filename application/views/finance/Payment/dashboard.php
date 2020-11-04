<head>
	<title>Payment</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-briefcase'></i></a> /Payment</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Supplier</label>
		<select class='form-control' id='supplier' required>
		<?php foreach($suppliers as $supplier){ ?>
			<option value='<?= $supplier->id ?>'><?= $supplier->name ?></option>
		<?php } ?>
		</select>
		<br>
		<p><strong>Rp. <span id='paymentValue'></span></strong></p>
		<hr>
		<div id='invoiceTable' style='display:none'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Invoice</th>
					<th>Due date</th>
					<th>Value</th>
					<th>Paid</th>
					<th>Action</th>
				</tr>
				<tbody id='invoiceTableContent'></tbody>
			</table>
		</div>
		<p id='invoiceTableText'>There is no invoice found.</p>
	</div>
</div>
<script>
	var paymentValue;
	$(document).ready(function(){
		if($('#supplier').val() != null){
			refreshView();
			$('#invoiceTable').show();
		} else {
			$('#invoiceTable').hide();
		}
	})

	$('#supplier').change(function(){
		if($('#supplier').val() != null){
			refreshView();
			$('#invoiceTable').show();
		} else {
			$('#invoiceTable').hide();
		}
	})

	function refreshView(supplier = $('#supplier').val()){
		$.ajax({
			url:"<?= site_url('Finance/getIncompletedInvoiceBySupplierId') ?>",
			data:{
				id: $('#supplier').val()
			},
			success:function(response){
				paymentValue		= 0;
				$('#paymentValue').html(numeral(paymentValue).format('0,0.00'));
				$('#invoiceTableContent').html("");
				var itemCount		= 0;
				$.each(response, function(index, item){
					var formattedDate		= item.date;
					var id			= item.id;
					var invoice_document	= item.invoice_document;
					var tax_document		= item.tax_document;
					var value				= parseFloat(item.value);
					var paid				= parseFloat(item.paid);
					var date				= new Date(formattedDate);
					var payment				= parseInt(item.payment);
					var dueDate				= new Date(date.setDate(date.getDate() + payment));
					var formattedDueDate	= dueDate.getFullYear() + "-" + (dueDate.getMonth() + 1).toString().padStart(2, "0") + "-" + dueDate.getDate().toString().padStart(2, "0");

					$('#invoiceTableContent').append("<tr><td>" + my_date_format(formattedDate) + "</td><td><p><strong>" + invoice_document + "</strong></p><p>" + tax_document + "</p></td><td>" + my_date_format(formattedDueDate) + "</td><td>Rp. " + numeral(value).format('0,0.000') + "</td><td>Rp. " + numeral(paid).format('0,0.00') + "</td><td><input type='checkbox' class='paymentCheckBox' data-value='" + (value - paid) + "'></td></tr>")

					itemCount++;
				});

				if(itemCount > 0){
					$('#invoiceTable').show();
					$('#invoiceTableText').hide();
				} else {
					$('#invoiceTable').hide();
					$('#invoiceTableText').show();
				}

				$('.paymentCheckBox').each(function(){
					$(this).change(function(){
						if($(this).is(":checked")){
							paymentValue += parseFloat($(this).attr('data-value'));
						} else {
							paymentValue -= parseFloat($(this).attr('data-value'));
						}

						refreshPaymentText();			
					});
				})
			}
		})
	}

	function refreshPaymentText(){
		$('#paymentValue').html(numeral(paymentValue).format('0,0.00'));
	}
</script>