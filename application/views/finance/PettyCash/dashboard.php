<head>
	<title>Petty cash - Transaction</title>
</head>
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
			<button type='button' class='form-control' id='classButton' style='text-align:left!important'></button>
			<input type='hidden' id='class' name='class' required>
			
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
				<td id='class_p'></td>
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

<div class='alert_wrapper' id='pettyCashClassWrapper'>
	<div class='alert_box_full'>
		<button class='button alert_full_close_button'>&times;</button>
		<h3 style='font-family:bebasneue'>Select expense class</h3>
		<br>
		<div id='pettyCashClassTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Name</th>
					<th>Description</th>
					<th>Action</th>
				</tr>
				<tbody id='pettyCashClassTableContent'></tbody>
			</table>
		</div>
		<p id='pettyCashClassTableText'>There is no class found.</p>
	</div>
</div>

<script>
	$('#classButton').click(function(){
		update_select();
	})

	$('#pettyCashForm').validate({
		ignore:"",
		rules: {"hidden_field": {required:true}}
	});

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
						$("#classButton").html("");
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
			url:'<?= site_url('Expense/getItems') ?>',
			beforeSend:function(){
				$('#classButton').attr('disabled', true);
			},
			success:function(response){
				$('#classButton').attr('disabled', false);
				$('#pettyCashClassTableContent').html('');
				var classes		= response.classes;
				$.each(classes, function(index, value){
					var parent_id		= value.parent_id;
					var id				= value.id;
					var name			= value.name;
					var description		= value.description;
					
					if(parent_id == null){
						$('#pettyCashClassTableContent').append("<tr id='parent_option-" + id + "'><td>" + name + "</td><td colspan='2'>" + description + "</td></tr>");
					} else {
						$('#parent_option-' + parent_id).after("<tr><td>" + name + "</td><td>" + description + "</td><td><button class='button button_default_dark' onclick='selectClass(" + id + ")'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
					}
				});

				if(classes.length == 0){
					$('#pettyCashClassTable').hide();
					$('#pettyCashClassTableText').show();
				} else {
					$('#pettyCashClassTable').show();
					$('#pettyCashClassTableText').hide();
				}

				$('#pettyCashClassWrapper').fadeIn();
			}
		});
	}

	function selectClass(n){
		$.ajax({
			url:"<?= site_url('Expense/getById') ?>",
			data:{
				id:n
			},
			success:function(response){
				var name = response.name;
				var description = response.description;
				var id = response.id;

				$("#class_p").html("<label>" + name + "</label><p>" + description + "</p>");

				$('#class').val(id);
				$('#classButton').html(name + " - " + description);
				$('#pettyCashClassWrapper').fadeOut();
			}
		})
	}

	$('.alert_full_close_button').click(function(){
		$(this).parent().parent().fadeOut();
	})
</script>
