<head>
	<title>Billing - Report</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-usd'></i></a> / Report Billing</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div id='reportBillingTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Collector</th>
					<th>Action</th>
				</tr>
				<tbody id='reportBillingTableContent'></tbody>
			</table>
		</div>
		<p id='reportBillingTableText'>There is no incompleted billing report.</p>
	</div>
</div>

<div class='alert_wrapper' id='billingWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<label>Billing</label>
		<p id='billingDate_p'></p>
		<p id='billingName_p'></p>
		<img style='width:40px;height:40px;border-radius:50%' id='profileImageUrl'> <span id='billedBy_p'></span><br><br>
		<form id='billingReportForm'>
			<input type='hidden' id='billingId' name='id' required>
			<div class='table-responsive-md'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Customer</th>
					<th>Value</th>
					<th>Result</th>
				</tr>
				<tbody id='billingItemTable'></tbody>
			</table>
			</div>
			<button class='button button_default_dark' type='button' onclick='submitBillingReport()'><i class='fa fa-long-arrow-right'></i></button>
			<div class='notificationText danger' id='failedUpdateReport'><p>Failed to update file report.</p></div>
		</form>
	</div>
</div>

<script>
	$(document).ready(function(){
		refreshView();
	});

	$('#billingReportForm').validate();

	function refreshView(){
		$.ajax({
			url:"<?= site_url('Billing/getIncompletedItems') ?>",
			success:function(response){
				$('#reportBillingTableContent').html("");
				var countBilling = 0;
				$.each(response, function(index, item){
					var name = item.name;
					var id = item.id;
					var date = item.date;
					var billed_by = item.billed_by;
					if(item.image_url == null){
						var imageUrl = "<?= base_url() . '/assets/ProfileImages/defaultImage.png' ?>";
					} else {
						var imageUrl = "<?= base_url() . '/assets/ProfileImages/' ?>" + item.image_url;
					}

					$('#reportBillingTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td><img src='" + imageUrl + "' style='width:30px;height:30px;border-radius:50%;'>" + billed_by + "</td><td><button type='button' onclick='viewBilling(" + id + ")'' class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
					countBilling++;
				});

				if(countBilling > 0){
					$('#reportBillingTable').show();
					$('#reportBillingTableText').hide();
				} else {
					$('#reportBillingTable').hide();
					$('#reportBillingTableText').show();
				}
			}
		})
	}

	function submitBillingReport(){
		if($('#billingReportForm').valid()){
			$.ajax({
				url:"<?= site_url('Billing/fileReport') ?>",
				data:$("#billingReportForm").serialize(),
				type:"POST",
				beforeSend:function(){
					$('button').attr('disabled', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					refreshView();
					if(response == 1){
						$('#billingWrapper .slide_alert_close_button').click();
					} else {
						$('#failedUpdateReport').fadeIn(250, function(){
							setTimeout(function(){
								$('#failedUpdateReport').fadeOut(250);
							}, 1000);
						})
					}
				}
			})
		}
	}

	function viewBilling(n){
		$.ajax({
			url:"<?= site_url("Billing/getById") ?>",
			data:{
				id:n
			},
			success:function(response){
				var general = response.general;
				if(general.is_reported == 0){
					$('#billingId').val(n);
					var id = general.id;
					var date = general.date;
					var name = general.name;
					var created_by = general.created_by;
					var billed_by = general.billed_by;

					if(general.image_url == null){
						var imageUrl = "<?= base_url() . '/assets/ProfileImages/defaultImage.png' ?>";
					} else {
						var imageUrl = "<?= base_url() . '/assets/ProfileImages/' ?>" + general.image_url;
					}

					$('#billingDate_p').html(my_date_format(date));
					$("#billingName_p").html(name);
					$('#profileImageUrl').attr('src', imageUrl);
					$('#billedBy_p').html(billed_by);

					var items = response.items;
					console.log(items);
					$('#billingItemTable').html("");
					$.each(items, function(index, item){
						var date = item.date;
						var name = item.name;
						var billingId = item.id;
						var value = parseFloat(item.value);
						var paid = parseFloat(item.paid);
						var remainder = value - paid;
						var customerName = item.customerName;
						var customerAddress = item.address;
						var complete_address = item.address;
						var customer_number = item.number;
						var customer_block = item.block;
						var customer_rt = item.rt;
						var customer_rw = item.rw;
						var customer_city = item.city;
						var customer_postal = item.postal_code;
				
						if(customer_number != null){
							complete_address	+= ' No. ' + customer_number;
						}
				
						if(customer_block != null && customer_block != "000"){
							complete_address	+= ' Blok ' + customer_block;
						}
			
						if(customer_rt != '000'){
							complete_address	+= ' RT ' + customer_rt;
						}
				
						if(customer_rw != '000' && customer_rt != '000'){
							complete_address	+= ' /RW ' + customer_rw;
						}
				
						if(customer_postal != null){
							complete_address	+= ', ' + customer_postal;
						}
						$('#billingItemTable').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td><label>" + customerName + "</label><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td>Rp. " + numeral(remainder).format('0,0.00') + "</td><td><label>Result</label><select class='form-control' name='result[" + billingId + "]'><option value='0'>Failed</option><option value='1'>Succeded</option><option value='2'>Not visited</option></select><label>Note</label><textarea class='form-control' name='note[" + billingId + "]' style='resize:none' rows='4'></textarea><label>Next Billing Date</label><input type='date' class='form-control' name='nextBillingDate[" + billingId + "]'></tr>");
					})
					

					$('#billingWrapper').fadeIn(300, function(){
						$('#billingWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
					});
				} else {
					refreshView();
				}
			}
		})
	}
</script>
