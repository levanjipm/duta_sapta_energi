<h4><strong>Confirm case(s)</strong></h4>
<br>
<div id='inventoryCaseTable'>
	<table class='table table-bordered'>	
		<tr>
			<th>Date</th>
			<th>Type</th>
			<th>Created by</th>
			<th>Action</th>
		</tr>
		<tbody id='inventoryCaseTableContent'></tbody>
	</table>

	<select class='form-control' id='page' style='width:100px'>
		<option value='1'>1</option>
	</select>
</div>
<p id='inventoryCaseTableText'>There is no case to be confirmed.</p>

<div class='alert_wrapper' id='view_inventory_case_wrapper'>
	<button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Confirm event</h3>
		<hr>
		<label>Date</label>
		<p style='font-family:museo' id='view_date_p'></p>
		
		<label>Type</label>
		<p style='font-family:museo' id='type_p'></p>
		
		<label>Created by</label>
		<p style='font-family:museo' id='created_p'></p>
		
		<table class='table table-bordered' style='margin-bottom:0'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Price</th>
				<th>Quantity</th>
				<th>Total price</th>
			</tr>
			<tbody id='event_table'></tbody>
		</table>
		<div style='padding:2px 10px;background-color:#ffc107;width:100%;display:none;' id='warning_text'><p style='font-family:museo'><i class='fa fa-exclamation-triangle'></i> Warning! Insufficient stock detected.</p></div><br>
		<input type='hidden' name='id' id='event_id'>
		<input type='hidden' name='status' id='status'>

		<button type='button' class='button button_default_dark' onclick='confirmEvent()'><i class='fa fa-long-arrow-right'></i></button>
		<button type='button' class='button button_danger_dark' onclick='deleteEvent()'><i class='fa fa-trash'></i></button>

		<div class='notificationText danger' id='deleteFailedNotification'><p>Failed to delete event.</p></div>
		<div class='notificationText danger' id='confirmFailedNotification'><p>Failed to confirm event.</p></div>
	</div>
</div>
<script>
	$(document).ready(function(){
		refresh_view();
	})
	
	function view_case(n){
		$.ajax({
			url:'<?= site_url('Inventory_case/showById') ?>',
			data:{
				id:n
			},
			success:function(response){
				$('#event_id').val(n);
				var general		= response.general;
				var created_by	= general.created_by;
				var date		= general.date;
				var type		= general.type;
				
				$('#view_date_p').html(my_date_format(date));
				$('#created_p').html(created_by);
				if(type == 1){
					var text = 'Lost goods';
				} else if(type == 2){
					var text = 'Found goods';
				} else if(type == 3){
					var text = 'Dematerialized goods';
				} else if(type == 4){
					var text = 'Materialized goods';
				}
				
				$('#type_p').html(text);
				
				var status		= response.status;
				if(status){
					$('#warning_text').hide();
					$('#confirm_button').attr('disabled', false);
					$('#status').val(true);
				} else {
					$('#warning_text').show();
					$('#confirm_button').attr('disabled', true);
					$('#status').val(false);
				}
				$('#event_table').html('');
				
				var event_value	= 0;
				var details		= response.details;
				$.each(details, function(index, detail){
					var name	= detail.name;
					var reference	= detail.reference;
					var transaction	= detail.transaction;
					var price		= parseFloat(detail.price);
					var quantity	= parseFloat(detail.quantity);
					var total_price	= price * quantity;
					event_value		+= total_price;
					
					$('#event_table').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>Rp." + numeral(price).format('0,0.00') + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp." + numeral(total_price).format('0,0.00') + "</td></tr>");
				});
				
				$('#event_table').append("<tr><td colspan='3'></td><td>Total</td><td>Rp." + numeral(event_value).format('0,0.00') + "</td></tr>");
				
				$('#view_inventory_case_wrapper').fadeIn(300, function(){
					$('#view_inventory_case_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}
	
	$('.slide_alert_close_button').click(function(){
		$('#view_inventory_case_wrapper .alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$('#view_inventory_case_wrapper').fadeOut();
		});
	});
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Inventory_case/view_unconfirmed_case') ?>',
			data:{
				page:page
			},
			success:function(response){
				$('#inventoryCaseTableContent').html('');
				var caseCount = 0;
				var cases		= response.cases;
				$.each(cases, function(index, value){
					var id 			= value.id;
					var created_by 	= value.created_by;
					var type 		= value.type;
					var date 		= value.date;
					if(type == 1){
						var text = 'Lost goods';
					} else if(type == 2){
						var text = 'Found goods';
					} else if(type == 3){
						var text = 'Dematerialized goods';
					} else if(type == 4){
						var text = 'Materialized goods';
					}
					
					$('#inventoryCaseTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + text + "</td><td>" + created_by + "</td><td><button type='button' class='button button_default_dark' onclick='view_case(" + id + ")'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
					caseCount++;
				});
				
				var pages = response.pages;
				$('#page').html('');
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}

				if(caseCount > 0){
					$('#inventoryCaseTable').show();
					$('#inventoryCaseTableText').hide();
				} else {
					$('#inventoryCaseTable').hide();
					$('#inventoryCaseTableText').show();
				}
			}
		});
	}

	function deleteEvent(){
		var eventId = $('#event_id').val();
		$.ajax({
			url:"<?= site_url('Inventory_case/deleteById') ?>",
			data:{
				id: eventId
			},
			type:'POST',
			beforeSend:function(){
				$("button").attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				refresh_view();
				if(response == 1){
					$('#view_inventory_case_wrapper .slide_alert_close_button').click();
				} else {
					$('#deleteFailedNotification').fadeIn(250, setTimeout(function(){
						$('#deleteFailedNotification').fadeOut(250);
					}));
				}
			}
		})
	}

	function confirmEvent(){
		$.ajax({
			url:"<?= site_url("Inventory_case/confirmById") ?>",
			data:{
				id:$('#event_id').val()
			},
			type:'POST',
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				if(response == 1){
					refresh_view();
					$('#view_inventory_case_wrapper .slide_alert_close_button').click();
				} else {
					refresh_view();
					$('#confirmFailedNotification').fadeTo(250, 1);
					setTimeout(function(){
						$('#confirmFailedNotification').fadeTo(250, 0);
					}, 1000);
				}
			}
		})
	}
</script>