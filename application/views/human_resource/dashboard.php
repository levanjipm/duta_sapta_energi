<?php
	$accessLevelArray		= array();
	$accessLevelCountArray	= array();
	foreach($accessLevelRatio as $level => $count){
		array_push($accessLevelArray, $level);
		array_push($accessLevelCountArray, $count);
	}

	$attendanceDateArray	= array();
	$attendanceLabelArray	= array();
	$attendanceArray		= array();
	$attendanceData			= array();
	foreach($attendanceItems as $difference => $detail){
		$label		= date("d M Y", strtotime("-" . $difference . "day"));
		$attendanceDateArray[]	= $label;
		foreach($detail as $statusId => $item){
			if(!array_key_exists($statusId, $attendanceArray)){
				$attendanceArray[$statusId] = array();
				$attendanceLabelArray[$statusId] = $item['status'];
			};
			$attendanceArray[$statusId][$difference] = $item['count'];
		}
	}

	array_reverse($attendanceDateArray);
	
	foreach($attendanceArray as $key => $attendance){
		for($i = 0; $i <= 6; $i++){
			if(!array_key_exists($i, $attendance)){
				$attendanceArray[$key][$i] = 0;
			}
		}
		ksort($attendanceArray[$key]);
	}

	$opacityDivider		= count($attendanceLabelArray);
	foreach($attendanceArray as $key => $attendance){
		$label		= $attendanceLabelArray[$key];
		$data		= $attendance;

		//Differenciate the background color from opacity 1 to opacity 0.2//
		$opacity	= 1 - 0.8 * ($key / $opacityDivider);

		$item		= array(
			"label" => $label,
			"data" => $data,
			"background" => 'rgba(225, 115, 60, ' . $opacity . ')'
		);

		$attendanceData[]	= $item;
	}
	$attendanceDataFinal		= json_encode($attendanceData);
?>
<head>
    <title>Human Resource</title>
    <style>
        .dashboardBox{
            padding:8px;
            box-shadow:3px 3px 3px 3px rgba(50,50,50,0.3);
            border-radius:5px;
            margin-bottom:10px;
        }

        .dashboardBox .leftSide{
            width:50%;
            font-weight:bold;
            display:inline-block;
        }

        .dashboardBox .rightSide{
            width:45%;
            float:right;
            display:inline-block;
            text-align:center;
            margin:0 auto;
            top: 50%;
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
            position:absolute;
            border-left:2px solid #ccc;
        }

        .dashboardBox .rightSide h3{
            
            font-weight:bold;
            color:#E19B3C;            
        }

        .subtitleText{
            font-size:0.8em;
            color:#555;
            text-align:right;
        }

		.progressBarWrapper{
			width:100%;
			height:20px;
			background-color:#ccc;
			z-index:10;
			position:relative;
			border-radius:5px;
			margin-bottom:10px;
		}

		.progressBarWrapper p{
			z-index:100;
			float:right;
		}

		.progressBar{
			width:0;
			height:20px;
			position:absolute;
			top:0;
			left:0;
			z-index:20;
			border-radius:5px;
			background-color:rgb(1, 187, 0);
		}

		#levelChart{
			top:0;
			bottom:0;
			left:0;
			right:0;
			position:absolute;
			margin:auto;
		}
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
</head>
<div class='dashboard'>
    <br>
    <div class='dashboard_in'>
        <div class='row'>
            <div class='col-md-4 col-sm-12 col-xs-12'>
                <div class='dashboardBox clickable' onclick='getUsers()' >
                    <div class='leftSide'>
                        <h4><b>Active</b></h4>
                        <p>User</p>
                    </div>
                    <div class='rightSide'>
                        <h3><?= number_format($activeUser) ?></h3>
                        <p>&nbsp;</p>
                    </div>
                </div>
            </div>
			<div class='col-md-4 col-sm-12 col-xs-12'>
                <div class='dashboardBox clickable' onclick='window.location.href="<?= site_url('Attendance') ?>"'>
                    <div class='leftSide'>
                        <h4><b>Pending</b></h4>
                        <p>Attendance</p>
                    </div>
                    <div class='rightSide'>
                        <h3><?= number_format($pendingAttendance) ?></h3>
                        <p>&nbsp;</p>
                    </div>
                </div>
            </div>
        </div>
		<div class="row">
			<div class="col-sm-6">
				<div class="row">
					<div class="col-sm-12">
						<label>User By Access Level Ratio</label>
					</div>
					<div class="col-sm-6">
						<canvas id="levelChart" width="150" height="150"></canvas>
					</div>
					<div class='col-sm-6'>
					<?php for($i = 1; $i <= 5; $i++){ ?>
						<label>Level <?= $i ?></label>
						<div class='progressBarWrapper'>
							<p><?= $accessLevelRatio[$i] ?></p>
							<div class='progressBar' id='progress-<?= $i ?>'></div>
							<script>
								$('#progress-<?= $i ?>').animate({
									width: "<?= $accessLevelRatio[$i] * 100 / $activeUser ?>%"
								}, 1000)
							</script>
						</div>
					<?php } ?>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="row">
					<div class="col-sm-12">
						<label>Attendance</label>
						<canvas id='attendanceChart'></canvas>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>

<div class='alert_wrapper' id='userWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Active users</h3>
		<hr>
		<div id='userTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Name</th>
					<th>Information</th>
				</tr>
				<tbody id='userTableContent'></tbody>
			</table>
		</div>
		<p id='userTableText'>There is no active user found.</p>
	</div>
</div>

<script>
	function getUsers(){
		$.ajax({
			url:"<?= site_url('Users/getActiveUser') ?>",
			success:function(response){
				$('#userTableContent').html("");
				$.each(response, function(index, item){
					var name = item.name;
					var address = item.address;
					var email = item.email;
					$('#userTableContent').append("<tr><td>" + name + "</td><td><p>" + address + "</p><p>" + email + "</p></td></tr>")
				});

				if(response.length == 0){
					$('#userTable').hide();
					$('#userTableText').show();
				} else {
					$('#userTable').show();
					$('#userTableText').hide();
				}

				$('#userWrapper').fadeIn(300, function(){
					$('#userWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	$(document).ready(function(){
		getLevelRatio();
		getAttendanceHistory();
	});

	function getLevelRatio(){
		var ctx = document.getElementById("levelChart");
		var myChart = new Chart(ctx, {
			type: 'doughnut',
			data: {
			labels: <?= json_encode($accessLevelArray) ?>,
			datasets: [{
				label: 'User By Access Level',
				data: <?= json_encode($accessLevelCountArray) ?>,
				backgroundColor: [
					'rgba(1, 187, 0, 0.2)',
					'rgba(1, 187, 0, 0.4)',
					'rgba(1, 187, 0, 0.6)',
					'rgba(1, 187, 0, 0.8)',
					'rgba(1, 187, 0, 1)'
				],
				borderColor: [
					'rgba(1, 187, 0, 0.2)',
					'rgba(1, 187, 0, 0.4)',
					'rgba(1, 187, 0, 0.6)',
					'rgba(1, 187, 0, 0.8)',
					'rgba(1, 187, 0, 1)'
				],
				borderWidth: 1
			}]
			},
			options: {
			responsive: false,
			legend: {
				display: false,
			},
			}
		});
	}

	function getAttendanceHistory(){
		var history		= <?= $attendanceDataFinal ?>;
		var datasets	= [];
		$.each(history, function(index, item){
			let dataObject = [];
			$.each(item.data, function(index, data){
				dataObject.push(data);
			})

			var dataset = {
				label: item.label,
				data: dataObject,
				backgroundColor: item.background
			}

			datasets[index] = dataset;
		});

		var ctx = document.getElementById("attendanceChart");
		var myBarChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: <?= json_encode(array_reverse($attendanceDateArray)) ?>,
				datasets:datasets
			},
			options: {
				scales: {
					xAxes: [{
						stacked: true
					}],
					yAxes: [{
						stacked: true
					}]
				}
			}
		});
	}
</script>
