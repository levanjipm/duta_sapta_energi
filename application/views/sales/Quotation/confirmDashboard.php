<head>
	<title>Confirm Quotation</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-line-chart'></i></a> /Quotation /Confirm</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<input type='text' class='form-control' id='search_bar'><br>
		<div id='quotationTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Customer</th>
					<th>Action</th>
				</tr>
				<tbody id='quotationTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='quotationTableText'>There is no quotation to be confirmed.</p>
	</div>
</div>

<div class='alert_wrapper' id='validateQutationForm'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Confirm Quotation</h3>
		<hr>
		<label>Quotation</label>
		<p id='quotationName_p'></p>
		<p id='quotationDate_p'></p>
		<p id='quotationTaxing_p'></p>
		
		<label>Customer</label>
		<p id='customerName_p'></p>
		<p id='customerAddress_p'></p>
		<p id='customerCity_p'></p>

		<div class='table-responsive-lg'>
			<table class='table table-bordered'>
				<tr>
					<th>Reference</th>
					<th>Name</th>
					<th>Price list</th>
					<th>Discount</th>
					<th>Unit price</th>
					<th>Quantity</th>
					<th>Total price</th>
				</tr>
				<tbody id='quotationConfirmItems'></tbody>
			</table>
		</div>
		<label>Note</label>
		<p id='quotationNote_p'></p>
		
		<button class='button button_default_dark' onclick='confirmQuotation()'><i class='fa fa-long-arrow-right'></i></button> <button class='button button_danger_dark' onclick='deleteQuotation()'><i class='fa fa-trash'></i></button>

		<div class='notificationText danger' id='failedUpdateDataNotification'><p>Failed to 
	</div>
</div>
<script>
	var quotationId;
	$('#search_bar').change(function(){
		refreshView(1);
	});

	$('#page').change(function(){
		refreshView();
	})

	$(document).ready(function(){
		refreshView();
	});

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Quotation/getItems') ?>",
			data:{
				page: page,
				term: $('#search_bar').val()
			},
			success:function(response){
				var items = response.items;
				$('#quotationTableContent').html("");
				var quotationCount = 0;
				$.each(items, function(index, item){
					var name = item.name;
					var date = item.date;
					var id = item.id;
					var customerName = item.customerName;
					var complete_address = item.address;
					var customer_number		= item.number;
					var customer_block		= item.block;
					var customer_rt			= item.rt;
					var customer_rw			= item.rw;
					var customer_city		= item.city;
					var customer_postal		= item.postal_code;

					if(customer_number != null && customer_number != ''){
						complete_address	+= ' no. ' + customer_number;
					};
					
					if(customer_block != null && customer_block != ''){
						complete_address	+= ', blok ' + customer_block;
					};
					
					if(customer_rt != '000'){
						complete_address	+= ', RT ' + customer_rt + ', RW ' + customer_rw;
					}
					
					if(customer_postal != ''){
						complete_address += ', ' + customer_postal;
					}
					
					$('#quotationTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td><label>" + customerName + "</label><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><button class='button button_default_dark' onclick='viewQuotation(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");

					quotationCount++;
				});

				if(quotationCount > 0){
					$('#quotationTable').show();
					$('#quotationTableText').hide();
				} else {
					$('#quotationTable').hide();
					$('#quotationTableText').show();
				}
				var pages = response.pages;
				$('#page').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		})
	}

	function confirmQuotation()
	{
		$.ajax({
			url:"<?= site_url('Quotation/confirmById') ?>",
			data:{
				id: quotationId
			},
			type:"POST",
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				refreshView();
				if(response == 1){
					$('#validateQutationForm .slide_alert_close_button').click();
					window.location.href='<?= site_url('Quotation/print/') ?>' + quotationId;
				} else {
					$("#failedUpdateDataNotification").fadeIn(250, function(){
						setTimeout(function(){
							$('#failedUpdateDataNotification').fadeOut(250);
						}, 1000)
					})
				}
			}
		});
	}

	function deleteQuotation()
	{
		$.ajax({
			url:"<?= site_url('Quotation/deleteById') ?>",
			data:{
				id: quotationId
			},
			type:"POST",
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				refreshView();
				if(response == 1){
					$('#validateQutationForm .slide_alert_close_button').click();
					quotationId = null;
				} else {
					$("#failedUpdateDataNotification").fadeIn(250, function(){
						setTimeout(function(){
							$('#failedUpdateDataNotification').fadeOut(250);
						}, 1000)
					})
				}
			}
		});
	}

	function viewQuotation(n){
		$.ajax({
			url:"<?= site_url('Quotation/getById') ?>",
			data:{
				id:n
			},
			success:function(response){
				quotationId = n;
				var customer			= response.customer;
				var customerName		= customer.name;
				
				var complete_address	= customer.address;
				var customer_number		= customer.number;
				var customer_block		= customer.block;
				var customer_rt			= customer.rt;
				var customer_rw			= customer.rw;
				var customer_city		= customer.city;
				var customer_postal		= customer.postal_code;

				if(customer_number != null && customer_number != '000'){
					complete_address	+= ' no. ' + customer_number;
				};
					
				if(customer_block != null && customer_block != '000'){
					complete_address	+= ', blok ' + customer_block;
				};
					
				if(customer_rt != '000'){
					complete_address	+= ', RT ' + customer_rt + ', RW ' + customer_rw;
				}
					
				if(customer_postal != ''){
					complete_address += ', ' + customer_postal;
				}

				$('#customerName_p').html(customerName);
				$('#customerAddress_p').html(complete_address);
				$('#customerCity_p').html(customer_city);

				var general				= response.general;
				var taxing				= general.taxing;
				var note				= general.note;
				if(note == ""){
					var noteText = "<i>Not available</i>";
				} else {
					var noteText = note;
				}

				if(taxing == 1){
					var taxingText = "Taxable";
				} else {
					var taxingText = "Non-taxable";
				}

				var date = general.date;
				var name = general.name;

				$('#quotationName_p').html(name);
				$('#quotationDate_p').html(my_date_format(date));
				$('#quotationTaxing_p').html(taxingText);
				$('#quotationNote_p').html(noteText);

				var items= response.items;
				var quotationValue = 0;
				$('#quotationConfirmItems').html("");
				$.each(items, function(index, item){
					var reference		= item.reference;
					var name			= item.name;
					var price_list		= parseFloat(item.price_list);
					var discount		= parseFloat(item.discount);
					var quantity		= parseInt(item.quantity);
					var unitPrice		= price_list * (1 - discount / 100);
					var totalPrice		= unitPrice * quantity;

					if(taxing == 1){
						$('#quotationConfirmItems').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>Rp. " + numeral(price_list / 1.1).format('0,0.00') + "</td><td>" + numeral(discount).format('0,0.00') + "%</td><td>Rp. " + numeral(unitPrice / 1.1).format('0,0.00') + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(totalPrice / 1.1).format('0,0.00') + "</td></tr>");
					} else {
						$('#quotationConfirmItems').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>Rp. " + numeral(price_list).format('0,0.00') + "</td><td>" + numeral(discount).format('0,0.00') + "%</td><td>Rp. " + numeral(unitPrice).format('0,0.00') + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(totalPrice).format('0,0.00') + "</td></tr>");
					}

					quotationValue += totalPrice;
				});

				if(taxing == 1){
					$('#quotationConfirmItems').append("<tr><td colspan='4'></td><td colspan='2'><strong>Sub Total</strong></td><td>Rp. " + numeral(quotationValue / 1.1).format('0,0.00') + "</td></tr>");
					$('#quotationConfirmItems').append("<tr><td colspan='4'></td><td colspan='2'><strong>Tax</strong></td><td>Rp. " + numeral(quotationValue - quotationValue / 1.1).format('0,0.00') + "</td></tr>");
					$('#quotationConfirmItems').append("<tr><td colspan='4'></td><td colspan='2'><strong>Total</strong></td><td>Rp. " + numeral(quotationValue).format('0,0.00') + "</td></tr>");
				} else {
					$('#quotationConfirmItems').append("<tr><td colspan='4'></td><td colspan='2'><strong>Total</strong></td><td>Rp. " + numeral(quotationValue).format('0,0.00') + "</td></tr>");
				}
			},
			complete:function(){
				$('#validateQutationForm').fadeIn(300, function(){
					$('#validateQutationForm .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}
</script>
