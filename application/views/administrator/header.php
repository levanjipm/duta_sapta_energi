<head>
	<style>
		.dashboard{
			margin-left:0;
		}
	</style>
</head>
<div class='topnav_bar' style='padding-left:8px;'>
	<div style='width:50%;display:inline-block'><h3>Administrators</h3></div><div style='width:50%;display:inline-block;text-align:right;color:white'><?php if(!empty($user_login)){ ?><h4>Hello, <a href='<?= site_url('Profile') ?>' style='text-decoration:none;color:#fff'><?= $user_login->name ?> <img src='<?= $user_login->image_url == null ? base_url('assets/ProfileImages/defaultImage.png') : base_url('assets/ProfileImages/') . $user_login->image_url ?>' style='height:30px;border-radius:50%;width:30px'></img></a></h4><?php } else { ?><button type='button' class='button button_default_dark'>Login</button> <?php } ?></div>
</div>
