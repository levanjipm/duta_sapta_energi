<style>
    @page {
        size: A4;
        margin: 20px;
    }

    @media print {
        body * {
            visibility: hidden;
            
        }
        #printable, #printable * {
            visibility: visible;
        }

        #printable {
            position: absolute;
            padding:1rem;
            left:0;
            top:0;
            margin-left:-200px;
            margin-top:-70px;
            width:calc(100% + 200px);
        }

        #printButton{
            display:none;
        }

        #day{
            display:none;
        }
    }
</style>
<title>Customer Billing Schedule | Print</title>
<div class='dashboard'>
    <br>
    <div class='dashboard_in'>
        <div class='row'>
            <div class='col-12'>
                <select class='form-control' id='day'>
                    <option value='0'>Monday</option>
                    <option value='1'>Tuesday</option>
                    <option value='2'>Wednesday</option>
                    <option value='3'>Thursday</option>
                    <option value='4'>Friday</option>
                    <option value='5'>Saturday</option>
                    <option value='6'>Sunday</option>
                </select>
                <br>
                <button id='printButton' onclick='window.print()' class='button button_default_light'><i class='fa fa-print'></i></button>
                <br>
                <div id='printable'>
                    <table class='table table-bordered' id='scheduleTable'>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody id='scheduleTableContent'></tbody>
                    </table>
                    <p id='scheduleTableText'>There is no customer found.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        refreshView();
    })

    $('#day').change(function(){
        refreshView();
    })
    
    function refreshView(day = $('#day').val()){
        $.ajax({
            url:"<?= site_url('Schedule/getByDay') ?>",
            data: {
                day: day
            },
            beforeSend:function(){
                $('#scheduleTableContent').html("");
            },
            success:function(response){
                if(response.length > 0){
                    $('#scheduleTable').show();
                    $('#scheduleTableText').hide();
                    $('#printButton').show();

                    $.each(response, function(index, customer){
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
                        
                        $('#scheduleTableContent').append("<tr><td>" + customer_name + "</td><td><p>" + complete_address + ", " + customer_city + "</p></td></tr>");
                    })
                    
                } else {
                    $('#scheduleTable').hide();
                    $('#scheduleTableText').show();

                    $('#printButton').hide();
                }
            }
        })
    }
</script>