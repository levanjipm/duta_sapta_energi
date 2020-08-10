<head>
    <title><?= $item->reference ?> Stock card</title>
</head>
<div class='dashboard'>
    <div class='dashboard_head'>
        <p style='font-family:museo'><a href='<?= site_url('Inventory') ?>' title='Inventory'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Stock/view/inventory') ?>'>Check stock</a> / <?= $item->reference ?> - <?= $item->name ?></p>
    </div>
    <br>
    <div class='dashboard_in'>
        <h3 style='font-family:bebasneue'><?= $item->reference ?></h3>
        <p><?= $item->name ?>
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
<script>
    $(document).ready(function(){
        refresh_view();
    })
    
    function refresh_view(){
        $.ajax({
            url:"<?= site_url('Stock/viewCard') ?>",
            data:{
                id:<?= $item->id ?>,
                page: $("#page").val()
            },
            success:function(response){
                console.log(response);
            }
        })
    }
</script>