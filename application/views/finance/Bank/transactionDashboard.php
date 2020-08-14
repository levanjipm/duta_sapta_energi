<head>
	<title>Petty cash transaction</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-briefcase'></i></a> <a href='<?= site_url('Bank') ?>'>Bank</a> / Add transaction</p>
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
			
			<label>
				<input type='checkbox' name='petty_cash_transfer' id='petty_cash_transfer' onchange='adjust_form()'> Petty cash</input>
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
		<p style='font-family:museo' id='bank_name_p'></p>
		<p style='font-family:museo' id='transaction_value_p'></p>
		<p style='font-family:museo' id='transaction_type_p'></p>
		
		<label>Opponent</label>
		<p style='font-family:museo' id='opponent_name_p'></p>
		
		<button type='button' class='button button_default_dark' id='submitTransactionButton'><i class='fa fa-long-arrow-right'></i></button>
	</div>
</div>
<script>
	function adjust_form(){
		if($('#petty_cash_transfer').prop('checked') == true){
			$('#operational').hide();
			$('#transaction').val(2);
			$('#opponent_id').attr('required', false);
		} else {
			$('#operational').show();
			$('#transaction').val(1);
			$('#opponent_id').attr('required', true);
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
				$.each(opponents, function(index, opponent){
					var name		= opponent.name;
					var address		= opponent.address;
					var city		= opponent.city;
					var id			= opponent.id;
					$('#opponentTableContent').append("<tr><td><p id='opponent_name-" + id + "'>" + name + "</p><p>" + address + "</p><p>" + city + "</p></td><td><button type='button' class='button button_default_dark' onclick='add_opponent(" + id + ")' title='Choose " + name + "'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
					opponentCount++;
				});

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
	
	function add_opponent(n, type){
		var opponent_name = $('#opponent_name-' + n).html();
		$('#opponent_id').val(n);
		$('#opponent_selector_view').html(opponent_name);
		$('.alert_full_close_button').click();
	}

	$("#accountButton").click(function(){
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
				var name = response.name;
				var number = response.number;
				$('#accountButton').text(name + " - " + number);
				$('#account').val(n);

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