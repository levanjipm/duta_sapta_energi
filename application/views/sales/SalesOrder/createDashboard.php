<head>
	<title>Create sales order</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Sales_order') ?>'>Sales order </a> /Create</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<form action='<?= site_url('Sales_order/inputItem') ?>' method='POST' id='sales_order_form'>
			<label>Date</label>
			<input type='date' class='form-control' name='sales_order_date' id='sales_order_date' value='<?= date('Y-m-d') ?>'>
			
			<input type='hidden' value='<?= $guid ?>' name='guid'>
			
			<label>Customer</label>
			<button class='form-control' type='button' id='select_customer_button' style='text-align:left'></button>
			
			<label>Customer detail</label>
			<div class='information_box' id='customer_address_select'></div>
			<div style='padding:2px 10px;background-color:#ffc107;width:100%;display:none' id='warning_text'>
				<p ><i class='fa fa-exclamation-triangle'></i> Warning! This Sales Order might not be approved based on current customer's status</p>
			</div>
			<input type='hidden' name='customer_id' id='customer_id' required>
			<br>
			
			<label>Seller</label>
			<button type='button' class='form-control' id='sellerButton' style='text-align:left!important'>None</button>
			<input type='hidden' id='sales_order_seller' name='sales_order_seller' value="">
	
			<label>Taxing</label>
			<select class='form-control' name='taxing' id='taxing'>
				<option value='0'>Non taxable</option>
				<option value='1'>Taxable</option>
			</select>
	
			<label>Invoicing method</label>
			<select class='form-control' name='method' id='method'>
				<option value='1'>Retail</option>
				<option value='2'>Coorporate</option>
			</select>

			<label>Note</label>
			<textarea class='form-control' style='resize:none' rows='3' name='note' id='note'></textarea>
			
			<br>
			<button type='button' class='button button_default_dark' id='add_item_button'><i class='fa fa-shopping-cart'></i> Add item</button>
			<br><br>
			<div class="table-responsive-md">
				<table class='table table-bordered' id='cart_products_table' style='display:none'>
					<tr>
						<th>Reference</th>
						<th>Name</th>
						<th>Price list</th>
						<th>Discount (%)</th>
						<th>Quantity</th>
						<th>Action</th>
					</tr>
					<tbody id='cart_products'></tbody>
				</table>
			</div>
			
			<div class='table-responsive-md'>
				<table class='table table-bordered' id='bonus_cart_products_table' style='display:none'>
					<tr>
						<th>Item</th>
						<th>Description</th>
						<th>Quantity</th>
						<th>Action</th>
					</tr>
					<tbody id='bonus_cart_products'></tbody>
				</table>
			</div>
	
			<button type='button' class='button button_default_dark' onclick='validate_form()' style='display:none' id='submit_button'>Submit</button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='selectSellerWrapper'>
	<div class='alert_box_full'>		
		<button type='button' class='button alert_full_close_button' title='Close select customer session'>&times;</button>
		<h3 style='font-family:bebasneue'>Select seller</h3>
		<br>
		<div id='sellerTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Name</th>
					<th>Action</th>
				</tr>
				<tbody id='sellerTableContent'></tbody>
			</table>
			<select class='form-control' id='sellerPage' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='sellerTableText'>There is no seller found.</p>
	</div>
</div>

<div class='alert_wrapper' id='select_customer_wrapper'>
	<div class='alert_box_full'>		
		<button type='button' class='button alert_full_close_button' title='Close select customer session'>&times;</button>
		<h3 style='font-family:bebasneue'>Select customer</h3>
		<br>
		<input type='text' class='form-control' id='search_customer'>
		<br>
		<div id='customerTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Name</th>
					<th>Address</th>
					<th>Action</th>
				</tr>
				<tbody id='customerTableContent'></tbody>
			</table>
		
			<select class='form-control' id='customer_page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='customerTableText'>There is no customer found.</p>
	</div>
</div>

<div class='alert_wrapper' id='add_item_wrapper'>
	<div class='alert_box_full'>
		<button type='button' class='button alert_full_close_button' title='Close add item session'>&times;</button>

		<div class='row'>
			<div class='col-xs-12'>
				<h2 style='font-family:bebasneue'>Add item to cart</h2>
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

<div class='alert_wrapper' id='validate_sales_order_wrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide' id='validate_sales_order_box'>
		<h3 style='font-family:bebasneue'>Create Sales Order</h3>
		<hr>
		<label>Date</label>
		<p id='date'></p>
		
		<label>Taxing</label>
		<p id='taxing_p'></p>
		
		<label>Seller</label>
		<p id='seller_p'>None</p>
		
		<label>Customer</label>
		<p id='customer_p'></p>
		<p id='customer_address_p'></p>
		
		<label>Invoicing method</label>
		<p id='invoicing_p'></p>
		
		<div class='table-responsive-sm'>
		<table class='table table-bordered'>
				<tr>
					<th>Reference</th>
					<th>Name</th>
					<th>Price list</th>
					<th>Discount</th>
					<th>Unit price</th>
					<th>Quantity</th>
					<th>Total price</th>
				</tr>
				<tbody id='table_item_confirm'></tbody>
			</table>
		</div>

		<label>Note</label>
		<p id='salesOrderNote_p'></p>

		<div class='notificationText warning' id='warningDebtText'><i class='fa fa-exclamation-triangle'></i> Warning! We found a problem while checking customer's account.</p></div><br>
		
		<button class='button button_default_dark' onclick='submit_form()' id='submitFormButton'>Submit</button>
	</div>
</div>
<script>
	$('#sellerButton').click(function(){
		refreshSeller(1);
		$('#selectSellerWrapper').fadeIn();
	})

	$('#sellerPage').change(function(){
		refreshSeller();
	})

	function refreshSeller(page = $('#sellerPage').val())
	{
		$.ajax({
			url:"<?= site_url('Users/getSalesItems') ?>",
			data:{
				page: page
			},
			success:function(response){
				var items = response.users;
				var sellerCount = 0;
				$('#sellerTableContent').html("");
				$('#sellerTableContent').append("<tr><td><img src='<?= base_url() . '/assets/ProfileImages/defaultImage.png' ?>' style='width:30px;height:30px;border-radius:50%'> None</td><td><button class='button button_default_dark' onclick='selectEmptySeller()'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
				$.each(items, function(index, item){
					var name = item.name;
					var id = item.id;
					if(item.image_url == null){
						var imageUrl = "<?= base_url() . '/assets/ProfileImages/defaultImage.png' ?>";
					} else {
						var imageUrl = "<?= base_url() . '/assets/ProfileImages/' ?>" + item.image_url;
					}

					$('#sellerTableContent').append("<tr><td><img src='" + imageUrl + "' style='width:30px;height:30px;border-radius:50%'> " + name + "</td><td><button class='button button_default_dark' id='selectSellerButton-" + id + "'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
					$('#selectSellerButton-' + id).click(function(){
						$('#sales_order_seller').val(id);
						$('#sellerButton').html(name);
						$('#seller_p').html(name);
						$('#selectSellerWrapper .alert_full_close_button').click();
					});
					sellerCount++;
				});

				if(sellerCount == 0){
					$('#sellerTable').hide();
					$('#sellerTableText').show();
				} else {
					$("#sellerTable").show();
					$('#sellerTableText').hide();
				};

				var pages = response.pages;
				$('#sellerPage').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#sellerPage').append("<option value='" + i + "' selected>" + i  + "</option>");
					} else {
						$('#sellerPage').append("<option value='" + i + "'>" + i  + "</option>");
					}
				}
			}
		})
	}

	function selectEmptySeller(){
		$('#seller').val("");
		$('#sellerButton').html("None");
		$('#seller_p').html("None");
		$('#selectSellerWrapper .alert_full_close_button').click();
	}

	$('.slide_alert_close_button').click(function(){
		$('input').attr('readonly',false);
		$('select').attr('readonly',false);
		$('#table_item_confirm').html('');
		
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
	
	$('#add_item_button').click(function(){
		$('#search_bar').val('');
		refresh_view(1);
	});
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Item/showItems') ?>',
			data:{
				term:$('#search_bar').val(),
				page:page
			},
			success:function(response){
				$('#add_item_wrapper').fadeIn();
				$('#shopping_item_list_tbody').html('');
				var item_array	= response.items;
				var pages		= response.pages;
				var page		= response.page;
				
				if(item_array.length > 0){
					$('#shopping_item_list_table').show();
					$.each(item_array, function(index, item){
						var reference		= item.reference;
						var id				= item.id;
						var name			= item.name;
						
						$('#shopping_item_list_tbody').append("<tr><td>" + reference + "</td><td>" + name + "</td><td><button type='button' class='button button_success_dark' onclick='addItem(" + id + ")' title='Add " + reference + " to cart'><i class='fa fa-cart-plus'></i></button> <button type='button' class='button button_danger_dark' onclick='addBonusItem(" + id + ")' title='Add " + reference + " to cart as bonus'><i class='fa fa-gift'></i></button></td></tr>");
					});
				} else {
					$('#shopping_item_list_table').hide();
				}
				
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
	
	$("#sales_order_form").validate({
		ignore: '',
		rules: {"hidden_field": {min:1}}
	});
	
	function validate_form(n){
		if(!$("#sales_order_form").valid()){
			return false;
		} else {
			$('#sales_order_tbody').html('');
			$('input').attr('readonly',true);
			$('select').attr('readonly',true);
			$('#table_item_confirm').html('');
			
			var taxing 		= $("#taxing option:selected").html();
			var date 		= $("#sales_order_date").val();
			var customer	= $("#select_customer_button").text();
			var method		= $("#method option:selected").html();
			var note		= $('#note').val();
			
			$('#customer_p').html(customer);
			$('#customer_address_p').html($('#customer_address_select').html());
			$('#date').html(my_date_format(date));
			$('#taxing_p').html(taxing);
			$('#salesOrderNote_p').html((note == "") ? "<i>Not available</i>" : note);
			
			$('#invoicing_p').html(method);
			
			var sales_order_value = 0;
			
			$('td[id^="reference-"]').each(function(){
				var id 			= $(this).attr('id');
				var uid 		= parseInt(id.substring(10,50));
				var quantity 	= parseInt($('#quantity-' + uid).val());
				var discount 	= $('#discount-' + uid).val();
				var price_list 	= $('#price_list-' + uid).val();
				var name	 	= $('#name-' + uid).html();
				var reference	= $('#reference-' + uid).html();
				var unit_price	= price_list * ( 100 - discount) / 100;
				var total_price	= unit_price * quantity;
				sales_order_value += total_price;
				
				$('#table_item_confirm').append(
				"<tr>"+
					"<td>" + reference  + "</td>"+
					"<td>" + name + "</td>"+
					"<td>Rp. " + numeral(price_list).format('0,0.00') + "</td>"+
					"<td>" + numeral(discount).format('0,0.00') + " %</td>"+
					"<td>Rp. " + numeral(unit_price).format('0,0.00') + "</td>"+
					"<td>" + numeral(quantity).format('0,0') + "</td>"+
					"<td>Rp. " + numeral(total_price).format('0,0.00') + "</td>"+
				"</tr>"
				);
			});
			
			$('td[id^="bonus_reference-"]').each(function(){
				var id 			= $(this).attr('id');
				var uid 		= parseInt(id.substring(16,50));
				var quantity 	= parseInt($('#bonus_quantity-' + uid).val());
				var name	 	= $('#bonus_name-' + uid).html();
				var reference	= $('#bonus_reference-' + uid).html();
				$('#table_item_confirm').append(
				"<tr>"+
					"<td>" + reference  + "</td>"+
					"<td>" + name + "</td>"+
					"<td>Rp. " + numeral(0).format('0,0.00') + "</td>"+
					"<td>" + numeral(0).format('0,0.00') + " %</td>"+
					"<td>Rp. " + numeral(0).format('0,0.00') + "</td>"+
					"<td>" + numeral(quantity).format('0,0') + "</td>"+
					"<td>Rp. " + numeral(0).format('0,0.00') + "</td>"+
				"</tr>"
				);
			});
			
			$('#table_item_confirm').append(
				"<tr>"+
					"<td colspan='4'></td>"+
					"<td colspan='2'>Total</td>"+
					"<td>Rp. " + numeral(sales_order_value).format('0,0.00') + "</td>"+
				"</tr>"
			);
			$('#validate_sales_order_wrapper').fadeIn(300, function(){
				$('#validate_sales_order_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
			});
		}
	};
	
	$('.alert_full_close_button').click(function(){
		$(this).parents().find('.alert_wrapper').fadeOut();
	});
	
	function submit_form(){
		$("#sales_order_form").validate();
		$('#submitFormButton').attr('disabled', true);
		if($("#sales_order_form").valid()){
			$('#sales_order_form').submit();
		};
	};
	
	$('#customer_page').change(function(){
		refresh_customer_view();
	});
	
	$('#search_customer').change(function(){
		refresh_customer_view(1);
	});
	
	function refresh_customer_view(page = $('#customer_page').val()){
		$.ajax({
			url:'<?= site_url('Customer/showItems') ?>',
			data:{
				page:page,
				term:$('#search_customer').val(),
			},
			success:function(response){
				$('#customerTableContent').html('');
				var customerCount = 0;
				var customer_array	= response.customers;
				var pages			= response.pages;
				$.each(customer_array, function(index, customer){
					var customer_id		= customer.id;
					var customer_name	= customer.name;
					var customer_address	= customer.address;
					var customer_number		= customer.number;
					var customer_block		= customer.block;
					var customer_rt			= customer.rt;
					var customer_rw			= customer.rw;
					var customer_city		= customer.city;
					var customer_postal		= customer.postal_code;
					var customer_pic		= customer.pic_name;
					var complete_address	= customer_address;
					if(customer_number != null && customer_number != ''){
						complete_address	+= ' no. ' + customer_number;
					};
					
					if(customer_block != null && customer_block != ''){
						complete_address	+= ', blok ' + customer_block;
					};
					
					if(customer_rt != '000'){
						complete_address	+= ', RT ' + customer_rt + ', RW ' + customer_rw;
					}
					
					if(customer_postal != ''){
						complete_address += ', ' + customer_postal;
					}
					
					complete_address += ', ' + customer_city;
					
					$('#customerTableContent').append("<tr><td id='customer_name-" + customer_id + "'>" + customer_name + "</td><td id='customer_address-" + customer_id + "'>" + complete_address + "</td><td><button type='button' class='button button_success_dark' onclick='selectCustomer(" + customer_id + ")'><i class='fa fa-check'></i></button></td>");
					customerCount++;
					
					var page		= $('#customer_page').val();
					$('#customer_page').html('');
					for(i = 1; i <= pages; i++){
						if(i == page){
							$('#customer_page').append("<option value='" + i + "' selected>" + i + "</option>");
						} else {
							$('#customer_page').append("<option value='" + i + "'>" + i + "</option>");
						}
					}

					if(customerCount > 0){
						$('#customerTable').show();
						$('#customerTableText').hide();
					} else {
						$('#customerTable').hide();
						$('#customerTableText').show();
					}
				});
			}
		});
	}
	
	$('#search_customer').change(function(){
		refresh_customer_view();
	});
	
	$('#select_customer_button').click(function(){
		$('#select_customer_wrapper').slideToggle(300);
		$('#search_customer').val("");
		refresh_customer_view(1);
	});
	
	function selectCustomer(n){
		$.ajax({
			url:'<?= site_url('Customer/getCustomerAccount') ?>',
			data:{
				id:n
			},
			success:function(response){
				var customer		= response.customer;
				var plafond			= parseFloat(customer.plafond);
				var pending_value	= response.pending_value;
				var termOfPayment	= parseInt(customer.term_of_payment);
				
				var invoice			= response.pending_invoice;	

				var invoiceValue	= parseFloat(invoice.debt);
				var minimumDate		= new Date(invoice.date);
				var todayDate		= new Date();

				var difference		= Math.floor(Math.abs((todayDate - minimumDate) / (60 * 60 * 24 * 1000)));
				var pendingBank 	= response.pendingBank;
				
				var customer_name		= $('#customer_name-' + n).html();
				var customer_address	= $('#customer_address-' + n).html();
				$('#select_customer_button').html(customer_name);

				if(invoiceValue - pendingBank > plafond || (invoice.date != null && difference > termOfPayment)){
					$('#warningDebtText').show();
				} else {
					$('#warningDebtText').hide();
				}
				
				$('#customer_address_select').html('<p>' + customer_address + '</p><label>Plafond</label><p>Rp. ' + numeral(plafond).format('0,0.00') + '</p><label>Pending sales order value</label><p>Rp. ' + numeral(pending_value).format('0,0.00') + '</p><label>Receivable</label><p>Rp. ' + numeral(invoiceValue).format('0,0.00') + '</p>');
				$('#select_customer_wrapper').fadeOut();
				$('#customer_id').val(n);
			}
		});
	};
	
	function removeItem(n){
		$('#item_row-' + n).remove();
		
		if($('#cart_products tr').length == 0){
			$('#cart_products_table').hide();
			$('#submit_button').hide();
		}
	}
	
	function removeBonusItem(n){
		$('#bonus_item_row-' + n).remove();
		
		if($('#bonus_cart_products tr').length == 0){
			$('#bonus_cart_products_table').hide();
		}
	}
	
	function addItem(n){
		$.ajax({
			url:'<?= site_url('Item/add_item_to_cart') ?>',
			data:{
				price_list_id:n
			},
			type:'POST',
			beforeSend:function(){
				$('button').attr('disabled',true);
			},
			success:function(response){
				var item_id		= response.id;
				var reference	= response.reference;
				var name		= response.name;
				var price_list	= response.price_list;
				
				if($('#item_row-' + item_id).length == 0){
					$('#cart_products').append("<tr id='item_row-" + n + "'><td id='reference-" + n + "'>" + reference + "</td>" + 
						"<td id='name-" + n + "'>" + name + "</td>" + 
						"<td>Rp. " + numeral(price_list).format('0,0.00') + "</td><input type='hidden' id='price_list-" + n + "' value='" + price_list+ "'>" +
						"<td><input type='number' class='form-control' min='0' max='100' required name='discount[" + n + "]' id='discount-" + n + "'></td>" +
						"<td><input type='number' class='form-control' min='0' max='100' required name='quantity[" + n + "]' id='quantity-" + n + "'></td>" + 
						"<td><button type='button' class='button button_danger_dark' onclick='removeItem(" + n + ")'><i class='fa fa-trash'></i></button></td></tr>");
				}
				
				$('button').attr('disabled',false);
				$('.alert_full_close_button').click();
				
				if($('#cart_products tr').length > 0){
					$('#cart_products_table').show();
					$('#submit_button').show();
				}
			}
		})
	}
	
	function addBonusItem(n){
		if($('#cart_products tr').length > 0){
			$.ajax({
				url:'<?= site_url('Item/add_item_to_cart') ?>',
				data:{
					price_list_id:n
				},
				type:'POST',
				beforeSend:function(){
					$('button').attr('disabled',true);
				},
				success:function(response){
					var item_id		= response.id;
					var reference	= response.reference;
					var name		= response.name;
					
					if($('#bonus_item_row-' + item_id).length == 0){
						$('#bonus_cart_products').append("<tr id='bonus_item_row-" + n + "'><td id='bonus_reference-" + n + "'>" + reference + "</td><td id='bonus_name-" + n + "'>" + name + "</td>" + 
							"<td><input type='number' class='form-control' min='1' required name='bonus_quantity[" + n + "]' id='bonus_quantity-" + n + "'></td>" +
							"<td><button type='button' class='button button_danger_dark' onclick='removeBonusItem(" + n + ")'><i class='fa fa-trash'></i></button></td>");
					}
					$('button').attr('disabled',false);
					$('.alert_full_close_button').click();
					
					if($('#bonus_cart_products tr').length > 0){
						$('#bonus_cart_products_table').show();
					}
				}
			})
		}
	}
	
	$('#search_bar').change(function(){
		refresh_view();
	});
	
	$('#page').change(function(){
		refresh_view();
	});
</script>
