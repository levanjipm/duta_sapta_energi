<head>
	<title>Payable</title>
	<style>	
		.Payable_line{
			height:30px;
			background-color:#014886;
			border:none;
			transition:0.3s all ease;
			width:0;
			cursor:pointer;
			opacity:0.7;
		}
		
		.Payable_line:hover{
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

		#payable_chart{
			position:relative;
			z-index:5;
		}

		#payable_view_pane{
			position:relative;
		}
	
		#payable_grid{
			position:absolute;
			top:0;
			left:0;
			width:100%;
			height:100%;
			padding:0;
			z-index:0;
		}

		.grid{
			-ms-flex-preferred-size: 100%;
			box-sizing: border-box;
			height:100%;
			border-left:1px solid black;
			position:relative;
			padding:0;
			margin:0;
		}
	
		#grid_wrapper{
			display:-webkit-box;
			display:-ms-flexbox;
			display:flex;
			opacity:0;
		}
	</style>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> / Payable</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div id='payable_view_pane'>
			<div id='payable_chart'></div>
			<div id='payable_grid'>
				<div class='row' style='height:100%'>
					<div class='col-sm-7 col-xs-6 col-sm-offset-3 col-xs-offset-3' id='grid_wrapper'>
						<div class='grid' style='margin-left:0!important'></div>
						<div class='grid'></div>
						<div class='grid'></div>
						<div class='grid'></div>
						<div class='grid'></div>
						<div class='grid'></div>
						<div class='grid'></div>
						<div class='grid'></div>
						<div class='grid'></div>
						<div class='grid' style='border-right:1px solid black'></div>
					</div>
				</div>
			</div>
		</div>
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
	});

	$(window).resize(function(){
		adjust_grid();
	})
	
	function refresh_view(date_1 = 0, date_2 = 0){
		$.ajax({
			url:'<?= site_url('Payable/viewPayable') ?>',
			data:{
				date_1:date_1,
				date_2:date_2
			},
			success:function(response){
				$('#payable_chart').html('');
				var max_payable		= 0;
				supplierPayableArray = {};
				otherPayableArray = {};

				$.each(response, function(index, item){
					var name = item.name;
					if(supplierPayableArray[item.supplier_id] == null && otherPayableArray[item.other_opponent_id] == null){
						if(item.supplier_id != null){
							supplierPayableArray[item.supplier_id] = {};
							supplierPayableArray[item.supplier_id][0] = parseFloat(item.value) - parseFloat(item.paid);
							supplierPayableArray[item.supplier_id][1] = item.name;
							supplierPayableArray[item.supplier_id][3] = item.supplier_id;
							supplierPayableArray[item.supplier_id][4] = null;
						} else {
							otherPayableArray[item.other_opponent_id] = {};
							otherPayableArray[item.other_opponent_id][0] = parseFloat(item.value) - parseFloat(item.paid);
							otherPayableArray[item.other_opponent_id][1] = item.name;
							otherPayableArray[item.other_opponent_id][3] = null;
							otherPayableArray[item.other_opponent_id][4] = item.other_opponent_id;
						}
					} else {
						if(item.supplier_id != null){
							var currentValue = parseFloat(supplierPayableArray[item.supplier_id][0]);
							var payableValue = parseFloat(item.value) - parseFloat(item.paid);

							var totalValue = currentValue + payableValue;

							supplierPayableArray[item.supplier_id][0] = totalValue;
						} else {
							var currentValue = parseFloat(otherPayableArray[item.supplier_id][0]);
							var payableValue = parseFloat(item.value) - parseFloat(item.paid);

							var totalValue = currentValue + payableValue;

							otherPayableArray[item.other_opponent_id][0] = totalValue;
						}
					}
				});

				payableArray = [];
				$.each(supplierPayableArray, function(index, supplierPayable){
					payableArray.push(supplierPayable);
				});
				$.each(otherPayableArray, function(index, otherPayable){
					payableArray.push(otherPayable);
				});

				payableArray.sort(sortArray);
				var maxPayable = 0;
				$.each(payableArray, function(index, payable){
					if(payable[3] != null){
						var value= payable[0];
						var name = payable[1];
						var id	= payable[3];
						if(value > maxPayable){
							maxpayable = value;
						}
						$('#payable_chart').append("<div class='row' id='supplierPayable-" + id + "'><div class='col-sm-3 col-xs-3 center'><p><strong>" + name + "</strong></div><div class='col-sm-7 col-xs-6'><div class='Payable_line' id='supplierPayableLine-" + id + "' onclick='viewSupplierPayableDetail(" + id + ")' data-value='" + value + "'></div></div><div class='col-sm-2 col-xs-3 center' style='text-align:right'><p>Rp. " + numeral(value).format('0,0.00') + "</p></div></div><br>")
					} else {
						if(value > maxPayable){
							maxpayable = value;
						}
						var value= payable[0];
						var name = payable[1];
						var id	= payable[4];
						$('#payable_chart').append("<div class='row' id='otherPayable-" + id + "'><div class='col-sm-3 col-xs-3 center'><p><strong>" + name + "</strong></div><div class='col-sm-7 col-xs-6'><div class='Payable_line' id='otherPayableLine-" + id + "' onclick='viewOtherPayableDetail(" + id + ")' data-value='" + value + "'></div></div><div class='col-sm-2 col-xs-3 center' style='text-align:right'><p>Rp. " + numeral(value).format('0,0.00') + "</p></div></div><br>")
					}
				});
				$('.Payable_line').each(function(){
					var percentage = $(this).attr('data-value') * 100 / maxpayable;
					$(this).animate({
						'width': percentage + "%"
					},300);
				})

				adjust_grid();
			}
		});
	}

	function sortArray(a, b) {
		if (a[0] === b[0]) {
			return 0;
		}
		else {
			return (a[0] > b[0]) ? -1 : 1;
		}
	}

	function adjust_grid(){
		var width		= $('#grid_wrapper').width();
		var each		= (width) / 10;
		$('.grid').width(each);
		
		$('#grid_wrapper').fadeTo(500, 1);
	}

	function viewSupplierPayableDetail(n){
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

	function viewOtherPayableDetail(n){
		$.ajax({
			url:'<?= site_url('Payable/viewPayableByOtherId') ?>',
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

				var supplier 				= response.supplier;
				var supplierName 			= supplier.name;
				var complete_address		= supplier.description;
				var supplier_city			= supplier.type;

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
