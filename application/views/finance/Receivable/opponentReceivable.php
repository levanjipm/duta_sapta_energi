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
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-briefcase'></i></a> / <a href='<?= site_url('Receivable/finance') ?>'>Receivable</a> / <?= $opponentName ?></p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Customer</label>
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
			<tbody id='receivableTableContent'></tbody>
		</table>
	</div>
</div>
<script>
	$(document).ready(function(){
		refreshView();
	});

	function refreshView(){
		$.ajax({
			url:"<?= site_url('Receivable/getFinanceCompleteReceivableByOpponentId') ?>",
			data:{
				id: '<?= $opponent->id ?>'
			},
			success:function(response){
				$('#receivableTableContent').html("");
				var paymentArray = [];
				var totalReceivable = 0;
				var items = response.invoices;
				$.each(items, function(index, item){
					var id = item.id;
					var date = item.date;
					var name = item.name;
					var value = parseFloat(item.value);
					var taxInvoice = (item.taxInvoice == "" || item.taxInvoice == null) ? "<i>Not available</i>" : item.taxInvoice;
					var paid = parseFloat(item.paid);
					totalReceivable += value;

					$('#receivableTableContent').append("<tr id='receivableRow-" + id + "'><td>" + my_date_format(date) + "</td><td><p>" + name + "</p><p>" + taxInvoice + "</p></td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td></td><td>Rp. " + numeral(totalReceivable).format('0,0.00') + "</td></tr>");

					totalReceivable -= paid;
					paymentArray[id] = totalReceivable;
				});

				var receivable = response.receivables;
				$.each(receivable, function(index, item){
					var date = item.date;
					var value = parseFloat(item.value);
					var invoice_id = item.invoice_id;
					var payment = parseFloat(paymentArray[invoice_id]);


					$('#receivableRow-' + invoice_id).after("<tr><td>" + my_date_format(date) + "</td><td></td><td></td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Rp. " + numeral(payment).format('0,0.00') + "</td></tr>");
					paymentArray[invoice_id] = payment - value;
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
	
</script>
