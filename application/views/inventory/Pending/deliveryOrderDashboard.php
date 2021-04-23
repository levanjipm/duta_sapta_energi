<title>Pending Delivery Orders</title>
<div class="dashboard">
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Inventory') ?>' title='Inventory'><i class='fa fa-th'></i></a> /Delivery on Progress</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div id='deliveryOrderTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Customer</th>
					<th>Action</th>
				</tr>
				<tbody id='deliveryOrderTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>

		<p id='deliveryOrderTableText'>There is no pending delivery orders found.</p>
	</div>
</div>

<div class='alert_wrapper' id='deliveryOrderWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Delivery Order</h3>
		<hr>
		<label>Customer</label>
		<p id='customerNameP'></p>
		<p id='customerAdddresP'></p>
		<p id='customerCityP'></p>

		<label>Delivery Order</label>
		<p id='deliveryOrderNameP'></p>
		<p id='deliveryOrderDateP'></p>

		<label>Items</label>
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
			</tr>
			<tbody id='deliveryOrderItem'></tbody>
		</table>

		<button class='button button_default_dark' onclick='copyLocationClipboard()' id='customerLocationButton'><i class="fa fa-share-alt"></i> Share customer's Location</button>
		<input type='text' id='customerlocationurl' style='margin-left:-5000px'>
		<br><br>
		<div class='notificationText success' id='customerLocation'><p>Successfully copied customer's location to clipboard</p></div>
	</div>
</div>

<script>
	var customerLatitude;
	var customerLongitude;

	$(document).ready(function(){
		refreshView();
	});

	$('#page').change(function(){
		refreshView();
	})

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Delivery_order/getOnProgressItems') ?>",
			data:{
				page: page
			},
			success:function(response){
				var items = response.items;
				var itemCount = 0;
				$('#deliveryOrderTableContent').html("");
				$.each(items, function(index, item){
					var complete_address		= '';
					var customer_name			= item.customerName;
					complete_address			+= item.address;
					var customer_city			= item.city;
					var customer_number			= item.number;
					var customer_rt				= item.rt;
					var customer_rw				= item.rw;
					var customer_postal			= item.postal_code;
					var customer_block			= item.block;

					var customerLatitude		= item.latitude;
					var customerLongitude		= item.longitude;
	
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
					var date			= item.date;
					var id				= item.id;
					var name			= item.name;
					$('#deliveryOrderTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td><p>" + customer_name + "</p><p>" + complete_address + "</p><p>" + customer_city + "</p><td><button class='button button_default_dark' onclick='viewDeliveryOrder(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
					itemCount++;
				});

				if(itemCount > 0){
					$("#deliveryOrderTable").show();
					$('#deliveryOrderTableText').hide();
				} else {
					$('#deliveryOrderTable').hide();
					$('#deliveryOrderTableText').show();
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

	function viewDeliveryOrder(id){
		$.ajax({
			url:'<?= site_url('Delivery_order/getById') ?>',
			data:{
				id: id
			},
			success:function(response){
				var customer				= response.customer;
				var complete_address		= '';
				var customer_name			= customer.name;
				complete_address			+= customer.address;
				var customer_city			= customer.city;
				var customer_number			= customer.number;
				var customer_rt				= customer.rt;
				var customer_rw				= customer.rw;
				var customer_postal			= customer.postal_code;
				var customer_block			= customer.block;

				customerLatitude		= parseFloat(customer.latitude).toFixed(6);
				customerLongitude		= parseFloat(customer.longitude).toFixed(6);

				if(customer.latitude == null || customer.longitude == null){
					$('#customerLocationButton').hide();
				} else {
					$('#customerLocationButton').show();
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

				$('#customerNameP').html(customer_name);
				$('#customerAdddresP').html(complete_address);
				$('#customerCityP').html(customer_city);

				var general			= response.general;
				var date			= general.date;
				var name			= general.name;
				$('#deliveryOrderDateP').html(my_date_format(date));
				$('#deliveryOrderNameP').html(name);

				var items = response.items;
				$('#deliveryOrderItem').html("");
				$.each(items, function(index, item){
					var reference			= item.reference;
					var name				= item.name;
					var quantity			= item.quantity;
					
					$('#deliveryOrderItem').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td></tr>");
				})
			},
			complete:function(){
				$('#deliveryOrderWrapper').fadeIn(300, function(){
					$('#deliveryOrderWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function copyLocationClipboard(){
		var url = "https://maps.google.com/maps?q=" + customerLatitude + "," + customerLongitude;
		$('#customerlocationurl').val(url);
		var copyText = document.getElementById("customerlocationurl");
		copyText.select();
		document.execCommand("copy");

		$('#customerLocation').fadeIn(250);
		setTimeout(function(){
			$('#customerLocation').fadeOut(250)
		}, 1000);
	}
</script>
