<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> / Bank/ Assign bank</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Transaction</label>
		<select class='form-control' id='transaction'>
			<option value='1'>Credit</option>
			<option value='2'>Debit</option>
		</select>
		
		<label>Account</label>
		<button class='form-control' id='accountButton' style='text-align:left!important'></button>
		<input type='hidden' id='account' name='account'>
		<br>
		<div id='transactionTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Value</th>
					<th>Opponent</th>
					<th>Action</th>
				</tr>
				<tbody id='transactionTableContent'></tbody>
			</table>
			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>

		<p id='transactionTableText'>There is no unassigned bank data.</p>
	</div>
</div>
<form action='<?= site_url('Bank/assign_do') ?>' method='POST' id='assign_bank_form'>
	<input type='hidden' id='transaction_id' name='id'>
</form>

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
	refresh_view();
	
	$('#account').change(function(){
		refresh_view(1);
	});
	
	$('#page').change(function(){
		refresh_view();
	});
	
	$('#transaction').change(function(){
		refresh_view(1);
	});
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Bank/view_unassigned_data/accounting') ?>',
			data:{
				type:$('#transaction').val(),
				page:page,
				account:$('#account').val()
			},
			type:'GET',
			success:function(response){
				var pages 			= response.pages;
				var bank_data		= response.banks;

				var transactionCount = 0;
				$('#transactionTableContent').html('');
				$('#page').html('');
				$.each(bank_data, function(index, data){
					var id		= data.id;
					var date	= data.date;
					var value	= data.value;
					var name	= data.name;
					$('#transactionTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>" + name + "</td><td><button type='button' class='button button_default_dark' onclick='assign_bank_data(" + id + ")'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
					transactionCount++;
				});

				if(transactionCount > 0){
					$('#transactionTable').show();
					$('#transactionTableText').hide();
				} else {
					$('#transactionTable').hide();
					$('#transactionTableText').show();
				}
				
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				};
			}
		});
	}
	
	function assign_bank_data(n){
		$('#transaction_id').val(n);
		$('#assign_bank_form').submit();
	}

	$('#accountButton').click(function(){
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
				refresh_view();
			}
		})
	}
</script>