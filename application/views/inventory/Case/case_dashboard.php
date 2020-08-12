<head>
	<title>Inventory case</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Inventory') ?>' title='Inventory'><i class='fa fa-briefcase'></i></a> / Cases</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<button class='headerButton button button_mini_tab' id='createHeaderTab' onclick='loadHeaderTab("create")'>Create</button>
		<button class='headerButton button button_mini_tab' id='confirmHeaderTab' onclick='loadHeaderTab("confirm")'>Confirm</button>
		<hr>
		<div class='row' id='createTab'>
			<div class='col-xs-12'>
				<button class='createTabButton button button_mini_tab' id='lostTab' onclick='loadTab("lost")'>Lost goods</button>
				<button class='createTabButton button button_mini_tab' id='foundTab' onclick='loadTab("found")'>Found goods</button>
				<button class='createTabButton button button_mini_tab' id='dematerializedTab' onclick='loadTab("dematerialized")'>Dematerialized goods</button>
				<button class='createTabButton button button_mini_tab' id='materializedTab' onclick='loadTab("materialized")'>Materialized goods</button>
			</div>
			<br><br>
			<div class='col-xs-12' id='viewCreatePane'>
			</div>
		</div>

		<div class='row' id='confirmTab'>
			<div class='col-xs-12' id='viewConfirmPane'>
				Confirm
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#createHeaderTab').click();
	})

	function loadHeaderTab(functionName){
		$('.headerButton').removeClass('active');
		$('.headerButton').attr('disabled', true);

		if(functionName == "create"){
			$('#confirmHeaderTab').attr('disabled', false);
			$('#createHeaderTab').addClass('active');
			loadTab("lost");
			
			$('#confirmTab').fadeOut(250);
			setTimeout(function(){
				$('#createTab').fadeIn();
			}, 250)

		} else if(functionName == "confirm"){
			$('#createHeaderTab').attr('disabled', false);
			$('#confirmHeaderTab').addClass('active');

			$.ajax({
				url:'<?= site_url('Inventory_case/confirmDashboard') ?>',
				success:function(response){
					$('#viewConfirmPane').html(response);
				}
			})

			$('#createTab').fadeOut(250);
			setTimeout(function(){
				$('#confirmTab').fadeIn();
			}, 250)
		}
	}

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