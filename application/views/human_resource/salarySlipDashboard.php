<head>
	<title>Salary Slip</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Human_resource') ?>' title='Human resource'><i class='fa fa-briefcase'></i></a> /Salary slip</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<button class='button button_default_dark' id='createSalarySlipButton'><i class='fa fa-plus'></i> Create salary slip</button>
		<br><br>
		<p>Last 10 salary slips</p>
		<hr style='border-bottom:2px solid #2b2f38'>
		<table class='table table-bordered'>
			<tr>
				<th>Period</th>
				<th>User</th>
				<th>Salary</th>
				<th>Benefit</th>
				<th>Total</th>
				<th>Action</th>
			</tr>
			<tbody id='salary_table'></tbody>
		</table>
	</div>
</div>

<div class='alert_wrapper' id='createSalaryWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Create salary slip</h3>
		<hr>
		<form id='salarySlipForm'>
			<label>User</label>
			<button type='button' class='form-control' id='userButton' style='text-align:left!important'></button>
			<input type='hidden' id='user' required>

			<label>Period</label>
			<div class='input_group'>
				<input type='number' class='form-control' id='month' name='month' min='1' max='12' required placeholder='Month'>
				<input type='number' class='form-control' id='year' name='year' min='2020' maxlength='4' minlength='4' required placeholder='Year'>
			</div>

			<div class='notificationText warning' id='existSalarySlipNotification'><p><i class='fa fa-exclamation-triangle'></i> Salary slip already exist.</p></div>
		</form>
		<br>
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
					<td>Bonus</td>
					<td><input type='number' class='form-control' id='bonus' name='bonus'>
				</tr>
				<tr>
					<td colspan='2'></td>
					<td>Deduction</td>
					<td><input type='number' class='form-control' id='deduction' name'deduction'>
				</tr>
				<tr><td colspan='2'></td><td>Total</td><td>Rp. <span id='attendanceWage'></span></td></tr>
			</table>

			<button type='button' class='button button_default_dark' id='addBenefitButton'><i class='fa fa-plus'></i> Add benefit</button>
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

			<button type='button' class='button button_default_dark' onclick='createSalarySlip()'><i class='fa fa-long-arrow-right'></i></button>
		</div>
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
	$('#salarySlipForm').validate({
		ignore: '',
		rules: {"hidden_field": "required"}
	});

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
					$.each(items, function(index, value){
						var id = value.id;
						var name = value.name;
						var count = value.count;
						var itemCount = 0;
						if(count == 0){
							$('#attendanceTableContent').append("<tr><td>" + name + "</td><td>" + numeral(count).format("0,0") + "</td><td>Rp. 0.00</td><td>Rp. 0.00</tr>");
						} else if(count > 0){
							$('#attendanceTableContent').append("<tr><td>" + name + "</td><td>" + numeral(count).format("0,0") + "</td><td><input class='form-control' id='value-" + id + "'></td><td id='totalWage-" + id + "' required min='0'></td></tr>");
							itemCount++;
						}

						if(itemCount > 0){
							$("#attendanceTable").show();
						} else {
							$('#attendanceTable').hide();
						}
					
					});
				} else {
					$('#existSalarySlipNotification').fadeIn();
					setTimeout(function(){
						$('#existSalarySlipNotification').fadeOut();
					}, 1000)
				}
			}
		})	
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
							$('#salaryBenefitTableContent').append("<tr id='benefit-" + id + "'><td>" + name + "</td><td>" + description + "</td><td><input type='number' class='form-control' id='salaryBenefit-" + id + "' name='benefit[" + id + "]' required min='1'></td><td><button class='button button_danger_dark' onclick='removeBenefit(" + id + ")'><i class='fa fa-trash'></i></button></td></tr>");
						}

						if($("#salaryBenefitTableContent tr").length == 0){
							$('#salaryBenefitTable').hide();
						} else {
							$('#salaryBenefitTable').show();
						}

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

	$('#createSalarySlipButton').click(function(){
		$('#attendanceTable').hide();
		$('#salarySlipForm')[0].reset();

		$('#salaryBenefitTableContent').html("");
		$('#salaryBenefitTable').hide();
		
		$("#userButton").html("");
		$('#createSalaryWrapper').fadeIn(300, function(){
			$('#createSalaryWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	})
</script>
