<head>
	<title>Create Promotion</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /Promotion /Create</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<form action='<?= site_url('Promotion/insertItem') ?>' method='POST' id='promotionForm'>
		<label>Date</label>
			<div class='input_group'>
				<input type='date' class='form-control' name='startDate' id='startDate' value='<?= date('Y-m-d') ?>' required min='2020-01-01'>
				<input type='date' class='form-control' name='endDate' id='endDate' value='<?= date('Y-m-d') ?>' required min='2020-01-01'>
			</div>

			<label>Title</label>
			<input type='text' class='form-control' id='title' name='title' placeholder='Promotion Name' required minlength='10'>

			<label>Description</label>
			<textarea class='form-control' id='description' name='description' style='resize:none' rows='4' placeholder="Specify the promotion" required minlength='25'></textarea>

			<label>Note</label>
			<textarea class='form-control' id='note' name='note' style='resize:none' rows='4' placeholder="Write the note here" required></textarea>
			<br>
			<button type='button' class='button button_default_dark' onclick='validate_form()' id='submit_button'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='validatePromotionForm'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue' id='promotionTitle'></h3>
		<hr>
		<label>Availability</label>
		<p id='promotionDate'></p>

		<label>Description</label>
		<p id='promotionDescription'></p>

		<label>Note</label>
		<p id='promotionNote'></p>

		<button class='button button_default_dark' onclick='submit_form()'>Submit</button>
	</div>
</div>
<script>
	$('.slide_alert_close_button').click(function(){
		$('input').attr('readonly',false);
		$('select').attr('readonly',false);
		$('textarea').attr('readonly', false);
		$('#table_item_confirm').html('');
		
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
	
	$("#promotionForm").validate();
	
	function validate_form(n){
		if(!$("#promotionForm").valid()){
			return false;
		} else {
			$('input').attr('readonly',true);
			$('textarea').attr('readonly', true);
			$('select').attr('readonly',true);
			$('#table_item_confirm').html('');
			
			var title		= $('#title').val();
			var description	= $('#description').val();
			var note		= $('#note').val();

			var dateStart	= $('#startDate').val();
			var dateEnd		= $('#endDate').val();

			$('#promotionTitle').html(title);
			$('#promotionDate').html(my_date_format(dateStart) + " - " + my_date_format(dateEnd));
			$('#promotionDescription').html(description);
			$('#promotionNote').html(note);

			$('#validatePromotionForm').fadeIn(300, function(){
				$('#validatePromotionForm .alert_box_slide').show("slide", { direction: "right" }, 250);
			});
		}
	};
	
	$('.alert_full_close_button').click(function(){
		$(this).parents().find('.alert_wrapper').fadeOut();
	});
	
	function submit_form(){
		if($("#promotionForm").valid()){
			$('#promotionForm').submit();
		};
	};
</script>
