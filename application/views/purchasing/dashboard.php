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
                <div class='dashboardBox'>
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

<script>
    $(document).ready(function(){
        calculateNeeds();
        calculatePendingOrders();
    })

	function calculateNeeds(){
		$.ajax({
			url:'<?= site_url('Purchasing/calculateNeeds') ?>',
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
        $('#payableDetailWrapper').fadeIn(300, function(){
            $('#payableDetailWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
        });
    })

    function calculatePendingOrders(){
        $.ajax({
            url:"<?= site_url('Purchasing/countIncompletePurchaseOrders') ?>",
            success:function(response){
                $('#orders').text(response);
            }
        })
    }

    function viewPendingItems(){
        $('#pendingItemsWraper').fadeIn(300, function(){
            $('#pendingItemsWraper .alert_box_slide').show("slide", { direction: "right" }, 250);
        });
    }
</script>