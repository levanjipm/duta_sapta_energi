<head>
	<title>Return - Purchase</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> / Purchase return</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div id='purchaseReturnTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Return</th>
					<th>Supplier</th>
					<th>Action</th>
				</tr>
				<tbody id='purchaseReturnTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='purchaseReturnTableText'>There is no sales order to be confirmed.</p>
	</div>
</div>

<div class='alert_wrapper' id='purchaseReturnConfirmWrapper'>
    <button class='slide_alert_close_button'>&times;</button>
    <div class='alert_box_slide'>
        <h3 style='font-family:bebasneue'>Confirm purchase return</h3>
        <hr>
		<form id='purchaseReturnForm'>
			<label>Bank date</label>
			<input type='date' class='form-control' id='date' name='date' required min='<?= date("2020-01-01") ?>'>

			<label>Bank account</label>
			<button type='button' class='form-control' id='accountButton' style='text-align:left!important'></button>
			<input type='hidden' id='account' name='account' required>
	
			<label>Sales return</label>
			<p id='purchaseReturnDocument'></p>
			<p id='purchaseReturnDate'></p>

			<label>Supplier</label>
			<p id='supplierName_p'></p>
			<p id='supplierAddress_p'></p>
			<p id='supplierCity_p'></p>

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

			<input type='hidden' id='purchaseReturnId' name='id'>
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
	$('#purchaseReturnForm').validate({
		ignore:'',
		rules: {"hidden_field": {required: true}}
	});

	$(document).ready(function(){
		refreshView();
	})

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Purchase_return/getUnassignedPurchaseReturn') ?>",
			data:{
				page:page
			},
			success:function(response){
				var items = response.items;
				$('#purchaseReturnTableContent').html("");
				var itemCount = 0;
				$.each(items, function(index, item){
					var date 					= item.date;
					var name 					= item.name;
					var id						= item.id;

					var supplier				= item.supplier;

					var supplier_name 			= supplier.name;
					var complete_address		= supplier.address;
					var supplier_city			= supplier.city;
					var supplier_number			= supplier.number;
					var supplier_rt				= supplier.rt;
					var supplier_rw				= supplier.rw;
					var supplier_postal			= supplier.postal_code;
					var supplier_block			= supplier.block;

					if(supplier_number != null){
						complete_address	+= ' No. ' + supplier_number;
					}
					
					if(supplier_block != null){
						complete_address	+= ' Blok ' + supplier_block;
					}
				
					if(supplier_rt != '000'){
						complete_address	+= ' RT ' + supplier_rt;
					}
					
					if(supplier_rw != '000' && supplier_rw != '000'){
						complete_address	+= ' /RW ' + supplier_rw;
					}
					
					if(supplier_postal != null){
						complete_address	+= ', ' + supplier_postal;
					}

					$('#purchaseReturnTableContent').append("<tr><td><label>" + name + "</label><p>" + my_date_format(date) + "</p></td><td><label>" + supplier_name + "</label><p>" + complete_address + "</p><p>" + supplier_city + "</p></td><td><button class='button button_default_dark' onclick='viewReturn(" + id + ")'><i class='fa fa-eye'></i></button></tr>");
					itemCount++;
				})

				if(itemCount > 0){
					$('#purchaseReturnTable').show();
					$('#purchaseReturnTableText').hide();
				} else {
					$('#purchaseReturnTable').hide();
					$('#purchaseReturnTableText').show();
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
            url:"<?= site_url('Purchase_return/getSentById') ?>",
            data:{
                id:n
            },
            success:function(response){
				$('#purchaseReturnId').val(n);
                var general = response.general;
                var name = general.name;
                var date = general.date;
                $('#purchaseReturnDate').html(my_date_format(date));
                $('#purchaseReturnDocument').html(name);

                var supplier = response.supplier;
				var supplier_name = supplier.name;
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
				
				if(supplier_block != null || supplier_block != "000"){
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

                $("#supplierName_p").html(supplier_name);
                $('#supplierAddress_p').html(complete_address);
                $('#supplierCity_p').html(supplier_city);

                var items = response.items;
                $('#returnItemTable').html("");
                var salesReturnValue = 0;
                $.each(items, function(index, item){
                    var reference = item.reference;
                    var quantity = item.quantity;
                    var name = item.name;
                    var quantity = parseInt(item.quantity);
                    var unitPrice = parseFloat(item.price);
                    var totalPrice = unitPrice * quantity;

                    $('#returnItemTable').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>Rp. " + numeral(unitPrice).format('0,0.00') + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(totalPrice).format("0,0.00") + "</td><tr>")
                    salesReturnValue += totalPrice;
                });

                $('#returnItemTable').append("<tr><td colspan='3'></td><td>Total</td><td>Rp. " + numeral(salesReturnValue).format('0,0.00') + "</td></tr>");

                $('#purchaseReturnConfirmWrapper').fadeIn(300, function(){
					$('#purchaseReturnConfirmWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
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
		if($('#purchaseReturnForm').valid()){
			$.ajax({
				url:"<?= site_url('Purchase_return/updateBankDateById') ?>",
				data:$('#purchaseReturnForm').serialize(),
				type:'POST',
				beforeSend:function(){
					$('button').attr('disabled', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					refreshView();
					if(response == 1){
						$('#purchaseReturnForm').trigger("reset");
						$('#purchaseReturnConfirmWrapper .slide_alert_close_button').click();
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
</script>
