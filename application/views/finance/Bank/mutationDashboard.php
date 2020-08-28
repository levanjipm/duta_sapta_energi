<head>
	<title>Bank - Mutation</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-briefcase'></i></a> <a href='<?= site_url('Bank') ?>'>Bank</a> / Mutation</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Account</label>
		<button type='button' class='form-control' id='accountButton' style='text-align:left!important'></button>
		<input type='hidden' id='account' name='account' required>
		<label>Current balance</label>
		<p id='currentBalance_p'>Rp. 0.00</p>
		<br>
		<div class='input_group'>
			<input type='date' class='form-control' value='<?= date('Y-m-d') ?>' id='date_1'>
			<input type='date' class='form-control' value='<?= date('Y-m-d') ?>' id='date_2'>
			<div class='input_group_append'>
				<button type='button' class='button button_default_dark' id='search_transaction_button'><i class='fa fa-search'></i></button>
			</div>
		</div>
		<br>
		<div id='mutationTable' style='display:none'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Opponent</th>
					<th>Debit</th>
					<th>Credit</th>
					<th>Balance</th>
				</tr>
				<tbody id='mutationTableContent'></tbody>
			</table>
			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='mutationTableText'>There is no mutation found.</p>
	</div>
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

<script>
	$('#page').change(function(){
		refresh_view();
	});
	
	$('#search_transaction_button').click(function(){
		refresh_view(1);
	});

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
				getCurrentBalance(n);

				$('#bankAccountWrapper').fadeOut();
			}
		})
	}
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Bank/getMutation') ?>',
			data:{
				page:page,
				account:$('#account').val(),
				start:$('#date_1').val(),
				end:$('#date_2').val()
			},
			success:function(response){
				var pages			= response.pages;
				$('#page').html('');
				$('#mutationTableContent').html('');
				
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				};

				var final_balance = 0;
				var balance_array	= response.balance;
				$.each(balance_array, function(index, balance){
					if(balance.transaction == 1){
						final_balance 	+= parseFloat(balance.value);
					} else {
						final_balance	-= parseFloat(balance.value);
					}
				});

				current_balance		= final_balance;
				
				var mutationCount = 0;
				var transactions = response.mutations;
				$.each(transactions, function(index, transaction){
					var date		= transaction.date;
					var id			= transaction.id;
					var value		= transaction.value;
					var type		= transaction.transaction;
					var name		= transaction.name;
					
					if(type == 1){
						current_balance	+= parseFloat(value);
						$('#mutationTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Rp. " + numeral(0).format('0,0.00') + "</td><td>Rp. " + numeral( ).format('0,0.00') + "</td></tr>");
					} else {
						current_balance -= parseFloat(value);
						$('#mutationTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Rp. " + numeral(0).format('0,0.00') + "</td><td>Rp. " + numeral(current_balance).format('0,0.00') + "</td></tr>");
					}
					mutationCount++;			
				});

				if(mutationCount > 0){
					$('#mutationTable').show();
					$('#mutationTableText').hide();
				} else {
					$('#mutationTable').hide();
					$('#mutationTableText').show();
				}
			}
		});
	}

	function getCurrentBalance(accountId){
		$.ajax({
			url:"<?= site_url('Bank/getCurrentBalance') ?>",
			data:{
				id: accountId
			},
			success:function(response){
				$('#currentBalance_p').html("Rp. " + numeral(response).format('0,0.00'));
			}
		})
	}

	$('.alert_full_close_button').click(function(){
		$(this).parent().parent().fadeOut();
	})
</script>
