<?php
	$complete_address		= '';
	$complete_address		.= $supplier->address;
	$supplier_number		= $supplier->number;
	$supplier_block			= $supplier->block;
	$supplier_rt			= $supplier->rt;
	$supplier_rw			= $supplier->rw;
	$supplier_postal_code	= $supplier->postal_code;
	
	$created_by				= $general->created_by;
	$confirmed_by			= $general->confirmed_by;
	
	$complete_address		.= 'No. ' . $supplier_number;
	
	if($supplier_block		== '' && $supplier_block == '000'){
		$complete_address	.= 'Block ' . $supplier_block;
	};
	
	if($supplier_rt != '' && $supplier_rt != '000'){
		$complete_address	.= 'RT ' . $supplier_rt . '/ RW ' . $supplier_rw;
	}
	
	if($supplier_postal_code != ''){
		$complete_address	.= ', ' . $supplier_postal_code;
	}
	
	$delivery_address			= $general->dropship_address;
	$delivery_city				= $general->dropship_city;
	$delivery_contact_person	= $general->dropship_contact_person;
	$delivery_contact			= $general->dropship_contact;
	
	if($delivery_address ==  null){
		$delivery_address		= "<strong>PT Duta Sapta Energi</strong>";
		$delivery_city			= "Jalan Babakan Hantap no. 23";
		$delivery_contact_person	= 'Bandung';
		$delivery_contact			= '';
	}
?>
<head>
	<title>Purchase order - Edit</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Administrator') ?>' title='Administrator'><i class='fa fa-briefcase'></i></a> /Purchase Order / Edit</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<form id='purchaseOrderForm'>
			<label>Supplier</label>
			<p><?= $supplier->name ?></p>
			<p><?= $complete_address ?></p>
			<p><?= $supplier->city ?></p>

			<label>Status</label>
			<select class='form-control' name='status' id='purchase_order_status'>
				<option value='1' <?php if($general->status == null && $general->date_send_request != null){ echo "selected"; } ?>>Choose date</option>
				<option value='2' <?php if($general->status == "URGENT"){ echo "selected"; } ?>>Urgent delivery</option>
				<option value='3' <?php if($general->status == null && $general->date_send_request == null){ echo "selected"; } ?>>Unknown date</option>
			</select>

			<div id='purchase_order_status_detail'>
				<label>Send date request</label>
				<input type='date' class='form-control' id='request_date' name='request_date' required min='2020-01-01'>
			</div>

			<script>
				$(document).ready(function(){
					if($('#purchase_order_status').val() == 1){
						$('#purchase_order_status_detail').show();
						$('#request_date').attr('required', true);
					} else {
						$('#purchase_order_status_detail').hide();
						$('#request_date').attr('required', false);
					}
				})

				$('#purchase_order_status').change(function(){
					if($(this).val() == 1){
						$('#purchase_order_status_detail').show();
						$('#request_date').attr('required', true);
					} else {
						$('#purchase_order_status_detail').hide();
						$('#request_date').attr('required', false);
					}
				})
			</script>

			<label>Promo Code</label>
			<input type='text' class='form-control' name='promo_code' value='<?= $general->promo_code ?>'>

			<label>Delivery</label>
			<br>
			<label><input type='checkbox' id='dropship' <?= ($general->dropship_address != NULL) ? "checked": ""; ?>> Dropship</label>
			<br>
			<div id='dropship_detail' style='display:none'>
				<label>Address</label>
				<textarea class='form-control' name='dropship_address'><?= $general->dropship_address ?></textarea>
				
				<label>City</label>
				<input type='text' class='form-control' name='dropship_city' value='<?= $general->dropship_city ?>'>
				
				<label>Person in charge</label>
				<input type='text' class='form-control' name='dropship_contact_person' value='<?= $general->dropship_contact_person ?>'>
				
				<label>Contact number</label>
				<input type='text' class='form-control' name='dropship_contact' value='<?= $general->dropship_contact ?>'>
			</div>
			<script>
				$(document).ready(function(){
					if($('#dropship').is(":checked")){
						$('#dropship_detail').show();
						$('#dropship_detail input').attr('required', true);
						$('#dropship_detail textarea').attr('required', true);
					} else {
						$('#dropship_detail').hide();
						$('#dropship_detail input').attr('required', false);
						$('#dropship_detail textarea').attr('required', false);
					}
				});

				$('#dropship').change(function(){
					if($('#dropship').is(":checked")){
						$('#dropship_detail').show();
						$('#dropship_detail input').attr('required', true);
						$('#dropship_detail textarea').attr('required', true);
					} else {
						$('#dropship_detail').hide();
						$('#dropship_detail input').attr('required', false);
						$('#dropship_detail textarea').attr('required', false);
					}
				})
			</script>

			<label>Current Items</label>
			<div class='table-responsive-xl'>
				<table class='table table-bordered' id='existingTable'>
					<tr>
						<th>Reference</th>
						<th>Name</th>
						<th>Pricelist</th>
						<th>Discount</th>
						<th>Quantity</th>
						<th>Sent</th>
						<th>Action</th>
					</tr>
<?php foreach($items as $item){?>
					<tr id='existingItemRow-<?= $item->id ?>'>
						<td><?= $item->reference ?></td>
						<td><?= $item->name ?></td>
						<td><input type='number' class='form-control' name='pricelist[<?= $item->id ?>]' value='<?= $item->price_list ?>' required min='1'></td>
						<td><input type='number' class='form-control' min='0' max='100' name='discount[<?= $item->id ?>]' value='<?= (1 - ($item->net_price / $item->price_list)) * 100 ?>' <?= ($item->received > 0) ? "readonly" : "required"; ?>></td>
						<td><input type='number' class='form-control' name='quantity[<?= $item->id ?>]' value='<?= $item->quantity ?>' min='<?= max(1, $item->received) ?>' <?= ($item->received > 0) ? "readonly" : "required"; ?>></td>
						<td><?= number_format($item->received, 0) ?></td>
						<td><button type='button' class='button button_danger_dark' <?= ($item->received > 0) ? "disabled" : ""; ?> onclick='deleteExistingItem(<?= $item->id ?>)'><i class='fa fa-trash'></i></button></td>
					</tr>
<?php } ?>
				</table>
			</div>
			<p id='existingTableText' style='display:none'>There is no exist items in this purchase order.</p>
			<br>
			<button type='button' class='button button_default_dark' id='addItemButton'><i class='fa fa-cart-plus'></i> Add item</button>
			<br><br>

			<label>Additional Orders</label>
			<div id='extraItemTable' style='display:none'>
				<table class='table table-bordered'>
					<tr>
						<th>Reference</th>
						<th>Name</th>
						<th>Pricelist</th>
						<th>Discount</th>
						<th>Quantity</th>
						<th>Action</th>
					</tr>
					<tbody id='purchaseOrderExtraTable'></tbody>
				</table>
			</div>
			<p id='extraItemTableText'>There is no item found.</p>

			<label>Additional Bonus</label>
			<div id='extraBonusItemTable' style='display:none'>
				<table class='table table-bordered'>
					<tr>
						<th>Reference</th>
						<th>Name</th>
						<th>Quantity</th>
						<th>Action</th>
					</tr>
					<tbody id='purchaseOrderExtraBonusTable'></tbody>
				</table>
			</div>
			<p id='extraBonusItemTableText'>There is no bonus found.</p>

			<label>Note</label>
			<textarea class='form-control' name='note' rows='3' style='resize:none'><?= $general->note ?></textarea>
			<br>
			<button type='button' class='button button_default_dark' onclick='validatePurchaseOrder()'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='purchaseOrderWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Purchase order</h3>
		<hr>
		<label>Supplier</label>
		<p style='font-family:museo'><?= $supplier->name ?></p>
		<p style='font-family:museo'><?= $complete_address ?></p>
		<p style='font-family:museo'><?= $supplier->city ?></p>

		<label>Purchase order</label>
		<p style='font-family:museo'><?= $general->name ?></p>
		<p style='font-family:museo'><?= date('d M Y', strtotime($general->date)) ?></p>
		<p style='font-family:museo' id='purchase_order_status_p'></p>

		<label>Delivery address</label>
		<p style='font-family:museo' id='delivery_address_p'></p>
		<p style='font-family:museo' id='delivery_city_p'></p>
		<p style='font-family:museo' id='delivery_contact_person_p'></p>
		<p style='font-family:museo' id='delivery_contact_p'></p>

		<div class='table-responsive-lg'>
			<table class='table table-bordered'>
				<tr>
					<th>Reference</th>
					<th>Name</th>
					<th>Pricelist</th>
					<th>Discount</th>
					<th>Net price</th>
					<th>Quantity</th>
					<th>Total price</th>
					<th>Pending</th>
				</tr>
				<tbody id='purchaseOrderItemTable'></tbody>
			</table>
		</div>
		<form action="<?= site_url('Purchase_order/editForm') ?>" method="POST">
			<input type='hidden' id='purchaseOrderId' name='id'>
			<button class="button button_default_dark" onclick="editPurchaseOrder()"><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='add_item_wrapper'>
	<div class='alert_box_full'>
		<button type='button' class='button alert_full_close_button' title='Close add item session'>&times;</button>

		<div class='row'>
			<div class='col-xs-12'>
				<h3 style='font-family:bebasneue'>Add item to cart</h3>
				<hr>
				<label>Search</label>
				<input type='text' class='form-control' id='search_bar'>
				<br>
				<table class='table table-bordered' id='shopping_item_list_table'>
					<tr>
						<th>Reference</th>
						<th>Name</th>
						<th>Action</th>
					</tr>
					<tbody id='shopping_item_list_tbody'>
					</tbody>
				</table>
				
				<select class='form-control' id='page' style='width:100px'>
					<option value='1'></option>
				</select>
			</div>
		</div>
	</div>
</div>

<script>
	var deletedItems = [];
	var extraItems	= [];
	var extraItemsArray = [];
	var extraBonusItems	= [];

	$('#purchaseOrderForm').validate();

	function validatePurchaseOrder(){
		if($('#purchaseOrderForm').valid()){
			alert("Valid");
		}
	}

	function deleteExistingItem(n){
		$('#existingItemRow-' + n).hide();
		if(!deletedItems.includes(parseInt(n))){
			deletedItems.push(parseInt(n));
		}

		adjustExistingTable();
	}

	function adjustExistingTable(){
		var numOfVisibleRows = $('#existingTable tr:visible').length;
		if(numOfVisibleRows > 1){
			$('#existingTableText').hide();
			$('#existingTable').show();
		} else {
			$('#existingTableText').show();
			$('#existingTable').hide();
		}
	}

	$('#addItemButton').click(function(){
		$('#search_bar').val("");
		refreshItemView(1);
	});

	$('#search_bar').change(function(){
		refreshItemView(1);
	})

	$('#page').change(function(){
		refreshItemView();
	})

	function refreshItemView(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Item/showItems') ?>',
			data:{
				term:$('#search_bar').val(),
				page:page
			},
			success:function(response){
				$('#add_item_wrapper').slideDown(300);
				$('#shopping_item_list_tbody').html('');
				var item_array	= response.items;
				var pages		= response.pages;
				var page		= response.page;
				
				if($('#cart_products tr').length > 0){
					$('#cart_products_table').show();
				} else {
					$('#cart_products_table').hide();
				}
				
				$.each(item_array, function(index, item){
					var reference		= item.reference;
					var id				= item.item_id;
					var name			= item.name;
					
					$('#shopping_item_list_tbody').append("<tr><td>" + reference + "</td><td>" + name + "</td><td><button type='button' class='button button_success_dark' id='addItemButton-" + id + "' title='Add " + reference + " to cart'><i class='fa fa-cart-plus'></i></button> <button type='button' class='button button_danger_dark' id='addBonusItemButton-" + id + "' title='Add " + reference + " to cart as bonus'><i class='fa fa-gift'></i></button></td></tr>");

					$('#addItemButton-' + id).click(function(){
						$('.alert_full_close_button').click();
						if(!extraItems.includes(parseInt(id))){
							extraItems.push(parseInt(id));
							$('#purchaseOrderExtraTable').append("<tr id='extraItemRow-" + id + "'><td>" + reference + "</td><td>" + name + "</td><td><input type='number' class='form-control' name='extraPriceList[" + id + "]' id='extraPriceList-" + id + "' required min='1'></td><td><input type='number' class='form-control' name='extraDiscount[" + id + "]' required min='0' max='100'></td><td><input type='number' class='form-control' id='extraQuantity-" + id + "' name='extraQuantity[" + id + "]' min='1' required></td><td><button class='button button_danger_dark' onclick='deleteExtraItem(" + id + ")'><i class='fa fa-trash'></i></button></td></tr>");

							adjustExtraTable();
						}
					});

					$('#addBonusItemButton-' + id).click(function(){
						$('.alert_full_close_button').click();
						if(!extraBonusItems.includes(parseInt(id))){
							extraBonusItems.push(parseInt(id));
							$('#purchaseOrderExtraBonusTable').append("<tr id='extraItemBonusRow-" + id + "'><td>" + reference + "</td><td>" + name + "</td><td><input type='number' class='form-control' name='extraBonusQuantity[" + id + "]' min='1' required></td><td><button class='button button_danger_dark' onclick='deleteExtraItem(" + id + ")'><i class='fa fa-trash'></i></button></td></tr>");

							adjustExtraBonusTable();
						}
					});
				});
				
				$('#page').html('');
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		});
	}

	function deleteExtraItem(n){
		var index		= extraItems.indexOf(parseInt(n));
		extraItems.splice(index, 1, 0);
	}

	function adjustExtraTable(){
		var numOfVisibleRows = $('#purchaseOrderExtraTable tr').length;
		if(numOfVisibleRows > 0){
			$('#extraItemTableText').hide();
			$('#extraItemTable').show();
		} else {
			$('#extraItemTableText').show();
			$('#extraItemTable').hide();
		}
	}

	function adjustExtraBonusTable(){
		var numOfVisibleRows = $('#purchaseOrderExtraBonusTable tr').length;
		if(numOfVisibleRows > 0){
			$('#extraBonusItemTableText').hide();
			$('#extraBonusItemTable').show();
		} else {
			$('#extraBonusItemTableText').show();
			$('#extraBonusItemTable').hide();
		}
	}

	$('.alert_full_close_button').click(function(){
		$(this).parent().parent().fadeOut();
	});
</script>
