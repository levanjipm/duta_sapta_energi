<head>
	<title>Visit List - Recap</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> / Visit List / Recap</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<select class='form-control' id='month'>
<?php for($i = 1; $i <= 12; $i++){ ?>
				<option value='<?= $i ?>' <?= ($i == date('m')) ? "selected" : ""; ?>><?= date('F', mktime(0,0,0,$i, 1, 0)) ?></option>
<?php } ?>
			</select>
			<select class='form-control' id='year'>
<?php for($i = 2020; $i <= date('Y'); $i++){ ?>
				<option value='<?= $i ?>' <?= ($i == date('Y')) ? "selected" : ""; ?>><?= $i ?></option>
<?php } ?>
			</select>
			<select class='form-control' id='sales'>
<?php foreach($sales as $item){ ?>
				<option value='<?= $item->id ?>'><?= $item->name ?></option>
<?php } ?>
			</select>
		</div>
		<br>
		<div class='row'>
			<div class='col-xs-12' style='margin-bottom:20px'>
				<label>Area</label>
				<select class='form-control' id='area'>
				<?php foreach($customers as $areaId => $area){ ?>
					<option value='<?= $areaId ?>'><?= $area['name'] ?></option>
				<?php } ?>
				</select>
			</div>
			<div class='col-xs-12'>
			<?php foreach($customers as $areaId => $area){ ?>
				<div id='container-<?= $areaId ?>'>
					<table class='table table-bordered'>
						<tr id='tableHeader-<?= $areaId ?>'>	
							<th>Customer</th>
						</tr>
					<?php foreach($area['customers'] as $item){ ?>
						<tr id='tableBody-<?= $areaId ?>'>
							<td><?= $item['name'] ?>, <?= $item['city'] ?></td>
						</tr>
					<?php } ?>
					</table>
				</div>
			<?php } ?>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		refreshView();
	});

	$('#year').change(function(){
		refreshView();
	});

	$('#month').change(function(){
		refreshView();
	});

	$('#sales').change(function(){
		refreshView();
	});

	function refreshView(){
		var year		= $('#year').val();
		var month		= $('#month').val();
		var sales		= $('#sales').val();

		$.ajax({
			url:"<?= site_url('Visit_list/getRecap') ?>",
			data:{
				year: year,
				sales: sales,
				month: month
			},
			success:function(response){
				$('tr[id^="tableHeader-"]').each(function(){
					var id			= $(this).attr('id');
					var uid			= id.substr(12, 267);

					$(this).html("<th>Customer</th>");
					var lastDayDate = new Date(year, month, 0);
					var lastDay		= lastDayDate.getDay();
					var lastDate	= lastDayDate.getDate();
					
					for(i = 1; i <= lastDate; i++){
						var dayDate	= new Date(year, (month - 1), i);
						var date	= dayDate.getDay();
						if(date != 0){
							$('#tableBody-' + uid).append("<td>" + i + "</td>")
						}
					}
				})
			}
		})
	}
</script>
