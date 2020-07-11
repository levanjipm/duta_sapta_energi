<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-briefcase'></i></a> /Bank/ Account</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<button type='button' class='button button_default_dark' id='create_account_button'>Create new</button>
		<br><br>
<?php
	if(!empty($accounts)){
?>
		<table class='table table-bordered'>
			<tr>
				<th>Number</th>
				<th>Name</th>
				<th>Bank</th>
				<th>Action</th>
			</tr>
<?php
	foreach($accounts as $account){
		$id			= $account->id;
		$name		= $account->name;
		$number		= $account->number;
		$bank		= $account->bank;
		$branch		= $account->branch;
?>
			<tr>
				<td><?= $number ?></td>
				<td><?= $name ?></td>
				<td><p><?= $bank ?></p><p><?= $branch ?></p></td>
				<td><button class='button button_danger_dark' onclick='confirm_delete(<?= $id ?>)'><i class='fa fa-trash'></i></button></td>
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
	<button type='button' class='slide_alert_close_button'>&times </button>
	<div class='alert_box_slide'>
		<form action='<?= site_url('Bank/create') ?>' method='POST' id='add_bank_acccount_form'>
			<h3 style='font-family:bebasneue'>Add bank account</h3>
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
			<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='delete_account_wrapper'>
	<div class='alert_box_confirm_wrapper'>
		<div class='alert_box_confirm_icon'><i class='fa fa-trash'></i></div>
		<div class='alert_box_confirm'>
			<input type='hidden' id='delete_account_id'>
			<h3>Delete confirmation</h3>
			
			<p>You are about to delete this data.</p>
			<p>Are you sure?</p>
			<button class='button button_default_dark' onclick="$('#delete_account_wrapper').fadeOut()">Cancel</button>
			<button class='button button_danger_dark' onclick='delete_account()'>Delete</button>
			
			<br><br>
			
			<p style='font-family:museo;background-color:#f63e21;width:100%;padding:5px;color:white;position:relative;bottom:0;left:0;opacity:0' id='error_delete_account'>Deletation failed.</p>
		</div>
	</div>
</div>
<script>
	$('#add_bank_acccount_form').validate();

	$('#create_account_button').click(function(){
		$('#add_bank_account_wrapper').fadeIn(300, function(){
			$('#add_bank_account_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	});
	
	function confirm_delete(n){
		$('#delete_account_id').val(n);
		$('#delete_account_wrapper').fadeIn();
	}
	
	function delete_account(){
		$.ajax({
			url:'<?= site_url('Bank/delete_account') ?>',
			data:{
				id: $('#delete_account_id').val()
			},
			type:"POST",
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				if(response == 1){
					window.location.reload();
				} else {
					$('#error_delete_account').fadeTo(250, 1);
					setTimeout(function(){
						$('#error_delete_account').fadeTo(250, 0);
					}, 1000);
				}
			}
		});
	}
	
	$('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
</script>