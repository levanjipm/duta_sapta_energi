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
                <button class='button button_default_dark' id='addTypeButton'>Create type</button>
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

<div class='alert_wrapper' id='deleteTypeWrapper'>
	<div class='alert_box_confirm_wrapper'>
		<div class='alert_box_confirm_icon'><i class='fa fa-trash'></i></div>
		<div class='alert_box_confirm'>
			<input type='hidden' id='deleteTypeId'>
			<h3>Delete confirmation</h3>
			
			<p>You are about to delete this data.</p>
			<p>Are you sure?</p>
			<button class='button button_default_dark' onclick="$('#deleteTypeWrapper').fadeOut()">Cancel</button>
			<button class='button button_danger_dark' onclick='deleteType()'>Delete</button>
			
			<br><br>
			
			<p style='font-family:museo;background-color:#f63e21;width:100%;padding:5px;color:white;position:relative;bottom:0;left:0;opacity:0' id='error_delete_type'>Deletation failed.</p>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='editTypeWrapper'>
    <button class='slide_alert_close_button'>&times;</button>
    <div class='alert_box_slide'>
        <form id='editTypeDebtForm'>
            <h3 style='font-family:bebasneue'>Edit debt type</h3>
            <hr>

            <input type='hidden' id='editId'>
            <label>Name</label>
            <input type='text' class='form-control' id='editName'>

            <label>Description</label>
            <textarea class='form-control' id='editDescription' rows='3' style='resize:none'></textarea>
            <br>
            <button type='button' class='button button_default_dark' id='editItemButton'><i class='fa fa-long-arrow-right'></i></button>

            <div class='notificationText danger' id='failedEditNotification'><p>Failed to update item</p></div>
        </form>
    </div>
</div>

<div class='alert_wrapper' id='addTypeWrapper'>
    <button class='slide_alert_close_button'>&times;</button>
    <div class='alert_box_slide'>
        <form id='typeDebtForm'>
            <h3 style='font-family:bebasneue'>Insert debt type</h3>
            <hr>
            <label>Name</label>
            <input type='text' class='form-control' id='name'>

            <label>Description</label>
            <textarea class='form-control' id='description' rows='3' style='resize:none'></textarea>
            <br>
            <button type='button' class='button button_default_dark' id='insertItemButton'><i class='fa fa-long-arrow-right'></i></button>

            <div class='notificationText danger' id='failedInsertNotification'><p>Failed to insert item</p></div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function(){
        refreshView();
    });

    $('#typeDebtForm').validate();
    $('#editTypeDebtForm').validate();

    $('#insertItemButton').click(function(){
        if($('#typeDebtForm').valid()){
            $.ajax({
                url:"<?= site_url('Debt_type/insertItem') ?>",
                data:{
                    name: $('#name').val(),
                    description: $('#description').val()
                },
                type:'POST',
                beforeSend:function(){
                    $('button').attr('disabled', true);
                },
                success:function(response){
                    $('button').attr('disabled', false);
                    if(response == 1){
                        $('#typeDebtDocument').trigger("reset");
                        refreshView();
                        $('#addTypeWrapper .slide_alert_close_button').click();
                    } else {
                        refreshView();
                        $('#failedInsertNotification').fadeIn(250);
                        setTimeout(function(){
                            $('#failedInsertNotification').fadeOut(250);
                        }, 1000)
                    }
                }
            })
        }
    })

    $('#addTypeButton').click(function(){
        $('#addTypeWrapper').fadeIn(300, function(){
            $('#addTypeWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
        });
    })

    function confirmDelete(n){
        $('#deleteTypeId').val(n);
        $('#deleteTypeWrapper').fadeIn();
    }

    function deleteType(){
        $.ajax({
            url:'<?= site_url('Debt_type/deleteById') ?>',
            data:{
                id: $('#deleteTypeId').val()
            },
            type:'POST',
            beforeSend:function(){
                $('button').attr('disabled', true);
            },
            success:function(response){
                $('button').attr('disabled', false);
                refreshView();
                if(response == 1){
                    $('#deleteTypeWrapper').fadeOut();
                } else {
                    $('#error_delete_type').fadeTo(250, 1);
					setTimeout(function(){
						$('#error_delete_type').fadeOut(250, 0);
					}, 1000);
                }
            }
        })
    }

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

                    $('#typeTableContent').append("<tr><td>" + name + "</td><td>" + description + "</td><td><button class='button button_success_dark' onclick='openEditForm(" + id + ")'><i class='fa fa-pencil'></i></button> <button class='button button_danger_dark' onclick='confirmDelete(" + id + ")'><i class='fa fa-trash'></i></button></td></tr>");
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

    function openEditForm(n){
        $.ajax({
            url:'<?= site_url('Debt_type/getById') ?>',
            data:{
                id:n
            },
            success:function(response){
                $('#editId').val(n);
                $('#editName').val(response.name);
                $('#editDescription').val(response.description);

                $('#editTypeWrapper').fadeIn(300, function(){
                    $('#editTypeWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
                });
            }
        })
    }

    $('#editItemButton').click(function(){
        if($('#editTypeDebtForm').valid()){
            $.ajax({
                url:'<?= site_url('Debt_type/updateById') ?>',
                data:{
                    id: $('#editId').val(),
                    name: $('#editName').val(),
                    description: $('#editDescription').val()
                },
                type:'POST',
                beforeSend:function(){
                    $('button').attr('disabled', true);
                },
                success:function(response){
                    $('button').attr('disabled', false);
                    refreshView();

                    if(response == 1){
                        $('#editTypeWrapper .slide_alert_close_button').click();
                    } else {
                        $('#failedEditNotification').fadeIn(250);
                        setTimeout(function(){
                            $('#failedEditNotification').fadeOut(250);
                        }, 1000)
                    }
                }
            })
        }
    })

    $('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
</script>