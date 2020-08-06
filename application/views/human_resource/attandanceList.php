<div class='dashboard'>
<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Human_resource') ?>' title='Human resource'><i class='fa fa-briefcase'></i></a> / Attandance list</p>
	</div>
    <br>
    <div class='dashboard_in'>
        <div id='attendanceListTable'>
            <table class='table table-bordered'>
                <tr>
                    <th colspan='2'>User</th>
                    <th>Action</th>
                </tr>
                <tbody id='attendanceListTableContent'></tbody>
            </table>
        </div>
        <p id='attendanceListTableText'>There is no one left to be listed.</p>
    </div>
</div>
<script>
    $(document).ready(function(){
        refreshView();
    });

    function refreshView(){
        $.ajax({
            url:"<?= site_url('Attendance/getTodayAbsentee') ?>",
            success:function(response){
                var attendanceCount = 0;
                $('#attendanceListTableContent').html("");
                $.each(response, function(index, value){
                    var user = value.user;
                    var name = user.name;
                    var id = user.id;

                    var attendance = value.attendance;
                    var attendanceId = attendance.id;
                    var status = attendance.status;

                    $('#attendanceListTableContent').append("<tr><td>" + name + "</td><td><button class='button button_success_dark'>OK</button> <button class='button button_danger_dark'>No</button>");
                    attendanceCount++;
                })

                if(attendanceCount > 0){
                    $('#attendanceListTable').show();
                    $('#attendanceListTableText').hide();
                } else {
                    $('#attendanceListTable').hide();
                    $('#attendanceListTableText').show();
                }
            }
        })
    }
</script>