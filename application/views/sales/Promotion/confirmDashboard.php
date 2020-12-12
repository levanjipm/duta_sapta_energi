<head>
	<title>Confirm Promotion</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-line-chart'></i></a> /Promotion /Confirm</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div id='promotionTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Title</th>
					<th>Description</th>
					<th>Action</th>
				</tr>
				<tbody id='promotionTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='promotionTableText'>There is no promotion to be confirmed.</p>
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

		<button class='button button_default_dark' onclick='confirmPromotion()'><i class='fa fa-long-arrow-right'></i></button> <button class='button button_danger_dark' onclick='cancelPromotion()'><i class='fa fa-trash'></i></button>

		<div class='notificationText danger' id='failedUpdateNotification'><p>Failed to update data.</p></div>
	</div>
</div>
<script>
	var promotionId;
	$(document).ready(function(){
		refreshView();
	})

	$('#page').change(function(){
		refreshView();
	});

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Promotion/getUnconfirmedItems') ?>",
			data:{
				page: page
			},
			success:function(response){
				var items		= response.items;
				var itemCount		= 0;
				$('#promotionTableContent').html("");
				$.each(items, function(index, value){
					var title		= value.title;
					var description	= value.description;
					var start_date	= value.start_date;
					var end_date	= value.end_date;
					var id			= value.id;
					var note		= value.note;

					$('#promotionTableContent').append("<tr><td>" + my_date_format(start_date) + " - " + my_date_format(end_date) + "</td><td><label>" + title + "</label></td><td><p>" + description + "</p></td><td><button class='button button_default_dark' id='viewPromotionButton-" + id + "'><i class='fa fa-eye'></i></button></td></tr>");

					$('#viewPromotionButton-' + id).click(function(){
						$('#promotionTitle').html(title);
						$('#promotionDate').html(my_date_format(start_date) + " - " + my_date_format(end_date));
						$('#promotionDescription').html(description);
						$('#promotionNote').html(note);
						promotionId = id;
						$('#validatePromotionForm').fadeIn(300, function(){
							$('#validatePromotionForm .alert_box_slide').show("slide", { direction: "right" }, 250);
						});
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

				if(itemCount > 0){
					$('#promotionTable').show();
					$('#promotionTableText').hide();
				} else {
					$('#promotionTable').hide();
					$('#promotionTableText').show();
				}
			}
		})
	}

	function confirmPromotion(){
		$.ajax({
			url:'<?= site_url('Promotion/confirmById') ?>',
			data:{
				id: promotionId,
			},
			type:"POST",
			success:function(response){
				refreshView();
				if(response == 1){
					promotionId		= null;
					$('#validatePromotionForm .slide_alert_close_button').click();
				} else {
					$('#failedUpdateNotification').fadeTo(250, 1);
					setTimeout(function(){
						$('#failedUpdateNotification').fadeTo(250, 0);
					}, 1000)
				}
			}
		})
	}

	function deletePromotion(){
		$.ajax({
			url:'<?= site_url('Promotion/deleteById') ?>',
			data:{
				id: promotionId,
			},
			type:"POST",
			success:function(response){
				refreshView();
				if(response == 1){
					promotionId		= null;
					$('#validatePromotionForm .slide_alert_close_button').click();
				} else {
					$('#failedUpdateNotification').fadeTo(250, 1);
					setTimeout(function(){
						$('#failedUpdateNotification').fadeTo(250, 0);
					}, 1000)
				}
			}
		})
	}
</script>
