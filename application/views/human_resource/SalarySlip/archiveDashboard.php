<head>
	<title>Salary Slip - Archive</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Human_resource') ?>' title='Human resource'><i class='fa fa-briefcase'></i></a> /Salary slip / Archive</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<select class='form-control' id='month'>
				<option <?php if(date('m') == 1 ){ echo "selected"; } ?> value='1'>January</option>
				<option <?php if(date('m') == 2 ){ echo "selected"; } ?> value='2'>February</option>
				<option <?php if(date('m') == 3 ){ echo "selected"; } ?> value='3'>March</option>
				<option <?php if(date('m') == 4 ){ echo "selected"; } ?> value='4'>April</option>
				<option <?php if(date('m') == 5 ){ echo "selected"; } ?> value='5'>May</option>
				<option <?php if(date('m') == 6 ){ echo "selected"; } ?> value='6'>June</option>
				<option <?php if(date('m') == 7 ){ echo "selected"; } ?> value='7'>July</option>
				<option <?php if(date('m') == 8 ){ echo "selected"; } ?> value='8'>August</option>
				<option <?php if(date('m') == 9 ){ echo "selected"; } ?> value='9'>September</option>
				<option <?php if(date('m') == 10 ){ echo "selected"; } ?> value='10'>October</option>
				<option <?php if(date('m') == 11 ){ echo "selected"; } ?> value='11'>November</option>
				<option <?php if(date('m') == 12 ){ echo "selected"; } ?> value='12'>December</option>
			</select>
			<select class='form-control' id='year'>
				<?php for($i = 2020; $i <= date('Y'); $i++){ ?>
				<option value='<?= $i ?>' <?php if($i == date('Y')){ echo "selected"; } ?>><?= $i ?></option>
				<?php }  ?>
			</select>
		</div>
		<br>
		<div id='salaryTable'>
			<table class='table table-bordered'>
				<tr>
					<th>User</th>
					<th>Action</th>
				</tr>
				<tbody id='salaryTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='salaryTableText'>There is no salary slip found.</p>
	</div>
</div>

<div class='alert_wrapper' id='viewSalarySlipWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Salary Slip Archive</h3>
		<hr>
		<label>User</label>
		<p id='userName_p'></p>
		<p id='userEmail_p'></p>

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

		<button class='button button_danger_dark' type='button' onclick='deleteSalarySlip()'><i class='fa fa-trash'></i></button>

		<div class='notificationText danger' id='failedDeleteSalarySlip'><p>Failed to delete salary slip.</p></div>
	</div>
</div>
<script>
	var salarySlipId;

	$(document).ready(function(){
		refreshView();
	});

	$('#month').change(function(){
		refreshView(1);
	});

	$('#year').change(function(){
		refreshView(1);
	});

	$('#page').change(function(){
		refreshView();
	})
	function refreshView($page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Salary_slip/getItems') ?>",
			data:{
				month: $('#month').val(),
				year: $('#year').val(),
				page: $('#page').val()
			},
			success:function(response){
				var items = response.items;
				var itemCount = 0;
				$('#salaryTableContent').html("");
				$.each(items, function(index, item){
					var name = item.name;
					var image_url = (item.image_url == null) ? '<?= base_url() . "/assets/ProfileImages/defaultImage.png" ?>' : item.image_url;
					var id		= item.id;
					$('#salaryTableContent').append("<tr><td><img src='" + image_url + "' style='width:30px;height:30px;border-radius:50%'/> " + name + "</td><td><button class='button button_default_dark' onclick='viewSalarySlip(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
					itemCount++;
				});

				if(itemCount > 0){
					$('#salaryTable').show();
					$('#salaryTableText').hide();
				} else {
					$('#salaryTable').hide();
					$('#salaryTableText').show();
				}

				var pages = response.pages;
				$('#page').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$("#page").append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		});
	}

	function viewSalarySlip(n){
		$.ajax({
			url:"<?= site_url('Salary_slip/getById') ?>",
			data:{
				id: n
			},
			success:function(response){
				salarySlipId	= n;
				var general		= response.general;
				var basic		= parseFloat(general.basic);
				var bonus		= parseFloat(general.bonus);
				var deduction	= parseFloat(general.deduction);

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
			},
			complete: function(){
				$('#viewSalarySlipWrapper').fadeIn(300, function(){
					$('#viewSalarySlipWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function deleteSalarySlip(){
		if(salarySlipId != null){
			$.ajax({
				url:"<?= site_url('Salary_slip/deleteById') ?>",
				data:{
					id: salarySlipId
				},
				type:"POST",
				beforeSend:function(){
					$('button').attr('disabled', true);
					$('input').attr('readonly', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					$('input').attr('readonly', false);
					refreshView();
					if(response == 1){
						$('#viewSalarySlipWrapper .slide_alert_close_button').click();
					} else {
						$('#failedDeleteSalarySlip').fadeIn(250);
						setTimeout(function(){
							$('#failedDeleteSalarySlip').fadeOut(250);
						}, 1000);
					}
				}
			})
		}
	}
</script>
