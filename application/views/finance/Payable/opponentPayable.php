<?php
	$opponentName = $opponent->name;
	$opponentDescription = $opponent->description;
	$opponentType = $opponent->type;
?>
<head>
	<title><?= $opponentName ?> Receivable</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-usd'></i></a> /<a href='<?= site_url('Payable') ?>'>Payable</a> / <?= $opponentName ?></p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Opponent</label>
		<p><?= $opponentName ?></p>
		<p><?= $opponentDescription ?></p>
		<p><?= $opponentType ?></p>

		<label>Receivable</label>
		<table class='table table-bordered'>
			<tr>
				<th>Date</th>
				<th>Name</th>
				<th>Debit</th>
				<th>Credit</th>
				<th>Balance</th>
			</tr>
			<tbody id='payableTableContent'></tbody>
		</table>
	</div>
</div>

<div class='alert_wrapper' id='invoiceWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Debt Document</h3>
		<hr>
		<label>Debt Document</label>
		<p id='documentName_p'></p>
		<p id='documentTax_p'></p>
		<p id='documentDate_p'></p>

		<label>Value</label>
		<p id='valueP'></p>

		<label>Information</label>
		<p id='informationP'></p>

		<label>Payable</label>
		<div id='paymentTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Value</th>
				</tr>
				<tbody id='paymentTableContent'></tbody>
			</table>
		</div>
		<p id='paymentTableText'>There is no payment found.</p>
	</div>
</div>

<script>
	$(document).ready(function(){
		refreshView();
	});

	function refreshView(){
		$.ajax({
			url:"<?= site_url('Payable/getCompletePayableByOpponentId') ?>",
			data:{
				id: <?= $opponent->id ?>
			},
			success:function(response){
				$('#payableTableContent').html("");
				var paymentArray = [];
				var valueArray	= [];
				var totalPayable = 0;
				var totalValue		= 0;
				var items = response.items;
				$.each(items, function(index, item){
					var id = item.id;
					var date = item.date;
					var name = item.invoice_document;
					var value = parseFloat(item.value);
					var taxInvoice = (item.tax_document == "" || item.tax_document == null) ? "<i>Not available</i>" : item.tax_document;
					var paid = parseFloat(item.paid);
					totalPayable += value;
					totalValue	+= value;

					$('#payableTableContent').append("<tr id='payableRow-" + id + "'><td>" + my_date_format(date) + "</td><td><p>" + name + "</p><p>" + taxInvoice + "</p><button class='button button_transparent' onclick='viewInvoice(" + id + ")'><i class='fa fa-eye'></i></button></td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td></td><td>Rp. " + numeral(totalPayable).format('0,0.00') + "</td></tr>");

					totalPayable -= paid;
					paymentArray[id] = totalPayable;
					valueArray[id]	= totalValue;
				});

				var payables = response.payable;
				var rowString		= [];
				$.each(payables, function(index, item){
					var id			= item.id;
					var date		= item.date;
					var value		= parseFloat(item.value);
					var other_id	= item.other_purchase_id;
					var payment		= parseFloat(valueArray[other_id]);
					if(rowString[other_id] == null){
						rowString[other_id]		= "";
					};

					valueArray[other_id] = payment - value;

					rowString[other_id] += ("<tr><td>" + my_date_format(date) + "</td><td></td><td></td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Rp. " + numeral(valueArray[other_id]).format('0,0.00') + "</td></tr>");
					
				});

				$.each(rowString, function(index, value){
					if(value != null){
						$('#payableRow-' + index).after(value);
					}
				});

				var pendingBankValue = 0;
				var items = response.pendingBank;
				$.each(items, function(index, item ){
					pendingBankValue += parseFloat(item.value);
				});

				if(pendingBankValue > 0){
					$('#receivableTableContent').append("<tr><td colspan='2'><label>Pending bank data</label></td><td></td><td>Rp. " + numeral(pendingBankValue).format('0,0.00') + "</td><td></td></tr>");
				}
				
			}
		})
	}

	function viewInvoice(n){
		$.ajax({
			url:"<?= site_url('Debt/getBlankById') ?>",
			data:{
				id: n
			},
			success:function(response){
				var general			= response.debt;
				var name			= general.invoice_document;
				var id				= n;
				var information		= (general.information == null || general.information == "") ? "<i>Not available</i>" : general.information;
				var tax_document	= (general.tax_document == null || general.tax_document == "") ? "<i>Not available</i>" : response.tax_document;
				var taxing			= (general.taxing == 0) ? "Non - taxable" : "Taxable";
				var value			= general.value;
				var date			= general.date;

				$('#documentName_p').html(name);
				$('#documentTax_p').html(tax_document);
				$('#documentDate_p').html(my_date_format(date));

				$('#valueP').html("Rp. " + numeral(value).format('0,0.00'));
				$('#informationP').html(information);

				$('#paymentTableContent').html("");
				var payableCount		= 0;
				var payable			= response.payable;
				$.each(payable, function(index, item){
					var date			= item.date;
					var value			= item.value;
					$('#paymentTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td></tr>");
					payableCount++;
				});

				if(payableCount > 0){
					$('#paymentTable').show();
					$('#paymentTableText').hide();
				} else {
					$('#paymentTable').hide();
					$('#paymentTableText').show();
				}
			},
			complete: function(){
				$('#invoiceWrapper').fadeIn(300, function(){
					$('#invoiceWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}
</script>
