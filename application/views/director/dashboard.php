<head>
    <title>Directors</title>
    <style>
		.dashboardBox{
            padding:15px;
            box-shadow:3px 3px 3px 3px rgba(50,50,50,0.3);
            border-radius:5px;
            margin-bottom:20px;
			cursor:pointer;
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

        .progressBarWrapper{
			width:100%;
			height:20px;
			background-color:#ccc;
			z-index:10;
			position:relative;
			border-radius:5px;
		}

		.progressBar{
			width:0;
			height:20px;
			position:absolute;
			top:0;
			left:0;
			z-index:20;
			border-radius:5px;
		}

		#overdueARprogress{
			background-color:#E19B3C;
		}

		#ARprogress{
			background-color:#01BB00;
		}

		#overdueAPprogress{
			background-color:#E19B3C;
		}

		#APprogress{
			background-color:#01BB00;
		}

		.button_info{
			border-radius:50%;
			background-color:#5bc0de;
			border:none;
			outline:none!important;
			color:white;
			padding:2px;
			font-size:18px;
			width:24px;
			height:24px;
			line-height:1px;
		}
	</style>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
</head>
<div class='dashboard' style='padding-top:100px'>
    <div class='dashboard_in'>
		<div class='row'>
			<div class='col-xs-12'>
				<button class='button button_mini_tab' id='generalButton'><i class='fas fa-database'></i> General Information</button>
				<button class='button button_mini_tab' id='incomeButton'><i class='fas fa-file-contract'></i> Income Statement</button>
				<button class='button button_mini_tab' id='balanceButton'><i class='fas fa-balance-scale'></i> Balance Sheet</button>
			</div>
		</div>
		<div class='viewWrapper' id='generalInformation' style='display:none'>
			<div class='row' style='padding-top:20px'>
				<div class='col-md-12 col-sm-12 col-xs-12'>
					<table class='table table-bordered'>
						<tr>
							<th style='width:30%'>Properties</th>
							<th>Value</th>
							<th style='width:40%'>Information</th>
						</tr>
						<tr>
							<td>Current Ratio</td>
							<td><?= number_format($ratio, 2) ?></td>
							<td><p><strong>A ratio value lower than 1</strong> may indicate liquidity problems for the company, though the company may still not face an extreme crisis if it's able to secure other forms of financing. <strong>A ratio over 3</strong> may indicate that the company is not using its current assets efficiently or is not managing its working capital properly.</p></td>
						</tr>
						<tr>
							<td>Cash on Hand</td>
							<td>Rp. <?= number_format($petty + $bank, 2) ?></td>
							<td><p><strong>Current cash</strong> in bank account or petty cash.</p></td>
						</tr>
						<tr>
							<td>Receivable</td>
							<td><p id='AR'>Rp. 0.00</p></td>
							<td><p> - </p></td>
						</tr>
						<tr>
							<td>Payable</td>
							<td><p id='AP'>Rp. 0.00</p></td>
							<td><p> - </p></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class='viewWrapper' id='incomeStatement' style='display:none'>
			<div class='row' style='padding-top:20px'>
				<div class='col-md-12 col-sm-12 col-xs-12'>
					<label>Period</label>
					<div class='input_group'>
						<select class='form-control' id='incomeMonth'>
							<option value='0'>Select all year</option>
						<?php for($i = 1; $i <= 12; $i++){ ?>
							<option value='<?= $i ?>'><?= date("F", mktime(0,0,0,$i, 1, 2020)) ?></option>
						<?php } ?>
						</select>
						<select class='form-control' id='incomeYear'>
						<?php for($i = 2020; $i <= date("Y"); $i++) { ?>
							<option value='<?= $i ?>'><?= $i ?></option>
						<?php } ?>
						</select>
						<div class='input_group_append'>
							<button class='button button_default_dark' onclick='getIncomeStatement()'><i class='fas fa-long-arrow-alt-right'></i></button>
						</div>
					</div>
				</div>

				<div class="col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
					<table class="table table-bordered">
						<tr>
							<td>Sales</td>
							<td>Rp. <span id='salesP'></span></td>
						</tr>
						<tr>
							<td>Purchase</td>
							<td>Rp. <span id='purchaseP'></span></td>
						</tr>
						<tr>
							<td>Stock Change</td>
							<td>Rp. <span id='stockChangeP'></span></td>
						</tr>
						<tr>
							<td><label>Gross Operational Profit</label></td>
							<td>Rp. <span id='grossOperationalProfitP'></span></td>
						</tr>
						<tr>
							<td>Other sales (Operational)</td>
							<td>Rp. <span id='operationalSalesP'></p>
						</tr>
						<tr>
							<td>Other sales ( Non - operational )</td>
							<td>Rp. <span id='nonOperationalSalesP'></p>
						</tr>
						<tr>
							<td>Other purchase (Operational)</td>
							<td>Rp. <span id='operationalPurchaseP'></p>
						</tr>
						<tr>
							<td>Other purchase ( Non - operational )</td>
							<td>Rp. <span id='nonOperationalPurchaseP'></p>
						</tr>
						<tr>
							<td><label>Gross Other Profit</label></td>
							<td>Rp. <span id='grossOtherProfitP'></span></td>
						</tr>
						<tr>
							<td><label>Gross Total Profit</label></td>
							<td>Rp. <span id='grossTotalProfitP'></span></td>
						</tr>
						<tr>
							<td>Income</td>
							<td>Rp. <span id='incomeP'></p></td>
						</tr>
						<tr>
							<td>Expense</td>
							<td>Rp. <span id='expenseP'></p></td>
						</tr>
						<tr>
							<td><label>Net Profit Before Tax</label></td>
							<td>Rp. <span id='netProfitBeforeTaxP'></span></p>
						</tr>
						<tr>
							<td><label>Tax</label></td>
							<td>Rp. <span id='taxP'></span></p>
						</tr>
						<tr>
							<td><label>Net Profit</label></td>
							<td>Rp. <span id='netProfitAfterTaxP'></span></p>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class='viewWrapper' id='balanceStatement' style='display:none'>
			<div class='row' style='padding-top:20px'>
				<div class='col-md-12 col-sm-12 col-xs-12'>
					<label>Period</label>
					<div class='input_group'>
						<select class='form-control' id='balanceMonth'>
							<option value='0'>Select all year</option>
						<?php for($i = 1; $i <= 12; $i++){ ?>
							<option value='<?= $i ?>'><?= date("F", mktime(0,0,0,$i, 1, 2020)) ?></option>
						<?php } ?>
						</select>
						<select class='form-control' id='balanceYear'>
						<?php for($i = 2020; $i <= date("Y"); $i++) { ?>
							<option value='<?= $i ?>'><?= $i ?></option>
						<?php } ?>
						</select>
						<div class='input_group_append'>
							<button class='button button_default_dark' onclick='getBalanceStatement()'><i class='fas fa-long-arrow-alt-right'></i></button>
						</div>
					</div>
					<br>
					<label>Assets</label>
					<table class='table table-bordered'>
						<tr>
							<td><label>Cash on Hand</label></td>
							<td id='cashOnHandP'>Rp. 0.00</p>
						</tr>
						<tr>
							<td><label>Cash on Account</label></td>
							<td id='cashOnAccountP'>Rp. 0.00</p>
						</tr>
						<tr>
							<td><label>Receivable</label></td>
							<td id='receivableP'>Rp. 0.00</p>
						</tr>
						<tr>
							<td><label>Inventory Value</label></td>
							<td id='inventoryP'>Rp. 0.00</p>
						</tr>
						<tr>
							<td>Fixed Assets</td>
							<td id='assetsP'>Rp. 0.00</p>
						</tr>
						<tr>
							<td>Fixed Assets' depreciation</td>
							<td id='depreciationP'>Rp. 0.00</p>
						</tr>
						<tr>
							<td><label>Next Fixed Assets' value</label></td>
							<td id='assetsValueP'>Rp. 0.00</p>
						</tr>
						<tr>
							<td><label>Assets Value</label></td>
							<td id='totalAssetsValueP'>Rp. 0.00</td>
					</table>
					<label>Liabilities</label>
					<p id='debtP'>Rp. 0.00</p>

					<label>Equity</label>
					<p id='equityP'>Rp. 0.00</p>
				</div>
			</div>
		</div>
    </div>
</div>

<div class='alert_wrapper' id='cashOnAccountWrapper'>
	<h3 style='font-family:bebasneue'>Cash On Accounts</h3>
	<hr>
	<label>Other Operational Sales</label>
	<table class='table table-bordered' id='cashOnAccountTable'>
		<tr>
			<th>Type</th>
			<th>Value</th>
		</tr>
		<tbody id='cashOnAccountTableContent'></tbody>
	</table>

	<p id='cashOnAccountTableText'>There is no balance data found.</p>
</div>

<div class='alert_wrapper' id='otherSalesWrapper'>
	<h3 style='font-family:bebasneue'>Other Sales</h3>
	<hr>
	<label>Other Operational Sales</label>
	<table class='table table-bordered' id='otherOperationalSalesTable'>
		<tr>
			<th>Type</th>
			<th>Value</th>
		</tr>
		<tbody id='otherOperationalSalesTableContent'></tbody>
	</table>

	<p id='otherOperationalSalesTableText'>There is no sales found.</p>
	
	<label>Other Non Operational Sales</label>
	<table class='table table-bordered' id='otherNonOperationalSalesTable'>
		<tr>
			<th>Type</th>
			<th>Value</th>
		</tr>
		<tbody id='otherNonOperationalSalesTableContent'></tbody>
	</table>

	<p id='otherNonOperationalSalesTableText'>There is no sales found.</p>
</div>

<div class='alert_wrapper' id='otherPurchaseWrapper'>
	<h3 style='font-family:bebasneue'>Other Purchase</h3>
	<hr>
	<label>Other Operational Purchase</label>
	<table class='table table-bordered' id='otherOperationalPurchaseTable'>
		<tr>
			<th>Type</th>
			<th>Value</th>
		</tr>
		<tbody id='otherOperationalPurchaseTableContent'></tbody>
	</table>

	<p id='otherOperationalPurchaseTableText'>There is no purchase found.</p>
	
	<label>Other Non Operational Purchase</label>
	<table class='table table-bordered' id='otherNonOperationalPurchaseTable'>
		<tr>
			<th>Type</th>
			<th>Value</th>
		</tr>
		<tbody id='otherNonOperationalPurchaseTableContent'></tbody>
	</table>

	<p id='otherNonOperationalPurchaseTableText'>There is no purchase found.</p>
</div>

<div class='alert_wrapper' id='incomeWrapper'>
	<h3 style='font-family:bebasneue'>Income</h3>
	<hr>
	<table class='table table-bordered' id='incomeTable'>
		<tr>
			<th>Type</th>
			<th>Value</th>
		</tr>
		<tbody id='incomeTableContent'></tbody>
	</table>

	<p id='incomeTableText'>There is no income found.</p>
</div>

<div class='alert_wrapper' id='expenseWrapper'>
	<h3 style='font-family:bebasneue'>Expense</h3>
	<hr>
	<table class='table table-bordered' id='expenseTable'>
		<tr>
			<th>Type</th>
			<th>Value</th>
		</tr>
		<tbody id='expenseTableContent'></tbody>
	</table>

	<p id='expenseTableText'>There is no expense found.</p>
</div>

<div class='alert_wrapper' id='taxWrapper'>
	<h3 style='font-family:bebasneue'>Tax Expense</h3>
	<hr>
	<table class='table table-bordered' id='taxTable'>
		<tr>
			<th>Type</th>
			<th>Value</th>
		</tr>
		<tbody id='taxTableContent'></tbody>
	</table>

	<p id='taxTableText'>There is no tax found.</p>
</div>

<script>
	$(document).ready(function(){
		$('#generalButton').click();
		getReceivable();
		getPayable();
	});

	$('#generalButton').click(function(){
		$('.viewWrapper').fadeOut(250);
		setTimeout(function(){
			$('#generalInformation').fadeIn(250);
		}, 250)
	});
	$('#incomeButton').click(function(){
		$('.viewWrapper').fadeOut(250);
		setTimeout(function(){
			$('#incomeStatement').fadeIn(250);
		}, 250)
	});
	$('#balanceButton').click(function(){
		$('.viewWrapper').fadeOut(250);
		setTimeout(function(){
			$('#balanceStatement').fadeIn(250);
		}, 250)
	});
	$('.button_mini_tab').click(function(){
		$('.button_mini_tab').removeClass('active');
		$('.button_mini_tab').attr('disabled', false);
		$(this).addClass('active');
		$(this).attr('disabled', true);
	})

	function getReceivable(){
		$.ajax({
			url:"<?= site_url('Accounting/getInvoices') ?>",
			success:function(response){;
				var total			= parseFloat(response.due) + parseFloat(response.notDue);
				$('#AR').html("Rp. " + numeral(total).format('0,0.00'));
			}
		})
	}

	function getPayable(){
		$.ajax({
			url:"<?= site_url('Accounting/getPayable') ?>",
			success:function(response){
				var total			= parseFloat(response.due) + parseFloat(response.notDue);
				$('#AP').html("Rp. " + numeral(total).format('0,0.00'));
			}
		})
	}

	function getIncomeStatement(){
		$.ajax({
			url:"<?= site_url('Director/getIncomeStatement') ?>",
			data:{
				month: $('#incomeMonth').val(),
				year: $('#incomeYear').val()
			},
			success:function(response){
				var sales			= parseFloat(response.sales);
				var purchase		= parseFloat(response.purchase);
				var stockChange		= parseFloat(response.stockChange);

				var salesReturn		= parseFloat(response.salesReturn);
				var purchaseReturn	= parseFloat(response.purchaseReturn);

				$('#salesP').html(numeral(sales - salesReturn).format('0,0.00'));
				$('#purchaseP').html(numeral(purchase - purchaseReturn).format('0,0.00'));
				$('#stockChangeP').html(numeral(stockChange).format('0,0.00'));

				var grossOperationalProfit		= sales + stockChange - purchase;
				$('#grossOperationalProfitP').html(numeral(grossOperationalProfit).format('0,0.00'));

				var taxExpense		= 0;
				var taxCount		= 0;
				$('#taxTableContent').html("");

				var expense			= response.expense;
				var expenseValue 	= 0;
				var expenseCount	= 0;
				$('#expenseTableContent').html("");

				$.each(expense, function(index, item){
					var value	= item.value;
					var type	= item.type;

					if(type == 1 || type == 2){
						expenseValue += parseFloat(value);
						$('#expenseTableContent').append("<tr><td>" + item.name + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td></tr>");
						expenseCount++;
					} else if(type == 3){
						taxExpense += parseFloat(value);
						$('#taxTableContent').append("<tr><td>" + item.name + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td></tr>");
						taxCount++;
					}
				});

				$('#taxP').html(numeral(taxExpense).format('0,0.00'));

				if(expenseCount > 0){
					$('#expenseTable').show();
					$('#expenseTableText').hide();
				} else {
					$('#expenseTableText').show();
					$('#expenseTable').hide();
				}

				if(taxCount > 0){
					$('#taxTable').show();
					$('#taxTableText').hide();
				} else {
					$('#taxTableText').show();
					$('#taxTable').hide();
				}

				$('#expenseP').html(numeral(expenseValue).format('0,0.00'));

				var income			= response.income;
				var incomeValue		= 0;
				var incomeCount	= 0;
				$('#incomeTableContent').html("");

				$.each(income, function(index, item){
					var value	= item.value;
					incomeValue += parseFloat(value);
					$('#incomeTableContent').append("<tr><td>" + item.name + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td></tr>");	
					incomeCount++;
				});

				if(incomeCount > 0){
					$('#incomeTable').show();
					$('#incomeTableText').hide();
				} else {
					$('#incomeTableText').show();
					$('#incomeTable').hide();
				}

				$('#incomeP').html(numeral(incomeValue).format('0,0.00'));

				var otherSales		= response.otherSales;

				var operationalSales	= 0;
				var operationalSalesCount = 0;
				$('#otherOperationalSalesTableContent').html("");

				var nonOperationalSales	 = 0;
				var nonOperationalSalesCount = 0;
				$('#otherNonOperationalSalesTableContent').html("");

				$.each(otherSales, function(index, item){
					var name			= item.name;
					var is_operational	= item.is_operational;
					var value			= parseFloat(item.value);
					if(is_operational == 1){
						$('#otherOperationalSalesTableContent').append("<tr><td>" + name + "</td><td>Rp. " + numeral(value).format('0,0.00'));
						operationalSales += value;
						operationalSalesCount++;
					} else {
						$('#otherNonOperationalSalesTableContent').append("<tr><td>" + name + "</td><td>Rp. " + numeral(value).format('0,0.00'));
						nonOperationalSales += value;
						nonOperationalSalesCount++;
					}
				});

				if(operationalSalesCount > 0){
					$('#otherOperationalSalesTable').show();
					$('#otherOperationalSalesTableText').hide();
				} else {
					$('#otherOperationalSalesTable').hide();
					$('#otherOperationalSalesTableText').show();
				}

				if(nonOperationalSalesCount > 0){
					$('#otherNonOperationalSalesTable').show();
					$('#otherNonOperationalSalesTableText').hide();
				} else {
					$('#otherOperationalSalesTable').hide();
					$('#otherNonOperationalSalesTableText').show();
				}

				$('#operationalSalesP').html(numeral(operationalSales).format('0,0.00'));
				$('#nonOperationalSalesP').html(numeral(nonOperationalSales).format('0,0.00'));

				var otherPurchase	= response.otherPurchase;

				var operationalPurchase	= 0;
				var operationalPurchaseCount = 0;
				$('#otherOperationalPurchaseTableContent').html("");

				var nonOperationalPurchase	 = 0;
				var nonOperationalPurchaseCount = 0;
				$('#otherNonOperationalPurchaseTableContent').html("");

				$.each(otherPurchase, function(index, item){
					var name			= item.name;
					var is_operational	= item.is_operational;
					var value			= parseFloat(item.value);
					if(is_operational == 1){
						$('#otherOperationalPurchaseTableContent').append("<tr><td>" + name + "</td><td>Rp. " + numeral(value).format('0,0.00'));
						operationalPurchase += value;
						operationalPurchaseCount++;
					} else {
						$('#otherNonOperationalPurchaseTableContent').append("<tr><td>" + name + "</td><td>Rp. " + numeral(value).format('0,0.00'));
						nonOperationalPurchase += value;
						nonOperationalPurchaseCount++;
					}
				});

				if(operationalPurchaseCount > 0){
					$('#otherOperationalPurchaseTable').show();
					$('#otherOperationalPurchaseTableText').hide();
				} else {
					$('#otherOperationalPurchaseTable').hide();
					$('#otherOperationalPurchaseTableText').show();
				}

				if(nonOperationalPurchaseCount > 0){
					$('#otherNonOperationalPurchaseTable').show();
					$('#otherNonOperationalPurchaseTableText').hide();
				} else {
					$('#otherNonOperationalPurchaseTable').hide();
					$('#otherNonOperationalPurchaseTableText').show();
				}

				$('#operationalPurchaseP').html(numeral(operationalPurchase).format('0,0.00'));
				$('#nonOperationalPurchaseP').html(numeral(nonOperationalPurchase).format('0,0.00'));

				var grossOtherProfit = (operationalSales + nonOperationalSales) - (operationalPurchase + nonOperationalPurchase);
				$('#grossOtherProfitP').html(numeral(grossOtherProfit).format('0,0.00'));

				var grossTotalProfit	= grossOtherProfit + grossOperationalProfit;
				$('#grossTotalProfitP').html(numeral(grossTotalProfit).format('0,0.00'));

				var netProfit		= grossTotalProfit + incomeValue - expenseValue;
				$('#netProfitBeforeTaxP').html(numeral(netProfit).format('0,0.00'));

				var profit			= netProfit - taxExpense;
				$('#netProfitAfterTaxP').html(numeral(profit).format('0,0.00'));
			}
		})
	}

	function getBalanceStatement(){
		$.ajax({
			url:"<?= site_url('Director/getBalanceStatement') ?>",
			data:{
				month: $('#balanceMonth').val(),
				year: $('#balanceYear').val()
			},
			success:function(response){
				var cashOnHand		= parseFloat(response.cashOnHand);
				var cashOnAccount	= parseFloat(response.cashOnAccount);
				var receivable		= parseFloat(response.receivable);
				var assetValue		= parseFloat(response.assetValue);
				var depreciation	= parseFloat(response.depreciation);
				var stock			= parseFloat(response.stock);
				var assetInitialValue	= assetValue + depreciation;

				$('#cashOnAccountTable').html("");
				var cashOnAccountItem = 0;
				var cashOnAccountValue = 0;
				$.each(cashOnAccount, function(index, item){
					var name		= item.name;
					var credit		= item.credit;
					var debit		= item.debit;
					var balance		= credit - balance;
					$('#cashOnAccountTable').append("<tr><td>" + name + "</td><td>Rp. " + numeral(balance).format('0,0.00') + "</td></tr>");
					cashOnAccountValue += balance;
					cashOnAccountItem++;
				});

				var totalValue		= cashOnHand + cashOnAccountValue + receivable + assetValue + stock;

				if(cashOnAccountItem > 0){
					$('#cashOnAccountTable').show();
					$('#cashOnAccountTableText').hide();
				} else {
					$('#cashOnAccountTableText').show();
					$('#cashOnAccountTable').hide();
				}

				$('#cashOnHandP').html("Rp. " + numeral(cashOnHand).format('0,0.00'));
				$('#cashOnAccountP').html("Rp. " + numeral(cashOnAccountValue).format('0,0.00'));
				$('#receivableP').html("Rp. " + numeral(receivable).format('0,0.00'));
				$('#assetsValueP').html("Rp. " + numeral(assetValue).format('0,0.00'));
				$('#depreciationP').html("RP. " + numeral(depreciation).format('0,0.00'));
				$('#assetsP').html("Rp. " + numeral(assetInitialValue).format('0,0.00'));
				$('#inventoryP').html("Rp. " + numeral(stock).format('0,0.00'));

				$('#totalAssetsValueP').html("<strong>Rp. " + numeral(totalValue).format('0,0.00') + "</strong>");

				var debt		= response.debt;
				$('#debtP').html("Rp. " + numeral(debt).format('0,0.00'));

				var equity		= totalValue - debt;
				$('#equityP').html("Rp. " + numeral(equity).format('0,0.00'));
			}
		})
	}
</script>