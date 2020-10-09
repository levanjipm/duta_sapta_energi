<head>
	<title>Invoice - Create blank</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-bar-chart'></i></a> /Invoice /Create blank invoice</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<form action="<?= site_url('Invoice/insertBlankItem') ?>" method="POST" id='invoiceForm'>
			<label>Date</label>
			<input type='date' class='form-control' id='date' name='date' required min='2020-01-01'>

			<label>Document</label>
			<input type='text' class='form-control' id='invoiceDocumentName' name='invoiceDocument' required>

			<label>Opponent</label>
			<button type='button' class='form-control' id='selectOpponentButton' style='text-align:left'></button>
			<input type='hidden' id='opponentId' name='opponentId' required>
			<input type='hidden' id='opponentType' name='opponentType' required>

			<label>Type</label>
			<button type='button' class='form-control' id='selectTypeButton' style='text-align:left'></button>
			<input type='hidden' id='type' name='type' required>

			<label>Value</label>
			<input type='number' class='form-control' name='value' id='value' required min='1'>

			<label>Note</label>
			<textarea class='form-control' id='note' name='note' rows='3' style='resize:none'></textarea>
			<br>
			<button type='button' class='button button_default_dark' id='createBlankInvoiceButton'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='selectOpponentWrapper'>
	<div class='alert_box_full'>		
		<button type='button' class='button alert_full_close_button' title='Close select supplier session'>&times;</button>
		<h3 style='font-family:bebasneue'>Select Opponent</h3>
		<hr>
		<button class='button button_mini_tab' id='customerTabButton'>Customer</button>
		<button class='button button_mini_tab' id='opponentTabButton'>Opponents</button>
		<hr>
		<div id='customerView'>
			<input type='text' class='form-control' id='searchCustomerBar'>
			<br>
			<div id='customerTable'>
				<table class='table table-bordered'>
					<tr>
						<th>Name</th>
						<th>Address</th>
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
		<div id='opponentView'>
			<input type='text' class='form-control' id='searchOpponentBar'>
			<br>
			<div id='opponentTable'>
				<table class='table table-bordered'>
					<tr>
						<th>Name</th>
						<th>Type</th>
						<th>Action</th>
					</tr>
					<tbody id='opponentTableContent'></tbody>
				</table>
				
				<select class='form-control' id='opponentPage' style='width:100px'>
					<option value='1'>1</option>
				</select>
			</div>
			<p id='opponentTableText'>There is no opponent found.</p>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='selectTypeWrapper'>
	<div class='alert_box_full'>		
		<button type='button' class='button alert_full_close_button' title='Close select type session'>&times;</button>
		<h3 style='font-family:bebasneue'>Select debt type</h3>
		<br>
		<input type='text' class='form-control' id='searchTypeBar'>
		<br>
		<table class='table table-bordered'>
			<tr>
				<th>Name</th>
				<th>Description</th>
				<th>Action</th>
			</tr>
			<tbody id='debtTypeTableContent'></tbody>
		</table>
		
		<select class='form-control' id='debtTypePage' style='width:100px'>
			<option value='1'>1</option>
		</select>
	</div>
</div>

<div class='alert_wrapper' id='invoiceDocumentWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Create invoice document</h3>
		<hr>
		<label>Customer / Opponent</label>
		<p id='customer_name_p'></p>
		<p id='customer_address_p'></p>
		<p id='customer_city_p'></p>

		<label>Invoice</label>
		<p id='invoice_date_p'></p>
		<p id='invoice_name_p'></p>
		<p id='tax_invoice_name_p'></p>
		<p id='invoice_value_p'></p>

		<label>Information</label>
		<p id='information_p'></p>

		<label>Type</label>
		<p id='debtTypeText'></p>

		<button class='button button_default_dark' id='submitFormButton'><i class='fa fa-long-arrow-right'></i></button>
	</div>
</div>

<script>
	var opponentTypeChoose;

	$('#submitFormButton').click(function(){
		if($('#invoiceForm').valid()){
			$('#invoiceForm').submit();
		}
	})

	$('#invoiceForm').validate({
		ignore: '',
		rules: {"hidden_field": {required: true}}
	});

	$('#invoiceForm input').keydown(function (e) {
		if (e.keyCode == 13) {
			e.preventDefault();
			return false;
		}
	});

	$('#createBlankInvoiceButton').click(function(){
		if($('#invoiceForm').valid()){
			var taxDocumentName = $('#invoiceTaxDocument').val();
			var invoiceDocumentName = $('#invoiceDocumentName').val();
			var date = $("#date").val();
			var note = $("#note").val();
			var value = $('#value').val();

			$('#invoice_name_p').html(invoiceDocumentName);
			$('#tax_invoice_name_p').html(taxDocumentName);
			$('#invoice_value_p').html("Rp. " + numeral(value).format('0,0.00'));
			$('#invoice_date_p').html(my_date_format(date));
			$('#information_p').html((note == "")? "<i>Not available</i>" : note);

			$('#invoiceDocumentWrapper').fadeIn(300, function(){
				$('#invoiceDocumentWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
			});
		}
	});

	$('#selectTypeButton').click(function(){
		$('#searchTypeBar').val("");
		refreshType(1);
		$('#selectTypeWrapper').fadeIn();
	});

	$('#customerTabButton').click(function(){
		$('.button_mini_tab').attr('disabled', false);
		$('.button_mini_tab').removeClass('active');
		
		$(this).attr('disabled', true);
		$(this).addClass('active');
		$('#opponentView').fadeOut(250, function(){
			$('#customerView').fadeIn(250);
		});

		$('#searchCustomerBar').val("");
		refreshCustomer(1);
	});

	$('#opponentTabButton').click(function(){
		$('.button_mini_tab').attr('disabled', false);
		$('.button_mini_tab').removeClass('active');
		
		$(this).attr('disabled', true);
		$(this).addClass('active');
		$('#customerView').fadeOut(250, function(){
			$('#opponentView').fadeIn(250);
		});

		$('#searchOpponentBar').val("");
		refreshOpponent(1);
	});

	$('#searchCustomerBar').change(function(){
		refreshCustomer(1);
	});

	$('#customerPage').change(function(){
		refreshCustomer();
	})

	$('#searchOpponentBar').change(function(){
		refreshOpponent(1);
	});

	$('#opponentPage').change(function(){
		refreshOpponent();
	})

	$('#selectOpponentButton').click(function(){
		$('#searchCustomerBar').val("");
		$('#customerTabButton').click();
		refreshCustomer(1);
		$('#selectOpponentWrapper').fadeIn();
	})

	function refreshType(page = $('#debtTypePage').val()){
		$.ajax({
			url:"<?= site_url('Debt_type/getItems') ?>",
			data:{
				page: page,
				term: $('#searchTypeBar').val()
			},
			success:function(response){
				$('#debtTypeTableContent').html("");
				var items = response.items;
				$.each(items, function(index, item){
					var id 			= item.id;
					var name 		= item.name;
					var description = item.description;

					$('#debtTypeTableContent').append("<tr><td>" + name + "</td><td>" + description + "</td><td><button class='button button_success_dark' id='typeButton-" + id + "'><i class='fa fa-check'></i></button></td></tr>");

					$('#typeButton-' + id).click(function(){
						$('#selectTypeButton').text(name);
						$('#type').val(id);
						$('#debtTypeText').html(name);
						$('#selectTypeWrapper').fadeOut();
					})
				})

				var pages = response.pages;
				$('#debtTypePage').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#debtTypePage').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#debtTypePage').append("<option value='" + i + "'>" + i + "</option>")
					}
				}
			}
		})
	}

	function refreshOpponent(page = $('#opponentPage').val()){
		$.ajax({
			url:'<?= site_url("Opponent/getItems") ?>',
			data:{
				page:page,
				term: $('#searchOpponentBar').val()
			},
			success:function(response){
				var opponentCount = 0;
				var items = response.items;
				$('#opponentTableContent').html("");
				$('#opponentTableContent').html("");
				$.each(items, function(index, item){
					var name = item.name;
					var type = item.type;
					var description = item.description;
					var id = item.id;

					$('#opponentTableContent').append("<tr><td>" + name + "</td><td>" + type + "</td><td><button class='button button_default_dark' id='selectOpponentButton-" + id + "'><i class='fa fa-long-arrow-right'></i></td></tr>");
					$('#selectOpponentButton-' + id).click(function(){
						$('#selectOpponentButton').html(name);
						$('#opponentType').val(2);
						$('#opponentId').val(id);

						$('#customer_name_p').html(name);
						$('#customer_address_p').html(description);
						$('#customer_city_p').html(type);

						$('#selectOpponentWrapper').fadeOut();
					});
					opponentCount++;
				});

				$('#opponentPage').html("");
				for(i = 1; i <= page; i++){
					if(i == page){
						$("#opponentPage").append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$("#opponentPage").append("<option value='" + i + "'>" + i + "</option>");
					}
				}
				
				if(opponentCount > 0){
					$('#opponentTable').show();
					$('#opponentTableText').hide();
				} else {
					$('#opponentTable').hide();
					$('#opponentTableText').show();
				}
			}
		})
	}

	function refreshCustomer(page = $('#customerPage').val()){
		$.ajax({
			url:"<?= site_url('Customer/showItems') ?>",
			data:{
				page: page,
				term: $('#searchCustomerBar').val()
			},
			success:function(response){
				var customers = response.customers;
				var customerCount = 0;
				$('#customerTableContent').html("");
				$.each(customers, function(index, customer){
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
					
					$('#customerTableContent').append("<tr><td>" + customer_name + "</td><td><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><button type='button' class='button button_default_dark' id='selectCustomerButton-" + customer_id + "'><i class='fa fa-long-arrow-right'></i></button></tr>");
					$('#selectCustomerButton-' + customer_id).click(function(){
						$('#selectOpponentButton').html(customer_name);
						$('#opponentType').val(1);
						$('#opponentId').val(customer_id);

						$('#customer_name_p').html(customer_name);
						$('#customer_address_p').html(complete_address);
						$('#customer_city_p').html(customer_city);

						$('#selectOpponentWrapper').fadeOut();
					});
					customerCount++;
				});

				if(customerCount > 0){
					$('#customerTableText').hide();
					$('#customerTable').show();
				} else {
					$('#customerTableText').show();
					$('#customerTable').hide();
				}
				
				var pages = response.pages;
				for(i = 1; i <= pages; i++){
					if(page == i){
						$('#customerPage').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#customerPage').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		})
	}

	$('.alert_full_close_button').click(function(){
		$(this).parent().parent().fadeOut();
	})
</script>
