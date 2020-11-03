<head>
	<title>Receivable</title>
	<style>	
		.receivable_line{
			height:30px;
			background-color:#014886;
			border:none;
			transition:0.3s all ease;
			width:0;
			cursor:pointer;
			opacity:0.7;
		}
	
		.receivable_line:hover{
			background-color:#013663;
			transition:0.3s all ease;
			opacity:1;
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

		#receivable_chart{
			position:relative;
			z-index:5;
		}

		#receivable_view_pane{
			position:relative;
		}
	
		#receivable_grid{
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
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> /Receivable</p>
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
				<button class='form-control' style='text-align:left' id='searchCustomerButton'>Search Customer</button>
			</div>
			<div class='col-xs-12'>
				<hr>
			</div>
		</div>
		<div id='receivable_view_pane'>
			<div id='receivable_chart'></div>
			<div id='receivable_grid'>
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

<div class='alert_wrapper' id='receivable_detail_wrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Customer's receivable</h3>
		<hr>
		<label>Customer / Opponent</label>
		<p><strong><span id='customer_name_p'></span></strong></p>
		<p id='customer_address_p'></p>
		<p id='customer_city_p'></p>
		<hr>
		
		<table class='table table-bordered'>
			<tr>
				<th>Date</th>
				<th>Name</th>
				<th>Value</th>
				<th>Paid</th>
				<th>Balance</th>
			</tr>
			<tbody id='receivable_table'></tbody>
		</table>

		<button class='button button_default_dark' onclick='viewCompleteReceivable()'><i class='fa fa-eye'></i></button>
	</div>
</div>

<div class='alert_wrapper' id='selectCustomerWrapper'>
	<div class='alert_box_full'>
		<button class='button alert_full_close_button'>&times;</button>
		<h3 style='font-family:bebasneue'>Select Customer</h3>
		<hr>
		<input type='text' class='form-control' id='searchCustomerBar'>

		<br>
		<div id='customerTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Name</th>
					<th>Information</th>
					<th>Action</th>
				</tr>
				<tbody id='customerTableContent'></tbody>
			</table>

			<select class='form-control' id='customerPage' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='customerTableText'>There is no customer found.</p>
	</div>
</div>
<script>
	var customerId;
	var mode;

	function adjust_grid(){
		var width		= $('#grid_wrapper').width();
		var each		= (width) / 10;
		$('.grid').width(each);
		
		$('#grid_wrapper').fadeTo(500, 1);
	}
	
	$(document).ready(function(){
		refresh_view();
	});
	
	$(window).resize(function(){
		adjust_grid();
	});

	$('#category').change(function(){
		refresh_view();
	})

	function refresh_view(){
		$.ajax({
			url:'<?= site_url('Receivable/viewReceivable') ?>',
			data:{
				category: $('#category').val()
			},
			success:function(response){
				var receivableCount = 0;
				$('#receivable_chart').html('');
				var max_receivable		= 0;
				$.each(response, function(index,value){
					var id			= (value.customer_id == null) ? value.opponent_id : value.customer_id;
					var name 		= value.name;
					var receivable	= parseFloat(value.value);
					var city		= value.city;
					if(receivable > max_receivable){
						max_receivable = receivable;
					}
					if(receivable > 0){
						if(value.customer_id == null){
							$('#receivable_chart').prepend("<div class='row' id='receivable-" + id + "'><div class='col-sm-3 col-xs-3 center'><p><strong>" + name + "</strong>, " + city + "</p></div><div class='col-sm-7 col-xs-6'><div class='receivable_line' id='receivableBarOpponent-" + id + "' onclick='viewOpponentReceivable(" + id + ")'></div></div><div class='col-sm-2 col-xs-3 center' style='text-align:right'><p>Rp. " + numeral(receivable).format('0,0.00') + "</p></div></div><br>");
						} else {
							$('#receivable_chart').prepend("<div class='row' id='receivable-" + id + "'><div class='col-sm-3 col-xs-3 center'><p><strong>" + name + "</strong>, " + city + "</p></div><div class='col-sm-7 col-xs-6'><div class='receivable_line' id='receivableBarCustomer-" + id + "' onclick='viewCustomerReceivable(" + id + ")'></div></div><div class='col-sm-2 col-xs-3 center' style='text-align:right'><p>Rp. " + numeral(receivable).format('0,0.00') + "</p></div></div><br>");
						}
						receivableCount++;
					}
					
				});

				if(receivableCount == 0){
					$('#receivable_chart').html("There is no receivable found.");
					$('#receivable_grid').hide();
				} else {
					$('#receivable_grid').show();
				}
				
				$.each(response, function(index,value){
					var customerId			= value.customer_id;
					var opponentId			= value.opponent_id;

					var receivable	= value.value;
					var percentage	= Math.max(3, receivable * 100 / max_receivable);
					if(customerId == null){
						$('#receivableBarOpponent-' + opponentId).animate({
							'width': percentage + "%"
						},300);
					} else {
						$('#receivableBarCustomer-' + customerId).animate({
							'width': percentage + "%"
						},300);
					}					
				});
			}
		});
		setTimeout(function(){
			adjust_grid();
		},300);
	}

	function viewCustomerReceivable(n){
		$.ajax({
			url:'<?= site_url('Receivable/getReceivableByCustomerId') ?>',
			data:{
				id: n
			},
			success:function(response){
				mode = "customer";
				customerId = n;
				var customer = response.customer;
				var customer_name = customer.name;
				var complete_address = customer.address;
				var customer_number = customer.number;
				var customer_block = customer.block;
				var customer_rt = customer.rt;
				var customer_rw = customer.rw;
				var customer_city = customer.city;
				var customer_postal = customer.postal;
				
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
				
				$('#customer_name_p').html(customer_name);
				$('#customer_address_p').html(complete_address);
				$('#customer_city_p').html(customer_city);
				
				$('#receivable_table').html('');
				var receivable_array = response.receivable;
				$.each(receivable_array, function(index, receivable){
					var invoice_name = receivable.name;
					var todayDate = new Date().toISOString().slice(0,10);
					var date = receivable.date;
					var paid = parseFloat(receivable.paid);
					var invoiceValue = parseFloat(receivable.value);
					
					var date_diff = Math.ceil((Date.parse(todayDate) - Date.parse(date))/(1000 * 60 * 60 * 24));
					
					var residue = invoiceValue - paid;
					
					if(receivable.is_done == 0){
						$('#receivable_table').append("<tr><td>" + my_date_format(date) + " (" + numeral(date_diff).format('0,0') + " days)</td><td>" + invoice_name + "</td><td>Rp. " + numeral(invoiceValue).format('0,0.00') + "</td><td>Rp. " + numeral(paid).format('0,0.00') + "</td><td>Rp. " + numeral(residue).format('0,0.00') + "</td></tr>");
					}
				});

				var pendingBankValue = 0;
				var items = response.pendingBank;
				$.each(items, function(index, item ){
					pendingBankValue += parseFloat(item.value);
				});

				$('#receivable_table').append("<tr><td colspan='2'>Pending bank value</td><td>Rp. " + numeral(0).format('0,0.00') + "</td><td>Rp. " + numeral(pendingBankValue).format('0,0.00') + "</td><td>Rp. " + numeral(pendingBankValue).format('0,0.00') +"</td></tr>");

				$('#receivable_detail_wrapper').fadeIn(300, function(){
					$('#receivable_detail_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}

	function viewOpponentReceivable(n){
		$.ajax({
			url:'<?= site_url('Receivable/getReceivableByOpponentId') ?>',
			data:{
				id: n
			},
			success:function(response){
				customerId = n;
				mode = "opponent";

				var opponent = response.opponent;
				var opponentName = opponent.name;
				var opponentDescription = opponent.description;
				var opponentType = opponent.type;

				$('#customer_name_p').html(opponentName);
				$('#customer_address_p').html(opponentDescription);
				$('#customer_city_p').html(opponentType);

				$('#receivable_table').html('');
				var receivable_array = response.invoices;
				$.each(receivable_array, function(index, receivable){
					var invoice_name = receivable.name;
					var todayDate = new Date().toISOString().slice(0,10);
					var date = receivable.date;
					var paid = parseFloat(receivable.paid);
					var value = parseFloat(receivable.value);
					
					var date_diff = Math.ceil((Date.parse(todayDate) - Date.parse(date))/(1000 * 60 * 60 * 24));
					
					var residue = value - paid;
					if(receivable.is_done == 0){
						$('#receivable_table').append("<tr><td>" + my_date_format(date) + " (" + numeral(date_diff).format('0,0') + " days)</td><td>" + invoice_name + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Rp. " + numeral(paid).format('0,0.00') + "</td><td>Rp. " + numeral(residue).format('0,0.00') + "</td></tr>");
					}
				});

				var pendingBankValue = 0;
				var items = response.pendingBank;
				$.each(items, function(index, item ){
					pendingBankValue += parseFloat(item.value);
				});

				$('#receivable_table').append("<tr><td colspan='2'>Pending bank value</td><td>Rp. " + numeral(0).format('0,0.00') + "</td><td>Rp. " + numeral(pendingBankValue).format('0,0.00') + "</td><td>Rp. " + numeral(pendingBankValue).format('0,0.00') +"</td></tr>");


				$('#receivable_detail_wrapper').fadeIn(300, function(){
					$('#receivable_detail_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function viewCompleteReceivable(){
		if(mode == "customer"){
			window.location.href="<?= site_url('Receivable/viewByCustomerId/') ?>" + customerId;
		} else {
			window.location.href="<?= site_url('Receivable/viewByOpponentId/') ?>" + customerId;
		}
	}

	$('#searchCustomerButton').click(function(){
		$('#selectCustomerWrapper').fadeIn(300);
		$('#searchCustomerBar').val("");
		refreshCustomerView(1);
	});

	$('.alert_full_close_button').click(function(){
		$(this).parent().parent().fadeOut(300);
	});

	function refreshCustomerView(page = $('#customerPage').val()){
		$.ajax({
			url:"<?= site_url('Receivable/getCustomerOpponentItems') ?>",
			data:{
				page: page,
				term: $('#searchCustomerBar').val()
			},
			success:function(response){
				var items = response.items;
				var customerCount = 0;
				$('#customerTableContent').html("");
				$.each(items, function(index, customer){
					if(customer.opponentType == 1){
						var complete_address		= '';
						var customer_name			= customer.name;
						complete_address		+= customer.address;
						var customer_city			= customer.city;
						var customer_number			= customer.number;
						var customer_rt				= customer.rt;
						var customer_rw				= customer.rw;
						var customer_postal			= customer.postal_code;
						var customer_block			= customer.block;
						var customer_id				= customer.id;
		
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

						$('#customerTableContent').append("<tr><td>" + customer_name + "</td><td><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><button class='button button_default_dark' onclick='viewCustomer(" + customer_id + ")'><i class='fa fa-eye'></i></button></td></tr>");
						customerCount++;
					} else {
						var address = customer.address;
						var city = customer.city;
						var name = customer.name;
						var id = customer.id;

						$('#customerTableContent').append("<tr><td>" + name + "</td><td><p>" + address + "</p><p>" + city + "</p></td><td><button class='button button_default_dark' onclick='viewOpponent(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
						customerCount++;
					}
				});

				if(customerCount > 0){
					$('#customerTable').show();
					$('#customerTableText').hide();
				} else {
					$('#customerTable').hide();
					$('#customerTableText').show();
				}

				var pages = response.pages;
				$('#customerPage').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#customerPage').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#customerPage').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		})
	}

	$('#customerPage').change(function(){
		refreshCustomerView();
	});

	$('#searchCustomerBar').change(function(){
		refreshCustomerView(1);
	});

	function viewCustomer(n){
		window.location.href='<?= site_url('Receivable/viewByCustomerId/') ?>' + n;
	}

	function viewOpponent(n){
		window.location.href="<?= site_url('Receivable/viewByOpponentId/') ?>" + n;
	}
</script>
