<head>
    <title><?= $item->reference ?> Stock card</title>
</head>
<div class='dashboard'>
    <div class='dashboard_head'>
        <p style='font-family:museo'><a href='<?= site_url('Inventory') ?>' title='Inventory'><i class='fa fa-th'></i></a> /<a href='<?= site_url('Stock/view/Inventory') ?>'>Check stock</a> / <?= $item->reference ?> - <?= $item->name ?></p>
    </div>
    <br>
    <div class='dashboard_in'>
        <h3 style='font-family:bebasneue'><?= $item->reference ?></h3>
        <p><?= $item->name ?>
        <button class='button button_mini_tab' id='progressButton'><i class='fa fa-car'></i></button>
        <hr>
        <div id='stockTable'>
            <table class='table table-bordered'>
                <tr>
                    <th>Date</th>
                    <th>Document</th>
                    <th>Opponent</th>
                    <th>Quantity</th>
                    <th>Stock</th>
                </tr>
                <tbody id='stockTableContent'></tbody>
            </table>

            <select class='form-control' id='page' style='width:100px'>
                <option value='1'>1</option>
            </select>
        </div>
        <p id='stockTableText'>There is no data found.</p>
    </div>
</div>

<div class='alert_wrapper' id='progressWrapper'>
    <button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
        <h3 style='font-family:bebasneue'>Delivery On Progress</h3>
        <hr>
        <table class='table table-bordered'>
            <tr>
                <th>Customer</th>
                <th>Delivery Order</th>
                <th>Quantity</th>
            </tr>
            <tbody id='progressTableContent'></tbody>
        </table>
    </div>
</div>

<div class='alert_wrapper' id='documentWrapper'>
    <button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
        <label id='documentLabel'></label>
        <p id='documentNameP'></p>
        <p id='documentDateP'></p>

        <label id='opponentLabel'></label>
        <p id='opponentNameP'></p>
        <p id='opponentAddressP'></p>

        <label>Items</label>
        <table class='table table-bordered'>
            <tr>
                <th>Reference</th>
                <th>Name</th>
                <th>Quantity</th>
            </tr>
            <tbody id='tableItem'></tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function(){
        refresh_view();
        $('#progressButton').hide();
    });
    
    function refresh_view(){
        $.ajax({
            url:"<?= site_url('Stock/viewCard') ?>",
            data:{
                id:<?= $item->id ?>,
            },
            success:function(responseData){
                var response        = responseData.items;
                var i           = 0;
                var finalStock = 0;
                var itemCount = 0;
                $.each(response, function(index, item){
                    var name = item.name;
                    var quantity = parseInt(item.quantity);
                    var documentName = item.documentName;
                    var date = item.date;
                    var type    = item.documentType;
                    var id      = item.documentId;
                    
                    var page        = Math.floor(i / 10) + 1;
                    if(date != null){
                        finalStock += quantity;
                        if(type == "goodReceipt"){
                            $('#stockTableContent').prepend("<tr class='stockRow-" + page + "'><td>" + my_date_format(date) + "</td><td>" + documentName + "<button class='button button_mini_tab' onclick='viewDocument(" + id + ",1)'><i class='fa fa-eye'></i></button></td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>" + numeral(finalStock).format('0,0') + "</td></tr>");
                            itemCount++;
                        } else if(type == "salesReturn"){
                            $('#stockTableContent').prepend("<tr class='stockRow-" + page + "'><td>" + my_date_format(date) + "</td><td>" + documentName + "<button class='button button_mini_tab' onclick='viewDocument(" + id + ",2)'><i class='fa fa-eye'></i></button></td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>" + numeral(finalStock).format('0,0') + "</td></tr>");
                            itemCount++;
                        } else if(type == "event"){
                            $('#stockTableContent').prepend("<tr class='stockRow-" + page + "'><td>" + my_date_format(date) + "</td><td>" + documentName + "<button class='button button_mini_tab' onclick='viewDocument(" + id + ",3)'><i class='fa fa-eye'></i></button></td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>" + numeral(finalStock).format('0,0') + "</td></tr>");
                            itemCount++;
                        } else if(type == "deliveryOrder"){
                            $('#stockTableContent').prepend("<tr class='stockRow-" + page + "'><td>" + my_date_format(date) + "</td><td>" + documentName + "<button class='button button_mini_tab' onclick='viewDocument(" + id + ",4)'><i class='fa fa-eye'></i></button></td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>" + numeral(finalStock).format('0,0') + "</td></tr>");
                            itemCount++;
                        } else if(type == "purchaseReturn"){
                            $('#stockTableContent').prepend("<tr class='stockRow-" + page + "'><td>" + my_date_format(date) + "</td><td>" + documentName + "<button class='button button_mini_tab' onclick='viewDocument(" + id + ",5)'><i class='fa fa-eye'></i></button></td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>" + numeral(finalStock).format('0,0') + "</td></tr>");
                            itemCount++;
                        }

                        i++;
                    }
                });

                var progress            = responseData.progress;
                if(progress.length > 0){
                    $('#progressButton').show();
                }
                $.each(progress, function(index, item){
                    var customerName        = item.customerName;
                    var date                = item.date;
                    var name                = item.name;
                    var quantity            = parseInt(item.quantity);

                    $('#progressTableContent').append("<tr><td>" + customerName + "</td><td><label>" + name + "</label><p>" + my_date_format(date) + "</td><td>" + numeral(quantity).format('0,0') + "</td></tr>");
                })

                var pages = Math.max(Math.ceil(itemCount/20),1);
                $('#page').html("");
                for(i = 1; i <= pages; i++){
                    $('#page').append("<option value='" + i + "'>" + i + "</option>");
                }

                if(itemCount > 0){
                    $('#stockTable').show();
                    $('#stockTableText').hide();
                } else {
                    $('#stockTable').hide();
                    $('#stockTableText').show();
                }

                $('.tr[id^="stockRow-"]').each(function(){
                    $(this).hide();
                })

                $('.stockRow-1').each(function(){
                    $(this).show();
                })
            }
        })
    }

    $('#progressButton').click(function(){
        $('#progressWrapper').fadeIn(300, function(){
            $('#progressWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
        });
    })

    $('#page').change(function(){
        $('#stockTableContent').hide();
        $('.stockRow-' + $('#page').val()).show();
    })

    function viewDocument(id, type)
    {
        switch(type){
            case 1:
                //Good receipt//
                $.ajax({
                    url:"<?= site_url('Good_receipt/showById') ?>",
                    data:{
                        id: id
                    },
                    success:function(response){
                        var general     = response.general;
                        var good_receipt_date		= general.date;
                        var good_receipt_name		= general.name;
                        var complete_address		= '';
                        var supplier_name			= general.supplier_name;
                        var complete_address		= '';
                        var supplier_name			= general.supplier_name;
                        complete_address			+= general.address;
                        var supplier_city			= general.city;
                        var supplier_number			= general.number;
                        var supplier_rt				= general.rt;
                        var supplier_rw				= general.rw;
                        var supplier_postal			= general.postal_code;
                        var supplier_block			= general.block;

                        if(supplier_number != null){
                            complete_address	+= ' No. ' + supplier_number;
                        }
                        
                        if(supplier_block != null && supplier_block != "000"){
                            complete_address	+= ' Blok ' + supplier_block;
                        }
                    
                        if(supplier_rt != '000'){
                            complete_address	+= ' RT ' + supplier_rt;
                        }
                        
                        if(supplier_rw != '000' && supplier_rt != '000'){
                            complete_address	+= ' /RW ' + supplier_rw;
                        }
                        
                        if(supplier_postal != null){
                            complete_address	+= ', ' + supplier_postal;
                        }

                        $('#documentLabel').html("Good Receipt");
                        $('#documentNameP').html(good_receipt_name);
                        $('#documentDateP').html(my_date_format(good_receipt_date));

                        $('#opponentLabel').html("Supplier");
                        $('#opponentNameP').html(supplier_name);
                        $('#opponentAddressP').html(complete_address);

                        var items       = response.items;
                        $('#tableItem').html("");
                        $.each(items, function(index, item){
                            var reference       = item.reference;
                            var name            = item.name;
                            var quantity        = item.quantity;

                            $('#tableItem').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td></tr>");
                        })
                    },
                    complete:function(){
                        $('#documentWrapper').fadeIn(300, function(){
                            $('#documentWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
                        });
                    }
                });
                break;
            case 2:
                //Sales Return//
                $.ajax({
                    url:"<?= site_url('Sales_return/getReceivedById') ?>",
                    data:{
                        id:id
                    },
                    success:function(response){
                        var customer                = response.customer;
                        var complete_address		= '';
                        var customer_name			= customer.name;
                        complete_address			+= customer.address;
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

                        $('#opponentLabel').html("Customer");
                        $('#opponentNameP').html(customer_name);
                        $('#opponentAddressP').html(complete_address);

                        var items       = response.items;
                        $('#tableItem').html("");
                        $.each(items, function(index, item){
                            var reference       = item.reference;
                            var name            = item.name;
                            var quantity        = item.quantity;
                            $('#tableItem').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td></tr>");
                        });

                        var general         = response.general;
                        var date            = general.date;
                        var name            = general.name;

                        $('#documentLabel').html("Sales Return");
                        $('#documentNameP').html(name);
                        $('#documentDateP').html(my_date_format(date));
                    },
                    complete:function(){
                        $('#documentWrapper').fadeIn(300, function(){
                            $('#documentWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
                        });
                    }
                });
                break;
            case 3:
                //Event//
                $.ajax({
                    url:"<?= site_url('Inventory_case/showById') ?>",
                    data:{
                        id: id
                    },
                    success:function(response){
                        var general     = response.general;
                        var name        = general.name;
                        var date        = general.date;
                        var type        = general.type;

                        $('#documentLabel').html("Event");
                        $('#documentNameP').html(name);
                        $('#documentDateP').html(my_date_format(date));

                        $('#opponentLabel').html("Internal Transaction");

                        if(type == 1){
                            var text = 'Lost goods';
                        } else if(type == 2){
                            var text = 'Found goods';
                        } else if(type == 3){
                            var text = 'Dematerialized goods';
                        } else if(type == 4){
                            var text = 'Materialized goods';
                        }

                        $('#opponentNameP').html(text);
                        $('#opponentAddressP').html("");

                        var items       = response.details;
                        $('#tableItem').html("");
                        $.each(items, function(index, item){
                            var reference       = item.reference;
                            var name            = item.name;
                            var transaction     = item.transaction;
                            var quantity        = item.quantity;

                            if(transaction == "IN"){
                                $('#tableItem').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td></tr>");
                            } else if(transaction == "OUT"){
                                $('#tableItem').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>- " + numeral(quantity).format('0,0') + "</td></tr>");
                            }
                        })

                    }, 
                    complete:function(){
                        $('#documentWrapper').fadeIn(300, function(){
                            $('#documentWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
                        });
                    }
                });
                break;
            case 4:
                //Delivery Order//
                $.ajax({
                    url:"<?= site_url("Delivery_order/getById") ?>",
                    data:{
                        id: id
                    },
                    success:function(response){
                        var general     = response.general;
                        var name        = general.name;
                        var date        = general.date;
                        $('#documentLabel').html("Delivery Order");
                        $('#documentNameP').html(name);
                        $('#documentDateP').html(my_date_format(date));

                        var customer				= response.customer;
                        var complete_address		= '';
                        var customer_name			= customer.name;
                        complete_address			+= customer.address;
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

                        $('#opponentLabel').html("Customer");
                        $('#opponentNameP').html(customer_name);
                        $('#opponentAddressP').html(complete_address);

                        var items       = response.items;
                        $('#tableItem').html("");
                        $.each(items, function(index, item){
                            var reference       = item.reference;
                            var name            = item.name;
                            var quantity        = item.quantity;
                            $('#tableItem').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td></tr>");
                        })
                    },
                    complete:function(){
                        $('#documentWrapper').fadeIn(300, function(){
                            $('#documentWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
                        });
                    }
                });
                break;
            case 5: 
                //Purchase Return//
                $.ajax({
                    url:"<?= site_url('Purchase_return/getSentById') ?>",
                    data:{
                        id: id
                    },
                    success:function(response){
                        var general     = response.general;
                        var name        = general.name;
                        var date        = general.date;
                        
                        $('#documentLabel').html("Purchase Return");
                        $('#documentNameP').html(name);
                        $('#documentDateP').html(my_date_format(date));

                        var supplier                = response.supplier;
                        var complete_address		= '';
                        var supplier_name			= supplier.name;
                        complete_address			+= supplier.address;
                        var supplier_city			= supplier.city;
                        var supplier_number			= supplier.number;
                        var supplier_rt				= supplier.rt;
                        var supplier_rw				= supplier.rw;
                        var supplier_postal			= supplier.postal_code;
                        var supplier_block			= supplier.block;

                        if(supplier_number != null){
                            complete_address	+= ' No. ' + supplier_number;
                        }
                        
                        if(supplier_block != null && supplier_block != "000"){
                            complete_address	+= ' Blok ' + supplier_block;
                        }
                    
                        if(supplier_rt != '000'){
                            complete_address	+= ' RT ' + supplier_rt;
                        }
                        
                        if(supplier_rw != '000' && supplier_rt != '000'){
                            complete_address	+= ' /RW ' + supplier_rw;
                        }
                        
                        if(supplier_postal != null){
                            complete_address	+= ', ' + supplier_postal;
                        }

                        $('#opponentLabel').html("Supplier");
                        $('#opponentNameP').html(supplier_name);
                        $('#opponentAddressP').html(complete_address);

                        var items       = response.items;
                        $('#tableItem').html("");
                        $.each(items, function(index, item){
                            var reference       = item.reference;
                            var name            = item.name;
                            var quantity        = item.quantity;
                            $('#tableItem').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td></tr>");
                        })
                    },
                    complete:function(){
                        $('#documentWrapper').fadeIn(300, function(){
                            $('#documentWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
                        });
                    }
                });
                break;
        }
    }
</script>
