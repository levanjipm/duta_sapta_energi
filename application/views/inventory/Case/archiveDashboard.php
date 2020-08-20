<head>
	<style>
		.archive_row{
			padding:10px;
			border-bottom:1px solid #e2e2e2;
		}
	</style>
	<title>Inventory case - Archive</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Inventory') ?>' title='Inventory'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Good_receipt') ?>'>Good receipt</a> / Archive</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='row'>
			<div class='col-md-3 col-sm-4 col-xs-6'>
				<select class='form-control' id='month'>
<?php
	for($i = 1; $i <= 12; $i++){
?>
					<option value='<?= $i ?>' <?php if($i == date('m')){ echo('selected');} ?>><?= date('F', mktime(0,0,0,$i, 1)) ?></option>
<?php
	}
?>
				</select>
			</div>
			<div class='col-md-3 col-sm-4 col-xs-6'>
				<select class='form-control' id='year'>
<?php
	foreach($years as $year){
?>
					<option value='<?= $year->year ?>' <?php if($year->year == date('Y')){ echo('selected');} ?>><?= $year->year ?></option>
<?php
	}
?>
				</select>
			</div>
		</div>
		<br><br>
		<div id='archiveTableWrapper'>
			<div id='archiveTable'></div>
			<br>
			
			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='archiveTableText'>There is no event found.</p>
	</div>
</div>

<div class='alert_wrapper' id='viewEventWrapper'>
	<button type='button' class='slide_alert_close_button'>&times </button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Event Archive</h3>
		<hr>
		<label>Event</label>
		<p style='font-family:museo' id='eventName_p'></p>
		<p style='font-family:museo' id='eventType_p'></p>
		<p style='font-family:museo' id='eventDate_p'></p>

		<p style='font-family:museo'>Created by <span id='eventCreator_p'></span></p>
		
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
				<th>Transaction</th>
			</tr>
			<tbody id='eventTableContent'></tbody>
		</table>
	</div>
</div>

<script>
	$('#search_button').click(function(){
		refresh_view(1);
	});
	
	$('#page').change(function(){
		refresh_view();
	});

	$('#month').change(function(){
		refresh_view(1);
	});

	$('#year').change(function(){
		refresh_view(1);
	})
	
	$(document).ready(function(){
		refresh_view(1);
	})
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Inventory_case/viewArchives') ?>",
			data:{
				year: $('#year').val(),
				month: $('#month').val(),
				page:page,
				term:$('#search_bar').val()
			},
			success:function(response){
				$('#page').html('');
				var pages		= response.pages;
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
				
				$('#archiveTable').html('');
				
				var items		= response.items;
				if(items.length == 0){
					$('#archiveTableWrapper').hide();
					$('#archiveTableText').show();
				} else {
					$('#archiveTableWrapper').show();
					$('#archiveTableText').hide();
				}
				
				$.each(items, function(index, item){
					var is_confirm = item.is_confirm;
					var date = item.date;
					var name = item.name;
					var type = item.type;
					var id	= item.id;

					if(type == 1){
						var typeName = "Lost goods";
					} else if(type == 2){
						var typeName = "Found goods";
					} else if(type == 3){
						var typeName = "Dematerialized goods";
					} else if(type == 4){
						var typeName = "Materialized goods";
					};


					if(is_confirm == 0){
						$('#archiveTable').append("<div class='row archive_row'><div class='col-md-4 col-sm-5 col-xs-6'><p><strong>" + name + "</strong></p><p>" + typeName + "</p></div><div class='col-md-4 col-sm-5 col-xs-6'><p style='display:inline-block'>" + my_date_format(date) + " <strong>|</strong> </p> <button type='button' class='button button_transparent' onclick='open_view(" + id + ")' title='View " + name + "'><i class='fa fa-eye'></i></button> <button type='button' class='button button_verified' title='Confirmed'><i class='fa fa-check'></i></button></div>");
					} else {
						$('#archiveTable').append("<div class='row archive_row'><div class='col-md-4 col-sm-5 col-xs-6'><p><strong>" + name + "</strong></p><p>" + typeName + "</p></div><div class='col-md-4 col-sm-5 col-xs-6'><p style='display:inline-block'>" + my_date_format(date) + " <strong>|</strong> </p> <button type='button' class='button button_transparent' onclick='open_view(" + id + ")' title='View " + name + "'><i class='fa fa-eye'></i></button></div>");
					}
				});
				
				var button_width		= $('.button_verified').height();
				$('.button_verified').width(button_width);
			}
		});
	}
	
	function open_view(n){
		$.ajax({
			url:'<?= site_url('Inventory_case/showById') ?>',
			data:{
				id:n
			},
			success:function(response){
				var general					= response.general;
				var date		= general.date;
				var type		= general.type;
				var name		= general.name;
				var createdBy	= general.created_by;

				$('#eventCreator_p').html(createdBy);

				if(type == 1){
					var typeName = "Lost goods";
				} else if(type == 2){
					var typeName = "Found goods";
				} else if(type == 3){
					var typeName = "Dematerialized goods";
				} else if(type == 4){
					var typeName = "Materialized goods";
				};

				$('#eventName_p').html(name);
				$('#eventDate_p').html(my_date_format(date));
				$('#eventType_p').html(typeName);
				
				$('#eventTableContent').html('');
				
				var items		= response.details;
				$.each(items, function(index, item){
					var reference		= item.reference;
					var name			= item.name;
					var quantity		= item.quantity;
					var transaction		= item.transaction;
					$('#eventTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>" + transaction + "</td></tr>");
				});
				
				$('#viewEventWrapper').fadeIn(300, function(){
					$('#viewEventWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}
</script>