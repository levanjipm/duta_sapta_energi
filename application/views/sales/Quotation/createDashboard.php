<head>
	<title>Create Quotation</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-line-chart'></i></a> /Quotation /Create</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<form action='<?= site_url('Quotation/insertItem') ?>' method='POST' id='quotationForm'>
			<label>Date</label>
			<input type='date' class='form-control' name='date' id='quotationDate' value='<?= date('Y-m-d') ?>'>
			
			<label>Customer</label>
			<button class='form-control' type='button' id='selectCustomerButton' style='text-align:left'></button>
			<input type='hidden' name='customerId' id='customerId' required>

			<label>Taxing</label>
			<select class='form-control' id='taxing' name='taxing'>
				<option value='0'>Non-taxable</option>
				<option value='1'>Taxable</option>
			</select>

			<label>Note</label>
			<textarea class='form-control' id='quotationNote' name='note' style='resize:none' rows='4' placeholder="Specify the validity date and term of payment"></textarea>
			<br>
			<button type='button' class='button button_default_dark' id='add_item_button'><i class='fa fa-shopping-cart'></i> Add item</button>
			<br><br>
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
			<button type='button' class='button button_default_dark' onclick='validate_form()' style='display:none' id='submit_button'>Submit</button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='selectCustomerWrapper'>
	<div class='alert_box_full'>		
		<button type='button' class='button alert_full_close_button' title='Close select customer session'>&times;</button>
		<h3 style='font-family:bebasneue'>Select customer</h3>
		<br>
		<input type='text' class='form-control' id='search_customer'>
		<br>
		<table class='table table-bordered'>
			<tr>
				<th>Name</th>
				<th>Address</th>
				<th>Action</th>
			</tr>
			<tbody id='customer_table'></tbody>
		</table>
		
		<select class='form-control' id='customer_page' style='width:100px'>
			<option value='1'>1</option>
		</select>
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
				<div id='itemTable'>
					<table class='table table-bordered'>
						<tr>
							<th>Reference</th>
							<th>Name</th>
							<th>Action</th>
						</tr>
						<tbody id='itemTableContent'>
						</tbody>
					</table>
				
					<select class='form-control' id='page' style='width:100px'>
						<option value='1'></option>
					</select>
				</div>
				<p id='itemTableText'>There is no item found.</p>
			</div>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='validateQutationForm'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<label>Date</label>
		<p id='date'></p>
		
		<label>Taxing</label>
		<p id='taxing_p'></p>
		
		<label>Customer</label>
		<p id='customerName_p'></p>
		<p id='customerAddress_p'></p>

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

		<label>Note</label>
		<p id='quotationNote_p'></p>
		
		<button class='button button_default_dark' onclick='submit_form()'>Submit</button>
	</div>
</div>
<script>
	$('.slide_alert_close_button').click(function(){
		$('input').attr('readonly',false);
		$('select').attr('readonly',false);
		$('textarea').attr('readonly', false);
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
				$('#itemTableContent').html('');
				var item_array	= response.items;
				var pages		= response.pages;
				var page		= response.page;
				
				if(item_array.length > 0){
					$.each(item_array, function(index, item){
						var reference		= item.reference;
						var id				= item.id;
						var name			= item.name;
						
						$('#itemTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td><button type='button' class='button button_success_dark' onclick='addItem(" + id + ")' title='Add " + reference + " to cart'><i class='fa fa-cart-plus'></i></button> </td></tr>");
					});

					$('#itemTable').show();
					$('#itemTableText').hide();
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
			}
		});
	}
	
	$("#quotationForm").validate({
		ignore: '',
		rules: {"hidden_field": {min:1}}
	});
	
	function validate_form(n){
		if(!$("#quotationForm").valid()){
			return false;
		} else {
			$('input').attr('readonly',true);
			$('textarea').attr('readonly', true);
			$('select').attr('readonly',true);
			$('#table_item_confirm').html('');
			
			var taxing 		= $("#taxing option:selected").html();
			var date 		= $("#quotationDate").val();
			if($('#quotationNote').val() == ""){
				$('#quotationNote_p').html("<i>Not available</i>");
			} else {
				$('#quotationNote_p').html($('#quotationNote').html());
			}

			$('#date').html(my_date_format(date));
			$('#taxing_p').html(taxing);
			
			var quotationValue = 0;
			
			$('td[id^="reference-"]').each(function(){
				var id 			= $(this).attr('id');
				var uid 		= parseInt(id.substring(10,265));
				var quantity 	= parseInt($('#quantity-' + uid).val());
				var discount 	= $('#discount-' + uid).val();
				var price_list 	= $('#price_list-' + uid).val();
				var name	 	= $('#name-' + uid).html();
				var reference	= $('#reference-' + uid).html();
				var unit_price	= price_list * ( 100 - discount) / 100;
				var total_price	= unit_price * quantity;
				quotationValue += total_price;
				
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

			$('#table_item_confirm').append(
				"<tr>"+
					"<td colspan='4'></td>"+
					"<td colspan='2'>Total</td>"+
					"<td>Rp. " + numeral(quotationValue).format('0,0.00') + "</td>"+
				"</tr>"
			);

			$('#validateQutationForm').fadeIn(300, function(){
				$('#validateQutationForm .alert_box_slide').show("slide", { direction: "right" }, 250);
			});
		}
	};
	
	$('.alert_full_close_button').click(function(){
		$(this).parents().find('.alert_wrapper').fadeOut();
	});
	
	function submit_form(){
		$("#quotationForm").validate();
		
		if($("#quotationForm").valid()){
			$('#quotationForm').submit();
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
				$('#customer_table').html('');
				var customer_array	= response.customers;
				var pages			= response.pages;
				$.each(customer_array, function(index, customer){
					var id					= customer.id;
					var customer_name		= customer.name;
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
					
					$('#customer_table').append("<tr><td>" + customer_name + "</td><td>" + complete_address + "</td><td><button type='button' class='button button_success_dark' id='selectCustomerButton-" + id + "'><i class='fa fa-check'></i></button></td>");
					$('#selectCustomerButton-' + id).click(function(){
						$('#selectCustomerButton').html(customer_name);

						$('#customerName_p').html(customer_name);
						$('#customerAddress_p').html(complete_address);

						$('#customerId').val(id);
						$('#selectCustomerWrapper .alert_full_close_button').click();
					});
					
					var page		= $('#customer_page').val();
					$('#customer_page').html('');
					for(i = 1; i <= pages; i++){
						if(i == page){
							$('#customer_page').append("<option value='" + i + "' selected>" + i + "</option>");
						} else {
							$('#customer_page').append("<option value='" + i + "'>" + i + "</option>");
						}
					}
				});
			}
		});
	}
	
	$('#search_customer').change(function(){
		refresh_customer_view();
	});
	
	$('#selectCustomerButton').click(function(){
		$('#search_customer').val("");
		refresh_customer_view(1);
		$('#selectCustomerWrapper').slideToggle(300);
	});
	
	function removeItem(n){
		$('#item_row-' + n).remove();
		
		if($('#cart_products tr').length == 0){
			$('#cart_products_table').hide();
			$('#submit_button').hide();
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
						"<td><input type='number' class='form-control' min='0' required name='quantity[" + n + "]' id='quantity-" + n + "'></td>" + 
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
	
	$('#search_bar').change(function(){
		refresh_view();
	});
	
	$('#page').change(function(){
		refresh_view();
	});
</script>
