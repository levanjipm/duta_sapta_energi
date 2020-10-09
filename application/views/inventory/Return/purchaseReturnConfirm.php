<div id='returnConfirmTable'>
    <table class='table table-bordered'>
        <tr>
            <th>Date</th>
            <th>Supplier</th>
            <th>Action</th>
        </tr>
        <tbody id='returnConfirmTableContent'></tbody>
    </table>

    <select class='form-control' id='page' style='width:100px'>
        <option value='1'>1</option>
    </select>
</div>
<p id='returnConfirmText'>There is no purchase return to be confirmed.</p>

<div class='alert_wrapper' id='purchaseReturnConfirmWrapper'>
    <button class='slide_alert_close_button'>&times;</button>
    <div class='alert_box_slide'>
        <h3 style='font-family:bebasneue'>Confirm purchase return</h3>
        <hr>
        <label>Purchase return</label>
        <p id='purchaseReturnDocument_p'></p>
        <p id='purchaseReturnDate_p'></p>

        <label>Supplier</label>
        <p id='supplierName_p'></p>
        <p id='supplierAddress_p'></p>
        <p id='supplierCity_p'></p>

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
	var purchaseReturnSentId;
    $(document).ready(function(){
        refreshView();
    })

    $('#page').change(function(){
        refreshView();
    })

    function refreshView(page = $('#page').val()){
        $.ajax({
            url:"<?= site_url('Purchase_return/getUnconfirmedSentPurchaseReturn') ?>",
            data:{
                page: page,
            },
            success:function(response){
                var returnCount = 0;
                $('#returnConfirmTableContent').html("");
                var items = response.items;
                $.each(items, function(index, item){
                    var name = item.name;
					var date = item.date;
					var id = item.id;

					var supplier = item.supplier;
					var supplierName = supplier.name;
                    var complete_address = supplier.address;
                    var supplier_number = supplier.number;
                    var supplier_block = supplier.block;
                    var supplier_rt = supplier.rt;
                    var supplier_rw = supplier.rw;
                    var supplier_city = supplier.city;
                    var supplier_postal = supplier.postal;
                    
                    if(supplier_number != null){
                        complete_address	+= ' No. ' + supplier_number;
                    }
                    
                    if(supplier_block != null){
                        complete_address	+= ' Blok ' + supplier_block;
                    }
                
                    if(supplier_rt != '000'){
                        complete_address	+= ' RT ' + supplier_rt;
                    }
                    
                    if(supplier_rw != '000' && supplier_rt != '000'){
                        complete_address	+= ' /RW ' + supplier_rw;
                    }
                    
                    if(supplier_postal != null){
                        complete_address	+= ', ' + supplier_postal;
                    }

                    $('#returnConfirmTableContent').append('<tr><td><label>' + name + "</label><p>" + my_date_format(date) + "</p></td><td><label>" + supplierName + "</label><p>" + complete_address + "</p><p>" + supplier_city + "</p></td><td><button class='button button_default_dark' onclick='viewReturn(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");

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
            url:"<?= site_url('Purchase_return/getSentById') ?>",
            data:{
                id:n
            },
            success:function(response){
                purchaseReturnSentId = n;
                var general = response.general;
                var name = general.name;
                var date = general.date;

				$('#purchaseReturnDocument_p').html(name);
                $('#purchaseReturnDate_p').html(my_date_format(date));

                var supplier = response.supplier;
				var supplierName = supplier.name;
                var complete_address = supplier.address;
                var supplier_number = supplier.number;
                var supplier_block = supplier.block;
                var supplier_rt = supplier.rt;
                var supplier_rw = supplier.rw;
                var supplier_city = supplier.city;
                var supplier_postal = supplier.postal;
                    
                if(supplier_number != null){
                    complete_address	+= ' No. ' + supplier_number;
                }
                    
                if(supplier_block != null){
                    complete_address	+= ' Blok ' + supplier_block;
                }
                
                if(supplier_rt != '000'){
                    complete_address	+= ' RT ' + supplier_rt;
                }
                    
                if(supplier_rw != '000' && supplier_rt != '000'){
                    complete_address	+= ' /RW ' + supplier_rw;
                }
                    
                if(supplier_postal != null){
                    complete_address	+= ', ' + supplier_postal;
                }

                $("#supplierName_p").html(supplierName);
                $('#supplierAddress_p').html(complete_address);
                $('#supplierCity_p').html(supplier_city);

                var items = response.items;
                $('#returnItemTable').html("");
                $.each(items, function(index, item){
                    var reference = item.reference;
                    var name = item.name;
                    var quantity = parseInt(item.quantity);

                    $('#returnItemTable').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td><<tr>")
                });

                $('#purchaseReturnConfirmWrapper').fadeIn(300, function(){
					$('#purchaseReturnConfirmWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
            }
        })
    }

    function confirmReturn()
    {
        $.ajax({
            url:"<?= site_url('Purchase_return/confirmSentById') ?>",
            data:{
                id: purchaseReturnSentId
            },
            type:'POST',
            beforeSend:function(){
                $("button").attr('disabled', true);
            },
            success:function(response){
                $('button').attr('disabled', false);
                refreshView();
                if(response == 1){
					purchaseReturnSentId = null;
                    $('#purchaseReturnConfirmWrapper .slide_alert_close_button').click();
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
            url:"<?= site_url('Purchase_return/cancelSentById') ?>",
            data:{
                id: purchaseReturnSentId
            },
            type:'POST',
            beforeSend:function(){
                $("button").attr('disabled', true);
            },
            success:function(response){
                $('button').attr('disabled', false);
                refreshView();
                if(response == 1){
					purchaseReturnSentId = null;
                    $('#purchaseReturnConfirmWrapper .slide_alert_close_button').click();
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
