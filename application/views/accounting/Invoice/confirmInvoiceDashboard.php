<head>
    <title>Invoice - Confirm invoice</title>
</head>
<div class='dashboard'>
    <div class='dashboard_head'>
    <p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-bar-chart'></i></a> /<a href='<?= site_url('Invoice') ?>'>Invoice </a> /Confirm invoice</p>
    </div>
    <br>
    <div class='dashboard_in'>
        <div id='retailInvoiceTable'>
            <table class='table table-bordered'>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Customer</th>
                    <th>Action</th>
                </tr>
                <tbody id='retailInvoiceTableContent'></tbody>
            </table>
        </div>
        <p id='retailInvoiceTableText'>There is no invoice to be confirmed.</p>
    </div>
</div>
<div class='alert_wrapper' id='invoice_alert'>
    <button class='button slide_alert_close_button'>&times;</button>
    <div class='alert_box_slide'>
        <h3 style='font-family:bebasneue'>Confirm invoice</h3>
        <hr>

        <div id='taxInvoiceWrapper'>
            <label>Tax invoice</label>
            <input type='text' class='form-control' id='taxInvoice'>
        </div>

        <label>Customer</label>
        <p id='customer_name_p'></p>
        <p id='customer_address_p'></p>
        <p id='customer_city_p'></p>

        <label>Invoice</label>
        <p id='invoice_name_p'></p>
        <p id='invoice_date_p'></p>

        <label>Delivery order</label>
        <p id='delivery_order_name_p'></p>

        <label>Other</label>
        <p id='invoicing_method_p'></p>
        <p id='taxing_p'></p>

        <label>Items</label>
        <table class='table table-bordered'>
            <tr>
                <th>Reference</th>
                <th>Name</th>
                <th>Price list</th>
                <th>Discount</th>
                <th>Net price</th>
                <th>Quantity</th>
                <th>Total price</th>
            </tr>
            <tbody id='deliveryOrderTableContent'></tbody>
        </table>

        <input type='hidden' id='invoice_id'>
        <button class='button button_default_dark' title='Confirm invoice' onclick='confirmInvoice()'><i class='fa fa-long-arrow-right'></i></button>
   
        <div class='notificationText danger' id='confirmFailedNotification'><p>Failed to confirm invoice.</p></div>
    </div>
</div>
<script>
    $(document).ready(function(){
        refresh_view();
    });

    function refresh_view(){
        $.ajax({
            url:'<?= site_url("Invoice/getUnconfirmedinvoice") ?>',
            success:function(response){
                var invoiceCount = 0;
                $('#retailInvoiceTableContent').html('');

                $.each(response, function(index, value){
                    var id = value.id;
                    var date = value.date;
                    var name = value.name;
                    var customer = value.customer;

                    var customer_name = customer.name;
                    var complete_address = customer.address;
                    var customer_number = customer.number;
                    var customer_block = customer.block;
                    var customer_rt = customer.rt;
                    var customer_rw = customer.rw;
                    var customer_city = customer.city;
                    var customer_postal = customer.postal;
                    
                    if(customer_number != null){
                        complete_address	+= ' No. ' + customer_number;
                    }
                    
                    if(customer_block != null){
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

                    var deliveryOrder = value.deliveryOrder;
                    var isSent = deliveryOrder.is_sent;
                    if(isSent == 1){
                        $('#retailInvoiceTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>  " + name + "</td><td><label>" + customer_name + "</label><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><button class='button button_default_dark' onclick='viewInvoiceById(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");

                        invoiceCount++;
                    };
                });

                if(invoiceCount > 0){
                    $('#retailInvoiceTable').show();
                    $('#retailInvoiceTableText').hide();
                } else {
                    $('#retailInvoiceTable').hide();
                    $('#retailInvoiceTableText').show();
                }
            }
        })
    }

    function viewInvoiceById(n){
        $.ajax({
            url:"<?= site_url('Invoice/getById') ?>",
            data:{
                id: n
            },
            success:function(response){
                $('#invoice_id').val(n);
                var customer = response.customer;                
                var customer_name = customer.name;
                var complete_address = customer.address;
                var customer_number = customer.number;
                var customer_block = customer.block;
                var customer_rt = customer.rt;
                var customer_rw = customer.rw;
                var customer_city = customer.city;
                var customer_postal = customer.postal;
                
                if(customer_number != null){
                    complete_address	+= ' No. ' + customer_number;
                }
                
                if(customer_block != null){
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

                $('#customer_name_p').html(customer_name);
                $('#customer_address_p').html(complete_address);
                $('#customer_city_p').html(customer_city);

                var invoice = response.invoice;
                var name = invoice.name;
                var date = invoice.date;
                
                $('#invoice_name_p').html(name);
                $('#invoice_date_p').html(my_date_format(date));

                var delivery_order = response.delivery_order;
                $('#delivery_order_name_p').html(delivery_order.name);

                var sales_order = response.sales_order;
                var invoicing_method = sales_order.invoicing_method;
                if(invoicing_method == 1){
                    var invoicing_text = "Retail method";
                } else if(invoicing_method == 2){
                    var invoicing_text = "Coorporate method";
                };

                var taxing = sales_order.taxing;
                if(taxing == 1){
                    var taxing_text = "Taxable sales";
                    $('#taxInvoiceWrapper').show();
                    $('#taxInvoice').attr('required', true);
                } else if(taxing == 0){
                    var taxing_text = "Non-taxable sales";
                    $('#taxInvoiceWrapper').hide();
                    $('#taxInvoice').attr('required', false);
                }

                $('#invoicing_method_p').html(invoicing_text);
                $('#taxing_p').html(taxing_text);

                var items = response.items;
                var invoiceValue = 0;
                $('#deliveryOrderTableContent').html('');
                $.each(items, function(index, item){
                    var name = item.name;
                    var reference = item.reference;
                    var quantity = parseInt(item.quantity);
                    var price_list = parseFloat(item.price_list);
                    var discount = parseFloat(item.discount);
                    var net_price = price_list * (100 - discount) / 100;
                    var total_price = quantity * net_price;

                    invoiceValue += total_price;

                    $('#deliveryOrderTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>Rp. " + numeral(price_list).format('0,0.00') + "</td><td>" + numeral(discount).format('0,0.00') + "%</td><td>Rp. " + numeral(net_price).format('0,0.00') + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(total_price).format('0,0.00') + "</td></tr>");
                })

                $('#deliveryOrderTableContent').append("<tr><td colspan='4'></td><td colspan='2'><strong>Total</strong></td><td>Rp. " + numeral(invoiceValue).format('0,0.00') + "</td></tr>");

                $('#invoice_alert').fadeIn(300, function(){
                    $('#invoice_alert .alert_box_slide').show("slide", { direction: "right" }, 250);
                });
            }
        })
    }

    function confirmInvoice(){
        $.ajax({
            url:"<?= site_url('Invoice/confirmById') ?>",
            data:{
                id: $('#invoice_id').val(),
                taxInvoice: $('#taxInvoice').val()
            },
            type:'POST',
            beforeSend:function(){
                $('button').attr('disabled', true);
            },
            success:function(response){
                $('button').attr('disabled', false);
                if(response == 1){
                    refresh_view();
                    $('#invoice_alert .slide_alert_close_button').click();
                } else {
                    refresh_view();
                    $('#confirmFailedNotification').fadeIn(250);
                    setTimeout(function(){
                        $('#confirmFailedNotification').fadeOut(250);
                    }, 1000)
                }
            }
        })
    }

    $('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
</script>