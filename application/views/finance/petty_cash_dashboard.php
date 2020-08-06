<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-briefcase'></i></a> /Petty cash/ Transaction</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<form id='pettyCashForm'>
			<label>Date</label>
			<input type='date' class='form-control' name='date' id='transaction_date' required>
			
			<label>Value</label>
			<input type='number' class='form-control' name='value' min='1' required id='transaction_value'>
			
			<label>Class</label>
			<select class='form-control' id='transaction_class' name='class' required>
			</select>
			
			<label>Information</label>
			<textarea class='form-control' style='resize:none' name='information' id='information' required></textarea>
			
			<br>
			<button type='button' class='button button_default_dark' id='submit_button'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='addTransactionWrapper'>
	<button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Add transaction</h3>
		<hr>
		<table class='table table-bordered'>
			<tr>
				<td><strong>Date</strong></td>
				<td><p style='font-family:museo' id='date_p'></p></td>
			</tr>
			<tr>
				<td><strong>Value</strong></td>
				<td><p style='font-family:museo' id='value_p'></p></td>
			</tr>
			<tr>
				<td><strong>Class</strong></td>
				<td><p style='font-family:museo' id='class_p'></p></td>
			</tr>
			<tr>
				<td><strong>Information</strong></td>
				<td><p style='font-family:museo' id='information_p'></p></td>
			</tr>
		</table>
		<br>
		<button type='button' class='button button_default_dark' id='confirm_button'><i class='fa fa-long-arrow-right'></i></button>

		<div class='notificationText danger' id='failedInsertNotification'><p>Failed to insert data.</p></div>
	</div>
</div>
<script>
	$(document).ready(function(){
		update_select();
	});

	$('#pettyCashForm').validate();

	$('#pettyCashForm input').on('keypress', function(e) {
		return e.which !== 13;
	});

	$('#submit_button').click(function(){
		if($('#pettyCashForm').valid()){
			var date				= $('#transaction_date').val();
			var transaction_class	= $('#transaction_class option:selected').html();
			var value				= $('#transaction_value').val();
			var information			= $('#information').val();
			
			$('#date_p').html(my_date_format(date));
			$('#value_p').html('Rp. ' + numeral(value).format('0,0.00'));
			$('#class_p').html(transaction_class);
			$('#information_p').html(information);
			
			$('#addTransactionWrapper').fadeIn(300, function(){
				$('#addTransactionWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
			});
		}
	});
	
	$('#confirm_button').click(function(){
		$('#pettyCashForm').validate();
		
		if($('#pettyCashForm').valid()){
			$.ajax({
				url:"<?= site_url('Petty_cash/insertItem') ?>",
				data:{
					date				: $('#transaction_date').val(),
					class				: $('#transaction_class').val(),
					value				: $('#transaction_value').val(),
					information			: $('#information').val(),
				},
				type:'POST',
				beforeSend:function(){
					$('button').attr('disabled', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					if(response == 1){
						$('#pettyCashForm').trigger("reset");
						$('#addTransactionWrapper .slide_alert_close_button').click();
					} else {
						$('#failedInsertNotification').fadeIn(250);
						setTimeout(function(){
							$('#failedInsertNotification').fadeOut(250);
						}, 1000)
					}
				}
			})
		};
	});
	
	function update_select(){
		$.ajax({
			url:'<?= site_url('Expense/view_class') ?>',
			beforeSend:function(){
				$('#transaction_class').attr('disabled', true);
			},
			success:function(response){
				$('#transaction_class').attr('disabled', false);
				$('#transaction_class').html('');
				var classes		= response.classes;
				$.each(classes, function(index, value){
					var parent_id		= value.parent_id;
					var id				= value.id;
					var name			= value.name;
					
					if(parent_id == null){
						$('#transaction_class').append("<option id='parent_option-" + id + "' style='font-weight:bold' disabled>" + name + "</option>");
					} else {
						$('#parent_option-' + parent_id).after("<option value='" + id + "'>" + name + "</option>");
					}
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