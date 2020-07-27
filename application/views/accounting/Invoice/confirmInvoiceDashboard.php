<head>
    <title>Invoice - Confirm invoice</title>
</head>
<div class='dashboard'>
    <div class='dashboard_head'>
    <p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-bar-chart'></i></a> /<a href='<?= site_url('Invoice') ?>'>Invoice </a> /Confirm invoice</p>
    </div>
    <br>
    <div class='dashboard_in'>
        <div id='invoiceTable'>
            <table class='table table-bordered'>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Customer</th>
                    <th>Action</th>
                </tr>
                <tbody id='invoiceTableContent'></tbody>
            </table>
        </div>
        <p id='invoiceTableText'>There is no invoice to be confirmed.</p>
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
                $('#invoiceTableContent').html('');

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
                        $('#invoiceTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td>" + complete_address + "</td><td><button class='button button_default_dark'><i class='fa fa-eye'></i></button></td></tr>");

                        invoiceCount++;
                    };
                    
                    if(invoiceCount > 0){
                        $('#invoiceTable').show();
                        $('#invoiceTableText').hide();
                    } else {
                        $('#invoiceTable').hide();
                        $('#invoiceTableText').show();
                    }
                })
            }
        })
    }
</script>