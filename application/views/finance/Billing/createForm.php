<head>
	<title>Billing</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-briefcase'></i></a> / Create Billing</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Date</label>
		<p><?= date('d M Y', strtotime($date)); ?></p>

		<button class='button button_mini_tab' id='recommendedButton'>Recommended</button>
		<button class='button button_mini_tab' id='searchButton'>Search</button>
		<hr>
		<div id='recommendedView' style='display:none'>
			<input type='text' id='recommendedSearchBar' class='form-control'>
			<br>
			<div id='recommendedTable'>
				<table class='table table-bordered'>
					<tr>
						<th>Date</th>
						<th>Name</th>
						<th>Customer</th>
						<th>Action</th>
					</tr>
					<tbody id='recommendedTableContent'></tbody>
				</table>

				<select class='form-control' id='recommendedPage' style='width:100px'>
					<option value='1'>1</option>
				</select>
			</div>
			<p id='recommendedTableText'>There is no recommendation list</p>
		</div>
		<div id='searchView' style='display:none'>
			<input type='text' class='form-control' id='searchSearchBar'>
			<br>
			<div id='searchTable'>
				<table class='table table-bordered'>
					<tr>
						<th>Customer</th>
						<th>Action</th>
					</tr>
					<tbody id='searchTableContent'></tbody>
				</table>

				<select class='form-control' id='searchPage' style='width:100px'>
					<option value='1'>1</option>
				</select>
			</div>

			<p id='searchTableText'>There is no invoice found.</p>
		</div>
	</div>
</div>

<script>
	var includedInvoice = [];
	$(document).ready(function(){
		$('#recommendedButton').click();
	});

	$('#recommendedButton').click(function(){
		$('.button_mini_tab').attr('disabled', false);
		$('.button_mini_tab').removeClass("active");

		fetchRecommendationList();
		$('#recommendedButton').addClass('active');
		$('#recommendedButton').attr('disabled', true);
		$('#searchView').fadeOut(250, function(){
			$('#recommendedView').fadeIn(250);
		})
	})

	$('#searchButton').click(function(){
		$('.button_mini_tab').attr('disabled', false);
		$('.button_mini_tab').removeClass("active");

		fetchItemList();
		$('#searchButton').addClass('active');
		$('#searchButton').attr('disabled', true);
		$('#recommendedView').fadeOut(250, function(){
			$('#searchView').fadeIn(250);
		})
	});

	$('#recommendedPage').change(function(){
		fetchRecommendationList();
	})

	$('#searchSearchBar').change(function(){
		fetchItemList(1);
	});

	$('#searchPage').change(function(){
		fetchItemList();
	})

	function fetchRecommendationList(page = $('#recommendedPage').val()){
		$.ajax({
			url:"<?= site_url('Billing/getRecommendationList') ?>",
			data:{
				page:page,
				term: $('#recommendedSearchBar').val(),
				date: "<?= $date ?>"
			},
			success:function(response){
				var items = response.items;
				$('#recommendedTableContent').html("");
				var invoiceCount = 0;
				$.each(items, function(index, item){
					var date		= item.date;
					var invoiceName = item.name;
					var id			= item.id;

					var customerName = item.customerName;
					var customerAddress = item.address;
					var complete_address = customer.address;
					var customer_number = customer.number;
					var customer_block = customer.block;
					var customer_rt = customer.rt;
					var customer_rw = customer.rw;
					var customer_city = customer.city;
					var customer_postal = customer.postal_code;
				
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
					}

					if(includedInvoice[id] === undefined){
						$('#recommendedTableContent').append("<tr id='row-" + id + "'><td>" + my_date_format(date) +"</td><td>" + invoiceName + "</td><td><label>" + customerName + "</label><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
						invoiceCount++;
					}
				});

				if(invoiceCount > 0){
					$('#recommendedTable').show();
					$('#recommendedTableText').hide();
				} else {
					$('#recommendedTable').hide();
					$("#recommendedTableText").show();
				}

				var pages = response.pages;
				$('#recommendedPage').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#recommendedPage').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#recommendedPage').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		})
	}

	function fetchItemList(page = $('#recommendedPage').val()){
		$.ajax({
			url:"<?= site_url('Billing/getBillingData') ?>",
			data:{
				page:page,
				term: $('#searchSearchBar').val(),
			},
			success:function(response){
				var items = response.items;
				$('#searchTableContent').html("");
				var invoiceCount = 0;
				$.each(items, function(index, item){
					var id			= item.id;

					var customerName = item.name;
					var customerAddress = item.address;
					var complete_address = item.address;
					var customer_number = item.number;
					var customer_block = item.block;
					var customer_rt = item.rt;
					var customer_rw = item.rw;
					var customer_city = item.city;
					var customer_postal = item.postal_code;
				
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
					}

					$('#searchTableContent').append("<tr><td><label>" + customerName + "</label><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><button class='button button_default_dark'><i class='fa fa-eye' onclick='viewCustomer(" + id + ")'></i></button></tr>");
					invoiceCount++;
				});

				if(invoiceCount > 0){
					$('#searchTable').show();
					$('#searchTableText').hide();
				} else {
					$('#searchTable').hide();
					$("#searchTableText").show();
				}

				var pages = response.pages;
				$('#searchPage').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#searchPage').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#searchPage').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		})
	}

	function viewCustomer(n){
		$.ajax({
			url:"<?= site_url('Billing/getByCustomerId') ?>",
			data:{
				id:n
			},
			success:function(response){
				console.log(response);
			}
		})
	}
</script>
