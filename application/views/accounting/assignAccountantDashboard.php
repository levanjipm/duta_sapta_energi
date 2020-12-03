<head>
	<title>Assign accountant</title>
	<style>
		.subtitleText{
			color:#333;
			font-size:0.9em;
		}
	</style>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> / Assign accountant</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>User</label>
		<button type='button' class='form-control' id='accountantButton' style='text-align:left'></button>
		<br>
		<button type='button' id='saveAccountantButton' class='button button_default_dark' disabled><i class='fa fa-long-arrow-right'></i></button>
		<hr> 
		<div id='customerTableWrapper' style='display:none'>
			<input type='text' class='form-control' id='search_bar'>
			<br>
			<div id='customerTable'>
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
		</div>
	</div>
</div>

<div class='alert_wrapper' id='selectAccountantWrapper'>
	<div class='alert_box_full'>
		<button class='button alert_full_close_button'>&times;</button>
		<h3 style='font-family:bebasneue'>Select accountant</h3>
		<hr>
		<div id='accountantTable'>
			<table class='table table-bordered'>
				<tr>
					<th>User</th>
					<th>Action</th>
				</tr>
				<tbody id='accountantTableContent'></tbody>
			</table>

			<select class='form-control' id='accountantPage' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='accountantTableText'>There is no accountant available.</p>
	</div>
</div>

<script>
	var includedCustomerArray = [];
	var excludedCustomerArray = [];
	var accountantId;

	$('#saveAccountantButton').click(function(){
		$.ajax({
			url:"<?= site_url('Customer/assignAccountant') ?>",
			data:{
				included: includedCustomerArray,
				excluded: excludedCustomerArray,
				accountant: accountantId
			},
			type:"POST",
			beforeSend:function(){
				$('input').attr('readonly', true);
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('input').attr('readonly', false);
				$('button').attr('disabled', false);
			}
		})
	})

	$('#page').change(function(){
		refreshView();
	});

	$('#search_bar').change(function(){
		refreshView(1);
	})

	$('#accountantButton').click(function(){
		refreshAccountant(1);
	});

	function getAccountantitems(){
		$.ajax({
			url:"<?= site_url("Customer/showCustomerAccountantItems") ?>",
			data:{
				user: accountantId
			},
			success:function(response){
				var items = response.customers;
				$.each(items, function(index, item){
					var id = item.id;
					var status = item.accountantStatus;
					if(status == "0"){
						excludedCustomerArray.push("" + id + "");
					} else {
						includedCustomerArray.push("" + id + "");
					}
				})
			}
		});
	}

	function refreshView(page = $("#page").val()){
		$.ajax({
			url:"<?= site_url("Customer/showCustomerAccountantItems") ?>",
			data:{
				page: page,
				term: $('#search_bar').val(),
				user: accountantId
			},
			success:function(response){
				$('#customerTableContent').html("");
				var items = response.customers;
				$.each(items, function(index, item){
					var name					= item.name;
					var id						= item.id;
					var complete_address		= '';
					var customer_name			= item.name;
					complete_address			+= item.address;
					var customer_city			= item.city;
					var customer_number			= item.number;
					var customer_rt				= item.rt;
					var customer_rw				= item.rw;
					var customer_postal			= item.postal_code;
					var customer_block			= item.block;
					var customer_id				= item.id;
		
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
					};

					if(includedCustomerArray.includes(id)){
						$('#customerTableContent').append("<tr><td>" + name + "</td><td><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><input type='checkbox' id='checkbox-" + id + "' checked></td></tr>");
					} else {
						$('#customerTableContent').append("<tr><td>" + name + "</td><td><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><input type='checkbox' id='checkbox-" + id + "'></td></tr>");
					}


					$('#checkbox-' + id).change(function(){
						if($(this).is(':checked')){
							var index = excludedCustomerArray.indexOf("" + id + "");
							if (index > -1) {
								excludedCustomerArray.splice(index, 1);
							}

							if(!includedCustomerArray.includes("" + id + "")){
								includedCustomerArray.push("" + id + "");
							}				
						} else {
							var index = includedCustomerArray.indexOf("" + id + "");
							if (index > -1) {
								includedCustomerArray.splice(index, 1);
							}

							if(!excludedCustomerArray.includes("" + id + "")){
								excludedCustomerArray.push("" + id + "");
							}		
						}
					})
					
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
			}
		})
	}

	function refreshAccountant(page = $('#accountantPage').val()){
		$.ajax({
			url:"<?= site_url('Users/getAccountantItems') ?>",
			data:{
				page: page,
			},
			success:function(response){
				$('#accountantTableContent').html("");
				var accountantCount = 0;
				var items = response.users;
				$.each(items, function(index, item){
					var name = item.name;
					var id		= item.id;
					if(item.image_url == null){
						var imageUrl = "<?= base_url() . '/assets/ProfileImages/defaultImage.png' ?>";
					} else {
						var imageUrl = "<?= base_url() . '/assets/ProfileImages/' ?>" + item.image_url;
					}

					$('#accountantTableContent').append("<tr><td><img src='" + imageUrl + "' style='width:30px;height:30px;border-radius:50%'> " + name + "</td><td><button class='button button_default_dark' id='selectAccountantButton-" + id + "'><i class='fa fa-long-arrow-right'></i></button></td></tr>");

					$('#selectAccountantButton-' + id).click(function(){
						$('#saveAccountantButton').attr('disabled', false);
						$('#selectAllCustomerButton').attr('disabled', false);
						$('#warningText').show();
						$('#accountantButton').html(name);
						accountantId = id;
						getAccountantitems();
						$('#search_bar').val("");
						refreshView(1);

						$('#customerTableWrapper').fadeIn(250);
						$('#selectAccountantWrapper .alert_full_close_button').click();
					})
					accountantCount++;
				});

				if(accountantCount > 0){
					$('#accountantTable').show();
					$('#accountantTableText').hide();
				} else {
					$('#accountantTable').hide();
					$('#accountantTableText').show();
				}

				var pages = response.pages;
				$('#accountantPage').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#accountantPage').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#accountantPage').append("<option value='" + i + "'>" + i + "</option>");
					}
				}

				$('#selectAccountantWrapper').fadeIn();
			}
		})
	}

	$('.alert_full_close_button').click(function(){
		$(this).parent().parent().fadeOut();
	});

	$('#selectAllCustomerButton').click(function(){
		$('#selectAllCustomerWrapper').fadeIn();
	});
</script>
