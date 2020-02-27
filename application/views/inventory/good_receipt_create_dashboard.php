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
	<div class='alert_box_default'></div>
</div>

<script>
	$('#good_receipt_general_form').validate();
	
	function get_purchase_order_detail(){
		$('#good_receipt_general_form').validate();
		
		if($("#good_receipt_general_form").valid()){
			$.ajax({
				url:'<?= site_url('Purchase_order/get_incomplete_purchase_order_detail/') ?>',
				data:{
					purchase_order:$('#purchase_order_selector').val(),
					date:$('#date').val()
				},
				success:function(response){
					$('#validate_good_receipt_wrapper .alert_box_default').html(response);
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
</script>