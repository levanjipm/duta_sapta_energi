<head>
    <title>Debt - Types</title>
</head>
<div class='dashboard'>
    <div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Sales'><i class='fa fa-bar-chart'></i></a> /Debt type</p>
	</div>
    <br>
    <div class='dashboard_in'>
        <div class='input_group'>
            <input type='text' class='form-control'>
            <div class='input_group_append'>
                <button class='button button_default_dark'>Create type</button>
            </div>
        </div>
        <br>
        <div id='typeTable'>
            <table class='table table-bordered'>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
                <tbody id='typeTableContent'></tbody>
            </table>

            <select class='form-control' id='page' style='width:100px'>
                <option value='1'>1</option>
            </select>
        </div>
        <p id='typeTableText'>There is no data found.</p>
    </div>
</div>
<script>
    $(document).ready(function(){
        refreshView();
    })
    function refreshView(page = $('#page').val()){
        $.ajax({
            url:'<?= site_url('Debt_type/getItems') ?>',
            data:{
                page: page,
                term: $('#search_bar').val()
            },
            success:function(response){
                var itemCount = 0;
                var items = response.items;
                $('#typeTableContent').html("");
                $.each(items, function(index, item){
                    var name = item.name;
                    var description = item.description;
                    var id = item.id;

                    $('#typeTableContent').append("<tr><td>" + name + "</td><td>" + description + "</td><td><button class='button button_default_dark'><i class='fa fa-eye'></i></button> <button class='button button_danger_dark'><i class='fa fa-trash'></i></button></td></tr>");
                    itemCount++;
                });

                if(itemCount > 0){
                    $('#typeTableText').hide();
                    $('#typeTable').show();
                } else {
                    $('#typeTable').hide();
                    $('#typeTableText').show();
                }

                var pages = response.pages;
                $('#page').html("");
                for(i = 1; i <= pages; i++){
                    if(i == page){
                        $('#page').append("<option value='" + i + "' selected>" + i + "</option>");
                    } else {
                        $('#page').append("<option value='" + i + "'>" + i + "</option>"); 
                    }
                }
            }
        })
    }
</script>