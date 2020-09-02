<head>
	<title>Opponent</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-briefcase'></i></a> <a href='<?= site_url('Bank') ?>'>Bank</a> / Opponent</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Type</label>
		<select class='form-control' id='opponent_type'>
			<option value='customer'>Customer</option>
			<option value='supplier'>Supplier</option>
			<option value='other'>Other</option>
		</select>
		<br>

		<input type='text' class='form-control' id='search_bar'>
		<br>

		<div id='opponentTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Name</th>
					<th>Action</th>
				</tr>
				<tbody id='opponentTableContent'></tbody>
			</table>
		
			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='opponentTableText'>There is no opponent found.</p>
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
			url:'<?= site_url('Bank/showOpponent') ?>',
			data:{
				page:page,
				term:$('#search_bar').val(),
				type:$('#opponent_type').val()
			},
			success:function(response){
				$('#opponentTableContent').html('');
				var opponents		= response.opponents;
				var pages			= response.pages;
				var type			= $('#opponent_type').val();
				
				var opponentCount = 0;
				$.each(opponents, function(index, opponent){
					if(type == "other"){
						var name			= opponent.name;
						var description		= opponent.description;
						var id				= opponent.id;
						$('#opponentTableContent').append("<tr><td><label>" + name + "</label><p>" + description + "</p></td><td><button type='button' class='button button_default_dark' title='View " + name + "' onclick='view(`" + type + "`," + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
					} else {
						var name		= opponent.name;
						var city		= opponent.city;
						var id			= opponent.id;
						$('#opponentTableContent').append("<tr><td><label>" + name + "</label><p>" + city + "</p></td><td><button type='button' class='button button_default_dark' title='View " + name + "' onclick='view(`" + type + "`," + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
					}
					
					opponentCount++;
				});

				if(opponentCount > 0){
					$('#opponentTable').show();
					$('#opponentTableText').hide();
				} else {
					$('#opponentTable').hide();
					$('#opponentTableText').show();
				}
				
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
		if(type == 'customer'){
			window.location.href='<?= site_url('Bank/viewTransactions/1/') ?>' + id;
		} else  if(type == 'supplier'){
			window.location.href='<?= site_url('Bank/viewTransactions/2/') ?>' + id;
		} else if(type == 'other'){
			window.location.href='<?= site_url('Bank/viewTransactions/3/') ?>' + id;
		}
	}
</script>
