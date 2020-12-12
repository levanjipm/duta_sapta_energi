<head>
	<title>Income Report</title>
	<style>
		.progressBarWrapper{
			width:100%;
			height:30px;
			background-color:white;
			border-radius:10px;
			padding:5px;
			position:relative;
		}

		.progressBar{
			width:0;
			height:20px;
			background-color:#01bb00;
			position:relative;
			border-radius:10px;
			cursor:pointer;
			opacity:0.4;
			transition:0.3s all ease;
		}

		.progressBar:hover{
			opacity:1;
			transition:0.3s all ease;
		}

		.progressBarWrapper p{
			font-family:museo;
			color:black;
			font-weight:700;
			z-index:50;
			position:absolute;
			right:10px;
		}
	</style>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Income') ?>' title='Income'><i class='fa fa-usd'></i></a>/ Income/ Report</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='row'>
			<div class='col-md-2 col-sm-3 col-xs-4'>
				<select class='form-control' id='month'>
<?php
	for($i = 1; $i <= 12; $i++){
		if($i == date('m')){
			$selected = 'selected';
		} else {
			$selected = '';
		}
?>
					<option value='<?= $i ?>' <?= $selected ?>><?= date('F', mktime(1,1,1,$i,1)) ?></option>
<?php
	}
?>
				</select>
			</div>
			<div class='col-md-2 col-sm-3 col-xs-4'>
				<select class='form-control' id='year'>
<?php
	for($i = 2020; $i <= date("Y"); $i++){
?>
					<option value='<?= $i ?>'><?= $i ?></option>
<?php
	}
?>
				</select>
			</div>
		</div>
		<div class='row'>
			<div class='col-sm-12'>
				<br>
				<table class='table table-bordered'>
					<tr>
						<th>Name</th>
						<th>Description</th>
						<th>Value</th>
					</tr>
					<tbody id='incomeTableContent'></tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='viewDetailWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Income detail</h3>
		<hr>
		<div class='row'>
			<div class='col-sm-12'>
				<div class='table-responsive-md'>
					<table class='table table-bordered'>
						<tr>
							<th>Name</th>
							<th>Description</th>
							<th>Value</th>
						</tr>
						<tbody id='incomeDetailTableContent'></tbody>
					</table>
				</div>
			</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		refresh_view();
	});

	$('#month').change(function(){
		refresh_view();
	});

	$('#year').change(function(){
		refresh_view();
	})
	
	function refresh_view(){
		$.ajax({
			url:"<?= site_url('Income/getItemsByClass') ?>",
			data:{
				month: $('#month').val(),
				year: $('#year').val(),
			},
			success:function(response){
				var totalExpense = 0;
				$('#incomeTableContent').html("");
				groupNameArray = [];
				$.each(response, function(index, value){
					var groupId = value.id;
					var groupExpense = parseFloat(value.value);
					var groupName = value.name;
					var groupDescription = value.description;
					groupNameArray[groupId] = groupName;

					$('#incomeTableContent').append("<tr><td>" + groupName + "</td><td>" + groupDescription + "</td><td>Rp. " + numeral(groupExpense).format('0,0.00') + "<div class='progressBarWrapper'><p id='progressBarText-" + groupId + "'></p><div class='progressBar' data-value='" + groupExpense + "' id='progressBar-" + groupId + "'></div></td></tr>");
					totalExpense += groupExpense;
				});

				$('#incomeTableContent').append("<tr><td colspan='2'><strong>Total</strong></td><td>Rp. " + numeral(totalExpense).format('0,0.00') + "</td></tr>");

				$('.progressBar').each(function(){
					var id = $(this).attr('id');
					var uid = parseInt(id.substring(12, 267));
					var percentage = 100 * parseFloat($(this).attr('data-value')) / totalExpense;
					$('#progressBarText-' + uid).html(numeral(percentage).format('0,0.00') + "%");
					$(this).animate({
						width:percentage + "%"
					}, 1000);

					$(this).click(function(){
						getIncomeByClassId(uid, groupNameArray[uid]);
					});
				});
			}
		});
	}

	function getIncomeByClassId(id, parentName){
		$.ajax({
			url:"<?= site_url('Income/getByClassId') ?>",
			data:{
				month: $('#month').val(),
				year: $('#year').val(),
				id: id
			},
			success:function(response){
				var totalValue = 0;
				$('#incomeDetailTableContent').html("");
				$.each(response, function(index, item){
					var date = item.date;
					var value = item.value;
					var accountName = item.accountName;
					var accountNumber = item.accountNumber;
					$('#incomeDetailTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><p>" + accountName+ "</p><p>" + accountNumber + "</p><td>Rp. " + numeral(value).format('0,0.00') + "</td></tr>");
					totalValue += value;
				});
				$('#incomeDetailTableContent').append("<tr><td colspan='2'><label>Total</label></td><td>Rp. " + numeral(totalValue).format('0,0.00') + "</td></tr>");

				$('#viewDetailWrapper').fadeIn(300, function(){
					$('#viewDetailWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}
</script>
