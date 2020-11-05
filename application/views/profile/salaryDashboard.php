<head>
	<style>
		@media print{
			html, body{
				visibility:hidden;
			}

			#viewSalarySlipWrapper{
				position:absolute;
				top:0;
				left:0;
				width:100%;
				height:100%;
				visibility:visible;
				z-index:150;
				overflow-y:hidden;
				right:0;
			}

			#viewSalarySlipWrapper .alert_box_slide{
				width:100%!important;
				border-radius:0;
				box-shadow:0;
			}

			button{
				display:none;
			}

			#imageHeader{
				display:block;
			}
		}
	</style>
</head>
<div class='dashboard'>
	<div class='dashboard_in'>
		<label>Salary Slips</label>
		<div id='salarySlipTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Period</th>
					<th>Action</th>
				</tr>
				<tbody id='salarySlipTableContent'></tbody>
			</table>
		</div>
		<p id='salarySlipTableText'>There is no salary slip found.</p>

		<select class='form-control' id='page' style='width:100px'>
			<option value='1'>1</option>
		</select>
	</div>
</div>

<div class='alert_wrapper' id='viewSalarySlipWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<div class='row' id='imageHeader' style='display:none'>
			<div class='col-xs-4 col-xs-offset-4'>
				<img src='<?= base_url('assets/Logo_dark.png') ?>' style='width:40%;margin-left:30%'></img>
			</div>
			<div class='col-xs-12'>
				<hr style='border-top:4px solid #424242;margin:0;'>
				<hr style='border-top:2px solid #E19B3C;margin:0;'>
			</div>
		</div>
		<div class='row' style='color:#000'>
			<div class='col-xs-12'>
				<label>User</label>
				<p id='userName_p'></p>
				<p id='userEmail_p'></p>

				<label>Period</label>
				<p id='periodP'></p>

				<label>General Salary</label>
				<div class='table-responsive-lg'>
					<table class='table table-bordered'>	
						<tr>
							<th>Name</th>
							<th style='width:30%'>Information</th>
							<th>Value</th>
							<th>Count</th>
							<th>Total</th>
						</tr>
						<tbody id='salaryComponentTableContent'></tbody>
					</table>
				</div>
				
				<label>Benefit</label>
				<div id='benefitTable'>
					<table class='table table-bordered'>
						<tr>
							<th>Name</th>
							<th>Information</th>
							<th>Value</th>
						</tr>
						<tbody id='benefitTableContent'></tbody>
					</table>
				</div>
				<p id='benefitTableText'>There is no benefit found.</p>
				<button class='button button_default_dark' type='button' onclick='printSalarySlip()'><i class='fa fa-print'></i></button>
			</div>
		</div>
	</div>
</div>
<script>
	var monthArray		= ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
	$(document).ready(function(){
		refreshView();
	});

	$('#page').change(function(){
		refreshView();
	})

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Salary_slip/getArchiveByUserId') ?>",
			data:{
				id: <?= $this->session->userdata('user_id'); ?>,
				page: page
			},
			success:function(response){
				var items = response.items;
				var itemCount		= 0;
				$('#salarySlipTableContent').html("");
				$.each(items, function(index, value){
					var month		= value.month;
					var year		= value.year;
					var id			= value.id;

					var label		= monthArray[month - 1] + " " + year;
					$('#salarySlipTableContent').append("<tr><td>" + label + "</td><td><button class='button button_default_dark' onclick='viewSalarySlip(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");

					itemCount++;
				});

				if(itemCount > 0){
					$('#salarySlipTable').show();
					$('#salarySlipTableText').hide();
				} else {
					$('#salarySlipTable').hide();
					$('#salarySlipTableText').show();
				}

				var pages = response.pages;
				$('#page').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
					
				}
			}
		})
	}

	function viewSalarySlip(n){
		$.ajax({
			url:"<?= site_url('Salary_slip/getById') ?>",
			data:{
				id: n
			},
			success:function(response){
				var general		= response.general;
				var basic		= parseFloat(general.basic);
				var bonus		= parseFloat(general.bonus);
				var deduction	= parseFloat(general.deduction);

				var month		= parseInt(general.month);
				var year		= parseInt(general.year);

				$('#periodP').html(monthArray[month - 1] + " " + year);

				var absentee = response.absentee;
				$('#salaryComponentTableContent').html("");
				var generalWage = 0;
				$.each(absentee, function(index, value){
					var name = value.name;
					var description = value.description;
					var count = parseInt(value.count);
					var price = parseFloat(value.value);
					var totalPrice = count * price;
					generalWage		+= totalPrice;

					$('#salaryComponentTableContent').append("<tr><td>" + name + "</td><td>" + description + "</td><td>Rp. " + numeral(price).format('0,0.00') + "</td><td>" + numeral(count).format('0,0') + "</td><td>Rp. " + numeral(totalPrice).format('0,0.00') + "</td></tr>");
				});

				generalWage += (basic + bonus - deduction);

				$('#salaryComponentTableContent').append("<tr><td colspan='2'>Basic</td><td>Rp. " + numeral(basic).format('0,0.00') + "</td><td>1</td><td>Rp. " + numeral(basic).format('0,0.00') + "</td></tr>");
				$('#salaryComponentTableContent').append("<tr><td colspan='2'>Bonus</td><td>Rp. " + numeral(bonus).format('0,0.00') + "</td><td>1</td><td>Rp. " + numeral(bonus).format('0,0.00') + "</td></tr>");
				$('#salaryComponentTableContent').append("<tr><td colspan='2'>Deduction</td><td>Rp. " + numeral(deduction).format('0,0.00') + "</td><td>1</td><td>Rp. " + numeral(deduction).format('0,0.00') + "</td></tr>");

				var benefits = response.benefit;
				$('#benefitTableContent').html("");
				var benefitCount = 0;
				var benefitValue = 0;
				$.each(benefits, function(index, benefit){
					var name = benefit.name;
					var information = benefit.information;
					var value = parseFloat(benefit.value);
					benefitValue += value;

					if(value > 0){
						$('#benefitTableContent').append("<tr><td>" + name + "</td><td>" + information + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td></tr>");
						benefitCount++;
					}
				});

				if(benefitCount > 0){
					$('#salaryComponentTableContent').append("<tr><td colspan='2'>Benefits</td><td>Rp. " + numeral(benefitValue).format('0,0.00') + "</td><td>1</td><td>Rp. " + numeral(benefitValue).format('0,0.00') + "</td></tr>");
					generalWage += benefitValue;
					$('#benefitTable').show();
					$('#benefitTableText').hide();
				} else {
					$('#benefitTable').hide();
					$('#benefitTableText').show();
				}

				$('#salaryComponentTableContent').append("<tr><td colspan='2'>Grand Total</td><td>Rp. " + numeral(basic).format('0,0.00') + "</td><td>1</td><td>Rp. " + numeral(generalWage).format('0,0.00') + "</td></tr>");

				var user		= response.user;
				var username	= user.name;
				var email		= user.email;
				$('#userName_p').html(username);
				$('#userEmail_p').html(email);
			},
			complete: function(){
				$('#viewSalarySlipWrapper').fadeIn(300, function(){
					$('#viewSalarySlipWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function printSalarySlip()
	{
		window.print();
	}
</script>