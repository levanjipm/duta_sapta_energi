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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
                        <p>&nbsp;</p>
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
            </div>
            <div class='col-md-4 col-sm-12 col-xs-12'>
                <div class='dashboardBox'>
                    <div class='leftSide'>
                        <h4><b>Plafond</b></h4>
                        <p>Submission</p>
                    </div>
                    <div class='rightSide'>
                        <h3><?= number_format($plafondSubmission) ?></h3>
                        <p>&nbsp;</p>
                    </div>
                </div>
            </div>
            <div class='col-md-7 col-sm-12 col-xs-12'>
                <div id="salesChart" style='height:300px'></div>
            </div>
            <div class='col-md-5 col-sm-12 col-xs-12'>
                <div id="customerChart" style='height:300px'></div>
                <p id='customerChartText' style='margin-top:50px'>Data not available.</p>
            </div>
        </div>
    </div>
</div>
<script>
    var salesData = [];
    var customerData = [];
    $(document).ready(function(){
        refreshView();
    })
    function refreshView(){
        $.ajax({
            url:'<?= site_url('Sales/viewSalesByMonth') ?>',
            data:{
                offset: 6,
                range: 6
            },
            success:function(response){
                $.each(response, function(index, item){
                    var value = item.value;
                    var label = item.label;
                    var array = [label, value];
                    salesData.push(array);
                });
            }
        });

        $.ajax({
            url:'<?= site_url('Sales/viewSalesByCustomer') ?>',
            data:{
                offset: 5,
                month: <?= date('m') ?>,
                year: <?= date('Y') ?>
            },
            success:function(response){
                var headerArray = ["Customer", "Sales", { role: 'style'}];
                customerData.push(headerArray);
                var opacity = 1;
                $.each(response, function(index, item){
                    var value = parseFloat(item.value);
                    var label = item.name;
                    var styleString = "color: #01bb00; opacity: " + opacity;
                    var array = [label, value, styleString];
                    if(value > 0){
                        customerData.push(array);
                    }

                    opacity = opacity - 0.1;
                });

                if(customerData.length == 1){
                    $('#customerChart').hide();
                    $('#customerChartText').show();
                } else {
                    $('#customerChart').show();
                    $('#customerChartText').hide();
                }
            }
        })
    }

    google.charts.load('current', {packages: ['corechart', 'line']});
    google.charts.setOnLoadCallback(drawBasic);

    function drawBasic() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'X');
        data.addColumn('number', 'Sales');

        data.addRows(salesData);

        var options = {
            colors:['#E19B3C'],
            lineWidth: 4,
            pointSize: 10,
        };

        var salesChart = new google.visualization.LineChart(document.getElementById('salesChart'));

        salesChart.draw(data, options);

        var dataCustomer = google.visualization.arrayToDataTable(customerData);
        var view = new google.visualization.DataView(dataCustomer);

        var options = {
            bar: {groupWidth: "95%"},
            legend: { position: "none" },
        };

        var customerChart = new google.visualization.BarChart(document.getElementById("customerChart"));
        customerChart.draw(view, options);
    }
</script>