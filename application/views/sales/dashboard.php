<head>
    <title>Sales</title>
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
</head>
<div class='dashboard'>
    <br>
    <div class='dashboard_in'>
        <div class='row'>
            <div class='col-md-4 col-sm-12 col-xs-12'>
                <div class='dashboardBox'>
                    <div class='leftSide'>
                        <h4><b>Pending</b></h4>
                        <p>Sales order</p>
                    </div>
                    <div class='rightSide'>
                        <h3><?= number_format($incompleteSalesOrder) ?></h3>
                    </div>
                </div>
            </div>
            <div class='col-md-4 col-sm-12 col-xs-12'>
            <div class='dashboardBox'>
                <div class='leftSide'>
                    <h4><b>Active</b></h4>
                    <p>Customer</p>
                </div>
                <div class='rightSide'>
                    <h3><?= number_format($activeCustomer) ?> / <?= number_format($customer) ?></h3>
                    <p class='subtitleText'><?= date('d M Y') ?></p>
                </div>
            </div>
            <div class='col-md-4 col-sm-12 col-xs-12'>
            </div>
        </div>
    </div>
</div>