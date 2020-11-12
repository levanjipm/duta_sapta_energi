<head>
	<title>Promotion Archive</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /Promotion /Archive</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<select class='form-control' id='month'>
<?php for($i = 1; $i <= 12; $i++){ ?>
				<option value='<?= $i ?>' <?= ($i == date('m')) ? "selected" : "" ?>><?= date("F", mktime(0,0,0,$i, 1, date('Y'))) ?></option>
<?php } ?>
			</select>
			<select class='form-control' id='year'>
<?php for($i = 2020; $i <= date("Y"); $i++){ ?>
				<option value='<?= $i ?>'><?= $i ?></option>
<?php } ?>
			</select>
		</div>
		<br>
		<div id='archiveTable'>
		</div><br>
		
		<select class='form-control' id='page' style='width:100px'>
			<option value='1'>1</option>
		</select>
	</div>
</div>

<div class='alert_wrapper' id='viewPromotionWrapper'>
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
		
		<hr>
		<p>Created by <span id='createdBy_p'></span></p>
		<p id='confirmedBy_p'></p>
	</div>
</div>
<script>
	$(document).ready(function(){
		refreshView();
	})

	$('#page').change(function(){
		refreshView();
	});

	$('#month').change(function(){
		refreshView(1);
	});

	$('#year').change(function(){
		refreshView(1);
	})

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Promotion/getItems') ?>",
			data:{
				page: page,
				month: $('#month').val(),
				year: $('#year').val()
			},
			success:function(response){
				var items		= response.items;
				var itemCount		= 0;
				$('#archiveTable').html("");
				$.each(items, function(index, value){
					var title		= value.title;
					var description	= value.description;
					var start_date	= value.start_date;
					var end_date	= value.end_date;
					var id			= value.id;
					var note		= value.note;
					var created_by	= value.created_by;
					var confirmed_by	= value.confirmed_by;
					var is_confirm	= value.is_confirm;

					if(is_confirm == 1){
						$('#archiveTable').append("<div class='row archive_row'><div class='col-md-6 col-sm-6 col-xs-6'><p><strong>" + title + "</strong></p><p>" + description + "</p><p>Created by " + created_by + "</p></div><div class='col-md-6 col-sm-6 col-xs-6'><p style='display:inline-block'>" + my_date_format(start_date) + " - " + my_date_format(end_date) + "<strong> | </strong></p><button type='button' class='button button_transparent' id='viewPromotionButton-" + id + "' title='View " + title + "'><i class='fa fa-eye'></i></button> <button class='button button_verified'><i class='fa fa-check' title='Confirmed'></i></button></div>");

						$('#viewPromotionButton-' + id).click(function(){
							$('#promotionTitle').html(title);
							$('#promotionDate').html(my_date_format(start_date) + " - " + my_date_format(end_date));
							$('#promotionDescription').html(description);
							$('#promotionNote').html(note);
							$('#viewPromotionWrapper').fadeIn(300, function(){
								$('#viewPromotionWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
							});

							$('#confirmedBy_p').html("Confirmed by " + confirmed_by);
							$('#createdBy_p').html(created_by);
						})
					} else {
						$('#archiveTable').append("<div class='row archive_row'><div class='col-md-6 col-sm-6 col-xs-6'><p><strong>" + title + "</strong></p><p>" + description + "</p><p>Created by " + created_by + "</p></div><div class='col-md-6 col-sm-6 col-xs-6'><p style='display:inline-block'>" + my_date_format(start_date) + " - " + my_date_format(end_date) + "<strong> | </strong></p><button type='button' class='button button_transparent' id='viewPromotionButton-" + id + "' title='View " + title + "'><i class='fa fa-eye'></i></button></div>");

						$('#viewPromotionButton-' + id).click(function(){
							$('#promotionTitle').html(title);
							$('#promotionDate').html(my_date_format(start_date) + " - " + my_date_format(end_date));
							$('#promotionDescription').html(description);
							$('#promotionNote').html(note);
							$('#viewPromotionWrapper').fadeIn(300, function(){
								$('#viewPromotionWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
							});

							$('#confirmedBy_p').html("");
							$('#createdBy_p').html(created_by);
						})
					}					

					$('#viewPromotionButton-' + id).click(function(){
						$('#promotionTitle').html(title);
						$('#promotionDate').html(my_date_format(start_date) + " - " + my_date_format(end_date));
						$('#promotionDescription').html(description);
						$('#promotionNote').html(note);
						$('#viewPromotionWrapper').fadeIn(300, function(){
							$('#viewPromotionWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
						});

						$('#createdBy_p').html(created_by);
					})

					itemCount++;
				})
				var pages		= response.pages;
				$('#page').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}

				if(itemCount == 0){
					$('#archiveTable').html("There is no promotion found.");
				}
			}
		})
	}
</script>
