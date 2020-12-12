<title>Bank - Assign as Income</title>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-bar-chart'></i></a> / Bank/ Assign bank</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<form id='assignExpenseForm'>
			<label>Bank data</label>
			<p style='font-family:museo'><?= date('d M Y',strtotime($bank->date)) ?></p>
			<p style='font-family:museo'><?= $bank->name ?> ( <?= $type ?> )</p>
			<p style='font-family:museo'>Rp. <span id='bank_value_p'><?= number_format($bank->value,2) ?></span></p>
			<input type='hidden' value='<?= $bank->id ?>' name='bank_id' readonly>

			<label>Income class</label>
			<div id='pettyTable'>
				<table class='table table-bordered'>
					<tr>
						<th>Name</th>
						<th>Information</th>
						<th>Action</th>
					</tr>
					<tbody id='pettyTableContent'></tbody>
				</table>
			</div>
			<p id='pettyTableText'>There is no class found.</p>

			<label>Note</label>
			<textarea name='note' id='note' class='form-control' rows='3' style='resize:none'></textarea><br>
			<button type='button' class='button button_default_dark' id='submit_button'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='validateExpenseWrapper'>
	<button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Assign bank as Income</h3>
		<hr>
		<label>Date</label>
		<p style='font-family:museo'><?= date('d M Y',strtotime($bank->date)) ?></p>

		<label>Opponent</label>
		<p style='font-family:museo'><?= $bank->name ?> ( <?= $type ?> )</p>

		<label>Value</label>
		<p style='font-family:museo'>Rp. <span id='bank_value_p'><?= number_format($bank->value,2) ?></span></p>

		<label>Income class</label>
		<p style='font-family:museo' id='expenseClass_p'></p>

		<label>Note</label>
		<p style='font-family:museo' id='note_p'></p>

		<button type='button' class='button button_default_dark' id='submitChangeButton'><i class='fa fa-long-arrow-right'></i></button>
	</div>
</div>

<script>
	var chosenIncome;
	
	$('#submit_button').click(function(){
		if($('#assignExpenseForm').valid()){
			$('#note_p').html(($('#note').val() == "") ? "<i>Not available</i>" : $('#note').val());
			$('#validateExpenseWrapper').fadeIn(300, function(){
				$('#validateExpenseWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
			});
		}
	});

	$('#submitChangeButton').click(function(){
		if($('#assignExpenseForm').valid()){
			$.ajax({
				url:"<?= site_url('Bank/insertAssignIncome') ?>",
				data:{
					id: <?= $bank->id ?>,
					incomeClass: chosenIncome,
					note: $('#note').val()
				},
				type:"POST",
				beforeSend:function(){
					$('button').attr('disabled', true);
					$("input").attr('readonly', true);
					$('textarea').attr('readonly', true);
				},
				success:function(){
					window.location.href="<?= site_url('Bank/assignDashboard') ?>"
				}
			})
		}
	})

	$('#assignExpenseForm').validate();

	$(document).ready(function(){
		refresh_view();
	});

	function refresh_view(){
		$.ajax({
			url:'<?= site_url('Income/getAllItems') ?>',
			success:function(response){
				$('#pettyTableContent').html('');
				var countClass = 0;
				$.each(response, function(index, value){
					var name			= value.name;
					var id				= value.id;
					var description		= value.description;

					$('#pettyTableContent').append("<tr><td>" + name + "</td><td>" + description + "</td><td><input type='radio' name='class' value='" + id + "' id='radioButton-" + id + "' required></td></tr>");

					$('#radioButton-' + id).click(function(){
						if($(this).is(":checked")){
							$('#expenseClass_p').html(name);
							chosenIncome = $(this).val();
						}
					})
					countClass++;
				});

				if(countClass > 0){
					$('#pettyTable').show();
					$('#pettyTableText').hide();
				} else {
					$('#pettyTable').hide();
					$('#pettyTableText').show();
				}
			}
		});
	}
</script>
