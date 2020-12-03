<head>
	<title>Billing - Archive</title>
	<style>
		.archive_row{
			padding:10px;
			border-bottom:1px solid #e2e2e2;
		}
	</style>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-briefcase'></i></a> / Report Billing</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='row'>
			<div class='col-md-2 col-sm-3 col-xs-4'>
				<select class='form-control' id='month'>
<?php for($i = 1; $i <= 12; $i++){ ?>
					<option value='<?= $i ?>' <?php if($i == date('m')){ echo('selected');} ?>><?= date('F', mktime(0,0,0,$i, 1)) ?></option>
<?php } ?>
				</select>
			</div>
			<div class='col-md-2 col-sm-3 col-xs-4'>
				<select class='form-control' id='year'>
<?php for($i = 2020; $i <= date('Y'); $i++){ ?>
					<option value='<?= $i ?>' <?= ($i == date('Y')) ? 'selected': '' ?>><?= $i ?></option>
<?php } ?>
				</select>
			</div>
			<div class='col-xs-12'>
				<hr>
			</div>
		</div>
		<div class='row'>
			<div class='col-xs-12' id='archiveRow'></div>
		</div>
		<div class='row' style='margin-top:20px'>
			<div class='col-xs-12'>
				<select class='form-control' id='page' style='width:100px'>
					<option value='1'>1</option>
				</select>
			</div>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='billingWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<label>Billing</label>
		<p id='billingDate_p'></p>
		<p id='billingName_p'></p>
		<img style='width:40px;height:40px;border-radius:50%' id='profileImageUrl'> <span id='billedBy_p'></span><br><br>
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
	</div>
</div>

<script>
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

	function refreshView(page = $("#page").val()){
		$.ajax({
			url:"<?= site_url('Billing/GetArchive') ?>",
			data:{
				month: $("#month").val(),
				year: $("#year").val(),
				page: page
			},
			success:function(response){
				var items = response.items;
				var archiveCount = 0;
				$('#archiveRow').html("");
				$.each(items, function(index, item){
					var id = item.id;
					var date = item.date;
					var name = item.name;
					var billed_by = item.billed_by;
					var is_confirm = item.is_confirm;
					if(is_confirm == 0){
						$('#archiveRow').append("<div class='row archive_row'><div class='col-xs-4'><p><strong>" + name + "</strong></p></div><div  class='col-xs-4'><label>Billed by</label><p>" + billed_by + "</p></div><div  class='col-xs-4'><p style='display:inline-block'>" + my_date_format(date) + " <strong>|</strong> </p> <button type='button' class='button button_transparent' onclick='open_view(" + id + ")' title='View " + name + "'><i class='fa fa-eye'></i></button></div>");
						archiveCount++;
					} else {
						$('#archiveRow').append("<div class='row archive_row'><div class='col-xs-4'><p><strong>" + name + "</strong></p></div><div  class='col-xs-4'><label>Billed by</label><p>" + billed_by + "</p></div><div  class='col-xs-4'><p style='display:inline-block'>" + my_date_format(date) + " <strong>|</strong> </p> <button type='button' class='button button_transparent' onclick='open_view(" + id + ")' title='View " + name + "'><i class='fa fa-eye'></i></button> <button class='button button_verified'><i class='fa fa-check'></i></button></div>");
						archiveCount++;
					}
				});
				
				var button_width		= $('.button_verified').height();
				$('.button_verified').width(button_width);

				$('#page').html('');
				var pages		= response.pages;
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}

				if(archiveCount == 0){
					$('#archiveRow').html("<p>There is no archive Found.</p>");
				}
			}
		})
	}

	function open_view(n){
		$.ajax({
			url:"<?= site_url("Billing/getById") ?>",
			data:{
				id:n
			},
			success:function(response){
				var general = response.general;
				var id = general.id;
				var date = general.date;
				var name = general.name;
				var created_by = general.created_by;
				var billed_by = general.billed_by;

				if(general.image_url == null){
					var imageUrl = "<?= base_url() . '/assets/ProfileImages/defaultImage.png' ?>";
				} else {
					var imageUrl = "<?= base_url() . '/assets/ProfileImages/' ?>" + item.image_url;
				}

				$('#billingDate_p').html(my_date_format(date));
				$("#billingName_p").html(name);
				$('#profileImageUrl').attr('src', imageUrl);
				$('#billedBy_p').html(billed_by);

				var items = response.items;
				$('#billingItemTable').html("");
				$.each(items, function(index, item){
					console.log(item);
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
					var result = item.result;
					var note = item.note;
					var nextBillingDate = item.nextBillingDate;
					if(result == 0){
						var resultText = "Failed";
					} else if(result == 1){
						var resultText = "Succeded";
					} else if(result == 2){
						var resultText = "Not visited";
					}

					if(note == "" || note == null){
						var noteText = "<i>-</i>";
					} else {
						var noteText = note;
					}

					if(nextBillingDate == null){
						var nextBillingDateText = "<i>Not available</i>";
					} else {
						var nextBillingDateText = my_date_format(nextBillingDate);
					}
				
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

					$('#billingItemTable').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td><label>" + customerName + "</label><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td>Rp. " + numeral(remainder).format('0,0.00') + "</td><td><label>Result</label><p>" + resultText + "</p><label>Note</label><p>" + noteText + "</p><label>NextBillingDate</label><p>" + nextBillingDateText + "</p></td></tr>");
				})
					

				$('#billingWrapper').fadeIn(300, function(){
					$('#billingWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}
</script>
