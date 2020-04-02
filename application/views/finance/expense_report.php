<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Expense') ?>' title='Expense'><i class='fa fa-briefcase'></i></a>/ Expense/ Report</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='row'>
			<div class='col-md-2 col-sm-3 col-xs-4'>
				<select class='form-control' id='month'>
<?php
	for($i = 1; $i <= 12; $i++){
		if($i == date('m')){
			$selected = 'selected';
		} else {
			$selected = '';
		}
?>
					<option value='<?= $i ?>' <?= $selected ?>><?= date('F', mktime(1,1,1,$i,1)) ?></option>
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
					<option value='<?= $year->year ?>'><?= $year->year ?></option>
<?php
	}
?>
				</select>
			</div>
			<div class='col-md-4 col-sm-4 col-xs-4 col-sm-offset-2 col-md-offset-4' style='text-align:right' id='filter_button_wrapper'>
				<button class='button button_rounded_dark' onclick='view_filter()'><i class='fa fa-filter'></i></button>
			</div>
			<div class='filter_form_wrapper'>
				<div class='filter_form'>
					<button type='button' class='button button_mini_tab active' id='class_button' disabled>Class</button>
					<button type='button' class='button button_mini_tab' id='order_button'>Order</button>
					<br><br>
					<div id='view_pane_class'>
						<table class='table table-bordered'>
							<tr>
								<th>Name</th>
								<th>Action</th>
							</tr>
							<tbody id='petty_table'></tbody>
						</table>
						
						<div style='text-align:center'><button class='button button_rounded_dark'>Apply filter</button></div>
					</div>
					<div id='view_pane_order' style='display:none'>
						<label>Sort by</label>
						<select class='form-control' id='order_select'>
							<option value='1'>Date</option>
							<option value='2'>Value</option>
							<option value='2'>Information</option>
						</select>
						
						<label>Sort type</label>
						<select class='form-control' id='type_order_select'>
							<option value='1'>Ascending</option>
							<option value='2'>DescendingG</option>
						</select>
						<br>
						
						<div style='text-align:center'><button class='button button_rounded_dark'>Apply sort</button></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('#order_button').click(function(){
		$('#class_button').removeClass('active');
		$('#class_button').attr('disabled', false);
		$('#order_button').attr('disabled', true);
		$('#order_button').addClass('active');
		
		$('#view_pane_class').fadeOut(300, function(){
			setTimeout(function(){
				$('#view_pane_order').fadeIn();
			},300);
		});
	});
	
	$('#class_button').click(function(){
		$('#order_button').removeClass('active');
		$('#order_button').attr('disabled', false);
		$('#class_button').attr('disabled', true);
		$('#class_button').addClass('active');
		
		$('#view_pane_order').fadeOut(300, function(){
			setTimeout(function(){
				$('#view_pane_class').fadeIn();
			},300);
		});
	});
	
	function view_filter(){
		$.ajax({
			url:'<?= site_url('Expense/view_class') ?>',
			success:function(response){
				$('#petty_table').html('');
				var classes	= response.classes;
				$.each(classes, function(index, value){
					var name			= value.name;
					var id				= value.id;
					var parent_id		= value.parent_id;
					
					if(parent_id == null){
						$('#petty_table').append("<tr id='parent_tr-" + id + "'><td><strong>" + name + "</strong></td><td></td></tr>");
						
						$('#parent_id').append("<option value='" + id + "'>" + name + "</option>");
						$('#parent_update_id').append("<option value='" + id + "'>" + name + "</option>");
					} else {
						$('#parent_tr-' + parent_id).after("<tr><td style='padding-left:25px'>" + name + "</td><td><input type='checkbox' id='checkbox-" + id + "' checked></td></tr>");
					}
					
					
				});
			}
		});
		var position	= $('#filter_button_wrapper').position();
		var width		= $('#filter_button_wrapper').outerWidth();
		var height		= $('#filter_button_wrapper').height();
		var css_margin	= $('#filter_button_wrapper').css('margin-left');
		
		var margin		= parseFloat(css_margin.substring(0, css_margin.length -2));
		var window_width	= $(window).outerWidth();
		var far_left	= window_width - position.left - width - margin;
		var far_top		= position.top + height;
		
		$('.filter_form_wrapper').css('right', far_left);
		$('.filter_form_wrapper').css('top', far_top);
		$('.filter_form_wrapper').fadeIn();
	}
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
		});
	}
</script>