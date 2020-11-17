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
			<div class='input_group_append'>
				<button class='button button_default_dark' onclick='viewReport()'><i class='fa fa-file-text-o'></i></button>
			</div>
		</div>
		<br>
		<div class='row'>
			<div class='col-xs-12' style='margin-bottom:20px'>
				<label>Area</label>
				<div class='input_group'>
					<select class='form-control' id='area'>
					<?php foreach($customers as $areaId => $area){ ?>
						<option value='<?= $areaId ?>'><?= $area['name'] ?></option>
					<?php } ?>
					</select>
					<select class='form-control' id='type'>
						<option value='1'>View All Customers</option>
						<option value='2'>View With Data Only</option>
						<option value='3'>View Without Data Only</option>
					</select>
				</div>
			</div>
			<div class='col-xs-12'>
			<?php foreach($customers as $areaId => $area){ ?>
				<div id='container-<?= $areaId ?>'>
					<table class='table table-bordered'>
						<tr>	
							<th id='tableHeader-<?= $areaId ?>'>Customer</th>
						</tr>
					<?php foreach($area['customers'] as $item){ ?>
						<tr>
							<td id='tableCustomer-<?= $item['id'] ?>'><?= $item['name'] ?>, <?= $item['city'] ?></td>
						</tr>
					<?php } ?>
					</table>
				</div>
			<?php } ?>
			</div>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='visitListWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Visit List Summary</h3>
		<hr>
		<label>Scheduled Visit</label>
		<p id='sch'></p>

		<label>Success Visit</label>
		<p id='vis'></p>

		<label>Scheduled Customers</label>
		<p id='schArr'></p>

		<label>Successfully Visited Customers</label>
		<p id='visArr'></p>
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

	function hideShowTable(area = $('#area').val()){
		$('div[id^="container-"]').hide();
		$('#container-' + area).show();
	}

	$('#type').change(function(){
		if($('#type').val() == 1){
			$('.emptyCustomer').show();
			$('.notEmptyCustomer').show();
		} else if($('#type').val() == 2) {
			$('.emptyCustomer').hide();
			$('.notEmptyCustomer').show();
		} else if($("#type").val() == 3){
			$('.notEmptyCustomer').hide();
			$('.emptyCustomer').show();
		}
	})

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
			beforeSend:function(){
				$('th[id^="tableHeader-"]').each(function(){
					$(this).siblings().remove();
				})

				$('td[id^="tableCustomer-"]').each(function(){
					$(this).siblings().remove();
				})

				$('th[id^="tableHeader-"]').each(function(){
					var id			= $(this).attr('id');
					var uid			= parseInt(id.substr(12, 267));
					$(this).html("Customer");
					var lastDayDate = new Date(year, month, 0);
					var lastDay		= lastDayDate.getDay();
					var lastDate	= lastDayDate.getDate();
					
					for(i = lastDate; i >= 1; i--){
						var dayDate	= new Date(year, (month - 1), i);
						var date	= dayDate.getDay();
						if(date != 0){
							$('#tableHeader-' + uid).after("<th>" + i + "</th>")
						}
					}
				});

				var lastDayDate = new Date(year, month, 0);
				var lastDay		= lastDayDate.getDay();
				var lastDate	= lastDayDate.getDate();
				$('td[id^="tableCustomer-"]').each(function(){
					var tableCustomerId	= $(this).attr('id');
					var tableCustomerUid	= parseInt(tableCustomerId.substr(14, 269));
					$('#tableCustomer-' + tableCustomerUid).parent().removeClass('emptyCustomer');
					$('#tableCustomer-' + tableCustomerUid).parent().addClass('emptyCustomer');
					for(i = lastDate; i >= 1; i--){
						var dayDate	= new Date(year, (month - 1), i);
						var date	= dayDate.getDay();
						if(date != 0){
							$('#tableCustomer-' + tableCustomerUid).after("<td id='customer-" + tableCustomerUid + "-" + i + "'></td>");
						}
					}
				});

				hideShowTable();
			},
			success:function(response){
				var scheduled	= 0;
				var success		= 0;
				var scheduledCustomer		= [];
				var successCustomer			= [];
				$.each(response, function(index, value){
					var date		= parseInt(value.date);
					var result		= parseInt(value.result);
					var customer_id	= parseInt(value.customer_id);
					
					if(result == 0){
						$('#customer-' + customer_id + '-' + date).css('background-color', "#f63e21");
						if(!scheduledCustomer.includes(customer_id)){
							scheduledCustomer.push(customer_id);
						}

						$('#tableCustomer-' + customer_id).parent().removeClass('emptyCustomer');
						$('#tableCustomer-' + customer_id).parent().addClass('notEmptyCustomer');
						scheduled++;
					} else {
						$('#customer-' + customer_id + '-' + date).css('background-color', "rgb(1, 187, 0)");

						if(!successCustomer.includes(customer_id)){
							successCustomer.push(customer_id);
						}

						if(!scheduledCustomer.includes(customer_id)){
							scheduledCustomer.push(customer_id);
						}

						$('#tableCustomer-' + customer_id).parent().removeClass('emptyCustomer');
						$('#tableCustomer-' + customer_id).parent().addClass('notEmptyCustomer');
						scheduled++;
						success++;
					}
				})	

				$('#sch').html(numeral(scheduled).format("0,0"));
				$('#vis').html(numeral(success).format('0,0'));

				$('#schArr').html(numeral(scheduledCustomer.length).format('0,0'));
				$('#visArr').html(numeral(successCustomer.length).format('0,0'));
			},
			complete:function(){
				if($('#type').val() == 1){
					$('.emptyCustomer').show();
				} else {
					$('.emptyCustomer').hide();
				}
			}
		})
	}

	$('#area').change(function(){
		hideShowTable();
	});

	function viewReport(){
		$('#visitListWrapper').fadeIn(300, function(){
			$('#visitListWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	}
</script>
