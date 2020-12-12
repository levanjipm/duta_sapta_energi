<head>
	<title>Salary Slip</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Human_resource') ?>' title='Human resource'><i class='fa fa-user'></i></a> /Salary slip</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<form id='salarySlipForm'>
			<label>User</label>
			<button type='button' class='form-control' id='userButton' style='text-align:left!important'></button>
			<input type='hidden' id='user' name='user' required>

			<label>Period</label>
			<div class='input_group'>
				<input type='number' class='form-control' id='month' name='month' min='1' max='12' required placeholder='Month'>
				<input type='number' class='form-control' id='year' name='year' min='2020' maxlength='4' minlength='4' required placeholder='Year'>
			</div>

			<div class='notificationText warning' id='existSalarySlipNotification'><p><i class='fa fa-exclamation-triangle'></i> Salary slip already exist.</p></div>
		</form>
		<br>
		<form id='salarySlipDetailForm'>
			<div id='attendanceTable' style='display:none'>
				<table class='table table-bordered'>
					<tr>
						<th>Information</th>
						<th>Total</th>
						<th>Wage</th>
						<th>Total</th>
					</tr>
					<tbody id='attendanceTableContent'></tbody>
					<tr>
						<td colspan='2'></td>
						<td>Basic</td>
						<td><input type='number' class='form-control' id='basic' name='basic' required min='0'>
					</tr>
					<tr>
						<td colspan='2'></td>
						<td>Bonus</td>
						<td><input type='number' class='form-control' id='bonus' name='bonus' required min='0'>
					</tr>
					<tr>
						<td colspan='2'></td>
						<td>Deduction</td>
						<td><input type='number' class='form-control' id='deduction' name='deduction' required min='0'>
					</tr>
					<tr><td colspan='2'></td><td>Total</td><td>Rp. <span id='attendanceWage'></span></td></tr>
				</table>

				<button type='button' class='button button_default_dark' id='addBenefitButton'><i class='fa fa-plus'></i> Add benefit</button><br><br>
				<div id='salaryBenefitTable' style='display:none'>
					<table class='table table-bordered'>
						<tr>
							<th>Name</th>
							<th>Description</th>
							<th>Value</th>
							<th>Action</th>
						</tr>
						<tbody id='salaryBenefitTableContent'></tbody>
					</table>
				</div>
				<br>

				<button type='button' class='button button_default_dark' onclick='validateSalarySlip()'><i class='fa fa-long-arrow-right'></i></button>

				<div class='notificationText danger' id='failedInsertSalarySlip'><p>Failed to insert salary slip.</p></div>
			</div>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='validateSalarySlipWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Create Salary Slip</h3>
		<hr>
		<label>User</label>
		<p id='userName_p'></p>
		<p id='userEmail_p'></p>

		<label>Salary</label>
		<table class='table table-bordered'>
			<tr>
				<th>Information</th>
				<th>Unit Wage</th>
				<th>Quantity</th>
				<th>Total Wage</th>
			</tr>
			<tbody id='salarySlipDetailTableContent'></tbody>
		</table>

		<div id='benefitTablePreview'>
			<label>Benefits Detail</label>
			<table class='table table-bordered'>
				<tr>
					<th>Name</th>
					<th>Description</th>
					<th>Value</th>
				</tr>
				<tbody id='benefitTablePreviewContent'></tbody>
			</table>
		</div>

		<button class="button button_default_dark" onclick='submitSalarySlip()' type='button'><i class='fa fa-long-arrow-right'></i></button>

		<div class='notificationText danger' id='failedInsertNotification'><p>Failed to insert salary slip.</p></div>
	</div>
</div>

<div class='alert_wrapper' id='userWrapper'>
	<div class='alert_box_full'>
		<button type='button' class='button alert_full_close_button' title='Close user session'>&times;</button>
		<h3 style='font-family:bebasneue'>Choose user</h3>
		<hr>
		<input type='text' class='form-control' id='userSearchBar'><br>

		<div id='userTable'>
			<table class='table table-bordered'>
				<tr>
					<th>User</th>
					<th>Action</th>
				</tr>
				<tbody id='userTableContent'></tbody>
			</table>

			<select class='form-control' id='userPage' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='userTableText'>There is no user found.</p>
	</div>
</div>

<div class='alert_wrapper' id='benefitWrapper'>
	<div class='alert_box_full'>
		<button type='button' class='button alert_full_close_button' title='Close benefit session'>&times;</button>
		<h3 style='font-family:bebasneue'>Insert Benefit</h3>
		<hr>
		<input type='text' class='form-control' id='benefitSearchBar'><br>

		<div id='benefitTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Name</th>
					<th>Description</th>
					<th>Action</th>
				</tr>
				<tbody id='benefitTableContent'></tbody>
			</table>

			<select class='form-control' id='benefitPage' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='benefitTableText'>There is no benefit found.</p>
	</div>
</div>

<script>
	var totalValueArray = [];
	var salaryValueArray = [];
	var benefitArray = [];

	$('#salarySlipForm').validate({
		ignore: '',
		rules: {"hidden_field": "required"}
	});

	$('#salarySlipDetailForm').validate();

	$('#userButton').click(function(){
		$('#userSearchBar').val("");
		refreshUsers(1);
		$('#userWrapper').fadeIn();
	});

	$('#userSearchBar').change(function(){
		refreshUsers(1);
	})

	$('#userPage').change(function(){
		refreshUsers();
	});

	$('#benefitSearchBar').change(function(){
		refreshBenefit(1);
	});

	$('#benefitPage').change(function(){
		refreshBenefit();
	})

	$('.alert_full_close_button').click(function(){
		$(this).parent().parent().fadeOut();
	});

	$('#month').change(function(){
		if($('#salarySlipForm').valid()){
			getAttendanceList();
		}
	})

	$('#year').change(function(){
		if($('#salarySlipForm').valid()){
			getAttendanceList();
		}
	})

	$('#user').change(function(){
		if($('#salarySlipForm').valid()){
			getAttendanceList();
		}
	})

	function getAttendanceList(user = $('#user').val(), month = $('#month').val(), year = $('#year').val()){
		$.ajax({
			url:'<?= site_url('Attendance/getItemSalary') ?>',
			data:{
				user: user,
				month: month, 
				year: year
			},
			type:"POST",
			success:function(response){
				$('#attendanceTableContent').html("");
				var status = response.status;
				if(status == 1){
					var items = response.items;
					var itemCount = 0;
					$.each(items, function(index, value){
						var id = value.id;
						var name = value.name;
						var count = parseInt(value.count);
						if(count == 0){
							$('#attendanceTableContent').append("<tr><td>" + name + "</td><td>" + numeral(count).format("0,0") + "</td><td>Rp. 0.00</td><td>Rp. 0.00</tr>");
							salaryValueArray[id] = {"name": name, "count": 0, "value": 0};
						} else if(count > 0){
							$('#attendanceTableContent').append("<tr><td>" + name + "</td><td>" + numeral(count).format("0,0") + "</td><td><input class='form-control' id='value-" + id + "' name='value[" + id + "]'  required min='0'></td><td id='totalWage-" + id + "'></td></tr>");
								$('#value-' + id).change(function(){
									var totalValue = parseInt(count) * $(this).val();
									salaryValueArray[id] = {"name": name, "count": count, "value": totalValue};
									$('#totalWage-' + id).html("Rp. " + numeral(totalValue).format('0,0.00'));
									calculateWage();
								})
							itemCount += count;
						}
					});

					if(itemCount > 0){
						$("#attendanceTable").show();
					} else {
						$('#attendanceTable').hide();
					}
				} else {
					$('#existSalarySlipNotification').fadeIn();
					setTimeout(function(){
						$('#existSalarySlipNotification').fadeOut();
					}, 1000)
				}
			}
		})	
	}

	$('#basic').change(function(){
		totalValueArray["basic"] = parseFloat($(this).val());
		calculateWage();
	})

	$('#bonus').change(function(){
		totalValueArray["bonus"] = parseFloat($(this).val());
		calculateWage();
	})

	$('#deduction').change(function(){
		totalValueArray["deduction"] = (-1) * parseFloat($(this).val());
		calculateWage();
	})

	function calculateWage(){
		var attendanceWage = 0;
		for (var key in salaryValueArray) {
			attendanceWage += Math.max(0, parseFloat(salaryValueArray[key].value));
		}

		var basic		= Math.max(0, parseFloat(totalValueArray['basic']));
		var bonus		= Math.max(0, parseFloat(totalValueArray['bonus']));
		var deduction	= Math.min(0, parseFloat(totalValueArray['deduction']));
			
		var otherWage = basic + bonus + deduction;
		console.log(otherWage);
		var wage = attendanceWage + otherWage;

		$('#attendanceWage').html(numeral(wage).format("0,0.00"));
	}

	function refreshUsers(page = $('#userPage').val()){
		$.ajax({
			url:"<?= site_url('Users/getItems') ?>",
			data:{
				page: $('#userPage').val(),
				term: $('#userSearchBar').val()
			},
			success:function(response){
				var items = response.users;
				$('#userTableContent').html("");
				var userCount = 0;
				$.each(items, function(index, item){
					var id = item.id;
					var name = item.name;
					if(item.image_url == null){
						var imageUrl = "<?= base_url() . '/assets/ProfileImages/defaultImage.png' ?>";
					} else {
						var imageUrl = "<?= base_url() . '/assets/ProfileImages/' ?>" + item.image_url;
					}

					$('#userTableContent').append("<tr><td><img src='" + imageUrl + "' style='width:30px;height:30px;border-radius:50%'> " + name + "</td><td><button class='button button_default_dark' id='userSelectButton-" + id + "'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
					$('#userSelectButton-' + id).click(function(){
						$('#userButton').html(name);
						$("#user").val(id);
						$('#userWrapper .alert_full_close_button').click();
						if($('#salarySlipForm').valid()){
							getAttendanceList();
						}
					});
					userCount++;
				});

				if(userCount > 0){
					$('#userTable').show();
					$('#userTableText').hide();
				} else {
					$('#userTable').hide();
					$('#userTableText').show();
				}

				var pages = response.pages;
				$('#userPage').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#userPage').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#userPage').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		})
	}

	function refreshBenefit(page = $("#benefitPage").val()){
		$.ajax({
			url:"<?= site_url('Benefits/getItems') ?>",
			data:{
				page: page,
				term: $('#benefitSearchBar').val()
			},
			success:function(response){
				var items = response.benefits;
				var benefitCount = 0;
				$('#benefitTableContent').html("");
				$.each(items, function(index, item){
					var name = item.name;
					var description = item.information;
					var id = item.id;
					$('#benefitTableContent').append("<tr><td>" + name + "</td><td>" + description + "</td><td><button class='button button_default_dark' id='addBenefitButton-" + id + "' title='Select " + name + "'><i class='fa fa-long-arrow-right'></i></button></td></tr>");

					$('#addBenefitButton-' + id).click(function(){
						if($('#benefit-' + id).length == 0){
							$('#salaryBenefitTableContent').append("<tr id='benefit-" + id + "'><td>" + name + "</td><td>" + description + "</td><td><input type='number' class='form-control' id='salaryBenefit-" + id + "' name='benefit[" + id + "]' required min='1'></td><td><button class='button button_danger_dark' type='button' onclick='removeBenefit(" + id + ")'><i class='fa fa-trash'></i></button></td></tr>");
						}

						benefitArray[id] = {"name": name, "description": description, "value": 0};

						if($("#salaryBenefitTableContent tr").length == 0){
							$('#salaryBenefitTable').hide();
						} else {
							$('#salaryBenefitTable').show();
						}

						$('#salaryBenefit-' + id).change(function(){
							benefitArray[id].value = $('#salaryBenefit-' + id).val();
						})

						$('#benefitWrapper .alert_full_close_button').click();
					})
					benefitCount++;
				});

				if(benefitCount > 0){
					$('#benefitTable').show();
					$("#benefitTableText").hide();
				} else {
					$('#benefitTable').hide();
					$("#benefitTableText").show();
				}
				var pages = response.pages;
				$('#benefitPage').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#benefitPage').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#benefitPage').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		})
	}

	function removeBenefit(n){
		$('#benefit-' + n).remove();
		benefitArray[n] = null;

		if($("#salaryBenefitTableContent tr").length == 0){
			$('#salaryBenefitTable').hide();
		} else {
			$('#salaryBenefitTable').show();
		}
	}

	$('#addBenefitButton').click(function(){
		$('#searchBenefitBar').val("");
		refreshBenefit(1);
		$('#benefitWrapper').fadeIn();
	})

	function validateSalarySlip(){
		$('#salarySlipDetailTableContent').html("");
		var attendanceWage = 0;
		for (var key in salaryValueArray) {
			var name= salaryValueArray[key].name;
			var totalValue = salaryValueArray[key].value;
			var count = salaryValueArray[key].count;
			var unitValue = totalValue / count;

			$('#salarySlipDetailTableContent').append("<tr><td>" + name + "</td><td>Rp. " + numeral(unitValue).format('0,0.00') + "</td><td>" + count + "</td><td>Rp. " + numeral(totalValue).format('0,0.00'));
			attendanceWage += parseFloat(totalValue);
		}

		var basic = totalValueArray['basic'];
		var bonus = totalValueArray['bonus'];
		var deduction = totalValueArray['deduction'];

		$('#salarySlipDetailTableContent').append("<tr><td>Basic wage</td><td>Rp. " + numeral(basic).format('0,0.00') + "</td><td>1</td><td>Rp. " + numeral(basic).format('0,0.00'));
		$('#salarySlipDetailTableContent').append("<tr><td>Bonus wage</td><td>Rp. " + numeral(bonus).format('0,0.00') + "</td><td>1</td><td>Rp. " + numeral(bonus).format('0,0.00'));
		$('#salarySlipDetailTableContent').append("<tr><td>Wage deduction</td><td>Rp. " + numeral(deduction).format('0,0.00') + "</td><td>1</td><td>Rp. " + numeral(deduction).format('0,0.00'));

		$('#benefitTablePreviewContent').html("");
		if(benefitArray.length > 0){
			var benefitCountTable = 0;
			for(var key in benefitArray){
				if(benefitArray[key] != null){
					var name			= benefitArray[key].name;
					var description		= benefitArray[key].description;
					var value			= benefitArray[key].value;
					var id				= benefitArray[key].id;
					$('#benefitTablePreviewContent').append("<tr><td>" + name + "</td><td>" + description + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td></tr>");
				}
			}
		}

		if($('#benefitTablePreviewContent tr').length > 0){
			$('#benefitTablePreview').show();
		} else {
			$('#benefitTablePreview').hide();
		}

		$('#validateSalarySlipWrapper').fadeIn(300, function(){
			$('#validateSalarySlipWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	}

	function submitSalarySlip(){
		if($('#salarySlipForm').valid() && $('#salarySlipDetailForm').valid()){
			var data = $('#salarySlipDetailForm').serialize() + "&" + $('#salarySlipForm').serialize();
			console.log(data);
			$.ajax({
				url:"<?= site_url('Salary_slip/insertItem') ?>",
				data:data,
				type:"POST",
				beforeSend:function(){
					$('button').attr('disabled', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					if(response == 0){
						$('#failedInsertNotification').fadeIn(250);
						setTimeout(function(){
							$("#failedInsertNotification").fadeOut(250);
						}, 1000);
					} else {
						location.reload();
					}
				}
			})
		}
	}
</script>
