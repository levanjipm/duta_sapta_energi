<head>
	<title><?= $brand->name ?> Brand</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-line-chart'></i></a> /<a href='<?= site_url('Brand') ?>'>Brand</a>/ <?= $brand->name ?></p>
	</div>
	<br>
	<div class='dashboard_in'>
        <label>Name</label>
        <p><?= $brand->name ?></p>

        <label>Customer bought this brand</label>
        <div class='input_group'>
            <select class='form-control' id='customerBoughtMonth'>
                <option <?= date('m') == 1 ? 'selected' : '' ?> value='1'>January</option>
                <option <?= date('m') == 2 ? 'selected' : '' ?> value='2'>February</option>
                <option <?= date('m') == 3 ? 'selected' : '' ?> value='3'>March</option>
                <option <?= date('m') == 4 ? 'selected' : '' ?> value='4'>April</option>
                <option <?= date('m') == 5 ? 'selected' : '' ?> value='5'>May</option>
                <option <?= date('m') == 6 ? 'selected' : '' ?> value='6'>June</option>
                <option <?= date('m') == 7 ? 'selected' : '' ?> value='7'>July</option>
                <option <?= date('m') == 8 ? 'selected' : '' ?> value='8'>August</option>
                <option <?= date('m') == 9 ? 'selected' : '' ?> value='9'>September</option>
                <option <?= date('m') == 10 ? 'selected' : '' ?> value='10'>October</option>
                <option <?= date('m') == 11 ? 'selected' : '' ?> value='11'>November</option>
                <option <?= date('m') == 12 ? 'selected' : '' ?> value='12'>December</option>
            </select>
            <select class='form-control' id='customerBoughtYear'>
            <?php for($i = 2020; $i <= date("Y"); $i++){ ?>
                <option value='<?= $i ?>' <?= $i == date("Y") ? 'selected': '' ?>><?= $i ?></option>
            <?php } ?>
            </select>
        </div>
        <br>
        <table class='table table-bordered' id='customerBoughtTable'>
            <tr>
                <th>Area</th>
                <th>Count</th>
            </tr>
            <tbody id='customerBoughtTableBody'>
            </tbody>
        </table>
        <p id='customerBoughtTableText'>There is no customer found.</p>

        <table class='table table-bordered' id='itemClassTable'>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Value</th>
            </tr>
            <tbody id='itemClassTableBody'>
            </tbody>
        </table>
        <p id='itemClassTableText'>There is no item class found.</p>
    </div>
</div>
<script>
    $('document').ready(function(){
        $('#customerBoughtTable').hide();
        $('#customerBoughtTableText').show();
        calculateCustomerBought();
    })

    $('#customerBoughtMonth').change(function(){
        calculateCustomerBought();
    });

    $('#customerBoughtYear').change(function(){
        calculateCustomerBought();
    })
    
    function calculateCustomerBought(){
        let month = $('#customerBoughtMonth').val();
        let year = $('#customerBoughtYear').val();

        $.ajax({
            url:'<?= site_url('Brand/customerBought') ?>',
            data:{
                month: month,
                year: year,
                id: <?= $brand->id ?>
            },
            beforeSend:function(){
                $('#calculateCustomerBoughtButton').attr('disabled', true);
                $('#customerBoughtTableBody').html("");
                $('#itemClassTableBody').html("");
            }, success:function(response){
                var res         = JSON.parse(response);
                let totalCount = 0;
                var itemCount = 0;
                var totalValue      = 0;

                $.each(res.customerArea, function(index, data){
                    let count = parseInt(data.count);
                    let name = data.name;

                    $('#customerBoughtTableBody').append("<tr><td>" + name + "</td><td>" + numeral(count).format("0,0") + "</td></tr>");

                    totalCount += count;
                })

                $.each(res.valueType, function(index, data){
                    var value = parseFloat(data.value);
                    var name  = data.name;
                    var description = data.description;

                    $('#itemClassTableBody').append("<tr><td>" + name + "</td><td>" + description + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td></tr>");
                    totalValue  += value;
                    itemCount++;
                })

                $('#itemClassTableBody').append("<tr><td colspan='2'><strong>Total</strong></td><td>Rp. " + numeral(totalValue).format('0,0.00') + "</td></tr>");

                if(totalCount > 0){
                    $('#customerBoughtTableText').hide();
                    $('#customerBoughtTable').show();
                } else {
                    $('#customerBoughtTableText').show();
                    $('#customerBoughtTable').hide();
                }

                if(itemCount > 0){
                    $('#itemClassTableText').hide();
                    $('#itemClassTable').show();
                } else {
                    $('#itemClassTableText').show();
                    $('#itemClassTable').hide();
                }

                $('#calculateCustomerBoughtButton').attr('disabled', false);
            }
        })
    }
</script>