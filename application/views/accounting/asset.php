<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> /Asset</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<button class='button button_mini_tab' id='inventoryTab' onclick='loadTab(1)'>Operational</button>
		<button class='button button_mini_tab' id='nonInventoryTab' onclick='loadTab(2)'>Non-operational</button>
		<hr>
		<div class='row'>
			<div class='col-xs-12' id='inventoryView' style='display:none'>
				<label>Date</label>
				<input type='date' class='form-control' id='date'>
				<br>
				<label>Value</label>
				<p id='operationalValue'></p>
			</div>
			<div class='col-xs-12' id='nonInventoryView' style='display:none'>
				<label>Date</label>
				<input type='date' class='form-control' id='date'>
				<br>
				<label>Value</label>
				<p id='nonOperationalValue'></p>
			</div>
		</div>
	</div>
</div>
<script>
	$('document').ready(function(){
		loadTab(1);
	})
	var method = 1;

	function loadTab(event){
		$('.button_mini_tab').removeClass('active');
		$('.button_mini_tab').attr('disabled', false);

		if(event == 1){
			$('#inventoryTab').attr('disabled', true);
			$('#inventoryTab').addClass('active');
			$('#nonInventoryView').fadeOut(250);
			setTimeout(function(){
				$('#inventoryView').fadeIn(250);
			}, 250);
		} else {
			$('#nonInventoryTab').attr('disabled', true);
			$('#nonInventoryTab').addClass('active');
			$('#inventoryView').fadeOut(250);
			setTimeout(function(){
				$('#nonInventoryView').fadeIn(250);
			}, 250);
		}
	}
</script>
