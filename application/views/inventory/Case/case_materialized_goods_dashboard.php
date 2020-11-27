<style>
	.button_default_light{
		float:right;
	}
</style>
<form action='<?= site_url('Inventory_case/insertItem/materialized') ?>' method="POST" id='materializedForm'>
    <div class='row'>
        <div class='col-sm-12 col-xs-12'>
            <label>Date</label>
            <input type='date' name='date' class='form-control' required>
            <br>
        </div>
        <div class='col-sm-4 col-xs-12'>
            <label>Materialized item</label> 
            <input type='hidden' id='itemMatId' name='itemMatId'>
            <button type='button' class='button button_default_dark' id='addMatItemButton'><i class='fa fa-plus'></i> Add item</button>

            <div id='itemMatDetail' style='display:none'>
                <hr>
                <p><strong><span id='itemMatRef'></span></strong></p>
                <p id='itemMatName'></p>
                <label>Quantity</label>
                <input type='number' class='form-control' id='quantityMat' name='quantityMat' min='1' required value='0'>
            </div>
        </div>
        <div class='col-sm-8 col-xs-12'>
            <label>Source item</label>
            <button type='button' class='button button_default_dark' id='addProductItemButton'><i class='fa fa-plus'></i> Add Item</button>
            <hr>
            <table class='table table-bordered' id='productItemTable' style='display:none'>
                <tr>
                    <th>Reference</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
                <tbody id='productItemTableBody'></tbody>
            </table>
        </div>
    </div>
    <br>
    <div class='row'>
        <div class='col-xs-12' id='submitButtonWrapper'>
        </div>
    </div>
</form>

<div class='alert_wrapper' id='add_item_wrapper'>
	<div class='alert_box_full'>
        <button type='button' class='button alert_full_close_button' title='Close add item session'>&times;</button>

		<div class='row'>
			<div class='col-xs-12'>
				<h2 style='font-family:bebasneue'>Add item to list</h2>
				<hr>
				<label>Search</label>
				<input type='text' class='form-control' id='search_bar'>
				<br>
                <div id='itemListTable'>
                    <table class='table table-bordered'>
                        <tr>
                            <th>Reference</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                        <tbody id='itemListTableContent'></tbody>
                    </table>
                    
                    <select class='form-control' id='page' style='width:100px'>
                        <option value='1'></option>
                    </select>
                </div>
                <p id='itemListTableText'>Item not found.</p>
			</div>
		</div>
	</div>
</div>

<script>
    $('#materializedForm').validate({
        ignore: ''
    });

    var method = 1;

    $('#addMatItemButton').click(function(){
        method = 1;
		$('#search_bar').val('');
        refreshView(method, 1);
        $('#add_item_wrapper').fadeIn();
    });
    
    $('#addProductItemButton').click(function(){
        method = 2;
		$('#search_bar').val('');
        refreshView(method, 1);
        $('#add_item_wrapper').fadeIn();
    })
	
	function refreshView(method, page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Item/showItems') ?>',
			data:{
				term:$('#search_bar').val(),
				page: page,
			},
			success:function(response){
                var itemCount = 0;
				$('#itemListTableContent').html('');
				var item_array	= response.items;
				var pages		= response.pages;
				var page		= response.page;
                $.each(item_array, function(index, item){
                    var reference		= item.reference;
                    var id				= item.item_id;
                    var name			= item.name;
                    if(method == 1){
                        $('#itemListTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td><button type='button' class='demButton button button_default_dark' onclick='pickMatItem(" + id + ")' title='Add " + reference + " to list'><i class='fa fa-cart-plus'></i></button></td></tr>");
                    } else if(method == 2){
                        $('#itemListTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td><button type='button' class='productButton button button_success_dark' onclick='pickProductItem(" + id + ")' title='Add " + reference + " to list'><i class='fa fa-cart-plus'></i></button></td></tr>");
                    }

                    itemCount++;	
                });
				
                if(itemCount > 0){
                    $('#itemListTableContent').show();
                    $('#itemListTableText').hide();
                } else {
                    $('#itemListTableContent').hide();
                    $('#itemListTableText').show();
                }
				
				$('#page').html('');
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
				
				$('#add_item_wrapper').fadeIn();
			}
		});
	}
	
	$('#page').change(function(){
		refreshView(method);
	});
	
	$('#search_bar').change(function(){
		refreshView(method, 1);
	});
	
	$('.alert_full_close_button').click(function(){
		$(this).parents().find('.alert_wrapper').fadeOut();
    });

    function pickMatItem(n){
        $.ajax({
            url:'<?= site_url('Item/showById') ?>',
            data:{
                id: n
            },
            success:function(response){
                if(response != null) {
                    var reference = response.reference;
                    var id = response.id;
                    var name = response.name;

                    $('#itemMatRef').html(reference);
                    $('#itemMatName').html(name);
                    $('#itemMatId').val(id);

                    $('#itemMatDetail').show();
                    $('#add_item_wrapper .alert_full_close_button').click();
                }
            }
        })
    }

    function pickProductItem(n){
        $.ajax({
            url:'<?= site_url('Item/showById') ?>',
            data:{
                id: n
            },
            success:function(response){
                if(response != null) {
                    var reference = response.reference;
                    var id = response.id;
                    var name = response.name;

                    $('#productItemTableBody').append("<tr id='item-" + id + "'><td>" + reference + "</td><td>" + name + "</td><td><input type='number' class='form-control' name='productItem[" + id + "]' min='1' required></td><td><button class='button button_danger_dark' onclick='removeItem(" + id + ")'><i class='fa fa-trash'></i></button></tr>");

                    if($('#productItemTableBody > tr').length > 0){
                        $('#productItemTable').show();
                        if($('#itemDemId').val() != ""){
                            $('#submitButtonWrapper').html("<button class='button button_default_light'><i class='fa fa-long-arrow-right'></i></button>");
                        }  
                    } else {
                        $('#productItemTable').hide();
                        $('#submitButtonWrapper').html('');
                    }
                    $('#add_item_wrapper .alert_full_close_button').click();
                }
            }
        })
    };

    function removeItem(n){
        $('#item-' + n).remove();
        if($('#productItemTableBody > tr').length > 0){
            $('#productItemTable').show();
            if($('#itemDemId').val() != ""){
                $('#submitButtonWrapper').html("<button class='button button_default_dark'>Submit</button>");
            }  
        } else {
            $('#productItemTable').hide();
            $('#submitButtonWrapper').html('');
        }
    }
</script>