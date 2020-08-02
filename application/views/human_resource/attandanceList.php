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
    function refreshView(){
        $.ajax({
            url:"<?= site_url('Users/getTodayAbsentee') ?>",
            success:function(response){
                console.log(response);
            }
        })
    }
</script>