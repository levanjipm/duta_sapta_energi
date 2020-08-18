<head>
	<title>Good receipt - Create</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Inventory') ?>' title='Inventory'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Good_receipt') ?>'>Good receipt</a> /Create</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<form id='goodReceiptDashboardForm'>
			<label>Date</label>
			<input type='date' class='form-control' id='date' required>
			<br>
			<label>Supplier</label>
			<select class='form-control' id='supplier'>
			</select>
			<br>
			<label>Purchase order</label>
			<select id='purchase_order_selector' class='form-control' required></select>
			<br>
			<button type='button' class='button button_default_dark' onclick='getPurchaseOrder()'>Submit</button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='validate_good_receipt_wrapper'>
	<button type='button' class='slide_alert_close_button'>&times </button>
	<div class='alert_box_slide'>
		<form action='<?= site_url('Good_receipt/insertItem') ?>' method='POST' id='goodReceiptForm'>
			<h3 style='font-family:bebasneue'>Good receipt</h3>
			<hr>
			<label>Document</label>
			<p style='font-family:museo' id='document_date_p'></p>
			<input type='text' class='form-control' name='document' required placeholder='Document name'><br>
			
			<label>Purchase order</label>
			<p style='font-family:museo' id='purchase_order_name_p'></p>
			<p style='font-family:museo' id='purchase_order_date_p'></p>
			
			<input type='hidden' id='submit_date' name='submit_date'>
			<input type='hidden' id='guid' name='guid'><br>
			
			<label>Delivery address</label>
			<div id='delivery_address_p'></div><br>
			
			<table class='table table-bordered'>
				<tr>
					<th>Reference</th>
					<th>Name</th>
					<th>Quantity</th>
					<th>Received</th>
					<th>Action</th>
				</tr>
				<tbody id='goodReceiptTableContent'></tbody>
			</table>
			<input type='hidden' id='total_good_receipt' min='1' value='0'><br>
			<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
</div>
</div>

<script>
	$("#goodReceiptForm").validate({
		ignore: '',
		rules: {"hidden_field": {min:1}}
	});

	$('#goodReceiptDashboardForm').validate();
	
	function getPurchaseOrder(){
		if($("#goodReceiptDashboardForm").valid()){
			$.ajax({
				url:'<?= site_url('Good_receipt/getPurchaseOrderDetailById') ?>',
				data:{
					purchase_order:$('#purchase_order_selector').val(),
					date:$('#date').val()
				},
				success:function(response){
					var purchase_order_array	= response.general;
					var guid					= response.guid;
					var purchase_orders			= response.purchase_orders;
					
					var purchase_order_name		= purchase_order_array.name;
					var purchase_order_date		= purchase_order_array.date;

					var dropship_address		= purchase_order_array.dropship_address;
					var dropship_city			= purchase_order_array.dropship_city;
					var dropship_contact_person	= purchase_order_array.dropship_contact_person;
					
					$('#purchase_order_name_p').html(purchase_order_name);
					$('#purchase_order_date_p').html(my_date_format(purchase_order_date))
					$('#guid').val(guid);
					
					if(dropship_address == null){
						$('#delivery_address_p').html("<p style='font-family:museo'>PT Duta Sapta Energi</p><p style='font-family:museo'>Jalan Babakan Hantap no. 23</p><p style='font-family:museo'>Bandung</p>");
					} else {
						$('#delivery_address_p').html("<p style='font-family:museo'>" + dropship_address + "</p><p style='font-family:museo'>" + dropship_address + "</p><p style='font-family:museo'>" + dropship_city + "</p><p style='font-family:museo'>" + dropship_contact_person + "</p>");
					}

					$('#goodReceiptTableContent').html("");
					
					$.each(purchase_orders, function(index, purchase_order){
						var purchase_order_id	= purchase_order.id;
						var reference		= purchase_order.reference;
						var quantity		= purchase_order.quantity;
						var status			= purchase_order.status;
						var name			= purchase_order.name;
						var received		= purchase_order.received;
						var maximum			= quantity - received;
						var net_price		= purchase_order.net_price;
						
						if(status == 0){
							$('#goodReceiptTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>" + numeral(received).format('0,0') + "</td><td><input type='number' class='form-control' name='quantity[" + purchase_order_id + "]' id='quantity-" + purchase_order_id + "' max='" + maximum + "' min='0' onchange='updateGoodReceiptValue()'><input type='hidden' value='" + net_price + "' name='net_price[" + purchase_order_id + "]'></td></tr>");
						}
					});

					$('#submit_date').val($('#date').val());
					
					$('#document_date_p').html(my_date_format($('#date').val()));
					
					$('#validate_good_receipt_wrapper').fadeIn(300, function(){
						$('#validate_good_receipt_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
					});
				}
			});
		};
	};
	
	$(document).ready(function(){
		$.ajax({
			url:'<?= site_url('Good_receipt/getAllIncompletePurchaseOrderSupplier') ?>',
			success:function(response){
				$('#supplier').html('');
				$.each(response, function(index, value){
					var name = value.name;
					var id = value.id;
					var city = value.city;
					$('#supplier').append("<option value='" + id + "'>" + name + " - " + city + "</option>");
				});

				getPurchaseOrders();
			}
		})
	});

	$('#supplier').change(function(){
		getPurchaseOrders();
	})

	function getPurchaseOrders(){
		$.ajax({
			url:'<?= site_url('Purchase_order/getAllPendingPurchaseOrder') ?>',
			data:{
				supplier_id:$('#supplier').val()
			},
			success:function(response){
				$('#purchase_order_selector').html('');
				$.each(response, function(index, value){
					$('#purchase_order_selector').append("<option value='" + value.id + "'>" + value.name + "</option>");
				});
			}
		});
	}
	
	function updateGoodReceiptValue(){
		$('#total_good_receipt').val(0);
		var total	= 0;
		$('input[id^="quantity-"]').each(function(){
			total += parseInt($(this).val());
		});
		$('#total_good_receipt').val(total);
	}
</script>