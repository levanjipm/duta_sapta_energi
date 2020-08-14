<head>
	<title>Debt document</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Sales'><i class='fa fa-bar-chart'></i></a> /Debt document</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='row'>
			<div class='col-xs-12'>
				<button class='button button_mini_tab' id='regularTab' onclick='loadTab(1)'>Regular</button>
				<button class='button button_mini_tab' id='blankTab' onclick='loadTab(2)'>Blank</button>
				<hr>
			</div>
			<div class='col-xs-12' id='viewPane'></div>
			</div>
		</div>
	</div>
</div>
<script>
	var tabChosen = 1;

	$(document).ready(function(){
		loadTab()
	});

	function loadTab(n = tabChosen){
		tabChosen = n;
		$('.button_mini_tab').attr('disabled', false);
		$('.button_mini_tab').removeClass('active');

		if(n == 1){
			$('#regularTab').addClass('active');
			$('#regularTab').attr('disabled', true);
		} else {
			$('#blankTab').addClass('active');
			$('#blankTab').attr('disabled', true);
		};

		$.ajax({
			url:'<?= site_url("Debt/loadForm") ?>',
			data:{
				type: tabChosen
			},
			success:function(response){
				$('#viewPane').html(response);
			}
		})
	}	

	
</script>	