<head>
    <title>Finance</title>
    <style>
        .dashboardBox{
            padding:8px;
            box-shadow:3px 3px 3px 3px rgba(50,50,50,0.3);
            border-radius:5px;
            margin-bottom:10px;
			cursor:pointer;
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
		}

		.progressBar{
			width:0;
			height:20px;
			position:absolute;
			top:0;
			left:0;
			z-index:20;
			border-radius:5px;
		}

		#overdueARprogress{
			background-color:#E19B3C;
		}

		#ARprogress{
			background-color:#01BB00;
		}

		#overdueAPprogress{
			background-color:#E19B3C;
		}

		#APprogress{
			background-color:#01BB00;
		}

		.button_info{
			border-radius:50%;
			background-color:#5bc0de;
			border:none;
			outline:none!important;
			color:white;
			padding:2px;
			font-size:18px;
			width:24px;
			height:24px;
			line-height:1px;
		}
    </style>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
</head>
<div class='dashboard'>
    <br>
    <div class='dashboard_in'>
        <div class='row'>
            <div class='col-md-6 col-sm-12 col-xs-12'>
                <div class='dashboardBox clickable' onclick='window.location.href="<?= site_url('Bank/mutationDashboard') ?>"'>
                    <div class='leftSide'>
                        <h4><b>Current</b></h4>
                        <p>Cash</p>
                    </div>
                    <div class='rightSide'>
                        <h3>Rp. <?= number_format($bank,2) ?></h3>
                        <p>&nbsp;</p>
                    </div>
                </div>
            </div>
            <div class='col-md-6 col-sm-12 col-xs-12'>
                <div class='dashboardBox clickable' onclick='window.location.href="<?= site_url('Petty_cash/mutation') ?>"'>
                    <div class='leftSide'>
                        <h4><b>Current</b></h4>
                        <p>Petty Cash</p>
                    </div>
                    <div class='rightSide'>
                        <h3>Rp. <?= number_format($petty,2) ?></h3>
                        <p>&nbsp;</p>
                    </div>
                </div>
            </div>
			<div class='col-md-6 col-sm-12 col-xs-12' style="margin-top:20px">
				<div class='dashboardBox'>
					<div class='leftSide'>
                        <h4><b>Current</b></h4>
                        <p>Ratio</p>
                    </div>
                    <div class='rightSide'>
                        <h3><?= number_format($ratio,2) ?></h3>
                        <p>&nbsp;</p>
                    </div>
				</div>
			</div>
			<div class='col-md-6 col-sm-12 col-xs-12' style="margin-top:20px" onclick='viewPendingCustomerAssignment()'>
				<div class='dashboardBox'>
					<div class='leftSide'>
                        <h4><b>Pending</b></h4>
                        <p>Customer Assignment</p>
                    </div>
                    <div class='rightSide'>
                        <h3><?= number_format($customerAssignment,0) ?></h3>
                        <p>&nbsp;</p>
                    </div>
				</div>
			</div>
			<div class='col-md-6 col-sm-12 col-xs-12' style="margin-top:20px">
				<div class='dashboardBox'>
					<div class='leftSide'>
                        <h4><b>Debt - Equity</b></h4>
                        <p>Ratio</p>
                    </div>
                    <div class='rightSide'>
                        <h3><?= number_format($deratio,2) ?></h3>
                        <p>&nbsp;</p>
                    </div>
				</div>
			</div>
        </div>
		<div class="row" style="padding-top:20px">
			<div class="col-sm-6 col-xs-12">
				<div class="row">
					<div class='col-xs-12'>
						<h3 style='font-family:bebasneue'>Account Receivable</h3>
						<hr style='border-bottom:2px solid #333'>
					</div>
					<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
						<canvas id="ARChart" width="150" height="150"></canvas>
					</div>
					<div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
						<label>Overdue AR</label>
						<p id='overdueAR'></p>
						<div class='progressBarWrapper'>
							<div class='progressBar' id='overdueARprogress'></div>
						</div>
						<label>Account Receivable</label>
						<p id='AR'></p>
						<div class='progressBarWrapper'>
							<div class='progressBar' id='ARprogress'></div>
						</div>
					</div>
					<div class='col-xs-12'>
						<button class='button button_info' onclick='window.location.href="<?= site_url('Receivable/finance') ?>"' title="Go to receivable"><i class='fa fa-info'></i></a>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-xs-12">
				<div class="row">
					<div class='col-xs-12'>
						<h3 style='font-family:bebasneue'>Account Payable</h3>
						<hr style='border-bottom:2px solid #333'>
					</div>
					<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
						<canvas id="APChart" width="150" height="150"></canvas>
					</div>
					<div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
						<label>Overdue AP</label>
						<p id='overdueAP'></p>
						<div class='progressBarWrapper'>
							<div class='progressBar' id='overdueAPprogress'></div>
						</div>
						<label>Account Payable</label>
						<p id='AP'></p>
						<div class='progressBarWrapper'>
							<div class='progressBar' id='APprogress'></div>
						</div>
					</div>
					<div class='col-xs-12'>
						<button class='button button_info' onclick='window.location.href="<?= site_url('Payable/finance') ?>"' title="Go to payable"><i class='fa fa-info'></i></a>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>

<div class='alert_wrapper' id='pendingAssignmentWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
    <div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Pending Assignment</h3>
		<hr>
		<table class='table table-bordered' id='pendingAssignmentTable'>
			<tr>
				<th>Name</th>
				<th>Address</th>
				<th>City</th>
			</tr>
			<tbody id='pendingAssignmentTableContent'></tbody>
		</table>

		<p id='pendingAssignmentTableText'>There is no pending assignment for customers' schedule.</p>
	</div>
</div>

<script>
	$(document).ready(function(){
		getReceivable();
		getPayable();
	})

	$('#unassignedCustomersBox').click(function(){
		if(<?= $user_login->access_level ?> > 1){
			window.location.href='<?= site_url('Customer/assignAccountantDashboard') ?>';
		}
	});

	function getReceivable(){
		$.ajax({
			url:"<?= site_url('Accounting/getInvoices') ?>",
			success:function(response){
				$('#overdueAR').html("Rp. " + numeral(response.due).format('0,0.00'));
				$('#AR').html("Rp. " + numeral(response.notDue).format('0,0.00'));

				var total			= parseFloat(response.due) + parseFloat(response.notDue);
				if(total > 0){
					$('#overdueARprogress').animate({
						width: (parseFloat(response.due) * 100 / total).toString() + "%"
					}, 1000);

					$('#ARprogress').animate({
						width: (parseFloat(response.notDue) * 100 / total).toString() + "%"
					}, 1000);

					var ctx = document.getElementById("ARChart");
					var myChart = new Chart(ctx, {
					  type: 'doughnut',
					  data: {
						labels: ['Due invoices', 'Invoices not yet due'],
						datasets: [{
						  label: 'Invoices',
						  data: [response.due, response.notDue],
						  backgroundColor: [
							'#E19B3C',
							'#01BB00'
						  ],
						  borderColor: [
							'rgba(225,155,60,1)',
							'rgba(1, 187, 0, 0)',
						  ],
						  borderWidth: 5
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
			}
		})
	}

	function getPayable(){
		$.ajax({
			url:"<?= site_url('Accounting/getPayable') ?>",
			success:function(response){
				$('#overdueAP').html("Rp. " + numeral(response.due).format('0,0.00'));
				$('#AP').html("Rp. " + numeral(response.notDue).format('0,0.00'));

				var total			= parseFloat(response.due) + parseFloat(response.notDue);
				if(total > 0){
					$('#overdueAPprogress').animate({
						width: (parseFloat(response.due) * 100 / total).toString() + "%"
					}, 1000);

					$('#APprogress').animate({
						width: (parseFloat(response.notDue) * 100 / total).toString() + "%"
					}, 1000);

					var ctx = document.getElementById("APChart");
					var myChart = new Chart(ctx, {
					  type: 'doughnut',
					  data: {
						labels: ['Due debt', 'Debt not yet due'],
						datasets: [{
						  label: 'Debt',
						  data: [response.due, response.notDue],
						  backgroundColor: [
							'#E19B3C',
							'#01BB00'
						  ],
						  borderColor: [
							'rgba(225,155,60,1)',
							'rgba(1, 187, 0, 0)',
						  ],
						  borderWidth: 5
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
			}
		})
	}

	function viewPendingCustomerAssignment(){
		$.ajax({
			url:"<?= site_url('Schedule/getUnassignedCustomer') ?>",
			beforeSend:function(){
				$('#pendingAssignmentTableContent').html("");
			},
			success:function(response){
				$.each(response, function(index, customer){
					var complete_address		= '';
					var customer_name			= customer.name;
					complete_address		+= customer.address;
					var customer_city			= customer.city;
					var customer_number			= customer.number;
					var customer_rt				= customer.rt;
					var customer_rw				= customer.rw;
					var customer_postal			= customer.postal_code;
					var customer_block			= customer.block;
					var customer_id				= customer.id;
		
					if(customer_number != null){
						complete_address	+= ' No. ' + customer_number;
					}
					
					if(customer_block != null && customer_block != "000"){
						complete_address	+= ' Blok ' + customer_block;
					}
				
					if(customer_rt != '000'){
						complete_address	+= ' RT ' + customer_rt;
					}
					
					if(customer_rw != '000' && customer_rt != '000'){
						complete_address	+= ' /RW ' + customer_rw;
					}
					
					if(customer_postal != null){
						complete_address	+= ', ' + customer_postal;
					}

					$("#pendingAssignmentTableContent").append("<tr><td>" + customer_name + "</td><td>" + complete_address + "</td><td>" + customer_city + "</td></tr>");
				
					if(response.length == 0){
						$('#pendingAssignmentTableText').show();
						$('#pendingAssingmentTable').hide();
					} else {
						$('#pendingAssingmentTable').show();
						$('#pendingAssignmentTableText').hide();
					}
				});
			},
			complete:function(){
				$('#pendingAssignmentWrapper').fadeIn(300, function(){
					$('#pendingAssignmentWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}
</script>
