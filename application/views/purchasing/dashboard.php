<head>
    <title>Purchasing</title>
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
            <div class='col-lg-4 col-md-6 col-sm-12 col-xs-12'>
                <div class='dashboardBox' onclick='viewPendingItems()'>
                    <div class='leftSide'>
                        <h4><b>Pending</b></h4>
                        <p>Items</p>
                    </div>
                    <div class='rightSide'>
                        <h3 id='needs'></h3>
                        <p>&nbsp;</p>
                    </div>
                </div>
            </div>
            <div class='col-lg-4 col-md-6 col-sm-12 col-xs-12'>
                <div class='dashboardBox' onclick='viewPendingPurchaseOrders()'>
                    <div class='leftSide'>
                        <h4><b>Pending</b></h4>
                        <p>Purchase orders</p>
                    </div>
                    <div class='rightSide'>
                        <h3 id='orders'></h3>
                        <p class='subtitleText'><?= date('d M Y') ?></p>
                    </div>
                </div>
            </div>
			<div class='col-lg-4 col-md-6 col-sm-12 col-xs-12'>
                <div class='dashboardBox' onclick='viewRestockItems()'>
                    <div class='leftSide'>
                        <h4><b>Restock</b></h4>
                        <p>Items</p>
                    </div>
                    <div class='rightSide'>
                        <h3 id='restock'></h3>
                        <p class='subtitleText'>&nbsp;</p>
                    </div>
                </div>
            </div>
			<div class='col-lg-4 col-md-6 col-sm-12 col-xs-12'>
                <div class='dashboardBox'>
                    <div class='leftSide'>
                        <h4><b>Return</b></h4>
                        <p>Submission</p>
                    </div>
                    <div class='rightSide'>
                        <h3 id='return'></h3>
                        <p class='subtitleText'>&nbsp;</p>
                    </div>
                </div>
            </div>
		</div>
		<div class='row'>
			<div class='col-sm-7'>
				<div id='purchaseChart'></div>
			</div>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='pendingItemsWraper'>
    <button class='slide_alert_close_button'>&times;</button>
    <div class='alert_box_slide'>
        <h3 style='font-family:bebasneue'>Items needed.</h3>
        <hr>
        <form action='<?= site_url('Purchase_order/createFromDashboard') ?>' method="POST" id='purchaseOrderForm'>
            <div id='itemTable'>
                <table class='table table-bordered'>
                    <tr>
                        <th>Reference</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                    <tbody id='itemTableContent'></tbody>
                </table>
            </div>
            <p id='itemTableText'>No items need to be bought.</p>

            <input type='hidden' id='totalItem' min='1'>
            <button type='button' id='createPurchaseOrderButton' class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
        </form>
    </div>
</div>

<div class='alert_wrapper' id='pendingOrdersWrapper'>
    <button class='slide_alert_close_button'>&times;</button>
    <div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Pending purchase orders</h3>
		<hr>
		<div id='pendingPurchaseOrderTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Supplier</th>
					<th>Progress</th>
				</tr>
				<tbody id='pendingPurchaseOrderTableContent'></tbody>
			</table>
		</div>
		<p id='pendingPurchaseOrderTableText'>There is no pending purchase order.</p>
	</div>
</div>

<div class='alert_wrapper' id='restockItemsWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
    <div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Restock Items</h3>
		<hr>
		<div id='restockTable'>
			<form action='<?= site_url('Purchase_order/createFromDashboard') ?>' method="POST" id='restockForm'>
				<table class='table table-bordered'>
					<tr>
						<th>Reference</th>
						<th>Name</th>
						<th>3 months</th>
						<th>6 months</th>
						<th>Stock</th>
						<th>Action</th>
					</tr>
					<tbody id='restockTableContent'></tbody>
				</table>

				<button type='button' class='button button_default_dark' id='buttonSubmit' onclick='restockItem()' style='display:none'><i class='fa fa-long-arrow-right'></i></button>
			</form>
		</div>
		<p id='restockTableText'>There is no item to be restocked.</p>
	</div>
</div>
<script>
	var purchaseData = [];
    $(document).ready(function(){
        calculateNeeds();
        calculatePendingOrders();
		calculateRestockItems();
		calculateReturnSubmission();
		viewPurchaseByMonth();
    });

	$('#purchaseOrderForm').validate({
		ignore:"",
		rules: {"hidden_field": {min: 1}}
	});

	$('#restockForm').validate();

	function calculateNeeds(){
		$.ajax({
			url:'<?= site_url('Purchasing/calculateNeeds') ?>',
			beforeSend:function(){
				$('#needs').html("<i class='fa fa-spin fa-spinner'></i>")
			},
			success:function(response){
				var needs		= response.length;
                $('#needs').text(needs);

                if(needs > 0){
                    $.each(response, function(index, item){
                        var name = item.name;
                        var reference = item.reference;
                        var quantity = item.quantity;
                        var itemId = item.id;

                        $("#itemTableContent").append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td><td><input type='checkbox' name='items[" + itemId + "]' onchange='updateCheck()' value='" + quantity + "'></td></tr>");

                        $('#itemTable').show();
                        $('#itemTableText').hide();
                    })
                } else {
                    $('#itemTable').hide();
                    $('#itemTableText').show();
                }

                $("#createPurchaseOrderButton").hide();
			}
		});
    };

	function viewPurchaseByMonth(){
		$.ajax({
			url:'<?= site_url('Purchasing/viewPurchaseByMonth') ?>',
			success:function(response){
				$.each(response, function(index, value){
					purchaseData.push([value.label, value.value]);
				})
			}
		})
	}
    
    function updateCheck(){
        var totalItemCount = 0;
        $("input[name^='items[']").each(function(){
            if($(this).is(':checked')){
                var value = $(this).val();
                totalItemCount += parseInt(value);
            }
        });

        if(totalItemCount > 0){
            $("#createPurchaseOrderButton").show();
        } else {
            $("#createPurchaseOrderButton").hide();
        }
    }

    $('#createPurchaseOrderButton').click(function(){
		if($('#purchaseOrderForm').valid()){
			$('#purchaseOrderForm').submit();
		};
    })

    function calculatePendingOrders(){
        $.ajax({
            url:"<?= site_url('Purchasing/countIncompletePurchaseOrders') ?>",
			beforeSend:function(){
				$('#orders').html("<i class='fa fa-spin fa-spinner'></i>")
			},
            success:function(response){
                $('#orders').text(response);
            }
        })
    }

	function calculateRestockItems(){
		$.ajax({
			url:"<?= site_url('Purchasing/countRestockItems') ?>",
			beforeSend:function(){
				$('#restock').html("<i class='fa fa-spin fa-spinner'></i>")
			},
			success:function(response){
				$('#restockTableContent').html("");
				var itemCount = 0;
				$.each(response, function(index, item){
					var stock3 = item[0];
					var stock6 = item[1];
					var stock = item[2];
					var bought	= item[3];
					var itemDetail = item[4];
					var reference		= itemDetail.reference;
					var name			= itemDetail.name;

					$('#restockTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(stock3).format('0,0.0000') + "</td><td>" + numeral(stock6).format('0,0.0000') + "</td><td><p>" + numeral(stock).format('0,0') + "</p><p>Pending orders: " + numeral(bought).format('0,0') + "</p></td><td><input type='number' class='form-control' name='items[" + index + "]' id='itemsRestock-" + index + "' required min='0' onchange='checkQuantity()'></td></tr>");
					itemCount++;
				});

				if(itemCount > 0){
					$('#restockTable').show();
					$('#restockTableText').hide();
				} else {
					$('#restockTable').hide();
					$('#restockTableText').show();
				}

				$('#restock').html(numeral(itemCount).format('0,0'));
			}
		})
	}

	function calculateReturnSubmission(){
		$.ajax({
			url:"<?= site_url('Purchasing/countUnconfirmedPurchaseReturn') ?>",
			beforeSend:function(){
				$('#return').html("<i class='fa fa-spin fa-spinner'></i>")
			},
            success:function(response){
                $('#return').text(response);
            }
		})
	}

	function checkQuantity(){
		var totalQuantity = 0;
		$('input[id^="itemsRestock-"]').each(function(){
			totalQuantity += parseInt($(this).val());
		});

		if(totalQuantity > 0){
			$('#buttonSubmit').show();
		} else {
			$('#buttonSubmit').hide();
		}
	}

	$('#buttonSubmit').click(function(){
		if($('#restockForm').valid()){
			$('#restockForm').submit();
		}
	})

    function viewPendingItems(){
        $('#pendingItemsWraper').fadeIn(300, function(){
            $('#pendingItemsWraper .alert_box_slide').show("slide", { direction: "right" }, 250);
        });
    }

	function viewRestockItems(){
		$('#restockItemsWrapper').fadeIn(300, function(){
            $('#restockItemsWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
        });
	}

	function viewPendingPurchaseOrders(){
		window.location.href='<?= site_url('Purchase_order/pending') ?>';
	}

	google.charts.load('current', {packages: ['corechart', 'line']});
    google.charts.setOnLoadCallback(drawBasic);

    function drawBasic() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'X');
        data.addColumn('number', 'Purchase');

        data.addRows(purchaseData);

        var options = {
            colors:['#E19B3C'],
            lineWidth: 4,
            pointSize: 10,
        };

        var purchaseChart = new google.visualization.LineChart(document.getElementById('purchaseChart'));

        purchaseChart.draw(data, options);
	}
</script>
