<head>
    <title>Purchasing Return</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Purchasing') ?>' title='Purchasing'><i class='fa fa-briefcase'></i></a> /Return</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<form id='createReturnForm'>
			<label>Supplier</label>
			<button type='button' class='form-control' id='supplierButton' style='text-align:left!important'></button>
			<input type='hidden' id='supplier' name='supplier' required>
			<br>
			<button type='button' class='button button_default_dark' id='addItemButton'><i class='fa fa-plus'></i> Add item</button>
			<br><br>
			<div id='returnItemTable' style='display:none'>
				<table class='table table-bordered'>
					<tr>
						<th>Reference</th>
						<th>Name</th>
						<th>Quantity</th>
						<th>Price</th>
						<th>Action</th>
					</tr>
					<tbody id='returnItemTableContent'></tbody>
				</table>

				<button type='button' id='createReturnButton' class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
			</div>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='addItemWrapper'>
	<div class='alert_box_full'>
		<button type='button' class='button alert_full_close_button' title='Close add item session'>&times;</button>
		<h3 style='font-family:bebasneue'>Add return item</h3>
		<hr>
		<label>Search</label>
		<input type='text' class='form-control' id='search_bar'>
		<br>
		<div id='itemTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Reference</th>
					<th>Name</th>
					<th>Action</th>
				</tr>
				<tbody id='itemTableContent'></tbody>
			</table>
			
			<select class='form-control' id='page' style='width:100px'>
				<option value='1'></option>
			</select>
		</div>
		<p id='itemTableText'>There is no item found.</p>
	</div>
</div>

<div class='alert_wrapper' id='supplierWrapper'>
	<div class='alert_box_full'>
		<button type='button' class='button alert_full_close_button' title='Close supplier session'>&times;</button>
		<div class='row'>
			<div class='col-xs-12'>
				<h2 style='font-family:bebasneue'>Select supplier</h2>
				<hr>
				<label>Search</label>
				<input type='text' class='form-control' id='searchSupplierBar'>
				<br>
				<div id='supplierTable'>
					<table class='table table-bordered'>
						<tr>
							<th>Name</th>
							<th>Information</th>
							<th>Action</th>
						</tr>
						<tbody id='supplierTableContent'></tbody>
					</table>
					
					<select class='form-control' id='supplierPage' style='width:100px'>
						<option value='1'></option>
					</select>
				</div>
				<p id='supplierTableText'>There is no supplier found.</p>
			</div>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='returnWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Purchase return</h3>
		<hr>
		<label>Supplier</label>
		<p id='supplierName_p'></p>
		<p id='supplierAddress_p'></p>
		<p id='supplierCity_p'></p>

		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Quantity</th>
				<th>Price</th>
				<th>Total price</th>
			</tr>
			<tbody id='returnItemFinal'></tbody>
		</table>
		<button type='button' id='submitReturnButton' class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>

		<br>
		<div class='notificationText danger' id='failedInsertReturn'><p>Failed to insert item.</p></div>
	</div>
</div>

<script>
	$('#createReturnForm').validate({
		ignore:"",
		rules: {"hidden_field": {required: true}}
	});

	$('#search_bar').change(function(){
		refresh_view(1);
	})

	$('#page').change(function(){
		refresh_view();
	})

	$('#addItemButton').click(function(){
		$('#search_bar').val('');
		refresh_view(1);
	});

	$('#supplierButton').click(function(){
		$('#searchSupplierBar').val("");
		refreshSupplierView(1);
	});

	$('#supplierPage').change(function(){
		refreshSupplierView();
	})

	$('#searchSupplierBar').change(function(){
		refreshSupplierView(1);
	})

	function refreshSupplierView(page = $('#supplierPage').val()){
		$.ajax({
			url:"<?= site_url('Supplier/showItems') ?>",
			data:{
				page: page,
				term: $('#searchSupplierBar').val()
			},
			success:function(response){
				var items = response.suppliers;
				$('#supplierTableContent').html("");
				$.each(items, function(index, item){
					var supplierId				= item.id;
					var supplierName 			= item.name;
					var complete_address		= '';
					complete_address			+= item.address;
					var supplier_city			= item.city;
					var supplier_number			= item.number;
					var supplier_rt				= item.rt;
					var supplier_rw				= item.rw;
					var supplier_postal			= item.postal_code;
					var supplier_block			= item.block;

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

					$('#supplierTableContent').append("<tr><td>" + supplierName + "</td><td><p>" + complete_address + "</p><p>" + supplier_city + "</p></td><td><button class='button button_default_dark' onclick='selectSupplier(" + supplierId + ")'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
				})

				var pages = response.pages;
				$('#supplierPage').html("");
				for(i = 1; i <= pages; i ++){
					if(i == page){
						$('#supplierPage').append("<option value='" + i + "' selected>" + i + "</option>")
					} else {
						$('#supplierPage').append("<option value='" + i + "'>" + i + "</option>");
					}
				}

				if(items.length == 0){
					$('#supplierTable').hide();
					$('#supplierTableText').show();
				} else {
					$('#supplierTable').show();
					$('#supplierTableText').hide();
				}

				$('#supplierWrapper').fadeIn();
			}
		})
	}

	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Item/showItems') ?>',
			data:{
				term:$('#search_bar').val(),
				page:page
			},
			success:function(response){
				$('#itemTableContent').html("");
				var items		= response.items;
				var pages		= response.pages;
				
				if(items.length > 0){
					$('#itemTable').show();
					$('#itemTableText').hide();
					$.each(items, function(index, item){
						var reference		= item.reference;
						var name			= item.name;
						var id				= item.item_id;
						
						$('#itemTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td><button type='button' class='button button_default_dark' onclick='addItem(" + id + ")' title='Add " + reference + " to cart'><i class='fa fa-cart-plus'></i></button></td></tr>");
					});
				} else {
					$('#itemTable').hide();
					$('#itemTableText').show();
				}
				
				$('#page').html('');
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}

				$('#addItemWrapper').fadeIn();
			}
		});
	}

	function addItem(n){
		if($('#tr-' + n).length == 0){
			$.ajax({
				url:'<?= site_url("Item/showById") ?>',
				data:{
					id:n
				},
				success:function(response){
					var reference = response.reference;
					var name = response.name;
					$('#returnItemTableContent').append("<tr id='tr-" + n + "'><td id='reference-" + n + "'>" + reference + "</td><td id='name-" + n + "'>" + name + "</td><td><input type='number' class='form-control' id='quantity-" + n + "' name='quantity[" + n + "]' required min='0'></td><td><input type='number' class='form-control' id='price-" + n + "' name='price[" + n + "]' required min='0'></td><td><button type='button' class='button button_danger_dark' onclick='removeItem(" + n + ")'><i class='fa fa-trash'></i></button></td></tr>");

					$('#returnItemTable').show();
					$('#addItemWrapper').fadeOut();
				}
			})
		}
	}

	function removeItem(n){
		$('#tr-' + n).remove();
		if($('#returnItemTableContent tr').length == 0){
			$('#returnItemTable').hide();
		} else {
			$('#returnItemTable').show();
		}
	}

	function selectSupplier(n){
		$.ajax({
			url:"<?= site_url('Supplier/getById') ?>",
			data:{
				id:n
			},
			success:function(response){
				var name = response.name;
				var city = response.city;

				var complete_address		= '';
				complete_address			+= response.address;
				var supplier_city			= response.city;
				var supplier_number			= response.number;
				var supplier_rt				= response.rt;
				var supplier_rw				= response.rw;
				var supplier_postal			= response.postal_code;
				var supplier_block			= response.block;

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

				$('#supplierButton').html(name + " - " + city);
				$('#supplier').val(n);

				$('#supplierName_p').html(name);
				$('#supplierAddress_p').html(complete_address);
				$('#supplierCity_p').html(supplier_city);

				$('#supplierWrapper').fadeOut();
			}
		})
	}

	$('#createReturnButton').click(function(){
		if($('#createReturnForm').valid()){
			var returnValue = 0;
			$('input[id^="quantity-"]').each(function(){
				var id = parseInt($(this).attr('id').substring(9, 264));
				var reference = $('#reference-' + id).html();
				var name = $('#name-' + id).html();

				var quantity = parseInt($(this).val());

				$(this).val(quantity);

				var price = parseFloat($('#price-' + id).val());
				var totalPrice = quantity * price;
				returnValue += totalPrice;

				$('#returnItemFinal').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(price).format('0,0.00') + "</td><td>Rp. " + numeral(totalPrice).format('0,0.00') + "</td></tr>"); 
			});

			$('#returnItemFinal').append("<tr><td colspan='2'></td><td colspan='2'>Total</td><td>Rp. " + numeral(returnValue).format('0,0.00') + "</td></tr>");


			$('#returnWrapper').fadeIn(300, function(){
				$('#returnWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
			});
		}
	});

	$('#submitReturnButton').click(function(){
		if($('#createReturnForm').valid()){
			$.ajax({
				url:'<?= site_url('Purchase_return/insertItem') ?>',
				data:$('#createReturnForm').serialize(),
				type:"POST",
				beforeSend:function(){
					$('input').attr('readonly', true);
					$('button').attr('disabled', true);
				},
				success:function(response){
					$('input').attr('readonly', false);
					$('button').attr('disabled', false);
					if(response == 1){
						$('#returnItemTableContent').html("");
						$('#returnItemTable').hide();
						$('#createReturnForm').trigger("reset");
						$("#supplierButton").html("");

						$('#returnWrapper .slide_alert_close_button').click();
					} else {
						$('#failedInsertReturn').fadeIn();
						setTimeout(function(){
							$('#failedInsertReturn').fadeOut();
						}, 1000)
					}
				}
			})
		}
	})

	$('.alert_full_close_button').click(function(){
		$(this).parent().parent().fadeOut();
	})
</script>
