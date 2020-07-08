<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-briefcase'></i></a> / Expense class</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<button type='button' class='button button_default_dark' id='create_account_button'>Create new class</button>
		<br><br>
		<input type='text' class='form-control' id='search_bar'>
		<br>
		<table class='table table-bordered'>
			<tr>
				<th>Name</th>
				<th>Information</th>
				<th>Action</th>
			</tr>
			<tbody id='income_table'></tbody>
		</table>
		
		<select class='form-control' id='page' style='width:100px'>
			<option value='1'>1</option>
		</select>
	</div>
</div>

<div class='alert_wrapper' id='add_class_wrapper'>
	<button type='button' class='slide_alert_close_button'>&times </button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Create new class form</h2>
		<hr>
		<form action='<?= site_url('Income/add_class') ?>' method='POST' id='add_class_form'>
			<label>Name</label>
			<input type='text' class='form-control' name='name' required>
			
			<label>Information</label>
			<textarea class='form-control' name='information' required style='resize:none'></textarea>
			<br>
			
			<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='edit_class_wrapper'>
	<button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Edit class form</h2>
		<hr>
		<form action='<?= site_url('Income/update_class') ?>' method='POST' id='edit_class_form'>
			<input type='hidden' id='income_id' name='id'>
			
			<label>Name</label>
			<input type='text' class='form-control' id='income_name' name='name' required>
			
			<label>Information</label>
			<textarea class='form-control' name='information' id='income_information' required style='resize:none'></textarea>
			<br>
			<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>
<script>
	$('#edit_class_form').validate();
	
	$('#search_bar').change(function(){
		refresh_view(1);
	});
	
	$('#page').change(function(){
		refresh_view();
	});
	
	$(document).ready(function(){
		refresh_view();
	});
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Income/show_all') ?>',
			data:{
				page:page,
				term:$('#search_bar').val()
			},
			success:function(response){
				var classes		= response.classes;
				var pages		= response.pages;
				
				$('#income_table').html('');
				
				$.each(classes, function(index, value){
					var name		= value.name;
					var description	= value.description;
					var id			= value.id;
					
					$('#income_table').append("<tr><td>" + name + "</td><td>" + description + "</td><td><button type='button' class='button button_success_dark' onclick='open_edit_form(" + id + ")'><i class='fa fa-pencil'></i></button></td></tr>");
				});
				
				$('#page').html('');
				
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		});
	}
	
	function open_edit_form(income_id){
		$.ajax({
			url:'<?= site_url('Income/get_by_id') ?>',
			data:{
				id:income_id
			},
			type:'POST',
			success:function(response){
				var id = response.id;
				var name = response.name;
				var description = response.description;
				
				$('#income_id').val(id);
				$('#income_name').val(name);
				$('#income_information').val(description);
				$('#edit_class_wrapper').fadeIn(300, function(){
					$('#edit_class_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}
	
	$('#create_account_button').click(function(){
		$('#add_class_wrapper').fadeIn(300, function(){
			$('#add_class_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	});
	
	$('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
</script>