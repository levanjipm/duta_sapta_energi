<head>
	<title>Billing - Delete</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Administrators') ?>' title='Administrators'><i class='fa fa-briefcase'></i></a> /Delivery Order / Delete</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<select class='form-control' id='month'>
			<?php for($i = 1; $i <= 12; $i++){ ?>
				<option value='<?= $i ?>' <?= ($i == date('m')) ? "selected" : '' ?>><?= date('F', mktime(0,0,0,$i, 1, date("Y"))) ?></option>
			<?php } ?>
			</select>
			<select class='form-control' id='year'>
			<?php for($i = 2020; $i <= date("Y"); $i++){ ?>
				<option value='<?= $i ?>'><?= $i ?></option>
			<?php } ?>
			</select>
		</div>
		<div id='billingTable'>
			<br>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Collector</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
				<tbody id='billingTableContent'></tbody>
			</table>
			<br>
			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='billingTableText'>There is no billing found.</p>
	</div>
</div>

<div class='alert_wrapper' id='billingWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Delete Billing</h3>
		<hr>
		<label>Billing</label>
		<P id='billingNameP'></p>
		<p id='billingDateP'></p>
		<p id='billingCollectorP'></p>
		
		<table class='table table-bordered'>
			<tr>
				<th>Customer</th>
				<th>Invoice</th>
				<th>Value</th>
			</tr>
			<tbody id='billingItemTableContent'></tbody>
		</table>

		<button class='button button_danger_dark' onclick='confirmDeleteBilling()'><i class='fa fa-trash'></i></button>
	</div>
</div>

<div class='alert_wrapper' id='deleteBillingWrapper'>
	<div class='alert_box_confirm_wrapper'>
		<div class='alert_box_confirm_icon'><i class='fa fa-trash'></i></div>
		<div class='alert_box_confirm'>
			<h3>Delete confirmation</h3>
			
			<p>You are about to delete this data.</p>
			<p>Are you sure?</p>
			<button class='button button_default_dark' onclick="$('#deleteBillingWrapper').fadeOut()">Cancel</button>
			<button class='button button_danger_dark' onclick='deleteBilling()'>Delete</button>
			
			<br><br>
			
			<p style='font-family:museo;background-color:#f63e21;width:100%;padding:5px;color:white;position:relative;bottom:0;left:0;opacity:0' id='errorDeleteBilling'>Deletation failed.</p>
		</div>
	</div>
</div>

<script>
	var deleteId		= null;

	$(document).ready(function(){
		refreshView();
	});

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Billing/GetArchive') ?>",
			data:{
				page: page,
				month: $('#month').val(),
				year: $('#year').val()
			},
			success:function(response){
				var items		= response.items;
				var itemCount	= 0;
				$('#billingTableContent').html("");
				$.each(items, function(index, item){
					var id			= item.id;
					var date		= item.date;
					var name		= item.name;
					var collector	= item.billed_by;
					var isConfirm	= item.is_confirm;
					var isReported	= item.is_reported;
					if(isReported == 1){
						$('#billingTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td>" + collector + "</td><td>Reported</td><td></td></tr>");
					} else if(isConfirm == 1 && isReported == 0) {
						$('#billingTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td>" + collector + "</td><td>Confirmed</td><td><button class='button button_default_dark' onclick='viewBilling(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
					} else if(isConfirm == 0){
						$('#billingTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td>" + collector + "</td><td>Not Confirmed</td><td></td></tr>");
					}
					itemCount++;
				})
				var pages		= response.pages;
				$('#page').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>")
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}					
				}

				if(itemCount > 0){
					$("#billingTable").show();
					$('#billingTableText').hide();
				} else {
					$('#billingTable').hide();
					$("#billingTableText").show();
				}
			}
		})
	}

	function viewBilling(id){
		$.ajax({
			url:"<?= site_url('Billing/getById') ?>",
			data:{
				id: id
			},
			success:function(response){
				deleteId = id;
				var general		= response.general;
				var name		= general.name;
				var date		= general.date;
				var collector	= general.billed_by;
				
				$('#billingNameP').html(name);
				$('#billingDateP').html(my_date_format(date));
				$('#billingCollectorP').html(collector);

				$('#billingItemTableContent').html("");
				var items		= response.items;
				$.each(items, function(index, item){
					var customerName		= item.customerName;
					var city				= item.city;
					var name				= item.name;
					var value				= item.value;

					$('#billingItemTableContent').append("<tr><td>" + customerName + ", " + city + "</td><td>" + name + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td></tr>");
				});
			},
			complete:function(){
				$('#billingWrapper').fadeIn(300, function(){
					$('#billingWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function confirmDeleteBilling()
	{
		$('#deleteBillingWrapper').fadeIn();
	}

	function deleteBilling(){
		$.ajax({
			url:"<?= site_url('Administrators/cancelBillingById') ?>",
			data:{
				id: deleteId
			},
			type:"POST",
			beforeSend:function(){
				$('button').attr('disabled', true);
			}, 
			success:function(response){
				$('button').attr('disabled', false);
				refreshView();
				if(response == 1){
					deliveryOrderId = null;
					$('#deleteBillingWrapper').fadeOut();
					$('#billingWrapper .slide_alert_close_button').click();
				} else {
					$('#errorDeleteBilling').fadeTo(250, 1);
					setTimeout(function(){
						$('#errorDeleteBilling').fadeTo(250, 0);
					}, 1000);
				}
			}
		})
	}
</script>
