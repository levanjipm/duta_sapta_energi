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
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-usd'></i></a> / Payable</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='row'>
			<div class='col-xs-4'>
				<select class='form-control' id='category'>
					<option value='1'>-- Default --</option>
					<option value='2'>Past due date</option>
					<option value='3'>Less than 30 days</option>
					<option value='4'>30 - 45 days</option>
					<option value='5'>45 - 60 days</option>
					<option value='6'>More than 60 days</option>
				</select>
			</div>
			<div class='col-xs-4 col-xs-offset-4'>
				<button class='form-control' style='text-align:left' id='searchSupplierButton'>Search Supplier</button>
			</div>
			<div class='col-xs-12'>
				<hr>
				<h3 style='font-family:bebasneue'>Payable value: <span id='value'></span></h3>
				<br>
			</div>
		</div>
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

		<button type='button' id='viewPayableButton' class='button button_default_dark'><i class='fa fa-eye'></i></button>
	</div>
</div>

<div class='alert_wrapper' id='selectSupplierWrapper'>
	<div class='alert_box_full'>
		<button class='button alert_full_close_button'>&times;</button>
		<h3 style='font-family:bebasneue'>Select Supplier</h3>
		<hr>
		<input type='text' class='form-control' id='searchSupplierBar'>

		<br>
		<div id='supplierTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Name</th>
					<th>Information</th>
					<th>Action</th>
				</tr>
				<tbody id='supplierTableContent'></tbody>
			</table>

			<select class='form-control' id='supplierPage' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='supplierTableText'>There is no supplier found.</p>
	</div>
</div>

<script>
	var supplierId;
	var opponentId;

	$(document).ready(function(){
		refresh_view();
		adjust_grid();
	});

	$(window).resize(function(){
		adjust_grid();
	});

	$("#category").change(function(){
		refresh_view();
	});

	$("#searchSupplierButton").click(function(){
		$('#selectSupplierWrapper').fadeIn();
		$('#searchSupplierBar').val("");
		refreshSupplierView();
	})
	
	function refresh_view(){
		$.ajax({
			url:'<?= site_url('Payable/viewPayable') ?>',
			data:{
				category: $("#category").val()
			},
			success:function(response){
				$('#payable_chart').html('');
				var max_payable		= 0;
				supplierPayableArray = {};
				otherPayableArray = {};
				var totalPayableValue = 0;

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

					totalPayableValue += parseFloat(item.value) - parseFloat(item.paid);
				});

				payableArray = [];
				$.each(supplierPayableArray, function(index, supplierPayable){
					payableArray.push(supplierPayable);
				});

				$.each(otherPayableArray, function(index, otherPayable){
					payableArray.push(otherPayable);
				});

				var payableCount = 0;
				payableArray.sort(sortArray);
				var maxPayable = 0;
				$.each(payableArray, function(index, payable){
					if(payable[3] != null){
						var value= payable[0];
						var name = payable[1];
						var id	= payable[3];
						if(value > maxPayable){
							maxPayable = value;
						}
						$('#payable_chart').append("<div class='row' style='cursor:pointer' id='supplierPayable-" + id + "' onclick='viewSupplierPayableDetail(" + id + ")' ><div class='col-sm-3 col-xs-3 center'><p><strong>" + name + "</strong></div><div class='col-sm-7 col-xs-6'><div class='Payable_line' id='supplierPayableLine-" + id + "' data-value='" + value + "'></div></div><div class='col-sm-2 col-xs-3 center' style='text-align:right'><p>Rp. " + numeral(value).format('0,0.00') + "</p></div></div><br>");
						payableCount++;
					} else {
						var value= payable[0];
						var name = payable[1];
						var id	= payable[4];
						if(value > maxPayable){
							maxPayable = value;
						}

						$('#payable_chart').append("<div class='row' style='cursor:pointer' id='otherPayable-" + id + "' onclick='viewOtherPayableDetail(" + id + ")' ><div class='col-sm-3 col-xs-3 center'><p><strong>" + name + "</strong></div><div class='col-sm-7 col-xs-6'><div class='Payable_line' id='otherPayableLine-" + id + "' data-value='" + value + "'></div></div><div class='col-sm-2 col-xs-3 center' style='text-align:right'><p>Rp. " + numeral(value).format('0,0.00') + "</p></div></div><br>");
						payableCount++;
					}
				});

				$('.Payable_line').each(function(){
					var percentage = $(this).attr('data-value') * 100 / maxPayable;
					$(this).animate({
						'width': percentage + "%"
					},300);
				});

				if(payableCount == 0){
					$('#payable_chart').html("<p>There is no payable found.</p>");
					$('#grid_wrapper').hide();
				} else {
					adjust_grid();
					$('#grid_wrapper').show();
				}

				$('#value').html(numeral(totalPayableValue).format('0,0.00'));
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
				supplierId = n;
				opponentId = null;
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
				opponentId = n;
				supplierId = null;
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

	$('#viewPayableButton').click(function(){
		if(supplierId == null){
			window.location.href="<?= site_url('Payable/viewFinanceByOpponentId/') ?>" + opponentId;
		} else {
			window.location.href="<?= site_url('Payable/viewFinanceBySupplierId/') ?>" + supplierId;
		}
		
	})

	function refreshSupplierView(page = $('#supplierPage').val()){
		$.ajax({
			url:"<?= site_url('Payable/getSupplierOpponentItems') ?>",
			data:{
				page: page,
				term: $('#searchSupplierBar').val()
			},
			success:function(response){
				var items = response.items;
				var supplierCount = 0;
				$('#supplierTableContent').html("");
				$.each(items, function(index, item){
					var opponentType = item.opponentType;
					if(opponentType == 1){
						var supplierName			= item.name;
						var complete_address		= item.address;
						var supplier_city			= item.city;
						var supplier_number			= item.number;
						var supplier_rt				= item.rt;
						var supplier_rw				= item.rw;
						var supplier_postal			= item.postal_code;
						var supplier_block			= item.block;
						var id						= item.id;
	
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

						$('#supplierTableContent').append("<tr><td>" + supplierName + "</td><td><p>" + complete_address + "</p><p>" + supplier_city + "</p></td><td><button class='button button_default_dark' id='viewSupplierButton-" + id + "'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
						supplierCount++;
						$('#viewSupplierButton-' + id).click(function(){
							window.location.href="<?= site_url('Payable/viewFinanceBySupplierId/') ?>" + id;
						})

					} else if(opponentType == 2){
						var supplierName		= item.name;
						var complete_address	= item.address;
						var supplier_city		= item.city;
						var id					= item.id;

						$('#supplierTableContent').append("<tr><td>" + supplierName + "</td><td><p>" + complete_address + "</p><p>" + supplier_city + "</p></td><td><button class='button button_default_dark' id='viewOpponentButton-" + id + "'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
						supplierCount++;
						$('#viewOpponentButton-' + id).click(function(){
							window.location.href="<?= site_url('Payable/viewFinanceByOpponentId/') ?>" + id;
						})
					}
				});

				if(supplierCount > 0){
					$('#supplierTable').show();
					$('#supplierTableText').hide();
				} else {
					$('#supplierTable').hide();
					$('#supplierTableText').show();
				}

				var pages = response.pages;
				$('#supplierPage').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#supplierPage').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#supplierPage').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		})
	}

	$('#searchSupplierBar').change(function(){
		refreshSupplierView(1);
	})

	$('.alert_full_close_button').click(function(){
		$(this).parent().parent().fadeOut();
	})
</script>
