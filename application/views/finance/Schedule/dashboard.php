<div class='dashboard'>
    <br>
    <div class='dashboard_in'>
        <div class='row'>
            <div class='col-12'>
                <input type='text' class='form-control' id='search'>
                <br>
                <table class='table table-bordered'>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>Schedule</th>
                        </tr>
                    </thead>
                </table>
                <br>
                <select class='form-control' id='page' style='width:100px'>
                    <option value='1'>1</option>
                </select>
            </div>
        </div>
    </div>
</div>
<script>
    $('document').ready(function(){
        refreshView();
    })

    function refreshView(page = $('#page').val()){
        $.ajax({
            url:"<?= site_url('Schedule/getItems') ?>",
            data:{
                page: page,
                term: $('#search').val()
            },
            beforeSend:function(){
                $('input').attr('disabled', true);
                $('select').attr('disabled', true);
            },  
            success:function(response){
                $('input').attr('disabled', false);
                $('select').attr('disabled', false);

                console.log(response);
            }
        })
    }
</script>