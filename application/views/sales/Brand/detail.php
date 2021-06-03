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

        <label>Customer's target</label>
        <p>6 monhts from</p>
        <div class='input_group'>
            <select class='form-control' id='customerTargetMonth'>
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
            <select class='form-control' id='customerTargetYear'>
            <?php for($i = 2020; $i <= date("Y"); $i++){ ?>
                <option value='<?= $i ?>' <?= $i == date("Y") ? 'selected': '' ?>><?= $i ?></option>
            <?php } ?>
            </select>
            <div class='input_group_append'>
                <button 
                    class='button button_default_dark' 
                    onclick='calculateCustomerTarget()'
                    id='calculateCustomerBoughtButton'>
                    <i class='fa fa-eye'></i>
                </button>
            </div>
        </div>
        <div id='lineChart'></div>

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
        getChartItems();
    })

    function getChartItems(){
        $.ajax({
            url:"<?= site_url('Brand/getChartItems') ?>",
            data:{
                range: 6,
                from: 0,
                brand: <?= $brand->id ?>
            },
            success:function(response){
                var result = JSON.parse(response);
                var labelArray = [];

                Object.values(result).forEach(function(targetItem){
                    var name = targetItem.name;
                    var valueArray = targetItem.value;
                    Object.values(valueArray).forEach(function(valueItem){
                        if(!labelArray.includes(valueItem.label))
                            labelArray.unshift(valueItem.label);
                    })
                });

                var ctx = document.getElementById('lineChart').getContext('2d');
				var myLineChart = new Chart(ctx, {
					type: 'line',
					data: {
						labels: labelArray,
						datasets: [{
							backgroundColor: 'rgba(225, 155, 60, 0.4)',
							borderColor: 'rgba(225, 155, 60, 1)',
							data: valueArray
						}, {
							backgroundColor: 'rgba(1, 187, 0, 0.3)',
							borderColor: 'rgba(1, 187, 0, 1)',
							data: targetArray
						}],
					},
					options: {
						legend:{
							display:false
						}
					}
				});
            }
        });
    }
    
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