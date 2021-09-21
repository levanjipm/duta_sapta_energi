<style>
    .schedule-box{
        width:30px;
        height:30px;
        transition:0.3s all ease;
        background-color:rgba(50, 239, 89, 0.2);
        display:inline-block;
        margin-left:5px;
        content:"";
    }

    .schedule-box.activated{
        background-color:rgba(50, 239, 89, 0.8);
    }
</style>
<div class='dashboard'>
    <br>
    <div class='dashboard_in'>
        <div class='row'>
            <div class='col-12'>
                <input type='text' class='form-control' id='search'>
                <br>
                <table class='table table-bordered' id='scheduleTable'>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Schedule</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id='scheduleTableContent'></tbody>
                </table>
                <p id='ScheduleTableText'>There is no customer found.</p>
                <br>
                <select class='form-control' id='page' style='width:100px'>
                    <option value='1'>1</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class='alert_wrapper' id='edit_schedule_wrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Edit Schedule</h2>
		<hr>
		<label>Customer</label>
        <p id='customerNameP'></p>
        <p id='customerAddressP'></p>
        <p id='customerCityP'></p>
        <hr>
        <div class='schedule-box edit' id='schedule-box-edit-0' data-toggle='tooltip' data-placement='right' title='Monday'></div>
        <div class='schedule-box edit' id='schedule-box-edit-1' data-toggle='tooltip' data-placement='right' title='Tuesday'></div>
        <div class='schedule-box edit' id='schedule-box-edit-2' data-toggle='tooltip' data-placement='right' title='Wednesday'></div>
        <div class='schedule-box edit' id='schedule-box-edit-3' data-toggle='tooltip' data-placement='right' title='Thursday'></div>
        <div class='schedule-box edit' id='schedule-box-edit-4' data-toggle='tooltip' data-placement='right' title='Friday'></div>
        <div class='schedule-box edit' id='schedule-box-edit-5' data-toggle='tooltip' data-placement='right' title='Saturday'></div>
        <div class='schedule-box edit' id='schedule-box-edit-6' data-toggle='tooltip' data-placement='right' title='Sunday'></div>
        <br><br>
        <button class='button button_default_dark' id='submitEditButton'><i class='fa fa-long-arrow-right'></i></button>
	</div>
</div>

<script>
    var editArray = [0, 0, 0, 0, 0, 0, 0];
    var customerId = 0;

    $('[data-toggle="tooltip"]').tooltip();

    $('document').ready(function(){
        refreshView();

        $('div[id^="schedule-box-edit-"]').click(function(){
            var id = parseInt(($(this).attr('id')).substr(-1));
            if(editArray[id] == 1){
                editArray[id] = 0;
                $(this).removeClass('activated');
                if(editArray.filter(x => x == 0).length == 7){
                    $('#submitEditButton').attr('disabled', true);
                }
            } else {
                editArray[id] = 1;
                $(this).addClass('activated');
                $('#submitEditButton').attr('disabled', false);
            }
        })
    })

    $('#page').change(function(){ refreshView() });
    $('#search').change(function(){ refreshView(1)});

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

                $('#scheduleTableContent').html("");
            },  
            success:function(response){
                var items = response.items;
                var count = 0;

                $('input').attr('disabled', false);
                $('select').attr('disabled', false);

                $.each(items, function(index, customer){
                    var complete_address		= '';
					var customer_name			= customer.name;
					complete_address		    += customer.address;
					var customer_city			= customer.city;
					var customer_number			= customer.number;
					var customer_rt				= customer.rt;
					var customer_rw				= customer.rw;
					var customer_postal			= customer.postal_code;
					var customer_block			= customer.block;
					var customer_id				= customer.id;
		
					if(customer_number != null){
						complete_address	+= ' No. ' + customer_number;
					}
					
					if(customer_block != null && customer_block != "000"){
						complete_address	+= ' Blok ' + customer_block;
					}
				
					if(customer_rt != '000'){
						complete_address	+= ' RT ' + customer_rt;
					}
					
					if(customer_rw != '000' && customer_rt != '000'){
						complete_address	+= ' /RW ' + customer_rw;
					}
					
					if(customer_postal != null){
						complete_address	+= ', ' + customer_postal;
					}
                    $('#scheduleTableContent').append("<tr><td>" + customer.name + "</td><td><p>" + complete_address + "</p><p>" + customer.city + "</p></td><td><div class='schedule-box' id='schedule-box-" + customer_id + "-0' data-toggle='tooltip' data-placement='right' title='Monday'></div><div class='schedule-box' id='schedule-box-" + customer_id + "-1' data-toggle='tooltip' data-placement='right' title='Tuesday'></div><div class='schedule-box' id='schedule-box-" + customer_id + "-2' data-toggle='tooltip' data-placement='right' title='Wednesday'></div><div class='schedule-box' id='schedule-box-" + customer_id + "-3' data-toggle='tooltip' data-placement='right' title='Thursday'></div><div class='schedule-box' id='schedule-box-" + customer_id + "-4' data-toggle='tooltip' data-placement='right' title='Friday'></div><div class='schedule-box' id='schedule-box-" + customer_id + "-5' data-toggle='tooltip' data-placement='right' title='Saturday'></div><div class='schedule-box' id='schedule-box-" + customer_id + "-6' data-toggle='tooltip' data-placement='right' title='Sunday'></div></td><td><button class='button button_default_dark' onclick='openEditSchedule(" + customer_id + ")'><i class='fa fa-pencil'></i></button></td></tr>");

                    count++;

                    $.each(customer.schedule, function(index, item){
                        if(item != 0){
                            $('#schedule-box-' + customer_id + "-" + index).addClass('activated');
                        }
                    })
                })

                if(count > 0){
                    $('#ScheduleTableText').hide();
                    $('#ScheduleTable').show();
                } else {
                    $('#ScheduleTableText').show();
                    $('#ScheduleTable').hide();
                }

                $('#page').html("");
                for(var i = 1; i <= response.pages; i++){
                    if(i == page){
                        $('#page').append("<option value='" + i + "' selected>" + i + "</option>");
                    } else {
                        $('#page').append("<option value='" + i + "'>" + i + "</option>");
                    }
                }
            }
        })
    }

    function openEditSchedule(id){
        $.ajax({
            url:"<?= site_url('Schedule/getByCustomerId') ?>",
            data:{
                id: id
            },
            beforeSend:function(){
                $('.edit').removeClass('activated');
                editArray = [0, 0, 0, 0, 0, 0, 0];
            },
            success:function(customer){
                var complete_address		= '';
                var customer_name			= customer.name;
                complete_address		    += customer.address;
                var customer_city			= customer.city;
                var customer_number			= customer.number;
                var customer_rt				= customer.rt;
                var customer_rw				= customer.rw;
                var customer_postal			= customer.postal_code;
                var customer_block			= customer.block;
                var customer_id				= customer.id;
    
                if(customer_number != null){
                    complete_address	+= ' No. ' + customer_number;
                }
                
                if(customer_block != null && customer_block != "000"){
                    complete_address	+= ' Blok ' + customer_block;
                }
            
                if(customer_rt != '000'){
                    complete_address	+= ' RT ' + customer_rt;
                }
                
                if(customer_rw != '000' && customer_rt != '000'){
                    complete_address	+= ' /RW ' + customer_rw;
                }
                
                if(customer_postal != null){
                    complete_address	+= ', ' + customer_postal;
                }

                $('#customerNameP').html(customer.name);
                $('#customerAddressP').html(complete_address);
                $('#customerCityP').html(customer_city);

                $.each(customer.schedule, function(index, item){
                    if(item != 0){
                        $('#schedule-box-edit-' + index).addClass('activated');
                        editArray[index] = 1;
                    }
                    
                })

                customerId = customer_id;

                $('#edit_schedule_wrapper').fadeIn(300, function(){
                    $('#edit_schedule_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
                });
            }
        })
    }

    $('#submitEditButton').click(function(){
        $.ajax({
            url:'<?= site_url('Schedule/editByCustomerId') ?>',
            data:{
                customer_id: customerId,
                schedule: editArray
            },
            type:"POST",
            beforeSend:function(){
                $('button').attr('disabled', true);
            },
            success:function(response){
                $('button').attr('disabled', false);
                refreshView();
                $('#edit_schedule_wrapper .slide_alert_close_button').click();
            }
        });
    })
</script>