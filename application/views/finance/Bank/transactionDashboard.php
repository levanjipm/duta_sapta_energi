<head>
	<title>Bank transaction</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-usd'></i></a> <a href='<?= site_url('Bank') ?>'>Bank</a> / Add transaction</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<form id='transactionForm'>
			<label>Account</label>
			<button type='button' class='form-control' id='accountButton' style='text-align:left!important'></button>
			<input type='hidden' id='account' name='account' required>
	
			<label>Date</label>
			<input type='date' class='form-control' name='date' required min='2020-01-01' id='date'>
			
			<label>Value</label>
			<input type='number' class='form-control' required min='1' name='value' id='value'>

			<div id='otherAccountWrapper' style='display:none'>
				<label>Other Internal Account</label>
				<button type='button' class='form-control' id='otherAccountButton' style='text-align:left!important'></button>
				<input type='hidden' id='otherAccount' name='otherAccount'>
			</div>
			
			<label>
				<input type='checkbox' name='petty_cash_transfer' id='petty_cash_transfer' onchange='adjust_form()'> Petty cash</input>
			</label>
			<label>
				<input type='checkbox' name='internalAccountTransfer' id='internalAccountTransfer' onchange='adjustInternalForm()'> Internal Transfer</input>
			</label>
			<br>
			<div id='operational'>
				<label>Transaction</label>
				<select class='form-control' name='transaction' id='transaction'>
					<option value='1'>Credit</option>
					<option value='2'>Debit</option>
				</select>
		
				<label>Opponent</label>
				<select class='form-control' id='opponent_type' name='type'>
					<option value='' disabled selected>Please select opponent type</option>
					<option value='customer'>Customer</option>
					<option value='supplier'>Supplier</option>
					<option value='other'>Other</option>
				</select>
				
				<input type='hidden' id='opponent_id' name='id' required>
				<p style='font-family:museo'><span id='opponent_selector_view'>No opponent</span> has been selected</p>
			</div>
			<button class='button button_default_dark' type='button' id='submit_button'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
	<br>
</div>

<div class='alert_wrapper' id='bankAccountWrapper'>
	<div class='alert_box_full'>
	<h3 style='font-family:bebasneue'>Choose an account</h3>
	<button type='button' class='button alert_full_close_button' title='Close select account session'>&times;</button>
		<br>
		<div class='row'>
			<div class='col-xs-12'>
				<input type='text' class='form-control' id='searchAccountBar'>
				<br>
				<div id='accountTable'>
					<table class='table table-bordered'>
						<tr>
							<th>Number</th>
							<th>Name</th>
							<th>Bank</th>
							<th>Action</th>
						</tr>
						<tbody id='accountTableContent'></tbody>
					</table>
					<select class='form-control' id='accountPage' style='width:100px'>
						<option value='1'>1</option>
					</select>
				</div>
				<p id='accountTableText'>There is no account found.</p>
			</div>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='opponentAlertWrapper'>
	<div class='alert_box_full'>
	<h3 style='font-family:bebasneue'>Choose an opponent</h3>
	<button type='button' class='button alert_full_close_button' title='Close select opponent session'>&times;</button>
		<br>
		<div class='row'>
			<div class='col-xs-12'>
				<input type='text' class='form-control' id='search_bar'>
				<br>
				<div id='opponentTable'>
					<table class='table table-bordered'>
						<tr>
							<th>Opponent</th>
							<th>Action</th>
						</tr>
						<tbody id='opponentTableContent'></tbody>
					</table>
				
					<select class='form-control' id='page' style='width:100px'>
						<option value='1'>1</option>
					</select>
				</div>
				<p id='opponentTableText'>There is no opponent found.</p>
			</div>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='transactionWrapper'>
	<button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Add transaction</h3>
		<hr>
		<label>Transaction</label>
		<p style='font-family:museo' id='transaction_date_p'></p>
		<p style='font-family:museo' id='transaction_value_p'></p>
		<p style='font-family:museo' id='transaction_type_p'></p>

		<label>Account</label>
		<p style='font-family:museo' id='bankName_p'></p>
		<p style='font-family:museo' id='bankNumber_p'></p>
		
		<label>Opponent</label>
		<p style='font-family:museo' id='opponentName_p'></p>
		<p style='font-family:museo' id='opponentAddress_p'></p>
		<p style='font-family:museo' id='opponentCity_p'></p>
		
		<button type='button' class='button button_default_dark' id='submitTransactionButton'><i class='fa fa-long-arrow-right'></i></button>
	</div>
</div>
<script>
	var mode;
	function adjust_form(){
		if($('#petty_cash_transfer').prop('checked') == true){
			$('#operational').hide();
			$('#transaction').val(2);

			$('#internalAccountTransfer').prop('checked', false);
			$('#opponent_id').attr('required', false);

			$('#otherAccount').attr('required', false);
			$('#otherAccountWrapper').hide();
		} else {
			$('#operational').show();
			$('#transaction').val(1);
			$('#opponent_id').attr('required', true);
		}
	}

	function adjustInternalForm()
	{
		if($('#internalAccountTransfer').prop('checked') == true){
			$('#operational').hide();
			$('#transaction').val(2);

			$('#petty_cash_transfer').prop('checked', false);
			$('#opponent_id').attr('required', false);

			$('#otherAccount').attr('required', true);
			$('#otherAccountWrapper').show();
		} else {
			$('#operational').show();
			$('#transaction').val(1);
			$('#opponent_id').attr('required', true);

			$('#otherAccount').attr('required', false);
			$('#otherAccountWrapper').hide();
		}
	}
	
	$("#transactionForm").validate({
		ignore: '',
		rules: {"hidden_field": {required: true}}
	});
	
	$('#opponent_type').change(function(){
		refresh_view();
	});
	
	$('#search_bar').change(function(){
		refresh_view(1);
	});
	
	$('#page').change(function(){
		refresh_view()
	});
	
	function refresh_view(page = $('#page').val()){	
		$.ajax({
			url:'<?= site_url('Bank/showOpponent') ?>',
			data:{
				type:$('#opponent_type').val(),
				page:page,
				term:$('#search_bar').val()
			},
			type:'GET',
			success:function(response){
				$('#opponentTableContent').html('');
				var opponents	= response.opponents;
				var pages		= response.pages;
				var opponentCount = 0;
				if($('#opponent_type').val() == "customer"){
					$.each(opponents, function(index, opponent){
						var complete_address		= '';
						var customer_name			= opponent.name;
						complete_address			+= opponent.address;
						var customer_city			= opponent.city;
						var customer_number			= opponent.number;
						var customer_rt				= opponent.rt;
						var customer_rw				= opponent.rw;
						var customer_postal			= opponent.postal_code;
						var customer_block			= opponent.block;
						var customer_id				= opponent.id;
		
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

						$('#opponentTableContent').append("<tr><td><p>" + customer_name + "</p><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><button type='button' class='button button_default_dark' id='addCustomerButton-" + customer_id + "' title='Choose " + customer_name + "'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
						$('#addCustomerButton-' + customer_id).click(function(){
							$('#opponent_id').val(customer_id);

							$('#opponentName_p').html(customer_name);
							$('#opponentAddress_p').html(complete_address);
							$('#opponentCity_p').html(customer_city);

							$('#opponent_selector_view').html(customer_name);
							$('.alert_full_close_button').click();
						})
						opponentCount++;
					});
				} else if($('#opponent_type').val() == "supplier") {
					$.each(opponents, function(index, item){
						var complete_address		= '';
						var supplier_name			= item.name;
						complete_address			+= item.address;
						var supplier_city			= item.city;
						var supplier_number			= item.number;
						var supplier_rt				= item.rt;
						var supplier_rw				= item.rw;
						var supplier_postal			= item.postal_code;
						var supplier_block			= item.block;
						var supplier_id				= item.id;
			
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

						$('#opponentTableContent').append("<tr><td><p>" + supplier_name + "</p><p>" + complete_address + "</p><p>" + supplier_city + "</p></td><td><button type='button' class='button button_default_dark' id='addSupplierButton-" + supplier_id + "' title='Choose " + supplier_name + "'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
						$('#addSupplierButton-' + supplier_id).click(function(){
							$('#opponent_id').val(supplier_id);

							$('#opponentName_p').html(supplier_name);
							$('#opponentAddress_p').html(complete_address);
							$('#opponentCity_p').html(supplier_city);

							$('#opponent_selector_view').html(supplier_name);
							$('.alert_full_close_button').click();
						})
						opponentCount++;
					})
				} else if($('#opponent_type').val() == "other") {
					$.each(opponents, function(index, opponent){
						var name		= opponent.name;
						var type		= opponent.type;
						var description	= opponent.description;
						var id			= opponent.id;
						$('#opponentTableContent').append("<tr><td><p id='opponent_name-" + id + "'>" + name + "</p><p>" + description + "</p><p>" + type + "</p></td><td><button type='button' class='button button_default_dark' id='addOtherButton-" + id + "' title='Choose " + name + "'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
						$('#addOtherButton-' + id).click(function(){
							$('#opponent_id').val(id);

							$('#opponentName_p').html(name);
							$('#opponentAddress_p').html(description);
							$('#opponentCity_p').html(type);

							$('#opponent_selector_view').html(name);
							$('.alert_full_close_button').click();
						})
						opponentCount++;
					});
				}

				if(opponentCount > 0){
					$('#opponentTable').show();
					$('#opponentTableText').hide();
				} else {
					$('#opponentTable').hide();
					$('#opponentTable').show();
				}
				
				$('#page').html('');
				for(i = 1; i <= pages; i++){
					if(page == i){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				};
			}	
		});
		
		$('#opponentAlertWrapper').fadeIn(300);
	};

	$("#accountButton").click(function(){
		mode = 1;
		$('#searchAccountBar').val("");
		refreshAccount(1);
		$('#bankAccountWrapper').fadeIn();
	});

	$("#otherAccountButton").click(function(){
		mode = 2;
		$('#searchAccountBar').val("");
		refreshAccount(1);
		$('#bankAccountWrapper').fadeIn();
	})

	$('#searchAccountBar').change(function(){
		refreshAccount(1);
	});

	$('#accountPage').change(function(){
		refreshAccount();
	})

	function refreshAccount(page = $('#accountPage').val()){
		$.ajax({
			url:'<?= site_url('Bank_account/getItems') ?>',
			data:{
				page: page,
				term: $('#searchAccountBar').val()
			},
			success:function(response){
				$('#accountTableContent').html("");
				var accountCount = 0;
				var items = response.items;
				$.each(items, function(index, item){
					var number 		= item.number;
					var name		= item.name;
					var id			= item.id;
					var bank		= item.bank;
					var branch		= item.branch;

					$('#accountTableContent').append("<tr><td>" + number + "</td><td>" + name + "</td><td><label>" + bank + "</label><p>" + branch + "</p></td><td><button class='button button_default_dark' onclick='selectAccount(" + id + ")'><i class='fa fa-long-arrow-right'></i></button></td></tr>");

					accountCount++;
				})
				var pages = response.pages;
				$('#accountPage').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#accountPage').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#accountPage').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
				
				if(accountCount > 0){
					$('#accountTable').show();
					$('#accountTableText').hide();
				} else {
					$('#accountTable').hide();
					$('#accountTableText').show();
				}
			}
		})
	}

	function selectAccount(n){
		$.ajax({
			url:"<?= site_url('Bank_account/getById') ?>",
			data:{
				id: n
			},
			success:function(response){
				var name	= response.name;
				var number	= response.number;
				var branch	= response.branch;

				if(mode == 1){
					$('#accountButton').text(name + " - " + number);
					$('#account').val(n);
					$('#bankName_p').html(name);
					$('#bankNumber_p').html(number);

				} else if(mode == 2){
					$('#otherAccountButton').text(name + " - " + number);
					$('#otherAccount').val(n);
					$('#opponentName_p').html(name);
					$('#opponentAddress_p').html(number);
					$('#opponentCity_p').html(branch);
				}

				$('#bankAccountWrapper').fadeOut();
			}
		})
	}

	$('#submitTransactionButton').click(function(){
		if($('#transactionForm').valid()){
			$.ajax({
				url:'<?= site_url('Bank/insertItem') ?>',
				data:$('#transactionForm').serialize(),
				type:"POST",
				beforeSend:function(){
					$('button').attr('disabled', true);
				},
				success:function(){
					$('button').attr('disabled', false);
					$('#transactionForm').trigger("reset");
					$('#accountButton').text("");

					$('#transactionWrapper .slide_alert_close_button').click();
				}
			})
		}
	})
	
	$('#submit_button').click(function(){
		if(!$("#transactionForm").valid()){
			return false;
		} else {
			var bank_name		= $('#account option:selected').html();
			var opponent_name	= $('#opponent_selector_view').html();
			var date			= $('#date').val();
			var value			= $('#value').val();
			var transaction		= $('#transaction option:selected').html();
			
			if($('#petty_cash_transfer').prop('checked') == true){
				$('#transaction_date_p').html(my_date_format(date));
				$('#bank_name_p').html(bank_name);
				$('#opponent_name_p').html('Petty cash transaction');
				$('#transaction_type_p').html(transaction);
				$('#transaction_value_p').html('Rp. ' + numeral(value).format('0,0.00'));
			} else {
				$('#transaction_date_p').html(my_date_format(date));
				$('#bank_name_p').html(bank_name);
				$('#opponent_name_p').html(opponent_name);
				$('#transaction_type_p').html(transaction);
				$('#transaction_value_p').html('Rp. ' + numeral(value).format('0,0.00'));
			}
			
			$('#transactionWrapper').fadeIn(300, function(){
				$('#transactionWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
			});
		}
	});

	$('.alert_full_close_button').click(function(){
		$(this).parent().parent().fadeOut();
	})
</script>
