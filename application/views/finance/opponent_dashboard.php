<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-briefcase'></i></a> <a href='<?= site_url('Bank') ?>'>Bank</a> / Opponent</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Type</label>
		<select class='form-control' id='opponent_type'>
			<option value='1'>Customer</option>
			<option value='2'>Supplier</option>
			<option value='3'>Other</option>
		</select>
		<br>
		<input type='text' class='form-control' id='search_bar'><br>
		
		<button type='button' class='button button_default_dark' id='add_other_opponent_button' disabled>Add other opponent</button><br><br>
		<table class='table table-bordered'>
			<tr>
				<th>Name</th>
				<th>Action</th>
			</tr>
			<tbody id='opponent_table'></tbody>
		</table>
		
		<select class='form-control' id='page' style='width:100px'>
			<option value='1'>1</option>
		</select>
	</div>
</div>

<div class='alert_wrapper' id='add_other_opponent_wrapper'>
	<button type='button' class='alert_close_button'>&times </button>
	<div class='alert_box_default'>
		<h3 style='font-family:bebasneue'>Add Opponent</h3>
		<form action='<?= site_url('Bank/add_other_opponent') ?>' method='POST' id='add_other_opponent_form'>
			<label>Name</label>
			<input type='text' class='form-control' name='name' required><br>
			
			<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<script>
	$('#add_other_opponent_form').validate();
	
	refresh_view();
	
	$('#opponent_type').change(function(){
		$('#search_bar').val('');
		refresh_view(1);
	});
	
	$('#page').change(function(){
		refresh_view();
	});
	
	$('#search_bar').change(function(){
		refresh_view(1);
	});
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Bank/view_opponent') ?>',
			data:{
				page:page,
				term:$('#search_bar').val(),
				type:$('#opponent_type').val()
			},
			success:function(response){
				$('#opponent_table').html('');
				var opponents		= response.opponents;
				var pages			= response.pages;
				var type			= $('#opponent_type').val();
				
				if(type != 3){
					$('#add_other_opponent_button').attr('disabled', true);
				} else {
					$('#add_other_opponent_button').attr('disabled', false);
				};
				
				$.each(opponents, function(index, opponent){
					var name		= opponent.name;
					var id			= opponent.id;
					$('#opponent_table').append("<tr><td>" + name + "</td><td><button type='button' class='button button_default_dark' title='View " + name + "' onclick='view(" + type + "," + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
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
	
	$('#add_other_opponent_button').click(function(){
		$('#add_other_opponent_wrapper').fadeIn();
	});
	
	$('.alert_close_button').click(function(){
		$(this).parent().fadeOut();
	});
	
	function view(type, id){
		window.location.href='<?= site_url('Bank/view_transaction/') ?>' + type + '/' + id;
	}
</script>