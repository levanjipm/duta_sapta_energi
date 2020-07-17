<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Inventory') ?>' title='Inventory'><i class='fa fa-briefcase'></i></a> /Delivery order / Confirm</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<button class='button button_mini_tab' id='confirmTab' onclick='loadTab("confirm")'>Confirm</button>
		<button class='button button_mini_tab' id='sentTab' onclick='loadTab("sent")'>Sent</button>
		<hr>
		<div id='confirmWrapper'>
			<div id='confirmDeliveryOrderTable'>
				<h4><strong>Confirm delivery order</strong></h4>
				<table class='table table-bordered'>
					<tr>
						<th>Date</th>
						<th>Name</th>
						<th>Customer</th>
						<th>Action</th>
					</tr>
					<tbody id='confirmDeliveryOrderTableContent'></tbody>
				</table>

				<select class='form-control' id='pageConfirm' style='width:100px'>
					<option value='1'>1</option>
				</select>
			</div>
			<p id='confirmDeliveryOrderTableText'>There is no unconfirmed delivery order.</p>
		</div>

		<div id='sentWrapper' style='display:none'>
			<div id='sentDeliveryOrderTable'>
				<h4><strong>Confirm sent delivery order</strong></h4>
				<table class='table table-bordered'>
					<tr>
						<th>Date</th>
						<th>Name</th>
						<th>Customer</th>
						<th>Action</th>
					</tr>
					<tbody id='sentDeliveryOrderTableContent'></tbody>
				</table>

				<select class='form-control' id='pageSent' style='width:100px'>
					<option value='1'>1</option>
				</select>
			</div>
			<p id='sentDeliveryOrderTableText'>There is no unsent delivery order.</p>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='viewDeliveryOrderWrapper'>
	<button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Confirm delivery order</h3>
		<hr>
		<label>Customer</label>
		<p id='customer_name'></p>
		<p id='customer_address'></p>
		<p id='customer_city'></p>
		
		<label>Delivery order</label>
		<p id='delivery_order_name'></p>
		<p id='delivery_order_date'></p>
		
		
		<table class='table table-bordered' style='margin-bottom:0'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
			</tr>
			<tbody id='delivery_order_table'>
			</tbody>
		</table>
		
		<div class='notificationText warning' id='warning_text'><p style='font-family:museo'><i class='fa fa-exclamation-triangle'></i> Warning! Insufficient stock detected.</p></div><br>
		
		<div class='notificationText danger' id='warning_text_2'><p style='font-family:museo'><i class='fa fa-exclamation-triangle'></i> Delivery order cannot be proceed. Please ask accounting department to create invoice first.</p></div><br>
		
		<form action='<?= site_url('Delivery_order/confirm') ?>' method='POST' id='delivery_order_form'>
			<input type='hidden' id='delivery_order_id' name='id'>
			<button class='button button_default_dark' id='send_delivery_order_button'><i class='fa fa-long-arrow-right'></i></button>
			<button type='button' class='button button_danger_dark' id='cancel_delivery_order_button'><i class='fa fa-trash'></i></button>
		</form>
	</div>
</div>

<script>
	var eventChosen = "confirm";

	$(document).ready(function(){
		loadTab(eventChosen);
	})

	function refresh_view(event, page){
		if(event == "confirm"){
			$.ajax({
				url:'<?= site_url('Delivery_order/getUnconfirmedDeliveryOrder') ?>',
				data:{
					page: page
				},
				success:function(response){
					var items = response.items;
					var itemCount = 0;
					$('#confirmDeliveryOrderTableContent').html('');
					$.each(items, function(index, item){
						var id = item.id;
						var date = item.date;
						var name = item.name;

						var customer = item.customer;
						var customer_name			= customer.name;
						var complete_address		= customer.address;
						var customer_city			= customer.city;
						var customer_number			= customer.number;
						var customer_rt				= customer.rt;
						var customer_rw				= customer.rw;
						var customer_postal			= customer.postal_code;
						var customer_block			= customer.block;
			
						if(customer_number != ''){
							complete_address	+= ' No. ' + customer_number;
						}
						
						if(customer_block != ''){
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

						$('#confirmDeliveryOrderTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td><p>" + customer_name + "</p><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><button class='button button_default_dark' onclick='viewDeliveryOrder(" + id + ")'><i class='fa fa-eye'></i></button></tr>");
						itemCount++;
					});

					if(itemCount > 0){
						$('#confirmDeliveryOrderTableText').hide();
						$('#confirmDeliveryOrderTable').show();
					} else {
						$('#confirmDeliveryOrderTableText').show();
						$('#confirmDeliveryOrderTable').hide();
					}
				}
			})
		}
	}

	function loadTab(event){
		$('.button_mini_tab').removeClass('active');
		$('.button_mini_tab').attr('disabled', false);
		if(event == "confirm"){
			$('#confirmTab').addClass('active');
			$('#confirmTab').attr('disabled', true);

			$('#sentWrapper').fadeOut(250);
			setTimeout(function(){
				$('#confirmWrapper').fadeIn(250)
			},250);		

			eventChosen = "confirm";
			refresh_view(eventChosen,1);
		} else if(event == "sent"){
			$('#sentTab').addClass('active');
			$('#sentTab').attr('disabled', true);

			$('#confirmWrapper').fadeOut(250);
			setTimeout(function(){
				$('#sentWrapper').fadeIn(250)
			},250);	

			eventChosen = "sent";
			refresh_view(eventChosen,1);
		}
	}

	$('#cancel_delivery_order_button').click(function(){
		$.ajax({
			url:'<?= site_url('Delivery_order/deleteById') ?>',
			data:{
				id:$('#delivery_order_id').val()
			},
			type:'POST',
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(){
				$('button').attr('disabled', false);
				window.location.reload();
			}
		});
	});
	
	function viewDeliveryOrder(n){
		$('#delivery_order_table').html('');
		$.ajax({
			url:"<?= site_url('Delivery_order/getById') ?>",
			data:{
				id: n
			},
			success:function(response){
				var general		= response.general;
				var id = general.id;
				var date = general.date;
				var name = general.name;
				$('#delivery_order_id').val(id);

				$('#delivery_order_name').html(name);
				$('#delivery_order_date').html(my_date_format(date));

				var items = response.items;
				$.each(items, function(index, item){
					var name = item.name;
					var quantity = item.quantity;
					var reference = item.reference;

					$('#delivery_order_table').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td></tr>");
				});
				
				var customer = response.customer;
				var customer_name			= customer.name;
				var complete_address		= customer.address;
				var customer_city			= customer.city;
				var customer_number			= customer.number;
				var customer_rt				= customer.rt;
				var customer_rw				= customer.rw;
				var customer_postal			= customer.postal_code;
				var customer_block			= customer.block;
	
				if(customer_number != ''){
					complete_address	+= ' No. ' + customer_number;
				}
				
				if(customer_block != ''){
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
				
				$('#customer_name').html(customer_name);
				$('#customer_address').html(complete_address);
				$('#customer_city').html(customer_city);
				
				$('#delivery_order_form').attr('action', '<?= site_url('Delivery_order/confirm') ?>');
				
				var info		= response.info;
				if(info == 'Stock'){
					$('#warning_text').show();
					$('#send_delivery_order_button').attr('disabled', true);
				} else {
					$('#warning_text').hide();
					$('#send_delivery_order_button').attr('disabled', false);
				}
				
				$('#viewDeliveryOrderWrapper').fadeIn(300, function(){
					$('#viewDeliveryOrderWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}
	
	function send_delivery_order_detail(n){
		$('#delivery_order_table').html('');
		$.ajax({
			url:"<?= site_url('Delivery_order/show_by_code_delivery_order_id/') ?>" + n,
			success:function(response){
				var general		= response.general;
				$.each(general, function(index, value){
					var reference	= value.reference;
					var name		= value.name;
					var quantity	= value.quantity;
					
					$('#delivery_order_table').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td></tr>");
				});
				
				$('#delivery_order_name').html(general[0].do_name);
				$('#delivery_order_date').html(general[0].date);
				$('#customer_name').html(general[0].customer_name);
				$('#customer_address').html(general[0].address);
				$('#customer_city').html(general[0].city);
				$('#delivery_order_id').val(general[0].id);
				$('#delivery_order_form').attr('action', '<?= site_url('Delivery_order/send') ?>');
				
				var info		= response.info;
				if(info == 'Stock'){
					$('#warning_text').show();
					$('#send_delivery_order_button').attr('disabled', true);
				} else {
					$('#warning_text').hide();
					$('#send_delivery_order_button').attr('disabled', false);
				}
				var invoice = response.invoice;
				var invoice_id = invoice.invoice_id;
				var invoice_method = invoice.invoicing_method;

				if(invoice_id == null && invoice_method == 1){
					$('#warning_text_2').show();
					$('#send_delivery_order_button').attr('disabled', true);
				} else {
					$('#warning_text_2').hide();
					$('#send_delivery_order_button').attr('disabled', false);
				}				
				
				$('#view_delivery_order_wrapper').fadeIn(300, function(){
					$('#view_delivery_order_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}
	
	$('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
</script>