<head>
    <title>Attendance - Status</title>
</head>
<div class='dashboard'>
<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Human_resource') ?>' title='Human resource'><i class='fa fa-briefcase'></i></a> / Attandance status</p>
	</div>
    <br>
    <div class='dashboard_in'>
        <button class='button button_default_dark' id='addStatusButton'><i class='fa fa-plus'></i> Add status</button>
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
            <input type='text' class='form-control' id='name'>
            
            <label>Description</label>
            <textarea class='form-control' id='description' style='resize:none' rows='3'></textarea>

            <label>Point</label>
            <input type='number' class='form-control' id='point'><br>

            <button type='button' class='button button_default_dark' id='addStatusSubmitButton'><i class='fa fa-long-arrow-right'></i></button>

            <div class='notificationText danger' id='createFailedNotification'><p>Failed to insert status.</p></div>
        </div>
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

    $('#createNewStatusForm').validate();

    function refreshView(){
        $.ajax({
            url:"<?= site_url('Attendance/getAttendanceStatus') ?>",
            success:function(response){
                var statusCount = 0;
                $('#statusListTableContent').html("");
                $.each(response, function(index, value){
                    var name = value.name;
                    var id = value.id;
                    var description = value.description;

                    $('#statusListTableContent').append("<tr><td>" + name + "</td><td>" + description + "</td><td><button class='button button_default_dark'><i class='fa fa-eye'></i></button> <button class='button button_danger_dark' onclick='deleteStatus(" + id + ")'><i class='fa fa-trash'></i></button>");
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