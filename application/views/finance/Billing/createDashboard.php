<head>
	<title>Billing</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-briefcase'></i></a> / Create Billing</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<form action='<?= site_url('Billing/createForm') ?>' method="GET">
		<label>Date</label>
		<input type='date' class='form-control' id='date' name='date' required min='<?= date('2020-01-01') ?>'><br>

		<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>
