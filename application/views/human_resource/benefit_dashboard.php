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
			<tbody id='users_table'></tbody>
		</table>
		
		<select class='form-control' id='page' style='width:100px'>
			<option value='1' selected>1</option>
		</select>
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
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Benefits/get_benefits') ?>',
			data:{
				page: page,
				term: $('#search_bar').val()
			},
			success:function(response){
				$('#users_table').html('');
				var pages = response.pages;
				var users = response.users;
				$.each(users, function(index, user){
					var id = user.id;
					var name = user.name;
					var address = user.address;
					var email = user.email;
					var is_active = user.is_active;
					if(is_active != 1){
						$('#users_table').append("<tr><td>" + name + "</td><td>" + email + "</td><td><p>" + address + "</p></td><td><button class='button button_transparent' onclick='view_user(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
					} else {
						$('#users_table').append("<tr><td>" + name + "</td><td>" + email + "</td><td><p>" + address + "</p></td><td><button class='button button_transparent' onclick='view_user(" + id + ")'><i class='fa fa-eye'></i></button> <button class='button button_verified'><i class='fa fa-check'></i></button></td></tr>");
					}
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
	};
	
	function view_user(n){
		$.ajax({
			url:'<?= site_url('Users/get_by_id') ?>',
			data:{
				id:n
			},
			success:function(response){
				var name = response.name;
				var address = response.address;
				var email = response.email;
				var bank_account = response.bank_account;
				var is_active = response.is_active;
				var access_level = response.access_level;
				$('#user_id').val(n);
				
				$('#user_name_p').text(name);
				$('#user_address_p').text(address);
				$('#user_email_p').text(email);
				$('#user_bank_p').text(bank_account);
				$('#user_access_p').text(access_level);
				
				if(is_active == 1){
					$('#update_status_button').removeClass('button_success_dark').addClass('button_danger_dark');
					$('#update_status_button').text('Set as inactive');
					$('#active_button').show();
				} else {
					$('#update_status_button').removeClass('button_danger_dark').addClass('button_success_dark');
					$('#update_status_button').text('Set as active');
					$('#active_button').hide();
				};
				
				$('#view_user_wrapper').fadeIn(300, function(){
					$('#view_user_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}
	
	$('#add_new_user_button').click(function(){
		$('#add_user_wrapper').fadeIn(300, function(){
			$('#add_user_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	})
	
	$('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
	
	function update_status(){
		$.ajax({
			url:'<?= site_url('Users/update_status') ?>',
			data:{
				id: $('#user_id').val()
			},
			type:'POST',
			beforeSend: function(){
				$('button').attr('disabled', true);
			},
			success:function(){
				$('button').attr('disabled', false);
				refresh_view();
				$('.slide_alert_close_button').click();
			}
		});
	}
</script>