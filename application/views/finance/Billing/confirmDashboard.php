<head>
	<title>Billing</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-briefcase'></i></a> / Confirm Billing</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div id='billingTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Collector</th>
					<th>Action</th>
				</tr>
				<tbody id='billingTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='billingTableText'>There is no billing to be confirmed.</p>
	</div>
</div>

<div class='alert_wrapper' id='confirmBillingWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Confirm Billing</h3>
		<hr>
		<label>Billing</label>
		<p id='billingName_p'></p>
		<p id='date_p'></p>
		<p>Created by <span id='createdBy_p'></span></p>

		<label>Collector</label><br>
		<img style='width:40px;height:40px;border-radius:50%' id='collectorImageUrl'> <span id='collectorName_p'></span><br><br>

		<label>Invoices</label>
		<table class='table table-bordered'>
			<tr>
				<th>Date</th>
				<th>Name</th>
				<th>Customer</th>
				<th>Value</th>
			</tr>
			<tbody id='billingItemTable'></tbody>
		</table>
	</div>
</div>

<script>
	$(document).ready(function(){
		refreshView();
	});

	$('#page').change(function(){
		refreshView();
	});

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Billing/getUnconfirmedBilling') ?>",
			data:{
				page: page
			},
			success:function(response){
				var items = response.items;
				$('#billingTableContent').html("");
				var billingCount = 0;
				$.each(items, function(index, item){
					var collector = item.collector;
					var id = item.id;
					var name = item.name;
					if(item.image_url == null){
						var imageUrl = "<?= base_url() . '/assets/ProfileImages/defaultImage.png' ?>"; 
					} else {
						var imageUrl = "<?= base_url() . '/assets/ProfileImages/' ?>" + item.image_url;
					}
					var date = item.date;
					$('#billingTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><img src='" + imageUrl + "' style='width:30px;height:30px;border-radius:50%'> " + name + "</td><td><button class='button button_default_dark' onclick='viewBilling(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
					billingCount++;
				})

				if(billingCount > 0){
					$('#billingTable').show();
					$('#billingTableText').hide();
				} else {
					$('#billingTable').hide();
					$('#billingTableText').show();
				}

				var pages = response.pages;
				$('#page').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$("#page").append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		})
	}

	function viewBilling(n){
		$.ajax({
			url:"<?= site_url('Billing/getById') ?>",
			data:{
				id:n
			},
			success:function(response){
				var general = response.general;
				var collectorName = general.billed_by;
				if(general.image_url == null){
					var imageUrl = "<?= base_url() . '/assets/ProfileImages/defaultImage.png' ?>"; 
				} else {
					var imageUrl = "<?= base_url() . '/assets/ProfileImages/' ?>" + general.image_url;
				}
				$('#collectorName_p').html(collectorName);
				$('#collectorImageUrl').attr('src', imageUrl);

				var date = general.date;
				var name = general.name;
				var createdBy = general.created_by;
				$('#date_p').html(my_date_format(date));
				$('#billingName_p').html(name);
				$('#createdBy_p').html(createdBy);

				var items = response.items;
				$('#billingItemTable').html("");
				$.each(items, function(index, item){
					var date = item.date;
					var name = item.name;
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

					$('#billingItemTable').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td><label>" + customerName + "</label><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td>Rp. " + numeral(remainder).format('0,0.00') + "</td></tr>");
				})

				$('#confirmBillingWrapper').fadeIn(300, function(){
					$('#confirmBillingWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}
</script>
