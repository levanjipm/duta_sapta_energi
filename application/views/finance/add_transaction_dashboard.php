<div class='dashboard'>
	<h2 style='font-family:bebasneue'>Bank transaction</h2>
	<hr>
	<form action='<?= site_url('Finance/input') ?>' method='POST' id='transaction_form'>
	<label>Account</label>
	<select class='form-control' name='account' id='account'>
<?php
	foreach($accounts as $account){
?>
		<option value='<?= $account->id ?>'><?= $account->number ?> - <?= $account->name ?> ( <?= $account->bank ?> )</option>
<?php
	}
?>
	</select>
	
	<label>Date</label>
	<input type='date' class='form-control' name='date' required min='2020-01-01' id='date'>
	
	<label>Value</label>
	<input type='number' class='form-control' required min='1' name='value' id='value'>
	
	<label>Transaction</label>
	<select class='form-control' name='transaction' id='transaction'>
		<option value='1'>Credit</option>
		<option value='2'>Debit</option>
	</select>
	
	<label>Opponent</label>
	<select class='form-control' id='opponent_type' name='type'>
		<option value='' disabled selected>Please select opponent type</option>
		<option value='customer'>Customer</option>
		<option value='supplier'>Supplier</option>
		<option value='other'>Other</option>
	</select>
	
	<input type='hidden' id='opponent_id' name='id' required>
	<p style='font-family:museo'><span id='opponent_selector_view'>No opponent</span> has been selected</p>
	<button class='button button_default_light' type='button' id='submit_button'><i class='fa fa-long-arrow-right'></i></button>
	</form>
	<br>
</div>
<div class='alert_wrapper' id='opponent_alert_wrapper'>
	<div class='alert_box_full'>
		<div class='row' style='text-align:center'>
			<div class='col-lg-2 col-md-2 col-sm-4 col-xs-4 col-lg-offset-5 col-md-offset-5 col-sm-offset-4 col-sm-offset-4'>
				<button type='button' class='button alert_full_close_button' title='Close add item session' onclick="$('#opponent_alert_wrapper').fadeOut()"></button>
			</div>
		</div>
		<br>
		<div class='row'>
			<div class='col-xs-12'>
				<input type='text' class='form-control' id='search_bar'>
				<br>
				<table class='table table-bordered'>
					<tr>
						<th>Opponent</th>
						<th>Action</th>
					</tr>
					<tbody id='opponent_table'></tbody>
				</table>
				
				<select class='form-control' id='page' style='width:100px'>
					<option value='1'>1</option>
				</select>
			</div>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='transaction_wrapper'>
	<button type='button' class='alert_close_button' onclick="$(this).parent().fadeOut()">&times </button>
	<div class='alert_box_default'>
		<h2 style='font-family:bebasneue'>Add transaction</h2>
		<hr>
		<label>Transaction</label>
		<p style='font-family:museo' id='transaction_date_p'></p>
		<p style='font-family:museo' id='bank_name_p'></p>
		<p style='font-family:museo' id='transaction_value_p'></p>
		<p style='font-family:museo' id='transaction_type_p'></p>
		
		<label>Opponent</label>
		<p style='font-family:museo' id='opponent_name_p'></p>
		
		<button type='button' class='button button_default_dark' onclick="$('#transaction_form').submit()"><i class='fa fa-long-arrow-right'></i></button>
	</div>
</div>
<script>
	$("#transaction_form").validate({
		ignore: '',
		rules: {"hidden_field": {required: true}}
	});
	
	$('#opponent_type').change(function(){
		refresh_view();
	});
	
	$('#search_bar').change(function(){
		refresh_view(1);
	});
	
	$('#page').change(function(){
		refresh_view()
	});
	
	function refresh_view(page = $('#page').val()){	
		$.ajax({
			url:'<?= site_url('Finance/show_opponent') ?>',
			data:{
				type:$('#opponent_type').val(),
				page:page,
				term:$('#search_bar').val()
			},
			type:'GET',
			success:function(response){
				$('#opponent_table').html('');
				var opponents	= response.opponents;
				var pages		= response.pages;
				$.each(opponents, function(index, opponent){
					var name		= opponent.name;
					var address		= opponent.address;
					var city		= opponent.city;
					var id			= opponent.id;
					$('#opponent_table').append("<tr><td><p id='opponent_name-" + id + "'>" + name + "</p><p>" + address + "</p><p>" + city + "</p></td><td><button type='button' class='button button_default_dark' onclick='add_opponent(" + id + ")' title='Choose " + name + "'><i class='fa fa-mouse-pointer'></i></button></td></tr>");
				});
				
				$('#page').html('');
				for(i = 1; i <= pages; i++){
					if(page == i){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				};
			}	
		});
		
		$('#opponent_alert_wrapper').fadeIn(300);
	};
	
	function add_opponent(n, type){
		var opponent_name = $('#opponent_name-' + n).html();
		$('#opponent_id').val(n);
		$('#opponent_selector_view').html(opponent_name);
		$('.alert_full_close_button').click();
	}
	
	$('#submit_button').click(function(){
		if(!$("#transaction_form").valid()){
			return false;
		} else {
			var bank_name		= $('#account option:selected').html();
			var opponent_name	= $('#opponent_selector_view').html();
			var date			= $('#date').val();
			var value			= $('#value').val();
			var transaction		= $('#transaction option:selected').html();
			
			$('#transaction_date_p').html(date);
			$('#bank_name_p').html(bank_name);
			$('#opponent_name_p').html(opponent_name);
			$('#transaction_type_p').html(transaction);
			$('#transaction_value_p').html('Rp. ' + numeral(value).format('0,0.00'));
			
			$('#transaction_wrapper').fadeIn();
		}
	});
</script>