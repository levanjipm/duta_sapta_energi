<title>Reset bank</title>
<style>
	.boxDefault{
		display:inline-block;
		background-color:#E19B3C;
		width:20px;
		height:20px;
		border-radius:7px;
		border:none;
		cursor:pointer;
		opacity:0.3;
		transition:0.3s all ease;
	}

	.boxDefault:hover{
		opacity:1;
		transition:0.3s all ease;
	}

	.transactionRow{
		margin:0;
		margin-bottom:20px;
	}

	.transactionLine{
		position:absolute;
		width:10px;
		top:-20px;
		left:0;
		border-left:2px dashed #333;
		height:calc(100% + 20px);
	}
</style>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-bar-chart'></i></a> / Bank/ Reset bank</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<form id='resetBankForm'>
			<label>Assignments</label>
			<select class='form-control' id='transaction'>
				<option value='1'>Credit</option>
				<option value='2'>Debit</option>
			</select>
		
			<label>Account</label>
			<button type='button' class='form-control' id='accountButton' style='text-align:left!important'></button>
			<input type='hidden' id='account' name='account' required>

			<label>Date range</label>
			<div class='input_group'>
				<input type='date' class='form-control' id='dateStart' required min='2020-01-01'>
				<input type='date' class='form-control' id='dateEnd' required min='2020-01-01'>
			</div>
			<br>
			<button type='button' class='button button_default_dark' onclick='refresh_view(1)'><i class='fa fa-search'></i></button>
		</form>
		<br>
		<div id='transactionTable' style='display:none'>
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

<div class='alert_wrapper' id='resetBankWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<label>Bank Data</label>
		<p id='accountP'></p>
		<p id='accountNumberP'></p>

		<label>Transactions</label>
		<div style='position:relative'>
			<div id='treeTableContent'></div>
			<div id='treeTableLine'></div>
		</div>
	</div>
</div>

<form action="<?= site_url('Bank/resetBankForm') ?>" method="POST" id="bankTransactionForm">
	<input type='hidden' id='bankTransactionId' name='id'>
</form>

<script>
	$('#resetBankForm').validate();
	
	$('#page').change(function(){
		refresh_view();
	});

	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Bank/getAssignedTransactions') ?>',
			data:{
				type:$('#transaction').val(),
				page:page,
				account:$('#account').val(),
				dateStart: $('#dateStart').val(),
				dateEnd: $('#dateEnd').val()
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
					$('#transactionTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>" + name + "</td><td><button type='button' class='button button_default_dark' onclick='viewAssignmentBankData(" + id + ")'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
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
	
	function viewAssignmentBankData(n){
		$.ajax({
			url:"<?= site_url('Bank/viewAssignmentById') ?>",
			data:{
				id: n
			},
			success:function(response){
				$('#treeTableContent').html("");
				$.each(response, function(index, value){
					if(index == 0){
						var id		= value.id;
						var is_done	= value.is_done;
						var type	= (value.type == "" || value.type == null) ? "" : "Assigned as " + value.type;
						if(is_done == 1){
							$('#treeTableContent').append("<div class='row transactionRow'><div class='col-xs-6'><div class='boxDefault'></div> Rp. " + numeral(value.value).format('0,0.00') + "</div><div class='col-xs-4' style='text-align:right'>" + type + "</div><div class='col-xs-2'><button class='button button_default_dark' onclick='reset(" + id + ")'><i class='fa fa-history'></i></button></div></div><div style='margin-left:20px;position:relative;' id='major-" + id + "'><div class='transactionLine'></div></div>");
						} else {
							$('#treeTableContent').append("<div class='row transactionRow'><div class='col-xs-6'><div class='boxDefault'></div> Rp. " + numeral(value.value).format('0,0.00') + "</div><div class='col-xs-4' style='text-align:right'>" + type + "</div><div class='col-xs-2'></div></div><div style='margin-left:20px;position:relative;' id='major-" + id + "'><div class='transactionLine'></div></div>");
						}						
					} else {
						var level		= value.level;
						var id			= value.id;
						var isdone		= value.is_done;
						var parent_id	= value.bank_transaction_major;
						var margin		= ( 1 + parseInt(level)) * 10;
						var type		= (value.type == "") ? "" : "Assigned as " + value.type;

						if(isdone == 1){
							$('#major-' + parent_id).append("<div class='row transactionRow'><div class='col-xs-6'><div class='boxDefault'></div> Rp. " + numeral(value.value).format('0,0.00') + "</div><div class='col-xs-4' style='text-align:right'>" + type + "</div><div class='col-xs-2' style='text-align:right'><button class='button button_default_dark' onclick='reset(" + id + ")'><i class='fa fa-history'></i></button></div></div><div id='major-" + id + "' style='margin-left:" + margin + "px;position:relative;'></div>");
						} else {
							$('#major-' + parent_id).append("<div class='row transactionRow'><div class='col-xs-6'><div class='boxDefault'></div> Rp. " + numeral(value.value).format('0,0.00') + "</div><div class='col-xs-4' style='text-align:right'>" + type + "</div><div class='col-xs-2' style='text-align:right'></div></div><div id='major-" + id + "' style='margin-left:" + margin + "px;position:relative;'><div class='transactionLine'></div></div>");
						}
					}
				});

				$('#resetBankWrapper').fadeIn(300, function(){
					$('#resetBankWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function reset(n){
		$('#bankTransactionId').val(n);
		$('#bankTransactionForm').submit();
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

				$('#accountP').html(name);
				$('#accountNumberP').html(number);

				$('#account').val(n);

				$('#bankAccountWrapper').fadeOut();
			}
		})
	}

	$('.alert_full_close_button').click(function(){
		$(this).parent().parent().fadeOut();
	})
</script>
