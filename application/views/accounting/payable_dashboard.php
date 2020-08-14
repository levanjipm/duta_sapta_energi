<head>
	<title>Payable</title>
	<style>	
		.receivable_line{
			height:30px;
			background-color:#014886;
			border:none;
			transition:0.3s all ease;
			width:0;
			cursor:pointer;
		}
		
		.receivable_line:hover{
			background-color:#013663;
			transition:0.3s all ease;
		}
		
		.center{
			position: relative;
		}
		
		.center p{
			position:absolute;
			margin:0;
			top:50%;
			left:15px;
			transform: translate(0, -50%);
			text-align:left
		}
	</style>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> / Payable</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div id='payable_view_pane'></div>
	</div>
</div>

<div class='alert_wrapper' id='payableDetailWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Payable</h3>
		<hr>
		<label>Supplier</label>
		<p id='supplier_name_p'></p>
		<p id='supplier_address_p'></p>
		<p id='supplier_city_p'></p>

		<div class='table-responsive'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Tax invoice</th>
					<th>Value</th>
					<th>Paid</th>
					<th>Debt</th>
				</tr>
				<tbody id='payableTableContent'></tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		refresh_view();
	})
	
	function refresh_view(date_1 = 0, date_2 = 0){
		$.ajax({
			url:'<?= site_url('Payable/viewPayable') ?>',
			data:{
				date_1:date_1,
				date_2:date_2
			},
			success:function(response){
				$('#payable_view_pane').html('');
				var max_payable		= 0;
				$.each(response, function(index,value){
					var id			= value.supplier_id;
					var name 		= value.name;
					var debt		= value.value;
					var city		= value.city;
					var paid		= value.paid;
					
					var payable		= debt - paid;
					
					if(payable > max_payable){
						max_payable = payable;
						$('#payable_view_pane').prepend("<div class='row'><div class='col-sm-3 col-xs-3 center'><p>" + name + ", " + city + "</p></div><div class='col-sm-7 col-xs-6'><div class='receivable_line' id='payable-" + id + "' onclick='viewPayable(" + id + ")'></div></div><div class='col-sm-2 col-xs-3 center' style='text-align:right'><p>Rp. " + numeral(payable).format('0,0.00') + "</p></div></div><br>");
					} else {
						$('#payable_view_pane').append("<div class='row'><div class='col-sm-3 col-xs-3 center'><p>" + name + ", " + city + "</p></div><div class='col-sm-7 col-xs-6'><div class='receivable_line' id='payable-" + id + "' onclick='viewPayable(" + id + ")'></div></div><div class='col-sm-2 col-xs-3 center' style='text-align:right'><p>Rp. " + numeral(payable).format('0,0.00') + "</p></div></div><br>");
					}								
				});
				
				$.each(response, function(index,value){
					var id			= value.supplier_id;
					var debt		= value.value;
					var paid		= value.paid;
					
					var payable		= debt - paid;
					var percentage	= payable * 100 / max_payable;
					$('#payable-' + id).animate({
						'width': percentage + "%"
					},300);
				});
			}
		});
	}

	function viewPayable(n){
		$.ajax({
			url:'<?= site_url('Payable/viewPayableBySupplierId') ?>',
			data:{
				id: n
			},
			success:function(response){
				$('#payableTableContent').html("");
				var totalRemainder = 0;

				var items = response.items;
				$.each(items, function(index, item){
					var date = item.date;
					var invoiceDocument = item.invoice_document;
					var tax_document = item.tax_document;
					if(tax_document == null){
						var taxDocument = "<i>Not available</i>"
					} else {
						var taxDocument = tax_document;
					}

					var paid = parseFloat(item.paid);
					var value = parseFloat(item.value);
					var remainder = value - paid;
					totalRemainder += remainder;

					$('#payableTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + invoiceDocument + "</td><td>" + taxDocument + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Rp. " + numeral(paid).format('0,0.00') + "</td><td>Rp. " + numeral(remainder).format("0,0.00") + "</td></tr");
				});

				$('#payableTableContent').append("<tr><td colspan='3'></td><td colspan='2'>Total</td><td>Rp. " + numeral(totalRemainder).format("0,0.00") + "</td></tr");

				var supplier = response.supplier;
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

				$('#supplier_name_p').html(supplierName);
				$('#supplier_address_p').html(complete_address);
				$('#supplier_city_p').html(supplier_city);

				$('#payableDetailWrapper').fadeIn(300, function(){
					$('#payableDetailWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	
</script>