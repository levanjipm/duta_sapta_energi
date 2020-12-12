<head>
    <title>Attendance - Status</title>
</head>
<div class='dashboard'>
<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Human_resource') ?>' title='Human resource'><i class='fa fa-user'></i></a> / Attandance status</p>
	</div>
    <br>
    <div class='dashboard_in'>
        <div class='input_group'>
            <input type='text' class='form-control' id='search_bar'>
            <div class='input_group_append'>
                <button class='button button_default_dark' id='addStatusButton'><i class='fa fa-plus'></i> Add status</button>
            </div>
        </div>
        <br><br>
        <div id='statusListTable'>
            <table class='table table-bordered'>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
                <tbody id='statusListTableContent'></tbody>
            </table>

            <select class='form-control' id='page' style='width:100px'>
                <option value='1'>1</option>
            </select>
        </div>
        <p id='statusListTableText'>There is no status found.</p>
    </div>
</div>

<div class='alert_wrapper' id='addStatusWrapper'>
    <button class='slide_alert_close_button'>&times;</button>
    <div class='alert_box_slide'>
        <form id='createNewStatusForm'>
            <h3 style='font-family:bebasneue'>Create new status</h3>
            <hr>
            <label>Name</label>
            <input type='text' class='form-control' id='name' required>
            
            <label>Description</label>
            <textarea class='form-control' id='description' style='resize:none' rows='3' required minlength='25'></textarea>

            <label>Point</label>
            <input type='number' class='form-control' id='point' required><br>

            <button type='button' class='button button_default_dark' id='addStatusSubmitButton'><i class='fa fa-long-arrow-right'></i></button>

            <div class='notificationText danger' id='createFailedNotification'><p>Failed to insert status.</p></div>
        </form>
    </div>
</div>

<div class='alert_wrapper' id='editStatusWrapper'>
    <button class='slide_alert_close_button'>&times;</button>
    <div class='alert_box_slide'>
        <form id='editStatusForm'>
            <h3 style='font-family:bebasneue'>Edit status</h3>
            <hr>
            <input type='hidden' id='editId' name='id'>
            <label>Name</label>
            <input type='text' class='form-control' id='editName' name='name' required>
            
            <label>Description</label>
            <textarea class='form-control' id='editDescription' name='description' style='resize:none' rows='3' required minlength='25'></textarea>

            <label>Point</label>
            <input type='number' class='form-control' id='editPoint' name='point' required><br>

            <button type='button' class='button button_default_dark' id='editStatusSubmitButton'><i class='fa fa-long-arrow-right'></i></button>

            <div class='notificationText danger' id='updateFailedNotification'><p>Failed to update status.</p></div>
        </form>
    </div>
</div>

<div class='alert_wrapper' id='deleteStatusWrapper'>
	<div class='alert_box_confirm_wrapper'>
		<div class='alert_box_confirm_icon'><i class='fa fa-trash'></i></div>
		<div class='alert_box_confirm'>
			<input type='hidden' id='delete_status_id'>
			<h3>Delete confirmation</h3>
			
			<p>You are about to delete this data.</p>
			<p>Are you sure?</p>
			<button class='button button_default_dark' onclick="$('#deleteStatusWrapper').fadeOut()">Cancel</button>
			<button class='button button_danger_dark' onclick='confirm_delete()'>Delete</button>
			
			<br><br>
			
			<p style='font-family:museo;background-color:#f63e21;width:100%;padding:5px;color:white;position:relative;bottom:0;left:0;opacity:0' id='error_delete_status'>Deletation failed.</p>
		</div>
	</div>
</div>

<script>
    $(document).ready(function(){
        refreshView();
    });
    
    $('#search_bar').change(function(){
        refreshView(1);
    });

    $('#page').change(function(){
        refreshView();
    })

    $('#createNewStatusForm').validate();
    $('#editStatusForm').validate();

    function refreshView(page = $('#page').val()){
        $.ajax({
            url:"<?= site_url('Attendance/getAttendanceStatus') ?>",
            data:{
                page: $('#page').val(),
                term: $('#search_bar').val()
            },
            success:function(response){
                var items = response.items;
                var statusCount = 0;
                $('#statusListTableContent').html("");
                $.each(items, function(index, value){
                    var name = value.name;
                    var id = value.id;
                    var description = value.description;

                    $('#statusListTableContent').append("<tr><td>" + name + "</td><td>" + description + "</td><td><button class='button button_success_dark' onclick='openEditView(" + id + ")'><i class='fa fa-pencil'></i></button> <button class='button button_danger_dark' onclick='deleteStatus(" + id + ")'><i class='fa fa-trash'></i></button>");
                    statusCount++;
                })

                if(statusCount > 0){
                    $('#statusListTable').show();
                    $('#statusListTableText').hide();
                } else {
                    $('#statusListTable').hide();
                    $('#statusListTableText').show();
                }
            }
        })
    }

    function deleteStatus(n){
        $('#deleteStatusWrapper').fadeIn();
        $('#delete_status_id').val(n);
    }

    function openEditView(n){
        $.ajax({
            url:'<?= site_url('Attendance/getStatusById') ?>',
            data:{
                id: n
            },
            success:function(response){
                var name = response.name;
                var description = response.description;
                var id = response.id;
                var point = response.point;

                $('#editName').val(name);
                $('#editDescription').val(description);
                $('#editId').val(id);
                $('#editPoint').val(point);

                $('#editStatusWrapper').fadeIn(300, function(){
                    $('#editStatusWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
                });
            }
        })
    }

    $('#editStatusSubmitButton').click(function(){
        if($('#editStatusForm').valid()){
            $.ajax({
                url:"<?= site_url('Attendance/updateStatusById') ?>",
                data: $('#editStatusForm').serialize(),
                type:"POST",
                beforeSend:function(){
                    $('button').attr('disabled', true);
                },
                success:function(response){
                    $('button').attr('disabled', false);
                    refreshView();
                    if(response == 1){
                        $('#editStatusWrapper .slide_alert_close_button').click();
                    } else {
                        $('#updateFailedNotification').fadeIn();
                        setTimeout(function(){
                            $('#updateFailedNotification').fadeOut();
                        }, 1000)
                    }
                }
            })
        }
    })

    function confirm_delete(){
        $.ajax({
            url:'<?= site_url('Attendance/deleteStatusById') ?>',
            data:{
                id: $('#delete_status_id').val()
            },
            type:'POST',
            beforeSend:function(){
                $('button').attr('disabled', true);
            },
            success:function(response){
                $('button').attr('disabled', false);
                if(response == 1){
                    $('#deleteStatusWrapper').fadeOut();
                    refreshView();
                } else {
                    $('#error_delete_status').fadeTo(250, 1);
                    setTimeout(function(){
                        $('#error_delete_status').fadeTo(250, 0);
                    }, 1000);
                }
            }
        })
    }

    $('#addStatusButton').click(function(){
        $('#addStatusWrapper').fadeIn(300, function(){
            $('#addStatusWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
        });
    });
    
    $('#addStatusSubmitButton').click(function(){
        if($('#createNewStatusForm').valid()){
            $.ajax({
                url:'<?= site_url("Attendance/insertStatusItem") ?>',
                data:{
                    name: $('#name').val(),
                    description: $('#description').val(),
                    point: $('#point').val()
                },
                type:"POST",
                beforeSend:function(){
                    $('button').attr('disabled', true);
                },
                success:function(response){
                    $('button').attr('disabled', false);
                    if(response == 1){
                        $('#createNewStatusForm input').val("");
                        $('#createNewStatusForm textarea').val("");
                        $('#addStatusWrapper .slide_alert_close_button').click();
                        refreshView();

                    } else {
                        $('#createFailedNotification').fadeIn(250);
                        setTimeout(function(){
                            $('#createFailedNotification').fadeOut(250);
                        }, 1000);
                    }
                }
            })
        }
    });
</script>