<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-briefcase'></i></a> /Petty cash/ Transaction</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<form action='<?= site_url('Petty_cash/input_transaction') ?>' method='POST' id='petty_cash_form'>
			<label>Date</label>
			<input type='date' class='form-control' name='date' id='transaction_date' required>
			
			<label>Value</label>
			<input type='number' class='form-control' name='value' min='1' required id='transaction_value'>
			
			<label>Class</label>
			<select class='form-control' id='transaction_class' name='class' required>
			</select>
			
			<label>Information</label>
			<textarea class='form-control' style='resize:none' name='information' id='information'></textarea>
			
			<br>
			<button type='button' class='button button_default_dark' id='submit_button'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='add_transaction_wrapper'>
	<button type='button' class='alert_close_button'>&times </button>
	<div class='alert_box_default'>
		<h2 style='font-family:bebasneue'>Add transaction</h2>
		<hr>
		<table class='table table-bordered'>
			<tr>
				<td><strong>Date</strong></td>
				<td><p style='font-family:museo' id='date_p'></p></td>
			</tr>
			<tr>
				<td><strong>Transaction</strong></td>
				<td><p style='font-family:museo' id='transaction_p'></p></td>
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
	</div>
</div>
<script>
	$('#submit_button').click(function(){
		$('#petty_cash_form').validate();
		
		if($('#petty_cash_form').valid()){
			var date				= $('#transaction_date').val();
			var transaction_class	= $('#transaction_class option:selected').html();
			var value				= $('#transaction_value').val();
			var transaction			= $('#transaction option:selected').html();
			var information			= $('#information').val();
			
			$('#date_p').html(date);
			$('#transaction_p').html(transaction);
			$('#value_p').html('Rp. ' + numeral(value).format('0,0.00'));
			$('#class_p').html(transaction_class);
			$('#information_p').html(information);
			
			$('#add_transaction_wrapper').fadeIn();
		}
	});
	
	$('.alert_close_button').click(function(){
		$(this).parent().fadeOut();
	});
	
	$('#confirm_button').click(function(){
		$('#petty_cash_form').validate();
		
		if($('#petty_cash_form').valid()){
			$('#petty_cash_form').submit();
		};
	});
	
	update_select();
	
	function update_select(){
		$.ajax({
			url:'<?= site_url('Expense/view_class') ?>',
			success:function(response){
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
</script>