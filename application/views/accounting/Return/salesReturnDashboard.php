<head>
	<title>Return - Sales</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> / Sales return</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div id='salesReturnTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Customer</th>
					<th>Action</th>
				</tr>
				<tbody id='salesReturnTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='salesReturnTableText'>There is no sales order to be confirmed.</p>
	</div>
</div>

<div class='alert_wrapper' id='salesReturnConfirmWrapper'>
    <button class='slide_alert_close_button'>&times;</button>
    <div class='alert_box_slide'>
        <h3 style='font-family:bebasneue'>Confirm sales return</h3>
        <hr>
		<form id='salesReturnForm'>
			<label>Bank date</label>
			<input type='date' class='form-control' id='date' name='date' required min='<?= date("2020-01-01") ?>'>

			<label>Bank account</label>
			<button type='button' class='form-control' id='accountButton' style='text-align:left!important'></button>
			<input type='hidden' id='account' name='account' required>
	
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
					<th>Unit price</th>
					<th>Quantity</th>
					<th>Total price</th>
				</tr>
				<tbody id='returnItemTable'></tbody>
			</table>

			<input type='hidden' id='sales_return_received_id' name='id'>
			<button type='button' onclick='confirmReturn()'class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
			<button type='button' onclick='deleteReturn()' class='button button_danger_dark'><i class='fa fa-trash'></i></button>

        	<div class='notificationText danger' id='failedNotification'><p>Failed to update data.</p></div>
		</form>
    </div>
</div>

<div class='alert_wrapper' id='bankAccountWrapper'>
	<div class='alert_box_full'>
	<h3 style='font-family:bebasneue'>Choose an account</h3>
	<button type='button' class='button alert_full_close_button' title='Close select account session'>&times;</button>
		<br>
		<div class='row'>
			<div class='col-xs-12'>
				<input type='text' class='form-control' id='searchAccountBar'>
				<br>
				<div id='accountTable'>
					<table class='table table-bordered'>
						<tr>
							<th>Number</th>
							<th>Name</th>
							<th>Bank</th>
							<th>Action</th>
						</tr>
						<tbody id='accountTableContent'></tbody>
					</table>
					<select class='form-control' id='accountPage' style='width:100px'>
						<option value='1'>1</option>
					</select>
				</div>
				<p id='accountTableText'>There is no account found.</p>
			</div>
		</div>
	</div>
</div>

<script>
	$('#salesReturnForm').validate({
		ignore:'',
		rules: {"hidden_field": {required: true}}
	});

	$(document).ready(function(){
		refreshView();
	})

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Sales_return/getUnassignedSalesReturn') ?>",
			data:{
				page:page
			},
			success:function(response){
				var items = response.items;
				$('#salesReturnTableContent').html("");
				var itemCount = 0;
				$.each(items, function(index, item){
					var date 					= item.date;
					var name 					= item.name;
					var id						= item.id;

					var customerName 			= item.customerName;
					var complete_address		= item.address;
					var customer_city			= item.city;
					var customer_number			= item.number;
					var customer_rt				= item.rt;
					var customer_rw				= item.rw;
					var customer_postal			= item.postal_code;
					var customer_block			= item.block;

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

					$('#salesReturnTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td><label>" + customerName + "</label><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><button class='button button_default_dark' onclick='viewReturn(" + id + ")'><i class='fa fa-eye'></i></button></tr>");
					itemCount++;
				})

				if(itemCount > 0){
					$('#salesReturnTable').show();
					$('#salesReturnTableText').hide();
				} else {
					$('#salesReturnTable').hide();
					$('#salesReturnTableText').show();
				}

				var pages = response.pages;
				$('#page').html("");
				for(i = 1; i <= page; i++){
					if(page == i){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
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

                $("#customerName_p").val(customer_name);
                $('#customerAddress_p').html(complete_address);
                $('#customerCity_p').html(customer_city);

                var items = response.items;
                $('#returnItemTable').html("");
                var salesReturnValue = 0;
                $.each(items, function(index, item){
                    var reference = item.reference;
                    var name = item.name;
                    var quantity = parseInt(item.quantity);
                    var unitPrice = parseFloat(item.price);
                    var totalPrice = unitPrice * quantity;

                    $('#returnItemTable').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>Rp. " + numeral(unitPrice).format('0,0.00') + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(totalPrice).format("0,0.00") + "</td><tr>")
                    salesReturnValue += totalPrice;
                });

                $('#returnItemTable').append("<tr><td colspan='3'></td><td>Total</td><td>Rp. " + numeral(salesReturnValue).format('0,0.00') + "</td></tr>");

                $('#salesReturnConfirmWrapper').fadeIn(300, function(){
					$('#salesReturnConfirmWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
            }
        })
    }

	$('#accountButton').click(function(){
		refreshAccount(1);
		$('#bankAccountWrapper').fadeIn();
	})

	$('#searchAccountBar').change(function(){
		refreshAccount(1);
	});

	$('#accountPage').change(function(){
		refreshAccount();
	})

	function refreshAccount(page = $('#accountPage').val()){
		$.ajax({
			url:'<?= site_url('Bank_account/getItems') ?>',
			data:{
				page: page,
				term: $('#searchAccountBar').val()
			},
			success:function(response){
				$('#accountTableContent').html("");
				var accountCount = 0;
				var items = response.items;
				$.each(items, function(index, item){
					var number 		= item.number;
					var name		= item.name;
					var id			= item.id;
					var bank		= item.bank;
					var branch		= item.branch;

					$('#accountTableContent').append("<tr><td>" + number + "</td><td>" + name + "</td><td><label>" + bank + "</label><p>" + branch + "</p></td><td><button class='button button_default_dark' onclick='selectAccount(" + id + ")'><i class='fa fa-long-arrow-right'></i></button></td></tr>");

					accountCount++;
				})
				var pages = response.pages;
				$('#accountPage').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#accountPage').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#accountPage').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
				
				if(accountCount > 0){
					$('#accountTable').show();
					$('#accountTableText').hide();
				} else {
					$('#accountTable').hide();
					$('#accountTableText').show();
				}
			}
		})
	}

	function selectAccount(n){
		$.ajax({
			url:"<?= site_url('Bank_account/getById') ?>",
			data:{
				id: n
			},
			success:function(response){
				var name = response.name;
				var number = response.number;
				$('#accountButton').text(name + " - " + number);
				$('#account').val(n);

				$('#bankAccountWrapper').fadeOut();
			}
		})
	}

	function confirmReturn()
	{
		if($('#salesReturnForm').valid()){
			$.ajax({
				url:"<?= site_url('Sales_return/updateBankDateById') ?>",
				data:$('#salesReturnForm').serialize(),
				type:'POST',
				beforeSend:function(){
					$('button').attr('disabled', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					refreshView();
					if(response == 1){
						$('#salesReturnForm').trigger("reset");
						$('#salesReturnConfirmWrapper .slide_alert_close_button').click();
					} else {
						$('#failedNotification').fadeIn(250);
						setTimeout(function(){
							$('#failedNotification').fadeOut(250);
						}, 1000);
					}
				}
			})
		}
	}

	$('.alert_full_close_button').click(function(){
		$(this).parent().parent().fadeOut();
	})
</script>