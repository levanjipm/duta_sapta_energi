<head>
	<title>Debt document</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Sales'><i class='fa fa-bar-chart'></i></a> /Pending debt document</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<table class='table table-bordered' id='pendingDebtTable'>
			<tr>
				<th>Supplier</th>
				<th>Value</th>
				<th>Action</th>
			</tr>
			<tbody id='pendingDebtContent'></tbody>
		</table>
		<p id='pendingDebtText'>There is no pending debt document found.</p>
	</div>
</div>

<div class='alert_wrapper' id='pendingBillWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<table class='table table-bordered' id='goodReceiptTable'>
			<tr>
				<th>Purchase Order</th>
				<th>Date</th>
				<th>Name</th>
				<th>Value</th>
			</tr>
			<tbody id='goodReceiptTableContent'></tbody>
		</table>
		<br>
		<select class='form-control' id='page' style='width:100px'>
			<option value='1'>1</option>
		</select>
		<br>
		<button class='button button_default_dark' id='getPDFButton'>Get PDF</button>
		<p id='goodReceiptTableText'>There is no pending bills found.</p>
	</div>
</div>

<script>
	var page = 1;
	var supplierId = null;

	$(document).ready(function(){
		refreshView();
	});

	$('#page').change(function(){
		viewBySupplier(supplierId, page);
	})

	$('#getPDFButton').click(function(){
		window.open('<?= site_url('Debt/getUninvoicedDocumentsBySupplierPDF/') ?>' + supplierId);
	})
	
	function refreshView(){
		$.ajax({
			url:"<?= site_url('Debt/getPendingDocuments') ?>",
			beforeSend:function(){
				$('#pendingDebtContent').html("");
			},
			success:function(response){
				if(response.length > 0){
					$('#pendingDebtTable').show();
					$('#pendingDebtText').hide();

					$.each(JSON.parse(response), function(index, item){
						$('#pendingDebtContent').append("<tr><td>" + item.supplier_name + "</td><td>Rp. " + numeral(item.value).format('0,0.00') + "</td><td><button class='button button_default_dark' onclick='viewBySupplier(" + item.supplier_id + ", 1)'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
					})
				} else {
					$('#pendingDebtText').show();
					$('#pendingDebtTable').hide();
				}
			}
		})
	}

	function viewBySupplier(supplier_id, page = $('#page').val()){
		supplierId = supplier_id;
		page = page;
		$.ajax({
			url:"<?= site_url('Debt/getUninvoicedDocumentsBySupplierId') ?>",
			data:{
				supplier_id: supplierId,
				term: "",
				page: page
			},
			success:function(response){
				var bills = response.bills;
				var pages = response.pages;

				$('#page').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}

				if(bills.length > 0){
					$('#goodReceiptTable').show();
					$('#goodReceiptTableText').hide();

					$('#goodReceiptTableContent').html("");

					$.each(bills, function(index, bill){
						$('#goodReceiptTableContent').append("<tr><td>" + bill.purchase_order_name + "</td><td>" + my_date_format(bill.date) + "</td><td>" + bill.name + "</td><td>" + numeral(bill.value).format('0,0.00') + "</td></tr>")
					})
				} else {
					$('#goodReceiptTable').hide();
					$('#goodReceiptTable').show();
				}

				$('#pendingBillWrapper').fadeIn(300, function(){
					$('#pendingBillWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});	
			}
		})
	}
</script>