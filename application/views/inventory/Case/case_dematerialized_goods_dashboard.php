<h4><strong>Dematerialized</strong></h4>
<form action='<?= site_url('Inventory_case/input/dematerialized') ?>' method="POST" id='dematerializedForm'>
    <div class='row'>
        <div class='col-sm-12 col-xs-12'>
            <label>Date</label>
            <input type='date' class='form-control' required>
            <br>
        </div>
        <div class='col-sm-4 col-xs-12'>
            <label>Dematerialized item</label> 
            <input type='hidden' id='itemDemId'>
            <button type='button' class='button button_default_dark' id='addDemItemButton'>Add item</button>

            <div id='itemDemDetail' style='display:none'>
                <hr>
                <p><strong><span id='itemDemRef'></span></strong></p>
                <p id='itemDemName'></p>
                <label>Quantity</label>
                <input type='number' class='form-control' id='quantityDem' min='1' required value='0'>
            </div>
        </div>
        <div class='col-sm-8 col-xs-12'>
            <label>Product item</label>
            <button type='button' class='button button_default_dark' id='addProductItemButton'>Add Item</button>
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
				<table class='table table-bordered' id='shopping_item_list_table'>
					<tr>
						<th>Reference</th>
						<th>Name</th>
						<th>Action</th>
					</tr>
					<tbody id='itemListTable'>
					</tbody>
				</table>
				
				<select class='form-control' id='page' style='width:100px'>
					<option value='1'></option>
				</select>
			</div>
		</div>
	</div>
</div>

<script>
    $('#dematerializedForm').validate({
        ignore: ''
    });

    var method = 1;
    $('#addDemItemButton').click(function(){
        method = 1;
		$('#search_bar').val('');
        refresh_view(1, method);
        $('#add_item_wrapper').fadeIn();
    });
    
    $('#addProductItemButton').click(function(){
        method = 2;
		$('#search_bar').val('');
        refresh_view(1, method);
        $('#add_item_wrapper').fadeIn();
    })
	
	function refresh_view(page = $('#page').val(), method){
		$.ajax({
			url:'<?= site_url('Item/showItems') ?>',
			data:{
				term:$('#search_bar').val(),
				page: page,
			},
			success:function(response){
				$('#itemListTable').html('');
				var item_array	= response.items;
				var pages		= response.pages;
				var page		= response.page;
				
				if(item_array.length > 0){
					$.each(item_array, function(index, item){
						var reference		= item.reference;
						var id				= item.item_id;
                        var name			= item.name;
                        if(method == 1){
                            $('#itemListTable').append("<tr><td>" + reference + "</td><td>" + name + "</td><td><button type='button' class='demButton button button_default_dark' onclick='pickDemItem(" + id + ")' title='Add " + reference + " to list'><i class='fa fa-cart-plus'></i></button></td></tr>");
                        } else if(method == 2){
                            $('#itemListTable').append("<tr><td>" + reference + "</td><td>" + name + "</td><td><button type='button' class='productButton button button_success_dark' onclick='pickProductItem(" + id + ")' title='Add " + reference + " to list'><i class='fa fa-cart-plus'></i></button></td></tr>");
                        }
						
						
					});
				} else {
					$('#shopping_item_list_table').hide();
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
		refresh_view();
	});
	
	$('#search_bar').change(function(){
		refresh_view(1);
	});
	
	$('.alert_full_close_button').click(function(){
		$(this).parents().find('.alert_wrapper').fadeOut();
    });

    function pickDemItem(n){
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

                    $('#itemDemRef').html(reference);
                    $('#itemDemName').html(name);
                    $('#itemDemId').val(id);

                    $('#itemDemDetail').show();
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
                            $('#submitButtonWrapper').html("<button class='button button_default_dark'>Submit</button>");
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