<head>
	<title>Sales return - Delete Dashboard</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Administrators') ?>' title='Administrator'><i class='fa fa-briefcase'></i></a> /Sales Return / Delete</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div id='salesReturnTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Customer</th>
					<th>Action</th>
				</tr>
				<tbody id='salesReturnTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='salesReturnTableText'>There is no confirmed sales return.</p>
	</div>
</div>

<div class='alert_wrapper' id='salesReturnWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Sales Return</h3>
		<hr>
		<label>Customer</label>
		<p style='font-family:museo' id='customerNameP'></p>
		<p style='font-family:museo' id='customerAddressP'></p>
		<p style='font-family:museo' id='customerCityP'></p>

		<label>Sales Return</label>
		<p style='font-family:museo' id='salesReturnNameP'></p>
		<p style='font-family:museo' id='salesReturnDateP'></p>

		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
			</tr>
			<tbody id='salesReturnItemTable'></tbody>
		</table>

		<button class="button button_danger_dark" onclick="confirmClose()"><i class='fa fa-trash'></i></button>
	</div>
</div>

<div class='alert_wrapper' id='deleteSalesReturnWrapper'>
	<div class='alert_box_confirm_wrapper'>
		<div class='alert_box_confirm_icon'><i class='fa fa-trash'></i></div>
		<div class='alert_box_confirm'>
			<input type='hidden' id='delete_customer_id'>
			<h3>Delete confirmation</h3>
			
			<p>You are about to delete this data.</p>
			<p>Are you sure?</p>
			<button class='button button_default_dark' onclick="$('#deleteSalesReturnWrapper').fadeOut()">Cancel</button>
			<button class='button button_danger_dark' onclick='deleteSalesReturn()'>Delete</button>
			
			<br><br>
			
			<p style='font-family:museo;background-color:#f63e21;width:100%;padding:5px;color:white;position:relative;bottom:0;left:0;opacity:0' id='errorDeleteSalesReturn'>Deletation failed.</p>
		</div>
	</div>
</div>
<script>
	var salesReturnId = null;
	$(document).ready(function(){
		refresh_view();
	})

	$('#page').change(function(){
		refresh_view();
	})

	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Sales_return/getConfirmedReceivedReturn') ?>',
			data:{
				page: page,
			},
			type:'GET',
			success:function(response){
				var pages = response.pages;
				$('#page').html('');
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>")
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>")
					}
				}

				var countSalesReturn = 0;
				var items		= response.items;
				$('#salesReturnTableContent').html("");
				$.each(items, function(index, item){
					var customerName		= item.customerName;
					var date				= item.date;
					var id					= item.id;
					var name				= item.name;
					$('#salesReturnTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td>" + customerName + "</td><td><button class='button button_default_dark' onclick='viewSalesReturn(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
					countSalesReturn++;
				});

				if(countSalesReturn > 0){
					$('#salesReturnTable').show();
					$('#salesReturnTableText').hide();
				} else {
					$('#salesReturnTable').hide();
					$('#salesReturnTableText').show();
				}
			}
		});
	}

	function viewSalesReturn(n){
		$.ajax({
			url:"<?= site_url('Sales_return/getReceivedById') ?>",
			data:{
				id: n
			},
			success:function(response){
				salesReturnId	= n;
				var general		= response.general;
				var name		= general.name;
				var date		= general.date;

				$('#salesReturnNameP').html(name);
				$('#salesReturnDateP').html(my_date_format(date));

				var customer	= response.customer;
				var customer_name			= customer.name;
				var complete_address		= customer.address;
				var customer_city			= customer.city;
				var customer_number			= customer.number;
				var customer_rt				= customer.rt;
				var customer_rw				= customer.rw;
				var customer_postal			= customer.postal_code;
				var customer_block			= customer.block;
				if(customer_number != '' && customer_number != null){
					complete_address	+= ' No. ' + customer_number;
				}
				
				if(customer_block != null && customer_block != "000" && customer_block != ""){
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

				$('#customerNameP').html(customer_name);
				$('#customerAddressP').html(complete_address);
				$('#customerCityP').html(customer_city);

				$('#salesReturnItemTable').html("");
				var items		= response.items;
				$.each(items, function(index, item){
					var reference		= item.reference;
					var name			= item.name;
					var quantity		= item.quantity;

					$('#salesReturnItemTable').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td></tr>");
				});

			},
			complete:function(){
				$('#salesReturnWrapper').fadeIn(300, function(){
					$('#salesReturnWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function confirmClose(){
		$('#deleteSalesReturnWrapper').fadeIn();
	}

	function deleteSalesReturn(){
		$.ajax({
			url:"<?= site_url('Sales_return/deleteSalesReturnReceived') ?>",
			data:{
				id: salesReturnId
			},
			type:"POST",
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				refresh_view();
				if(response == 1){
					$('#deleteSalesReturnWrapper').fadeOut();
					$('#salesReturnWrapper .slide_alert_close_button').click();
				} else {
					$('#errorDeleteSalesReturn').fadeTo(1, 250);
					setTimeout(function(){
						$('#errorDeleteSalesReturn').fadeTo(0, 250);
					}, 1000);
				}
			}
		})
	}
</script>
