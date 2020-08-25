<head>
    <title>Purchasing Return</title>
	<style>
		.subtitleText{
			color:#333;
			font-size:0.8em;
			text-align:right;
		}
	</style>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Purchasing') ?>' title='Purchasing'><i class='fa fa-briefcase'></i></a> /Return</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<input type='text' class='form-control' id='search_bar'>
		<br>
		<div id='purchaseReturnTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Name</th>
					<th>Supplier</th>
					<th>Action</th>
				</tr>
				<tbody id='purchaseReturnTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='purchaseReturnTableText'>There is no purchase return to be confirmed.</p>
	</div>
</div>

<div class='alert_wrapper' id='purchaseReturnWrapper'>
	<button class='button slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Confirm purchase return</h3>
		<hr>
		<label>Purchase return data</label>
		<p id='purchaseReturnName_p'></p>

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
			<tbody id='purchaseReturnItemTable'></tbody>
		</table>
		<input type='hidden' id='purchaseReturnId'>
		<button class='button button_default_dark' title='Confirm purchase return' onclick='confirmReturn()'><i class='fa fa-long-arrow-right'></i></button>
		<button class='button button_danger_dark'><i class='fa fa-trash' title='Delete purchase return' onclick='deleteReturn()'></i></button>

		<div class='notificationText danger' id='failedConfirmNotification'><p>Failed to confirm purchase return.</p></div>
		<div class='notificationText danger' id='failedDeleteNotification'><p>Failed to delete purchase return.</p></div>
		
		<p class='subtitleText'>Created by <span id='createdBy_p'></span></p>
		<p class='subtitleText'>Created on <span id='createdDate_p'></span></p>

	</div>
</div>

<script>
	$(document).ready(function(){
		refreshView();
	});

	$("#page").change(function(){
		refreshView();
	})

	$('#search_bar').change(function(){
		refreshView(1);
	})

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Purchase_return/getUnconfirmedItems') ?>",
			data:{
				page: page,
				term: $('#search_bar').val()
			},
			success:function(response){
				var items = response.items;
				var returnCount = 0;
				$('#purchaseReturnTableContent').html("");
				$.each(items, function(index, item){
					var name = item.name;
					var supplierName = item.supplierName;
					var id = item.id;
					$('#purchaseReturnTableContent').append("<tr><td>" + name + "</td><td>" + supplierName + "</td><td><button class='button button_default_dark' onclick='viewReturn(" + id + ")'><i class='fa fa-long-arrow-right'></i></button>");
					returnCount++;
				});

				if(returnCount > 0){
					$('#purchaseReturnTable').show();
					$('#purchaseReturnTableText').hide();
				} else {
					$('#purchaseReturnTable').hide();
					$('#purchaseReturnTableText').show();
				}

				var pages = response.pages;
				$('#page').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}					
				}
			}
		})
	}

	function viewReturn(n){
		$.ajax({
			url:"<?= site_url('Purchase_return/getById') ?>",
			data:{
				id: n
			},
			success:function(response){
				var general = response.general;
				var createdBy = general.created_by;
				var createdDate = general.created_date;
				var name = general.name;
				$('#purchaseReturnId').val(n);

				$("#purchaseReturnName_p").html(name);
				$('#createdBy_p').html(createdBy);
				$('#createdDate_p').html(my_date_format(createdDate));

				var supplier = response.supplier;
				var supplierName 			= supplier.name;
				var complete_address		= '';
				complete_address			+= supplier.address;
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
					
				if(supplier_rw != '000' && supplier_rt != '000'){
					complete_address	+= ' /RW ' + supplier_rw;
				}
					
				if(supplier_postal != null){
					complete_address	+= ', ' + supplier_postal;
				}

				$('#supplierName_p').html(supplierName);
				$('#supplierAddress_p').html(complete_address);
				$('#supplierCity_p').html(supplier_city);

				var items = response.items;
				$('#purchaseReturnItemTable').html("");
				var returnValue = 0;
				$.each(items, function(index, item){
					var reference = item.reference;
					var name = item.name;
					var quantity	= parseInt(item.quantity);
					var price		= parseFloat(item.price);
					var totalPrice	= quantity * price;
					returnValue += totalPrice;

					$('#purchaseReturnItemTable').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>Rp. " + numeral(price).format('0,0.00') + "</td><td>" + numeral(quantity).format("0,0") + "</td><td>Rp. " + numeral(totalPrice).format('0,0.00') + "</td></tr>");
				});

				$('#purchaseReturnItemTable').append("<tr><td colspan='2'></td><td colspan='2'>Total</td><td>Rp. " + numeral(returnValue).format('0,0.00') + "</td></tr>");

				$('#purchaseReturnWrapper').fadeIn(300, function(){
					$('#purchaseReturnWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function confirmReturn(){
		$.ajax({
			url:"<?= site_url('Purchase_return/confirmById') ?>",
			data:{
				id: $('#purchaseReturnId').val()
			},
			type:"POST",
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				refreshView();
				if(response == 1){
					$('#purchaseReturnWrapper .slide_alert_close_button').click();
				} else {
					$('#failedConfirmNotification').fadeIn(250);
					setTimeout(function(){
						$('#failedConfirmNotification').fadeOut(250);
					}, 1000)
				}
			}
		})
	}

	function deleteReturn(){
		$.ajax({
			url:"<?= site_url('Purchase_return/deleteById') ?>",
			data:{
				id: $('#purchaseReturnId').val()
			},
			type:"POST",
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				refreshView();
				if(response == 1){
					$('#purchaseReturnWrapper .slide_alert_close_button').click();
				} else {
					$('#failedDeleteNotification').fadeIn(250);
					setTimeout(function(){
						$('#failedDeleteNotification').fadeOut(250);
					}, 1000)
				}
			}
		})
	}
</script>
