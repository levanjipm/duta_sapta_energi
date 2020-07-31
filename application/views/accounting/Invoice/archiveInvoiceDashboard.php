<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> / Invoice/ Archive</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='row'>
			<div class='col-md-2 col-sm-3 col-xs-4'>
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
			<div class='col-md-2 col-sm-3 col-xs-4'>
				<select class='form-control' id='year'>
<?php
	foreach($years as $year){
?>
					<option value='<?= $year->years ?>' <?php if($year->years == date('Y')){ echo('selected');} ?>><?= $year->years ?></option>
<?php
	}
?>
				</select>
			</div>
		</div>
		<br><br>
		<div id='archive_table'>
		</div><br>

		<select class='form-control' id='page' style='width:100px'>
			<option value='1'>1</option>
		</select>
	</div>
</div>
<script>
	$(document).ready(function(){
		refresh_view();
	})
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Invoice/getItems') ?>',
			data:{
				page: page,
				month: $('#month').val(),
				year: $('#year').val()
			},
			type:"GET",
			success:function(response){
				var items = response.items;
				var pages = response.pages;

				$.each(items, function(index, item){
					var name = item.name;
					var date = item.date;
					if(is_confirm == 0){
						$('#archiveTableContent').append("<div class='row archive_row'><div class='col-md-3 col-sm-3 col-xs-4'><p><strong>" + delivery_order_name + "</strong></p><p>" + sales_order_name + "</p></div><div class='col-md-3 col-sm-3 col-xs-3'><p><strong>" + customer_name + "</strong></p><p>" + complete_address + "</p><p>" + customer_city + "</p></div><div class='col-md-4 col-sm-5 col-xs-5 col-md-offset-2 col-sm-offset-1 col-xs-offset-2'><p style='display:inline-block'>" + my_date_format(delivery_order_date) + " <strong>|</strong> </p> <button type='button' class='button button_transparent' onclick='open_view(" + delivery_order_id + ")' title='View " + delivery_order_name + "'><i class='fa fa-eye'></i></button></div>");
					} else {
						$('#archiveTableContent').append("<div class='row archive_row'><div class='col-md-3 col-sm-3 col-xs-4'><p><strong>" + delivery_order_name + "</strong></p><p>" + sales_order_name + "</p></div><div class='col-md-3 col-sm-3 col-xs-3'><p><strong>" + customer_name + "</strong></p><p>" + complete_address + "</p><p>" + customer_city + "</p></div><div class='col-md-4 col-sm-5 col-xs-5 col-md-offset-2 col-sm-offset-1 col-xs-offset-2'><p style='display:inline-block'>" + my_date_format(delivery_order_date) + " <strong>|</strong> </p> <button type='button' class='button button_transparent' onclick='open_view(" + delivery_order_id + ")' title='View " + delivery_order_name + "'><i class='fa fa-eye'></i></button> <button type='button' class='button button_verified' title='Confirmed'><i class='fa fa-check'></i></button></div>");
					}
				})

				$('#page').html('');

				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>")
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>")
					}			
				}	
			}
		})
	}
</script>