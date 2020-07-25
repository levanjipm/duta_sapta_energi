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
    function refresh_view(){
        $.ajax({
            url:'<?= site_url("Invoice/getUnconfirmedinvoice") ?>',
            success:function(response){
                console.log(response);
            }
        })
    }
</script>