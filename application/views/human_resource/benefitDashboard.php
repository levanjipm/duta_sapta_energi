<head>
	<title>Employee's benefits</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Human_resource') ?>' title='Human resource'><i class='fa fa-briefcase'></i></a> / Employee's benefit</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<input type='text' class='form-control input-lg' id='search_bar' placeholder="Search benefits">
			<div class='input_group_append'>
				<button class='button button_default_dark' id='add_new_benefit_button'>Add new class</button>
			</div>
		</div>
		<br>
		
		<table class='table table-bordered'>
			<tr>
				<th>Name</th>
				<th>Information</th>
				<th>Action</th>
			</tr>
			<tbody id='benefit_table'></tbody>
		</table>
		
		<select class='form-control' id='page' style='width:100px'>
			<option value='1' selected>1</option>
		</select>
	</div>
</div>

<div class='alert_wrapper' id='delete_benefit_wrapper'>
	<div class='alert_box_confirm_wrapper'>
		<div class='alert_box_confirm_icon'><i class='fa fa-trash'></i></div>
		<div class='alert_box_confirm'>
			<input type='hidden' id='delete_benefit_id'>
			<h3>Delete confirmation</h3>
			
			<p>You are about to delete this data.</p>
			<p>Are you sure?</p>
			<button class='button button_default_dark' onclick="$('#delete_benefit_wrapper').fadeOut()">Cancel</button>
			<button class='button button_danger_dark' onclick='delete_benefit()'>Delete</button>
			
			<br><br>
			
			<p style='font-family:museo;background-color:#f63e21;width:100%;padding:5px;color:white;position:relative;bottom:0;left:0;opacity:0' id='error_delete_benefit'>Deletation failed.</p>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='edit_benefit_wrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Edit benefit</h3>
		<hr>
		<form id='editBenefitForm'>
		
			<input type='hidden' id='id_edit'>
			
			<label>Name</label>
			<input type='text' class='form-control' id='name_edit'>
			
			<label>Information</label>
			<textarea class='form-control' id='information_edit' rows='3' style='resize:none'></textarea>
			
			<div style='padding:2px 10px;background-color:#ffc107;width:100%;display:none;' id='warning_text_2'><p style='font-family:museo'><i class='fa fa-exclamation-triangle'></i> Input data failed.</p></div><br>
			<button class='button button_default_dark' type='button' id='editBenefitButton'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='add_benefit_wrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<form id='benefitForm'>
			<h3 style='font-family:bebasneue'>Add benefit</h3>
			<hr>
			<label>Name</label>
			<input type='text' class='form-control' id='name' required>
			
			<label>Information</label>
			<textarea class='form-control' id='information' required minlength='25' rows='3' style='resize:none'></textarea>
			<div style='padding:2px 10px;background-color:#ffc107;width:100%;display:none;' id='warning_text'><p style='font-family:museo'><i class='fa fa-exclamation-triangle'></i> Input data failed.</p></div><br>
			<button class='button button_default_dark' type='button' id='addBenefitbutton'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>
<script>
	$('#page').change(function(){
		refresh_view();
	});
	
	$('#search_bar').change(function(){
		refresh_view(1);
	});
	
	$(document).ready(function(){
		refresh_view();
	});
	
	$('#benefitForm').validate();
	$('#editBenefitForm').validate();
	
	$('#benefitForm').on('submit', function(){
		return false;
	});
	
	$('#editBenefitForm').on('submit', function(){
		return false;
	});
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Benefits/getItems') ?>',
			data:{
				page: page,
				term: $('#search_bar').val()
			},
			success:function(response){
				var benefits = response.benefits;
				$('#benefit_table').html('');
				$.each(benefits, function(index, benefit){
					var name = benefit.name;
					var information = benefit.information;
					var id = benefit.id;
					
					$('#benefit_table').append("<tr><td>" + name + "</td><td>" + information + "</td><td><button class='button button_success_dark' onclick='openEditView(" + id + ")'><i class='fa fa-pencil'></i></button> <button class='button button_danger_dark' onclick='confirmDelete(" + id + ")'><i class='fa fa-trash'></i></button></td></tr>");
				});
				
				$('#page').html('');
				var pages = response.pages;
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		});
	};
	
	$('#addBenefitbutton').click(function(){
		if($('#benefitForm').valid()){
			$.ajax({
				url:'<?= site_url('Benefits/insertItem') ?>',
				data:{
					name: $('#name').val(),
					information: $('#information').val()
				},
				type:'POST',
				beforeSend:function(){
					$('button').attr('disabled', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					if(response == 1) {
						$('#name').val('');
						$('#information').val('');
						refresh_view();
						$('#add_benefit_wrapper .slide_alert_close_button').click();
					} else {
						$('#warning_text').fadeIn();
						setTimeout(function(){
							$('#warning_text').fadeOut();
						}, 1000);
					}
				}
			});
		};
	});
	
	$('#add_new_benefit_button').click(function(){
		$('#add_benefit_wrapper').fadeIn(300, function(){
			$('#add_benefit_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	})
	
	$('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
	
	function confirmDelete(id){
		$('#delete_benefit_id').val(id);
		$('#delete_benefit_wrapper').fadeIn();
	};
	
	function delete_benefit(){
		$.ajax({
			url:"<?= site_url('Benefits/deleteById') ?>",
			data:{
				id: $('#delete_benefit_id').val()
			},
			type:'POST',
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				if(response == 1){
					refresh_view();
					$('#delete_benefit_wrapper').fadeOut();
				} else {
					$('#error_delete_benefit').fadeIn(250);
					setTimeout(function(){
						$('#error_delete_benefit').fadeOut(250);
					}, 1000);
				}
			}
		});
	}
	
	function openEditView(n){
		$.ajax({
			url:'<?= site_url('Benefits/getById') ?>',
			data:{
				id: n
			},
			success:function(response){
				var name = response.name;
				var information = response.information;
				
				$('#id_edit').val(n);
				$('#name_edit').val(name);
				$('#information_edit').val(information);
				
				$('#edit_benefit_wrapper').fadeIn(300, function(){
					$('#edit_benefit_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}
	
	$('#editBenefitButton').click(function(){
		$.ajax({
			url:"<?= site_url('Benefits/updateById') ?>",
			data:{
				id: $('#id_edit').val(),
				name: $('#name_edit').val(),
				information: $('#information_edit').val()
			},
			type:'POST',
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				if(response == 1){
					refresh_view();
					$('#edit_benefit_wrapper .slide_alert_close_button').click();
				} else {
					$('#warning_text_2').fadeIn();
					setTimeout(function(){
						$('#warning_text_2').fadeOut();
					}, 1000);
				}
			}
		});
	});
</script>