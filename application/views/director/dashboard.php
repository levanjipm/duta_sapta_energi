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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
</head>
<div class='dashboard' style='padding-top:100px'>
    <div class='dashboard_in'>
		<div class='row'>
			<div class='col-xs-12'>
				<button class='button button_mini_tab' id='generalButton'><i class='fas fa-database'></i> General Information</button>
				<button class='button button_mini_tab' id='incomeButton'><i class='fas fa-file-contract'></i> Income Statement</button>
				<button class='button button_mini_tab' id='balanceButton'><i class='fas fa-balance-scale'></i> Balance Sheet</button>
				<button class='button button_mini_tab' id='cashButton'><i class='fas fa-chart-line'></i> Cash Flow</button>
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
				</div>
			</div>
		</div>
		<div class='viewWrapper' id='cashStatement' style='display:none'>
			<div class='row' style='padding-top:20px'>
				<div class='col-md-12 col-sm-12 col-xs-12'>
				</div>
			</div>
		</div>
    </div>
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
	$('#cashButton').click(function(){
		$('.viewWrapper').fadeOut(250);
		setTimeout(function(){
			$('#cashStatement').fadeIn(250);
		}, 250)
	})
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
				console.log(response);
			}
		})
	}
</script>