<head>
	<title>Good Receipt - Delete</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Administrators') ?>' title='Administrators'><i class='fa fa-briefcase'></i></a> /Good Receipt / Delete</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Warning</label>
		<p>Good receipt(s) to be deleted must fullfill these requirements:</p>
		<ul>
			<li>Good receipt has been confirmed.</li>
			<li>Good receipt have not been invoiced.</li>
			<li>Items in the good receipt have not been taken.</li>
		</ul>
		<div id='goodReceiptTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Action</th>
				</tr>
				<tbody id='goodreceiptTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='goodReceiptTableText'>There is no good receipt to be deleted.</p>
	</div>
</div>

<div class='alert_wrapper' id='viewGoodReceiptWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Delete Delivery Order</h3>
		<hr>
		<label>Supplier</label>
		<p id='supplierName_p'></p>
		<p id='supplierAddress_p'></p>
		<p id='supplierCity_p'></p>

		<label>Good Receipt</label>
		<p id='goodReceiptName_p'></p>
		<p id='goodReceiptDate_p'></p>

		<label>Purchase Order</label>
		<p id='purchaseOrderName_p'></p>
		<p id='purchaseOrderDate_p'></p>

		<label>Items</label>
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
			</tr>
			<tbody id='itemTableContent'></tbody>
		</table>

		<button class='button button_danger_dark' onclick='confirmDeletegoodReceipt()'><i class='fa fa-trash'></i></button>
	</div>
</div>

<div class='alert_wrapper' id='deleteGoodReceiptWrapper'>
	<div class='alert_box_confirm_wrapper'>
		<div class='alert_box_confirm_icon'><i class='fa fa-trash'></i></div>
		<div class='alert_box_confirm'>
			<h3>Delete confirmation</h3>
			
			<p>You are about to delete this data.</p>
			<p>Are you sure?</p>
			<button class='button button_default_dark' onclick="$('#deleteGoodReceiptWrapper').fadeOut()">Cancel</button>
			<button class='button button_danger_dark' onclick='deletegoodReceipt()'>Delete</button>
			
			<br><br>
			
			<p style='font-family:museo;background-color:#f63e21;width:100%;padding:5px;color:white;position:relative;bottom:0;left:0;opacity:0' id='errorDeleteGoodReceipt'>Deletation failed.</p>
		</div>
	</div>
</div>

<script>
	var goodReceiptId;

	$(document).ready(function(){
		refreshView();
	});

	$('#page').change(function(){
		refreshView();
	})

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Good_receipt/getUninvoicedItems') ?>",
			data:{
				page: page
			},
			success:function(response){
				var items = response.items;
				$('#goodreceiptTableContent').html("");
				var goodReceiptCount = 0;
				$.each(items, function(index, item){
					var id						= item.id;
					var name					= item.name;
					var date					= item.date;
		
					$('#goodreceiptTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + name + "</td><td><button class='button button_default_dark' onclick='viewGoodReceipt(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
					goodReceiptCount++;
				});

				var pages = response.pages;
				$('#page').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}

				if(goodReceiptCount > 0){
					$('#goodReceiptTable').show();
					$('#goodReceiptTableText').hide();
				} else {
					$('#goodReceiptTable').hide();
					$('#goodReceiptTableText').show();
				}
			}
		})
	}

	function viewGoodReceipt(id){
		$.ajax({
			url:"<?= site_url('Good_receipt/showById') ?>",
			data:{
				id: id
			},
			success:function(response){
				goodReceiptId = id;
				var general = response.general;
				var name = general.name;
				var date = general.date;

				var purchaseOrderName = general.purchase_order_name;
				var purchaseOrderDate = general.purchase_order_date;

				var supplierName = general.supplier_name;
				var complete_address = "";
				var supplier_name			= general.supplier_name;
				var complete_address		= '';
				var supplier_name			= general.supplier_name;
				complete_address			+= general.address;
				var supplier_city			= general.city;
				var supplier_number			= general.number;
				var supplier_rt				= general.rt;
				var supplier_rw				= general.rw;
				var supplier_postal			= general.postal_code;
				var supplier_block			= general.block;

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

				$('#purchaseOrderDate_p').html(my_date_format(purchaseOrderDate));
				$('#purchaseOrderName_p').html(purchaseOrderName);

				$("#goodReceiptName_p").html(name);
				$('#goodReceiptDate_p').html(my_date_format(date));

				var items = response.items;
				$('#itemTableContent').html("");
				$.each(items, function(index, item){
					var reference = item.reference;
					var name = item.name;
					var quantity = item.quantity;

					$('#itemTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td></tr>");
				})

				$('#viewGoodReceiptWrapper').fadeIn(300, function(){
					$('#viewGoodReceiptWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function confirmDeletegoodReceipt()
	{
		$('#deleteGoodReceiptWrapper').fadeIn();
	}

	function deletegoodReceipt(){
		$.ajax({
			url:"<?= site_url('Administrators/deleteGoodReceiptById') ?>",
			data:{
				id: goodReceiptId
			},
			type:"POST",
			beforeSend:function(){
				$('button').attr('disabled', true);
			}, 
			success:function(response){
				$('button').attr('disabled', false);
				refreshView();
				if(response == 1){
					goodReceiptId = null;
					$('#deleteGoodReceiptWrapper').fadeOut();
					$('#viewGoodReceiptWrapper .slide_alert_close_button').click();
				} else {
					$('#errorDeleteGoodReceipt').fadeTo(250, 1);
					setTimeout(function(){
						$('#errorDeleteGoodReceipt').fadeTo(250, 0);
					}, 1000)
				}
			}
		})
	}
</script>
