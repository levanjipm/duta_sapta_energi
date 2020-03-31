<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-briefcase'></i></a> /Bank/ Account</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<button type='button' class='button button_default_dark' id='create_account_button'>Create new bank account</button>
		<br><br>
<?php
	if(!empty($accounts)){
?>
		<table class='table table-bordered'>
			<tr>
				<th>Number</th>
				<th>Name</th>
				<th>Bank</th>
			</tr>
<?php
	foreach($accounts as $account){
		$name		= $account->name;
		$number		= $account->number;
		$bank		= $account->bank;
		$branch		= $account->branch;
?>
			<tr>
				<td><?= $number ?></td>
				<td><?= $name ?></td>
				<td><p><?= $bank ?></p><p><?= $branch ?></p></td>
			</tr>
<?php
	}
?>
		</table>
<?php
	}
?>
	</div>
</div>
<div class='alert_wrapper' id='add_bank_account_wrapper'>
	<button type='button' class='alert_close_button'>&times </button>
	<div class='alert_box_default'>
	<form action='<?= site_url('Bank/create') ?>' method='POST' id='add_bank_acccount_form'>
		<h2 style='font-family:bebasneue'>Add bank account</h2>
		<hr>
		<label>Name</label>
		<input type='text' class='form-control' name='name' required>
		
		<label>Number</label>
		<input type='text' class='form-control' name='number' required>
		
		<label>Bank</label>
		<input type='text' class='form-control' name='bank' required>
		
		<label>Branch</label>
		<input type='text' class='form-control' name='branch' required>
		<br>
		<button class='button button_success_dark'><i class='fa fa-long-arrow-right'></i></button>
	</div>
</div>
<script>
	$('#create_account_button').click(function(){
		$('#add_bank_account_wrapper').fadeIn();
	});
	
	$('.alert_close_button').click(function(){
		$(this).parent().fadeOut();
	});
	
	$('#add_bank_acccount_form').validate();
</script>