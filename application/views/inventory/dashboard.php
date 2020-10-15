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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
                <div class='dashboardBox'>
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
                <div class='dashboardBox'>
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
        </div>
    </div>
</div>
<script>
	$(document).ready(function(){
		getChartNumbers();
	});

	function getChartNumbers(){
		$.ajax({
			url:"<?= site_url('Inventory/getDashboardItems') ?>",
			success:function(response){
				$('#pendingSalesOrders').html(numeral(response.salesOrders).format('0,0'));
				$("#pendingDeliveryOrders").html(numeral(response.deliveryOrders).format('0,0'));
				$('#pendingPurchaseOrders').html(numeral(response.purchaseOrders).format('0,0'));
			}
		})
	}
</script>
