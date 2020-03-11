<div class='dashboard'>
	<h2 style='font-family:bebasneue'>Good receipt</h2>
	<p style='font-family:museo'>Create good receipt</p>
	<hr>
	<form id='good_receipt_general_form'>
	<label>Date</label>
	<input type='date' class='form-control' id='date' required>
	<br>
	<label>Supplier</label>
	<select class='form-control' id='supplier'>
<?php
	foreach($suppliers as $supplier){
?>
		<option value='<?= $supplier->id ?>'><?= $supplier->name ?></option>
<?php
	}
?>
	</select>
	<br>
	<label>Purchase order</label>
	<select id='purchase_order_selector' class='form-control' required></select>
	<br>
	<button type='button' class='button button_default_light' onclick='get_purchase_order_detail()'>Submit</button>
	</form>
</div>

<div class='alert_wrapper' id='validate_good_receipt_wrapper'>
	<button type='button' class='alert_close_button'>&times </button>
	<div class='alert_box_default'>
		<form action='<?= site_url('Good_receipt/input') ?>' method='POST' id='good_receipt_form'>
		<h2 style='font-family:bebasneue'>Good receipt</h2>
		<hr>
		<label>Document</label>
		<input type='text' class='form-control' name='document' required>
		<label>Purchase order</label>
		<p style='font-family:museo' id='purchase_order_name_p'></p>
		<input type='hidden' id='submit_date' name='submit_date'>
		<input type='hidden' id='guid' name='guid'>
		
		<label>Delivery address</label>
		<div id='delivery_address_p'></div>
		
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
				<th>Received</th>
				<th>Action</th>
			</tr>
			<tbody id='good_receipt_tbody'></tbody>
		</table>
		<input type='hidden' id='total_good_receipt' min='1' value='0'>
		<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<script>
	$("#good_receipt_form").validate({
		ignore: '',
		rules: {"hidden_field": {min:1}}
	});
	
	function get_purchase_order_detail(){
		$('#good_receipt_general_form').validate();
		
		if($("#good_receipt_general_form").valid()){
			$.ajax({
				url:'<?= site_url('Purchase_order/get_incomplete_purchase_order_detail') ?>',
				data:{
					purchase_order:$('#purchase_order_selector').val(),
					date:$('#date').val()
				},
				success:function(response){
					console.log(response);
					var purchase_order_array	= response.general;
					var guid					= response.guid;
					var purchase_orders			= response.purchase_orders;
					
					var purchase_order_name		= response.name;
					var dropship_address		= response.dropship_address;
					var dropship_city			= response.dropship_city;
					var dropship_contact_person	= response.dropship_contact_person;
					
					$('#purchase_order_name_p').html(purchase_order_name);
					$('#guid').val(guid);
					
					if(dropship_address == null){
						$('#delivery_address_p').html("<p style='font-family:museo'>PT Duta Sapta Energi</p><p style='font-family:museo'>Jalan Babakan Hantap no. 23</p><p style='font-family:museo'>Bandung</p>");
					} else {
						$('#delivery_address_p').html("<p style='font-family:museo'>" + dropship_address + "</p><p style='font-family:museo'>" + dropship_address + "</p><p style='font-family:museo'>" + dropship_city + "</p><p style='font-family:museo'>" + dropship_contact_person + "</p>");
					}
					
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
							$('#good_receipt_tbody').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>" + numeral(received).format('0,0') + "</td><td><input type='number' class='form-control' name='quantity[" + purchase_order_id + "]' id='quantity-" + purchase_order_id + "' max='" + maximum + "' min='0' onchange='update_total_value()'><input type='hidden' value='" + net_price + "' name='net_price[" + purchase_order_id + "]'></td></tr>");
						}
					});
					
					$('#validate_good_receipt_wrapper').fadeIn();
					$('#submit_date').val($('#date').val());
				}
			});
		};
	};
	
	$(document).ready(function(){
		$.ajax({
			url:'<?= site_url('Good_receipt/get_incompleted_purchase_order') ?>',
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
	});
	
	function update_total_value(){
		$('#total_good_receipt').val(0);
		var total	= 0;
		$('input[id^="quantity-"]').each(function(){
			total += parseInt($(this).val());
		});
		$('#total_good_receipt').val(total);
	}
	
	$('.alert_close_button').click(function(){
		$(this).parent().fadeOut();
	});
</script>