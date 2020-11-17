<head>
	<title>Purchase return - Delete Dashboard</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Administrators') ?>' title='Administrator'><i class='fa fa-briefcase'></i></a> /Purchase Return / Delete</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div id='salesReturnTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Supplier</th>
					<th>Action</th>
				</tr>
				<tbody id='salesReturnTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='salesReturnTableText'>There is no confirmed purchase return.</p>
	</div>
</div>

<div class='alert_wrapper' id='purchaseReturnWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Purchase Return</h3>
		<hr>
		<label>Supplier</label>
		<p style='font-family:museo' id='supplierNameP'></p>
		<p style='font-family:museo' id='supplierAddressP'></p>
		<p style='font-family:museo' id='supplierCityP'></p>

		<label>Purchase Return</label>
		<p style='font-family:museo' id='purchaseReturnNameP'></p>
		<p style='font-family:museo' id='purchaseReturnDateP'></p>

		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
			</tr>
			<tbody id='purchaseReturnItemTable'></tbody>
		</table>

		<button class="button button_danger_dark" onclick="confirmClose()"><i class='fa fa-trash'></i></button>
	</div>
</div>

<div class='alert_wrapper' id='deletePurchaseReturnWrapper'>
	<div class='alert_box_confirm_wrapper'>
		<div class='alert_box_confirm_icon'><i class='fa fa-trash'></i></div>
		<div class='alert_box_confirm'>
			<input type='hidden' id='delete_customer_id'>
			<h3>Delete confirmation</h3>
			
			<p>You are about to delete this data.</p>
			<p>Are you sure?</p>
			<button class='button button_default_dark' onclick="$('#deletePurchaseReturnWrapper').fadeOut()">Cancel</button>
			<button class='button button_danger_dark' onclick='deletePurchaseReturn()'>Delete</button>
			
			<br><br>
			
			<p style='font-family:museo;background-color:#f63e21;width:100%;padding:5px;color:white;position:relative;bottom:0;left:0;opacity:0' id='errorDeletePurchaseReturn'>Deletation failed.</p>
		</div>
	</div>
</div>
<script>
	var purchaseReturnId = null;
	$(document).ready(function(){
		refresh_view();
	})

	$('#page').change(function(){
		refresh_view();
	})

	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Purchase_return/getConfirmedReceivedReturn') ?>',
			data:{
				page: page,
			},
			type:'GET',
			success:function(response){
				var pages = response.pages;
				$('#page').html('');
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>")
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>")
					}
				}

				var countSalesReturn = 0;
				var items		= response.items;
				$('#salesReturnTableContent').html("");
				$.each(items, function(index, item){
					var supplierName		= item.supplierName;
					var date				= item.date;
					var id					= item.id;
					var name				= item.name;
					$('#salesReturnTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td>" + supplierName + "</td><td><button class='button button_default_dark' onclick='viewPurchaseReturn(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
					countSalesReturn++;
				});

				if(countSalesReturn > 0){
					$('#salesReturnTable').show();
					$('#salesReturnTableText').hide();
				} else {
					$('#salesReturnTable').hide();
					$('#salesReturnTableText').show();
				}
			}
		});
	}

	function viewPurchaseReturn(n){
		$.ajax({
			url:"<?= site_url('Purchase_return/getSentById') ?>",
			data:{
				id: n
			},
			success:function(response){
				purchaseReturnId	= n;
				var general		= response.general;
				var name		= general.name;
				var date		= general.date;

				$('#salesReturnNameP').html(name);
				$('#salesReturnDateP').html(my_date_format(date));

				var supplier				= response.supplier;
				var supplier_name			= supplier.name;
				var complete_address		= supplier.address;
				var supplier_city			= supplier.city;
				var supplier_number			= supplier.number;
				var supplier_rt				= supplier.rt;
				var supplier_rw				= supplier.rw;
				var supplier_postal			= supplier.postal_code;
				var supplier_block			= supplier.block;

				if(supplier_number != '' && supplier_number != null){
					complete_address	+= ' No. ' + supplier_number;
				}
				
				if(supplier_block != null && supplier_block != "000" && supplier_block != ""){
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

				$('#supplierNameP').html(supplier_name);
				$('#supplierAddressP').html(complete_address);
				$('#supplierCityP').html(supplier_city);

				$('#purchaseReturnItemTable').html("");
				var items		= response.items;
				$.each(items, function(index, item){
					var reference		= item.reference;
					var name			= item.name;
					var quantity		= item.quantity;

					$('#purchaseReturnItemTable').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td></tr>");
				});

			},
			complete:function(){
				$('#purchaseReturnWrapper').fadeIn(300, function(){
					$('#purchaseReturnWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function confirmClose(){
		$('#deletePurchaseReturnWrapper').fadeIn();
	}

	function deletePurchaseReturn(){
		$.ajax({
			url:"<?= site_url('Purchase_return/deletePurchaseReturnSent') ?>",
			data:{
				id: purchaseReturnId
			},
			type:"POST",
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				refresh_view();
				if(response == 1){
					$('#deletePurchaseReturnWrapper').fadeOut();
					$('#purchaseReturnWrapper .slide_alert_close_button').click();
				} else {
					$('#errorDeletePurchaseReturn').fadeTo(1, 250);
					setTimeout(function(){
						$('#errorDeletePurchaseReturn').fadeTo(0, 250);
					}, 1000);
				}
			}
		})
	}
</script>
