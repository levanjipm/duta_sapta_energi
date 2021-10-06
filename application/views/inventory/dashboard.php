<head>
    <title>Inventory</title>
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
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
</head>
<div class='dashboard'>
    <br>
    <div class='dashboard_in'>
        <div class='row'>
            <div class='col-md-4 col-sm-12 col-xs-12'>
                <div class='dashboardBox clickable' onclick="window.location.href='<?= site_url('Inventory/pendingDeliveryOrderDashboard') ?>'">
                    <div class='leftSide'>
                        <h4><b>Pending</b></h4>
                        <p>Delivery order</p>
                    </div>
                    <div class='rightSide'>
                        <h3 id='pendingDeliveryOrders'>0</h3>
                        <p>&nbsp;</p>
                    </div>
                </div>
            </div>
            <div class='col-md-4 col-sm-12 col-xs-12'>
                <div class='dashboardBox clickable' onclick="window.location.href='<?= site_url('Inventory/pendingPurchaseOrderDashboard') ?>'">
                    <div class='leftSide'>
                        <h4><b>Pending</b></h4>
                        <p>Purchase Order</p>
                    </div>
                    <div class='rightSide'>
                        <h3 id='pendingPurchaseOrders'>0</h3>
                        <p>&nbsp;</p>
                    </div>
                </div>
            </div>
            <div class='col-md-4 col-sm-12 col-xs-12'>
                <div class='dashboardBox clickable' onclick="window.location.href='<?= site_url('Inventory/pendingSalesOrderDashboard') ?>'">
                    <div class='leftSide'>
                        <h4><b>Pending</b></h4>
                        <p>Sales Order</p>
                    </div>
                    <div class='rightSide'>
                        <h3 id='pendingSalesOrders'>0</h3>
                        <p>&nbsp;</p>
                    </div>
                </div>
            </div>
            <div class='col-md-4 col-sm-12 col-xs-12'>
                <div class='dashboardBox clickable' onclick="fetchPendingAssignment()">
                    <div class='leftSide'>
                        <h4><b>Pending</b></h4>
                        <p>Customer Assignment</p>
                    </div>
                    <div class='rightSide'>
                        <h3 id='unassignedCustomer'>0</h3>
                        <p>&nbsp;</p>
                    </div>
                </div>
            </div>
            <div class='col-md-12 col-sm-12 col-xs-12' style='margin-top:20px'>
                <label>Daily Shipments</label>
                <canvas id='chartWrapper' width="100" height="30"></canvas>
            </div>
        </div>
    </div>
</div>

<div class='alert_wrapper' id='pendingRouteWrapper'>
<button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
        <h3 style='font-family:bebasneue'>Pending Customer Route Assignment</h3>
        <table class='table table-bordered' id='routeTable'>
			<tr>
				<th>Name</th>
				<th>Address</th>
			</tr>
			<tbody id='routeTableContent'></tbody>
		</table>
        <p id='routeTableText'>There is no customer to be assigned.</p>
	</div>
</div>

<script>
	$(document).ready(function(){
		getChartNumbers();
	});

    function fetchPendingAssignment(){
        $.ajax({
            url:"<?= site_url('Route/getPendingAssignment') ?>",
            beforeSend:function(){
                $('#routeTableContent').html("");
            },
            success:function(response){
                if(response.length == 0){
                    $('#routeTable').hide();
                    $('#routeTableText').show();
                } else {
                    $('#routeTable').show();
                    $('#routeTableText').hide();

                    $.each(response, function(index, item){
                        var complete_address		= '';
                        var customer_name			= item.name;
                        complete_address		    += item.address;
                        var customer_city			= item.city;
                        var customer_number			= item.number;
                        var customer_rt				= item.rt;
                        var customer_rw				= item.rw;
                        var customer_postal			= item.postal_code;
                        var customer_block			= item.block;
                        var customer_id				= item.id;
            
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
                        $('#routeTableContent').append("<tr><td>" + item.name + "</td><td>" + complete_address + "</td>")
                    })
                }

                $('#pendingRouteWrapper').fadeIn(300, function(){
                    $('#pendingRouteWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
                });
            }
        })
    }

	function getChartNumbers(){
		$.ajax({
			url:"<?= site_url('Inventory/getDashboardItems') ?>",
			success:function(response){
				$('#pendingSalesOrders').html(numeral(response.salesOrders).format('0,0'));
				$("#pendingDeliveryOrders").html(numeral(response.deliveryOrders).format('0,0'));
				$('#pendingPurchaseOrders').html(numeral(response.purchaseOrders).format('0,0'));
                $('#unassignedCustomer').html(numeral(response.customer).format('0'));
			}
		})
	}

    $(document).ready(function(){
        $.ajax({
            url:"<?= site_url('Inventory/getDailyShipments') ?>",
            dataType: "json",
            success:function(response){
                var sentArray = [];
                var confirmedArray = [];
                var labelArray = [];

                $.each(response, function(index, item){
                    labelArray.push(item.label);
                    confirmedArray.push(item.confirmed);
                    sentArray.push(item.sent);
                });

                var ctx = document.getElementById('chartWrapper').getContext('2d');
                var myLineChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labelArray,
                        datasets: [{
                            label: 'Confirmed Delivery Order',
                            backgroundColor: 'rgba(225, 155, 60, 0.4)',
                            borderColor: 'rgba(225, 155, 60, 1)',
                            data: confirmedArray
                        }, {
                            label: 'Sent Delivery Order',
                            backgroundColor: 'rgba(1, 187, 0, 0.4)',
                            borderColor: 'rgba(1, 187, 0, 1)',
                            data: sentArray
                        }],
                    },
                    options: {
                        legend:{
                            display:true
                        }
                    }
                });

            }
        });
    });		
</script>
