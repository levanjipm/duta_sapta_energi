<div id='returnConfirmTable'>
    <table class='table table-bordered'>
        <tr>
            <th>Date</th>
            <th>Customer</th>
            <th>Action</th>
        </tr>
        <tbody id='returnConfirmTableContent'></tbody>
    </table>

    <select class='form-control' id='page' style='width:100px'>
        <option value='1'>1</option>
    </select>
</div>
<p id='returnConfirmText'>There is no sales return to be confirmed.</p>

<div class='alert_wrapper' id='salesReturnConfirmWrapper'>
    <button class='slide_alert_close_button'>&times;</button>
    <div class='alert_box_slide'>
        <h3 style='font-family:bebasneue'>Confirm sales return</h3>
        <hr>
        <label>Sales return</label>
        <p id='salesReturnDocument'></p>
        <p id='salesReturnDate'></p>

        <label>Customer</label>
        <p id='customerName_p'></p>
        <p id='customerAddress_p'></p>
        <p id='customerCity_p'></p>

        <label>Items</label>
        <table class='table table-bordered'>
            <tr>    
                <th>Reference</th>
                <th>Name</th>
                <th>Quantity</th>
            </tr>
            <tbody id='returnItemTable'></tbody>
        </table>

        <input type='hidden' id='sales_return_received_id'>
        <button type='button' onclick='confirmReturn()'class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
        <button type='button' onclick='deleteReturn()' class='button button_danger_dark'><i class='fa fa-trash'></i></button>

        <div class='notificationText danger' id='failedNotification'><p>Failed to update data.</p></div>
    </div>
</div>
<script>
    $(document).ready(function(){
        refreshView();
    })

    $('#page').change(function(){
        refreshView();
    })

    function refreshView(page = $('#page').val()){
        $.ajax({
            url:"<?= site_url('Sales_return/getUnconfirmedSalesReturn') ?>",
            data:{
                page: page,
            },
            success:function(response){
                var returnCount = 0;
                $('#returnConfirmTableContent').html("");
                var items = response.items;
                $.each(items, function(index, item){
                    var customer_name = item.name;
                    var complete_address = item.address;
                    var customer_number = item.number;
                    var customer_block = item.block;
                    var customer_rt = item.rt;
                    var customer_rw = item.rw;
                    var customer_city = item.city;
                    var customer_postal = item.postal;
                    
                    if(customer_number != null){
                        complete_address	+= ' No. ' + customer_number;
                    }
                    
                    if(customer_block != null){
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

                    var salesReturnName = item.salesReturnName;
                    var salesReturnId   = item.id;

                    $('#returnConfirmTableContent').append('<tr><td>' + salesReturnName + "</td><td><label>" + customer_name + "</label><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><button class='button button_default_dark' onclick='viewReturn(" + salesReturnId + ")'><i class='fa fa-eye'></i></button></td></tr>");

                    returnCount++;
                })

                var pages = response.pages;
                $('#page').html("");
                for(i = 1; i<= pages; i++){
                    if(i == page){
                        $('#page').append("<option value='" + i + "' selected>" + i + "</option>");
                    } else {
                        $('#page').append("<option value='" + i + "'>" + i + "</option>")
                    }
                }

                if(returnCount > 0){
                    $("#returnConfirmTable").show();
                    $('#returnConfirmText').hide();
                } else {
                    $("#returnConfirmTable").hide();
                    $('#returnConfirmText').show();
                }
            }
        })
    }

    function viewReturn(n)
    {
        $.ajax({
            url:"<?= site_url('Sales_return/getReceivedById') ?>",
            data:{
                id:n
            },
            success:function(response){
                $('#sales_return_received_id').val(n);

                var general = response.general;
                var name = general.name;
                var date = general.date;
                $('#salesReturnDate').html(my_date_format(date));
                $('#salesReturnDocument').html(name);

                var customer = response.customer;
				var customer_name = customer.name;
				var complete_address = customer.address;
				var customer_number = customer.number;
				var customer_block = customer.block;
				var customer_rt = customer.rt;
				var customer_rw = customer.rw;
				var customer_city = customer.city;
				var customer_postal = customer.postal;
				
				if(customer_number != null){
					complete_address	+= ' No. ' + customer_number;
				}
				
				if(customer_block != null){
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

                $("#customerName_p").html(customer_name);
                $('#customerAddress_p').html(complete_address);
                $('#customerCity_p').html(customer_city);

                var items = response.items;
                $('#returnItemTable').html("");
                $.each(items, function(index, item){
                    var reference = item.reference;
                    var quantity = item.quantity;
                    var name = item.name;
                    var quantity = parseInt(item.quantity);

                    $('#returnItemTable').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td><<tr>")
                });

                $('#salesReturnConfirmWrapper').fadeIn(300, function(){
					$('#salesReturnConfirmWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
            }
        })
    }

    function confirmReturn()
    {
        $.ajax({
            url:"<?= site_url('Sales_return/confirmReceivedById') ?>",
            data:{
                id: $('#sales_return_received_id').val()
            },
            type:'POST',
            beforeSend:function(){
                $("button").attr('disabled', true);
            },
            success:function(response){
                $('button').attr('disabled', false);
                refreshView();
                if(response == 1){
                    $('#salesReturnConfirmWrapper .slide_alert_close_button').click();
                } else {
                    $("#failedNotification").fadeIn(250);
                    setTimeout(function(){
                        $('#failedNotification').fadeOut(250);
                    }, 1000)
                }
            }
        })
    }

    function deleteReturn()
    {
        $.ajax({
            url:"<?= site_url('Sales_return/deleteReceivedById') ?>",
            data:{
                id: $('#sales_return_received_id').val()
            },
            type:'POST',
            beforeSend:function(){
                $("button").attr('disabled', true);
            },
            success:function(response){
                $('button').attr('disabled', false);
                refreshView();
                if(response == 1){
                    $('#salesReturnConfirmWrapper .slide_alert_close_button').click();
                } else {
                    $("#failedNotification").fadeIn(250);
                    setTimeout(function(){
                        $('#failedNotification').fadeOut(250);
                    }, 1000)
                }
            }
        })
    }

    $('.slide_alert_close_button').click(function(){
        $(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
    })
</script>