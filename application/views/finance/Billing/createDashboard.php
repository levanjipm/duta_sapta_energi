<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-briefcase'></i></a> / Create Billing</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Date</label>
        <input type='date' class='form-control' id='date'>
        <br>
        <button class='button button_default_dark' id='viewSuggestionButton' style='display:none' title='View suggestion list'><i class='fa fa-eye'></i></button>
        <hr>
        <input type='text' class='form-control' id='search_bar'>
        <br>
        <div class='customerTable'>
            <table class='table table-bordered'>
                <tr>
                    <th>Customer</th>
                    <th>Information</th>
                    <th>Action</th>
                </tr>
                <tbody id='customerTableContent'></tbody>
            </table>
            <select class='form-control' id='page' style='width:100px'>
                <option value='1'>1</option>
            </select>
        </div>
        <p id='customerTableText'>There is no customer found.</p>
	</div>
</div>

<div class='alert_wrapper' id='suggestionWrapper'>
    <button class='slide_alert_close_button'>&times;</button>
    <div class='alert_box_slide'>
        <h3 style='font-family:bebasneue'>Billing suggestion</h3>
        <hr>
        <div id='billingSuggestion'>
            <table class='table table-bordered'>
                <tr>
                    <th>Date</th>
                    <th>Invoice</th>
                    <th>Customer</th>
                    <th>Value</th>
                    <th>Action</th>
                </tr>
                <tbody id='billingSuggestionContent'></tbody>
            </table>
        </div>
        <p id='billingSuggestionText'>There is no suggestion found.</p>
    </div>
</div>

<script>
    var invoiceArray = [];

    $(document).ready(function(){
        refreshView();
    });

    $('#page').change(function(){
        refreshView();
    });

    $('#search_bar').change(function(){
        refreshView(1);
    });

    $('#date').change(function(){
        refreshSuggestion();
    })

    function refreshSuggestion(date = $('#date').val()){
        $.ajax({
            url:'<?= site_url('Receivable/getReceivablesSuggestions') ?>',
            data:{
                date: date
            },
            success:function(response){
                var suggestionCount = 0;
                $('#billingSuggestionContent').html("");
                $.each(response, function(index, value){
                    var name = value.name;
                    var debt = value.value;
                    var date = value.date;
                    var id = value.id;

                    var customer    = value.customer;
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

                    $('#billingSuggestionContent').append("<tr id='bill-" + id + "'><td>" + my_date_format(date) + "</td><td>" + name + "</td><td><p>" + customer_name + "</p><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td>Rp. " + numeral(debt).format('0,0.00') + "</td><td><button class='button button_default_dark' onclick='addInvoice(" + id + ")'><i class='fa fa-plus'></i></button></td></tr>");

                    suggestionCount++;
                });

                if(suggestionCount > 0){
                    $('#billingSuggestionText').hide();
                    $('#billingSuggestion').show();
                    $('#viewSuggestionButton').show();
                } else {
                    $('#billingSuggestionText').show();
                    $('#billingSuggestion').hide();
                    $('#viewSuggestionButton').hide();
                }
            }
        })
    }

    $('#viewSuggestionButton').click(function(){
        $('#suggestionWrapper').fadeIn(300, function(){
            $('#suggestionWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
        });
    })

    function refreshView(page = $('#page').val()){
        $.ajax({
            url:'<?= site_url('Receivable/getReceivables') ?>',
            data:{
                page: page,
                term: $('#search_bar').val(),
            },
            success:function(response){
                var items = response.items;
                var customerCount = 0;
                $('#customerTableContent').html("");
                $.each(items, function(index, customer){
                    var value = customer.value;
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

                    $('#customerTableContent').append("<tr><td><label>" + customer_name + "</label><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td><button class='button button_default_dark'><i class='fa fa-eye'></i></button></td></tr>");
                    customerCount++;
                });

                if(customerCount > 0){
                    $('#customerTable').show();
                    $('#customerTableText').hide();
                } else {
                    $('#customerTable').hide();
                    $('#customerTableText').show();
                }
            }
        })
    }

    $('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
</script>