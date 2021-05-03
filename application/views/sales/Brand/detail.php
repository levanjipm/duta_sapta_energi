<head>
	<title><?= $brand->name ?> Brand</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-line-chart'></i></a> /<a href='<?= site_url('Brand') ?>'>Brand</a>/ <?= $brand->name ?></p>
	</div>
	<br>
	<div class='dashboard_in'>
        <label>Name</label>
        <p><?= $brand->name ?></p>

        <label>Customer's target</label>
        <p></p>

        <label>Customer bought this brand</label>
        <div class='input_group'>
            <select class='form-control' id='customerBoughtMonth'>
                <option value='1'>January</option>
                <option value='2'>February</option>
                <option value='3'>March</option>
                <option value='4'>April</option>
                <option value='5'>May</option>
                <option value='6'>June</option>
                <option value='7'>July</option>
                <option value='8'>August</option>
                <option value='9'>September</option>
                <option value='10'>October</option>
                <option value='11'>November</option>
                <option value='12'>December</option>
            </select>
            <select class='form-control' id='customerBoughtYear'>
            <?php for($i = 2020; $i <= date("Y"); $i++){ ?>
                <option value='<?= $i ?>'><?= $i ?></option>
            <?php } ?>
            </select>
            <div class='input_group_append'>
                <button 
                    class='button button_default_dark' 
                    onclick='calculateCustomerBought()'
                    id='calculateCustomerBoughtButton'>
                    <i class='fa fa-eye'></i>
                </button>
            </div>
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
    </div>
</div>
<script>
    $('document').ready(function(){
        $('#customerBoughtTable').hide();
        $('#customerBoughtTableText').show();
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
            }, success:function(response){
                let totalCount = 0;

                $.each(JSON.parse(response), function(index, data){
                    let count = parseInt(data.count);
                    let name = data.name;

                    $('#customerBoughtTableBody').append("<tr><td>" + name + "</td><td>" + numeral(count).format("0,0") + "</td></tr>");

                    totalCount += count;
                })

                if(totalCount > 0){
                    $('#customerBoughtTableText').hide();
                    $('#customerBoughtTable').show();
                } else {
                    $('#customerBoughtTableText').show();
                    $('#customerBoughtTable').hide();
                }

                $('#calculateCustomerBoughtButton').attr('disabled', false);
            }
        })
    }
</script>