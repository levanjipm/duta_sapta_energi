<div class='dashboard'>
<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Human_resource') ?>' title='Human resource'><i class='fa fa-user'></i></a> / Attandance list</p>
	</div>
    <br>
    <div class='dashboard_in'>
        <div id='attendanceListTable'>
            <table class='table table-bordered'>
                <tr>
                    <th>User</th>
                    <th>Action</th>
                </tr>
                <tbody id='attendanceListTableContent'></tbody>
            </table>
        </div>
        <p id='attendanceListTableText'>There is no one left to attend.</p>
    </div>
</div>

<div class='alert_wrapper' id='statusWrapper'>
    <div class='alert_box_full'>		
        <button type='button' class='button alert_full_close_button' title='Close select supplier session'>&times;</button>
        <h3 style='font-family:bebasneue'>Select status</h3>
        <input type='text' class='form-control' id='search_bar'><br>

        <div id='statusTable'>
            <table class='table table-bordered'>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
                <tbody id='statusTableContent'></tbody>
            </table>

            <select class='form-control' id='page' style='width:100px'>
                <option value='1'>1</option>
            </select>
        </div>
        <p id='statusTableText'>There is no status found.</p>
    </div>
</div>
<script>
    $(document).ready(function(){
        refreshView();
    });

    var attendanceUser;

    function refreshView(){
        $.ajax({
            url:"<?= site_url('Attendance/getTodayAbsentee') ?>",
            success:function(response){
                var attendanceCount = 0;
                $('#attendanceListTableContent').html("");
                $.each(response, function(index, value){
                    var name = value.name;
                    if(value.status == null){
                        if(value.image_url != null){
                            var imageUrl = "<?= base_url() . '/assets/ProfileImages/' ?>" + value.image_url;
                        } else {
                            var imageUrl = "<?= base_url() . '/assets/ProfileImages/defaultImage.png' ?>";
                        }

                        var id = value.id;

                        $('#attendanceListTableContent').append("<tr><td><img src='" + imageUrl + "' style='width:30px;height:30px;border-radius:50%' title='" + name + "'> " + name + "</td><td><button class='form-control' onclick='openStatus(" + id + ")' id='status-" + id + "'></button></td></tr>");

                        attendanceCount++;
                    }
                });

                var pages = response.pages;
                $('#page').html("");
                for(i = 1; i <= pages; i++){
                    if(i == page){
                        $('#page').append("<option value='" + i + "' selected>" + i + "</option>");
                    } else {
                        $('#page').append("<option value='" + i + "'>" + i + "</option>");
                    }
                }

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

    function openStatus(userId){
        attendanceUser = userId;
        refreshStatus(1);
        $('#statusWrapper').fadeIn();
    }

    $('#search_bar').change(function(){
        refreshStatus(1);
    })

    $('#page').change(function(){
        refreshStatus();
    })

    function refreshStatus(page = $('#page').val()){
        $.ajax({
            url:"<?= site_url('Attendance/getAttendanceStatus') ?>",
            data:{
                page: page,
                term: $('#search_bar').val()
            },
            success:function(response){
                var items = response.items;
                $('#statusTableContent').html("");
                var statusCount = 0;
                $.each(items, function(index, item){
                    var name = item.name;
                    var description = item.description;
                    var id = item.id;

                    $('#statusTableContent').append("<tr><td>" + name + "</td><td>" + description + "</td><td><button class='button button_default_dark' onclick='selectStatus(" + id + ")'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
                    statusCount++;
                })
                var pages = response.pages;
                $('#page').html("");
                for(i = 1; i <= pages; i++){
                    if(i == page){
                        $('#page').append("<option value='" + i + "' selected>" + i + "</optiion>");
                    } else {
                        $('#page').append("<option value='" + i + "'>" + i + "</optiion>");
                    }
                }

                if(statusCount > 0){
                    $('#statusTable').show();
                    $('#statusTableText').hide();
                } else {
                    $('#statusTable').hide();
                    $('#statusTableText').show();
                }
            }
        });
    }

    function selectStatus(n){
        $.ajax({
            url:"<?= site_url('Attendance/insertItem') ?>",
            data:{
                status: n,
                userId: attendanceUser,
            },
            type:"POST",
            success:function(response){
                refreshView();
                $('#statusWrapper .alert_full_close_button').click();
            }
        })
    }

    $('.alert_full_close_button').click(function(){
		$(this).parent().parent().fadeOut();
	})
</script>