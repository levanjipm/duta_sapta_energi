<head>
	<title>Inventory case - Create</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Inventory') ?>' title='Inventory'><i class='fa fa-th'></i></a> /Cases/ Create</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class="row">
			<div class='col-xs-12'>
				<button class='createTabButton button button_mini_tab' id='lostTab' onclick='loadTab("lost")'>Lost goods</button>
				<button class='createTabButton button button_mini_tab' id='foundTab' onclick='loadTab("found")'>Found goods</button>
				<button class='createTabButton button button_mini_tab' id='dematerializedTab' onclick='loadTab("dematerialized")'>Dematerialized goods</button>
				<button class='createTabButton button button_mini_tab' id='materializedTab' onclick='loadTab("materialized")'>Materialized goods</button>
				<hr>
			</div>
			<div class='col-xs-12' id='viewCreatePane'>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#lostTab').click();
	})

	function loadTab(event){
		$.ajax({
			url:'<?= site_url('Inventory_case/createDashboard/') ?>' + event,
			success:function(response){
				$('.createTabButton').removeClass('active');
				$('.createTabButton').attr('disabled', false);

				$('#' + event + 'Tab').addClass('active');
				$('#' + event + 'Tab').attr('disabled', true)
				$('#viewCreatePane').html(response);
			}
		})
	}
</script>