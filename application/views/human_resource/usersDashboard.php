<head>
	<title>User dashboard</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Human_resource') ?>' title='Human resource'><i class='fa fa-briefcase'></i></a> / Users</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<input type='text' class='form-control input-lg' id='search_bar' placeholder="Search user">
			<div class='input_group_append'>
				<button class='button button_default_dark' id='add_new_user_button'>Add new user</button>
			</div>
		</div>
		<br>
		
		<table class='table table-bordered'>
			<tr>
				<th>Name</th>
				<th>Email</th>
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

<div class='alert_wrapper' id='view_user_wrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>User data</h3>
		<hr>
		<div class='row'>
			<div class='col-md-2 col-sm-3 col-xs-4'>
				<img style='width:100%;border-radius:50%' id='userProfileImage'><br><br>
				<button type='button' class='button button_danger_dark' id='deleteUserProfile'>Remove picture</button>
			</div>
			<div class='col-md-10 col-sm-9 col-xs-8'>
				<label>Name</label>
				<p id='user_name_p'></p>
		
				<label>Address</label>
				<p id='user_address_p'></p>
		
				<label>Email</label>
				<p id='user_email_p'></p>
		
				<label>Bank account</label>
				<p id='user_bank_p'></p>
		
				<label>Access level</label>
				<p id='user_access_p'></p>
		
				<label>Status</label><br>
				<div id='active_button'><button class='button button_verified'><i class='fa fa-check'></i></button> Active</div>
			</div>
		<br><br>
		
		<input type='hidden' id='user_id'>
		<button class='button button_danger_dark' onclick='update_status()' id='update_status_button'>Set as inactive</button>
	</div>
</div>

<div class='alert_wrapper' id='addUserWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Add user form</h3>
		<hr>
		<form id='add_user_form' enctype="multipart/form-data">
			<label>Name</label>
			<input type='text' class='form-control' id='name' name='name' required><br>
			
			<label>Address</label>
			<input type='text' class='form-control' id='address' name='address' required><br>
			
			<label>Email</label>
			<input type='email' class='form-control' id='email' name='email' required><br>
			
			<label>Password</label>
			<input type='password' class='form-control' id='password' name='password' minlength='8' required><br>
			
			<label>Bank account</label>
			<input type='text' class='form-control' id='bank_account' name='bank_account' required><br>
			
			<label>Access level</label>
			<select class='form-control' id='access_level' name='access_level'>
			<?php for($i = 1; $i <=5; $i++){ ?>
				<option value='<?= $i ?>'><?= $i ?></option>
			<?php } ?>
			</select>
			
			<label>Profile picture</label>
			<input type='file' class='form-control' id='image' name='image'>
			<br>
			<button type='button' id='submitFormButton' class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>
<script>
	$('#add_user_form').validate();
	
	$('#submitFormButton').click(function(){
		if($('#add_user_form').valid()){
			$.ajax({
				url:'<?= site_url('Users/insertItem') ?>',
				data:new FormData(document.getElementById('add_user_form')),
				processData:false,
				contentType:false,
				cache:false,
				async:false,
				type:'POST',
				beforeSend:function(){
					$('button').attr('disabled', true);
				},
				success:function(response){
					refresh_view();
					$('button').attr('disabled', false);
					$('#addUserWrapper .slide_alert_close_button').click();
				}	
			});
		};
	})
	
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
			url:'<?= site_url('Users/getItems') ?>',
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
				if(response.image_url == null){
					var imageUrl = '<?= base_url() . "/assets/ProfileImages/defaultImage.png" ?>';
					$('#deleteUserProfile').hide();
				} else {
					var imageUrl = '<?= base_url() . "/assets/ProfileImages/" ?>' + response.image_url;
					$('#deleteUserProfile').show();
				}

				$("#userProfileImage").attr('src', imageUrl);
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

	$('#deleteUserProfile').click(function(){
		$.ajax({
			url:"<?= site_url('Users/removePorfilePicture') ?>",
			data:{
				id: $("#user_id").val()
			},
			type:"POST",
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(){
				$('button').attr('disabled', false);
				view_user($('#user_id').val());
			}
		})
	})
	
	$('#add_new_user_button').click(function(){
		$('#addUserWrapper').fadeIn(300, function(){
			$('#addUserWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	})
	
	
	
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
				$('#view_user_wrapper .slide_alert_close_button').click();
			}
		});
	}
</script>
