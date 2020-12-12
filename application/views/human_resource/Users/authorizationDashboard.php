<head>
	<title>User dashboard</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Human_resource') ?>' title='Human resource'><i class='fa fa-user'></i></a> / Users</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<input type='text' class='form-control' id='search_bar'>
		<br>
		
		<div id='userTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Name</th>
					<th>Email</th>
					<th>Information</th>
					<th>Action</th>
				</tr>
				<tbody id='userTableContent'></tbody>
			</table>
		
			<select class='form-control' id='page' style='width:100px'>
				<option value='1' selected>1</option>
			</select>
		</div>
		<p id='userTableText'>There is no user found.</p>
	</div>
</div>

<div class='alert_wrapper' id='viewAuthorizationWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Authorizations</h3>
		<hr>
		<label>User</label><br>
		<img id='userProfileImage' style='width:40px;height:40px;border-radius:50%;display:inline-block'> <p id='userName_p' style='display:inline-block'></p><br><br>

		<table class='table table-bordered'>
			<tr>
				<th>Department</th>
				<th>Action</th>
			</tr>
			<tbody id='authorizationTable'></tbody>
		</table>

		<label>Access level</label>
		<select class='form-control' id='access_level'>
			<option value='1'>1</option>
			<option value='2'>2</option>
			<option value='3'>3</option>
			<option value='4'>4</option>
			<option value='5'>5</option>
		</select><br>

		<button class='button button_default_dark' id='submitChangeButton'><i class='fa fa-long-arrow-right'></i></button>
	</div>
</div>
<script>
	var userId;
	var departmentArray = [];

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
				$('#userTableContent').html('');
				var userCount = 0;
				var pages = response.pages;
				var users = response.users;
				$.each(users, function(index, user){
					var id = user.id;
					var name = user.name;
					var address = user.address;
					var email = user.email;
					var is_active = user.is_active;
					if(is_active == 1){
						$('#userTableContent').append("<tr><td>" + name + "</td><td>" + email + "</td><td><p>" + address + "</p></td><td><button class='button button_transparent' onclick='view_user(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
						userCount++;
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

				if(userCount == 0){
					$('#userTable').hide();
					$('#userTableText').show();
				} else {
					$('#userTable').show();
					$('#userTableText').hide();
				}
			}
		});
	};
	
	function view_user(n){
		$.ajax({
			url:'<?= site_url('Users_authorization/getByUserId') ?>',
			data:{
				id:n
			},
			success:function(response){
				departmentArray = [];
				userId = n;
				var user = response.user;
				var access_level = user.access_level;
				$('#access_level').val(access_level);
				if(user.image_url == null){
					var imageUrl = '<?= base_url() . "/assets/ProfileImages/defaultImage.png" ?>';
				} else {
					var imageUrl = '<?= base_url() . "/assets/ProfileImages/" ?>' + user.image_url;
				}

				$("#userProfileImage").attr('src', imageUrl);
				var name = user.name;
				
				$('#userName_p').text(name);
				$('#authorizationTable').html("");

				var authorizations = response.authorization;
				$.each(authorizations, function(index, value){
					var departmentIcon = "<?= base_url() . '/assets/' ?>" + value.icon + ".png";
					var departmentName = value.name;
					var status = value.status;
					var id = value.id;

					if(status == 1){
						$('#authorizationTable').append("<tr><td><img src='" + departmentIcon + "' style='width:30px;height:30px;'> " + departmentName + "</td><td><input type='checkbox' id='authorization-" + id + "' checked></td></tr>");
						departmentArray.push("" + id + "");
					} else {
						$('#authorizationTable').append("<tr><td><img src='" + departmentIcon + "' style='width:30px;height:30px;'> " + departmentName + "</td><td><input type='checkbox' id='authorization-" + id + "'></td></tr>");
					}

					$('#authorization-' + id).change(function(){
						if($(this).is(':checked')){
							if(!departmentArray.includes("" + id + "")){
								departmentArray.push("" + id + "");
							}
						} else {
							if(departmentArray.includes("" + id + "")){
								var index = departmentArray.indexOf("" + id + "");
								departmentArray.splice(index, 1);
							}
						}
					})
				});
				
				$('#viewAuthorizationWrapper').fadeIn(300, function(){
					$('#viewAuthorizationWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}	
	
	$('#submitChangeButton').click(function(){
		$.ajax({
			url:"<?= site_url('Users_authorization/updateByUserId') ?>",
			data:{
				userId: userId,
				departments: departmentArray,
				access_level: $('#access_level').val()
			},
			type:"POST",
			beforeSend:function(){
				$("button").attr('disabled', true);
				$("input").attr('readonly', true);
			},
			success:function(){
				$("button").attr('disabled', false);
				$("input").attr('readonly', false);
				refresh_view();

				$('#viewAuthorizationWrapper .slide_alert_close_button').click();
				if(<?= $this->session->userdata('user_id') ?> == userId){
					location.reload(true);
				}
			}
		})
	})
</script>
