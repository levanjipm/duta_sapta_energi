<head>
	<title>Billing</title>
	<style>
        .dashboardBox{
            padding:8px;
            box-shadow:3px 3px 3px 3px rgba(50,50,50,0.3);
            border-radius:5px;
            margin-bottom:10px;
        }

        .dashboardBox .leftSide{
            width:50%;
            font-weight:bold;
            display:inline-block;
        }

        .dashboardBox .rightSide{
            width:45%;
            float:right;
            display:inline-block;
            text-align:center;
            margin:0 auto;
            top: 50%;
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
            position:absolute;
            border-left:2px solid #ccc;
        }

        .dashboardBox .rightSide h3{
            
            font-weight:bold;
            color:#E19B3C;            
        }

        .subtitleText{
            font-size:0.8em;
            color:#555;
            text-align:right;
        }
    </style>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-usd'></i></a> / Create Billing</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Date</label>
		<p><?= date('d M Y', strtotime($date)); ?></p>

		<button class='button button_default_dark' id='createBillingButton' disabled><i class='fa fa-plus'></i> Create (<span id='countedInvoice_span'>0</span>)</button>
		<br><br>

		<button class='button button_mini_tab' id='urgentButton'>Urgent</button>
		<button class='button button_mini_tab' id='recommendedButton'>Recommendation Based</button>
		<button class='button button_mini_tab' id='searchButton'>Schedule Based</button>
		<br><br>
		<div id='urgentView' style='display:none'>
			<input type='text' id='urgentSearchBar' class='form-control'>
			<br>
			<div id='urgentTable'>
				<table class='table table-bordered'>
					<tr>
						<th>Date</th>
						<th>Name</th>
						<th>Customer</th>
						<th>Action</th>
					</tr>
					<tbody id='urgentTableContent'></tbody>
				</table>

				<select class='form-control' id='urgentPage' style='width:100px'>
					<option value='1'>1</option>
				</select>
			</div>
			<p id='urgentTableTetxt'>There is no urgent list</p>
		</div>
		<div id='recommendedView' style='display:none'>
			<input type='text' id='recommendedSearchBar' class='form-control'>
			<br>
			<div id='recommendedTable'>
				<table class='table table-bordered'>
					<tr>
						<th>Date</th>
						<th>Name</th>
						<th>Customer</th>
						<th>Value</th>
						<th>Paid</th>
						<th>Last Billed / Next Billed</th>
						<th>Action</th>
					</tr>
					<tbody id='recommendedTableContent'></tbody>
				</table>

				<select class='form-control' id='recommendedPage' style='width:100px'>
					<option value='1'>1</option>
				</select>
			</div>
			<p id='recommendedTableText'>There is no recommendation list</p>
		</div>
		<div id='searchView' style='display:none'>
			<select class='form-control' id='area'>
				<?php foreach($areas as $area){ ?>
					<option value="<?= $area->id ?>"><?= $area->name ?> (<?= $area->count  ?>)</option>
				<?php } ?>
			</select>
			<br>
			<div id='searchTable'>
				<table class='table table-bordered'>
					<tr>
						<th>Customer</th>
						<th>Action</th>
					</tr>
					<tbody id='searchTableContent'></tbody>
				</table>

				<select class='form-control' id='searchPage' style='width:100px'>
					<option value='1'>1</option>
				</select>
			</div>

			<p id='searchTableText'>There is no invoice found.</p>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='customerReceivableWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Select invoice by customer</h3>
		<hr>
		<label>Customer</label>
		<p id='customerName_p'></p>
		<p id='customerAddress_p'></p>
		<p id='customerCity_p'></p>

		<table class='table table-bordered'>
			<tr>
				<th>Date</th>
				<th>Name</th>
				<th>Value</th>
				<th>Paid</th>
				<th>Last Billed</th>
				<th>Action</th>
			</tr>
			<tbody id='receivableTableContent'></tbody>
		</table>

		<select class='form-control' id='customerPage' style='width:100px'>
			<option value='1'>1</option>
		</select>
	</div>
</div>

<div class='alert_wrapper' id='createBillingWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Create Billing</h3>
		<hr>
		<label>Date</label>
		<p><?= date('d M Y',strtotime($date)) ?></p>

		<label>Invoices</label>
		<table class='table table-bordered'>
			<tr>
				<th>Date</th>
				<th>Customer</th>
				<th>Name</th>
				<th>Remainder value</th>
			</tr>
			<tbody id='billingTableContent'></tbody>
		</table>

		<button id='submitBillingButton' class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>

		<br>
		<div class='notificationText danger' id='failedInsertItemNotification'><p>Failed to insert item.</p></div>
	</div>
</div>
<script>
	var includedInvoice = [];
	var customerSelected = 0;

	$(document).ready(function(){
		$('#urgentButton').click();
	});

	$('#recommendedButton').click(function(){
		$('.button_mini_tab').attr('disabled', false);
		$('.button_mini_tab').removeClass("active");

		fetchRecommendationList();
		$('#recommendedButton').addClass('active');
		$('#recommendedButton').attr('disabled', true);

		$('#urgentView, #searchView').fadeOut(250, function(){
			setTimeout(function(){
				$('#recommendedView').fadeIn(250);
			}, 250)
		});
	})

	$('#searchButton').click(function(){
		$('.button_mini_tab').attr('disabled', false);
		$('.button_mini_tab').removeClass("active");

		fetchItemList();
		$('#searchButton').addClass('active');
		$('#searchButton').attr('disabled', true);
		$('#urgentView, #recommendedView').fadeOut(250, function(){
			setTimeout(function(){
				$('#searchView').fadeIn(250);
			}, 250)			
		})
	});

	$('#urgentButton').click(function(){
		$('.button_mini_tab').attr('disabled', false);
		$('.button_mini_tab').removeClass("active");

		fetchUrgentList();
		$('#urgentButton').addClass('active');
		$('#urgentButton').attr('disabled', true);
		$('#searchView, #recommendedView').fadeOut(250, function(){
			setTimeout(function(){
				$('#urgentView').fadeIn(250);
			}, 250)			
		})
	});

	$('#recommendedPage').change(function(){
		fetchRecommendationList();
	});

	$('#recommendedSearchBar').change(function(){
		fetchRecommendationList(1);
	})

	$('#area').change(function(){
		fetchItemList(1);
	});

	$('#searchPage').change(function(){
		fetchItemList();
	})

	function fetchRecommendationList(page = $('#recommendedPage').val()){
		$.ajax({
			url:"<?= site_url('Billing/getRecommendationList') ?>",
			data:{
				page:page,
				term: $('#recommendedSearchBar').val(),
				date: "<?= $date ?>"
			},
			success:function(response){
				var items = response.items;
				$('#recommendedTableContent').html("");
				var invoiceCount = 0;
				$.each(items, function(index, item){
					var date		= item.date;
					var invoiceName = item.name;
					var id			= item.id;
					var baseValue	= parseFloat(item.value);
					var discount	= parseFloat(item.discount);
					var delivery	= parseFloat(item.delivery);
					var value		= baseValue + delivery - discount;
					var paid		= parseFloat(item.paid);

					var customerName 		= item.customerName;
					var customerAddress 	= item.address;
					var complete_address 	= item.address;
					var customer_number 	= item.number;
					var customer_block 		= item.block;
					var customer_rt 		= item.rt;
					var customer_rw 		= item.rw;
					var customer_city 		= item.city;
					var customer_postal 	= item.postal_code;
				
					if(customer_number != null){
						complete_address	+= ' No. ' + customer_number;
					}
				
					if(customer_block != null && customer_block != "000"){
						complete_address	+= ' Blok ' + customer_block;
					}
			
					if(customer_rt != '000'){
						complete_address	+= ' RT ' + customer_rt;
					}
				
					if(customer_rw != '000' && customer_rt != '000'){
						complete_address	+= ' /RW ' + customer_rw;
					}
				
					if(customer_postal != null){
						complete_address	+= ', ' + customer_postal;
					}

					var lastBilledDate = (item.lastBillingDate == null) ? "Never" : my_date_format(item.lastBillingDate);
					var nextBilledDate = (item.nextBillingDate == null) ? "None" : my_date_format(item.nextBillingDate);

					if(!includedInvoice.includes("" + id + "")){
						$('#recommendedTableContent').append("<tr id='row-" + id + "'><td>" + my_date_format(date) +"</td><td>" + invoiceName + "</td><td><label>" + customerName + "</label><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Rp. " + numeral(paid).format('0,0.00') + "</td><td>" + lastBilledDate + " / " + nextBilledDate + "</td><td><button class='button button_default_dark' onclick='selectRecommendedInvoice(" + id + ")' id='selectInvoiceButtonRecommended-" + id + "'><i class='fa fa-plus'></i></button><button class='button button_danger_dark' onclick='removeRecommendedInvoice(" + id + ")' id='removeInvoiceButtonRecommended-" + id + "' style='display:none'><i class='fa fa-trash'></i></button></td></tr>");
						invoiceCount++;
					} else {
						$('#recommendedTableContent').append("<tr id='row-" + id + "'><td>" + my_date_format(date) +"</td><td>" + invoiceName + "</td><td><label>" + customerName + "</label><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Rp. " + numeral(paid).format('0,0.00') + "</td><td>" + lastBilledDate + " / " + nextBilledDate + "</td><td><button class='button button_default_dark' onclick='selectRecommendedInvoice(" + id + ")' id='selectInvoiceButtonRecommended-" + id + "' style='display:none'><i class='fa fa-plus'></i></button><button class='button button_danger_dark' onclick='removeRecommendedInvoice(" + id + ")' id='removeInvoiceButtonRecommended-" + id + "'><i class='fa fa-trash'></i></button></td></tr>");
						invoiceCount++;
					}
				});

				if(invoiceCount > 0){
					$('#recommendedTable').show();
					$('#recommendedTableText').hide();
				} else {
					$('#recommendedTable').hide();
					$("#recommendedTableText").show();
				}

				var pages = response.pages;
				$('#recommendedPage').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#recommendedPage').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#recommendedPage').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		})
	}

	function fetchUrgentList(page = $('#urgentPage').val()){
		$.ajax({
			url:"<?= site_url('Billing/getUrgentList') ?>",
			data:{
				page:page,
				term: $('#urgentSearchBar').val(),
				date: "<?= $date ?>"
			},
			success:function(response){
				var items = response.items;
				$('#urgentTableContent').html("");
				var invoiceCount = 0;
				$.each(items, function(index, item){
					var date		= item.date;
					var invoiceName = item.name;
					var id			= item.id;
					if(!includedInvoice.includes("" + id + "")){
						var customerName = item.customerName;
						var customerAddress = item.address;
						var complete_address = item.address;
						var customer_number = item.number;
						var customer_block = item.block;
						var customer_rt = item.rt;
						var customer_rw = item.rw;
						var customer_city = item.city;
						var customer_postal = item.postal_code;
					
						if(customer_number != null){
							complete_address	+= ' No. ' + customer_number;
						}
					
						if(customer_block != null && customer_block != "000"){
							complete_address	+= ' Blok ' + customer_block;
						}
				
						if(customer_rt != '000'){
							complete_address	+= ' RT ' + customer_rt;
						}
					
						if(customer_rw != '000' && customer_rt != '000'){
							complete_address	+= ' /RW ' + customer_rw;
						}
					
						if(customer_postal != null){
							complete_address	+= ', ' + customer_postal;
						}

						$('#urgentTableContent').append("<tr id='row-" + id + "'><td>" + my_date_format(date) +"</td><td>" + invoiceName + "</td><td><label>" + customerName + "</label><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><button class='button button_default_dark' onclick='selectUrgentInvoice(" + id + ")' id='selectInvoiceButtonUrgent-" + id + "'><i class='fa fa-long-arrow-right'></i></button><button class='button button_danger_dark' onclick='removeUrgentInvoice(" + id + ")' id='removeInvoiceButtonUrgent-" + id + "' style='display:none'><i class='fa fa-trash'></i></button></td></tr>");
						invoiceCount++;
					}
				});

				if(invoiceCount > 0){
					$('#urgentTable').show();
					$('#urgentTableText').hide();
				} else {
					$('#urgentTable').hide();
					$("#urgentTableText").show();
				}

				var pages = response.pages;
				$('#urgentPage').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#urgentPage').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#urgentPage').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		})
	}

	function fetchItemList(page = $('#searchPage').val(), area = $('#area').val()){
		$.ajax({
			url:"<?= site_url('Billing/getBillingData') ?>",
			data:{
				page: page,
				area: area,
				date: '<?= $_GET['date'] ?>'
			},
			success:function(response){
				var items = response.items;
				$('#searchTableContent').html("");
				var invoiceCount = 0;
				$.each(items, function(index, item){
					var id			= item.id;
					if(!includedInvoice.includes("" + id + "")){
						var customerName = item.name;
						var customerAddress = item.address;
						var complete_address = item.address;
						var customer_number = item.number;
						var customer_block = item.block;
						var customer_rt = item.rt;
						var customer_rw = item.rw;
						var customer_city = item.city;
						var customer_postal = item.postal_code;
					
						if(customer_number != null){
							complete_address	+= ' No. ' + customer_number;
						}
					
						if(customer_block != null && customer_block != "000"){
							complete_address	+= ' Blok ' + customer_block;
						}
				
						if(customer_rt != '000'){
							complete_address	+= ' RT ' + customer_rt;
						}
					
						if(customer_rw != '000' && customer_rt != '000'){
							complete_address	+= ' /RW ' + customer_rw;
						}
					
						if(customer_postal != null){
							complete_address	+= ', ' + customer_postal;
						}

						$('#searchTableContent').append("<tr><td><label>" + customerName + "</label><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><button class='button button_default_dark'  onclick='viewCustomer(" + id + ")'><i class='fa fa-eye'></i></button></tr>");
						invoiceCount++;
					}
				});

				if(invoiceCount > 0){
					$('#searchTable').show();
					$('#searchTableText').hide();
				} else {
					$('#searchTable').hide();
					$("#searchTableText").show();
				}

				var pages = response.pages;
				$('#searchPage').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#searchPage').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#searchPage').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		})
	}

	$('#customerPage').change(function(){
		viewCustomer(customerSelected, $('#customerPage').val());
	})

	function viewCustomer(n, page = 1){
		customerSelected = n;
		$.ajax({
			url:"<?= site_url('Billing/getByCustomerId') ?>",
			data:{
				id:n,
				page: page
			},
			success:function(response){
				var customer = response.customer;
				var customerName = customer.name;
				var customerAddress = customer.address;
				var complete_address = customer.address;
				var customer_number = customer.number;
				var customer_block = customer.block;
				var customer_rt = customer.rt;
				var customer_rw = customer.rw;
				var customer_city = customer.city;
				var customer_postal = customer.postal_code;
			
				if(customer_number != null){
					complete_address	+= ' No. ' + customer_number;
				}
			
				if(customer_block != null && customer_block != "000"){
					complete_address	+= ' Blok ' + customer_block;
				}
		
				if(customer_rt != '000'){
					complete_address	+= ' RT ' + customer_rt;
				}
			
				if(customer_rw != '000' && customer_rt != '000'){
					complete_address	+= ' /RW ' + customer_rw;
				}
			
				if(customer_postal != null){
					complete_address	+= ', ' + customer_postal;
				}
				$('#customerName_p').html(customerName);
				$('#customerAddress_p').html(complete_address);
				$('#customerCity_p').html(customer_city);

				var items = response.items;
				$("#receivableTableContent").html("");
				$.each(items, function(index, item){
					var date = item.date;
					var difference = Math.ceil(Math.abs(new Date(date) - new Date())/(1000*60*60*24));
					var name = item.name;
					var base_value  = parseFloat(item.value);
					var delivery	= parseFloat(item.delivery);
					var discount	= parseFloat(item.discount);
					var value = base_value + delivery - discount;

					var paid = item.paid;
					var id 		= item.id;

					var lastBillingDate = item.lastBillingDate;
					if(lastBillingDate == null){
						var lastBillingDateText = "<i>Never</i>";
					} else if(lastBillingDate == new Date(<?= $date ?>)){
						var lastBillingDateText = "<strong>On progress</strong>";
					} else {
						var lastBillingDateText = my_date_format(lastBillingDate);
					}

					if(!includedInvoice.includes("" + id + "")){
						$('#receivableTableContent').append("<tr><td>" + my_date_format(date) + " (" + numeral(difference).format('0,0') + " days)</td><td>" + name + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Rp. " + numeral(paid).format('0,0.00') + "</td><td>" + lastBillingDateText + "</td><td><button class='button button_default_dark' onclick='selectCustomerInvoice(" + id + ")' id='selectInvoiceButtonCustomer-" + id + "'><i class='fa fa-long-arrow-right'></i></button><button class='button button_danger_dark' onclick='removeCustomerInvoice(" + id + ")' id='removeInvoiceButtonCustomer-" + id + "' style='display:none'><i class='fa fa-trash'></i></button></tr>");
					} else {
						$('#receivableTableContent').append("<tr><td>" + my_date_format(date) + " (" + numeral(difference).format('0,0') + " days)</td><td>" + name + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Rp. " + numeral(paid).format('0,0.00') + "</td><td>" + lastBillingDateText + "</td><td><button class='button button_default_dark' onclick='selectCustomerInvoice(" + id + ")' id='selectInvoiceButtonCustomer-" + id + "' style='display:none'><i class='fa fa-long-arrow-right'></i></button><button class='button button_danger_dark' onclick='removeCustomerInvoice(" + id + ")' id='removeInvoiceButtonCustomer-" + id + "'><i class='fa fa-trash'></i></button></tr>");
					}
				})

				var pages = response.pages;
				$('#customerPage').html("");
				for(i = 1; i<= pages; i++){
					if(i == page){
						$('#customerPage').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#customerPage').append("<option value='" + i + "'>" + i + "</option>");
					}
				}

				$('#customerReceivableWrapper').fadeIn(300, function(){
					$('#customerReceivableWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function selectRecommendedInvoice(n){
		if(!includedInvoice.includes("" + n + "")){
			includedInvoice.push("" + n + "");
			$('#removeInvoiceButtonRecommended-' + n).show();
			$('#selectInvoiceButtonRecommended-' + n).hide();

			$('#countedInvoice_span').html(numeral(includedInvoice.length).format('0,0'));
			if(includedInvoice.length > 0){
				$('#createBillingButton').attr('disabled', false);
			} else {
				$('#createBillingButton').attr('disabled', true);
			}
		}
	}

	function removeRecommendedInvoice(n){
		var index = includedInvoice.indexOf("" + n + "");
		if (index > -1) {
			includedInvoice.splice(index, 1);
			$('#selectInvoiceButtonRecommended-' + n).show();
			$('#removeInvoiceButtonRecommended-' + n).hide();

			$('#countedInvoice_span').html(numeral(includedInvoice.length).format('0,0'));
			if(includedInvoice.length > 0){
				$('#createBillingButton').attr('disabled', false);
			} else {
				$('#createBillingButton').attr('disabled', true);
			}
		}
	}

	function selectUrgentInvoice(n){
		if(!includedInvoice.includes("" + n + "")){
			includedInvoice.push("" + n + "");
			$('#removeInvoiceButtonUrgent-' + n).show();
			$('#selectInvoiceButtonUrgent-' + n).hide();

			$('#countedInvoice_span').html(numeral(includedInvoice.length).format('0,0'));
			if(includedInvoice.length > 0){
				$('#createBillingButton').attr('disabled', false);
			} else {
				$('#createBillingButton').attr('disabled', true);
			}
		}
	}

	function removeUrgentInvoice(n){
		var index = includedInvoice.indexOf("" + n + "");
		if (index > -1) {
			includedInvoice.splice(index, 1);
			$('#selectInvoiceButtonUrgent-' + n).show();
			$('#removeInvoiceButtonUrgent-' + n).hide();

			$('#countedInvoice_span').html(numeral(includedInvoice.length).format('0,0'));
			if(includedInvoice.length > 0){
				$('#createBillingButton').attr('disabled', false);
			} else {
				$('#createBillingButton').attr('disabled', true);
			}
		}
	}

	function selectCustomerInvoice(n){
		if(!includedInvoice.includes("" + n + "")){
			includedInvoice.push("" + n + "");
			$('#removeInvoiceButtonCustomer-' + n).show();
			$('#selectInvoiceButtonCustomer-' + n).hide();

			$('#countedInvoice_span').html(numeral(includedInvoice.length).format('0,0'));
			if(includedInvoice.length > 0){
				$('#createBillingButton').attr('disabled', false);
			} else {
				$('#createBillingButton').attr('disabled', true);
			}
		}
	}

	function removeCustomerInvoice(n){
		var index = includedInvoice.indexOf("" + n + "");
		if (index > -1) {
			includedInvoice.splice(index, 1);
			$('#selectInvoiceButtonCustomer-' + n).show();
			$('#removeInvoiceButtonCustomer-' + n).hide();

			$('#countedInvoice_span').html(numeral(includedInvoice.length).format('0,0'));
			if(includedInvoice.length > 0){
				$('#createBillingButton').attr('disabled', false);
			} else {
				$('#createBillingButton').attr('disabled', true);
			}
		}
	}

	$('#createBillingButton').click(function(){
		if(includedInvoice.length > 0){
			$.ajax({
				url:"<?= site_url('Billing/getByIdArray') ?>",
				data:{invoices: includedInvoice},
				type:"POST",
				beforeSend:function(){
					$('button').attr('disabled', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					$('#billingTableContent').html("");
					$.each(response, function(index, item){
						var date = item.date;
						var name = item.name;
						var customerName = item.customerName;
						var customerAddress = item.address;
						var complete_address = item.address;
						var customer_number = item.number;
						var customer_block = item.block;
						var customer_rt = item.rt;
						var customer_rw = item.rw;
						var customer_city = item.city;
						var customer_postal = item.postal_code;
						var value = parseFloat(item.value);
						var paid = parseFloat(item.paid);

						var remainder = value - paid;
						if(customer_number != null){
							complete_address	+= ' No. ' + customer_number;
						}
					
						if(customer_block != null && customer_block != "000"){
							complete_address	+= ' Blok ' + customer_block;
						}
				
						if(customer_rt != '000'){
							complete_address	+= ' RT ' + customer_rt;
						}
					
						if(customer_rw != '000' && customer_rt != '000'){
							complete_address	+= ' /RW ' + customer_rw;
						}
					
						if(customer_postal != null){
							complete_address	+= ', ' + customer_postal;
						}
						$('#billingTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><label>" + customerName + "</label><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td>" + name + "</td><td>Rp. " + numeral(remainder).format('0,0.00') + "</td></tr>");
					});

					$('#createBillingWrapper').fadeIn(300, function(){
						$('#createBillingWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
					});
				}
			})
		}
	})

	$('#submitBillingButton').click(function(){
		$.ajax({
			url:"<?= site_url('Billing/insertItem') ?>",
			data:{invoices: includedInvoice, date: "<?= date('Y-m-d', strtotime($date)) ?>", collector: "<?= $collector ?>"},
			type:"POST",
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				if(response == 1){
					includedInvoice = [];
					$('#countedInvoice_span').html(numeral(includedInvoice.length).format('0,0'));
					if(includedInvoice.length > 0){
						$('#createBillingButton').attr('disabled', false);
					} else {
						$('#createBillingButton').attr('disabled', true);
					}
					$('#createBillingWrapper .slide_alert_close_button').click();

					$('#urgentButton').click();
				} else {
					$('#failedInsertItemNotification').fadeIn(250, function(){
						setTimeout(function(){
							$('#failedInsertItemNotification').fadeOut(250);
						}, 1000)
					})
				}
			}
		})
	})
</script>
