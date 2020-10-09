<head>
    <title>Accounting</title>
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
    </style>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<div class='dashboard'>
    <br>
    <div class='dashboard_in'>
        <div class='row'>
            <div class='col-md-4 col-sm-12 col-xs-12'>
                <div class='dashboardBox clickable' onclick='window.location.href="<?= site_url('Invoice') ?>"'>
                    <div class='leftSide'>
                        <h4><b>Pending</b></h4>
                        <p>Invoice</p>
                    </div>
                    <div class='rightSide'>
                        <h3 id='pendingInvoice'></h3>
                        <p>&nbsp;</p>
                    </div>
                </div>
            </div>
            <div class='col-md-4 col-sm-12 col-xs-12'>
                <div class='dashboardBox'>
                    <div class='leftSide'>
                        <h4><b>Pending</b></h4>
                        <p>Debt</p>
                    </div>
                    <div class='rightSide'>
                        <h3 id='pendingDebt'></h3>
                        <p>&nbsp;</p>
                    </div>
                </div>
            </div>
			<div class='col-md-4 col-sm-12 col-xs-12'>
                <div class='dashboardBox' id='unassignedCustomersBox'>
                    <div class='leftSide'>
                        <h4><b>Unassigned</b></h4>
                        <p>Customers</p>
                    </div>
                    <div class='rightSide'>
                        <h3 id='pendingCustomers'></h3>
                        <p>&nbsp;</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	$(document).ready(function(){
		refreshInvoice();
		refreshDebt();
		refreshPendingCustomers();
	})
	function refreshInvoice(){
		$.ajax({
			url:'<?= site_url('Accounting/getPendingInvoice') ?>',
			success:function(response){
				$('#pendingInvoice').html(response);
			}
		})
	}

	function refreshDebt()
	{
		$.ajax({
			url:'<?= site_url('Accounting/getPendingDebt') ?>',
			success:function(response){
				$('#pendingDebt').html(response);
			}
		})
	}

	function refreshPendingCustomers()
	{
		$.ajax({
			url:'<?= site_url('Accounting/getPendingCustomers') ?>',
			success:function(response){
				var unassigned = response.unassigned;
				var total = response.total;
				$('#pendingCustomers').html(numeral(unassigned).format('0,0') + " / " + numeral(total).format('0,0'));
			}
		})
	}

	$('#unassignedCustomersBox').click(function(){
		if(<?= $user_login->access_level ?> > 1){
			window.location.href='<?= site_url('Customer/assignAccountantDashboard') ?>';
		}
	});
</script>
