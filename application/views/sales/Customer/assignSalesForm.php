<head>
	<title>Customer - Sales</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> / Assign Customers to Sales</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Sales</label>
		<p><?= $sales->name ?></p>

		<button class='button button_mini_tab' onclick="$('#areaWrapper').toggle(300)"><i class='fa fa-filter'></i></button>

		<div class='row' id='areaWrapper' style='display:none'>
		</div>
		<br>
		<button class='button button_default_dark' onclick='submitChanges()'><i class='fa fa-long-arrow-right'></i></button>
		<br><br>
		<input type='text' class='form-control' id='searchBar'>
		<br>
		<div id='customerTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Name</th>
					<th>Information</th>
					<th>Action</th>
				</tr>
				<tbody id='customerTableContent'></tbody>
			</table>
			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='customerTableText'>There is no customer found.</p>
	</div>
</div>

<script>
	var includedAreas = [];
	var includedCustomers = [];
	var removedCustomers = [];

	$(document).ready(function(){
		getAreas();
	});

	$('#page').change(function(){
		refreshCustomerView();
	});

	$('#searchBar').change(function(){
		refreshCustomerView(1);
	})

	function getAreas(){
		$.ajax({
			url:"<?= site_url('Area/getAllItems') ?>",
			success:function(response){
				$.each(response, function(index, value){
					var id		= value.id;
					var name	= value.name;
					$('#areaWrapper').append("<div class='col-sm-4'><label><input type='checkbox' checked onchange='updateAreas(" + id + ")' id='checkBox-" + id + "'> " + name + "</label></div>")
					includedAreas.push(parseInt(value.id));
				})
			},
			complete:function(){
				refreshCustomerView(1);
			}
		})
	}

	function updateAreas(n){
		if($('#checkBox-' + n).is(":checked")){
			if(includedAreas.includes(n) == false){
				includedAreas.push(n);
			}
		} else {
			var index = includedAreas.indexOf(n);
			includedAreas.splice(index, 1);
		}

		refreshCustomerView(1);
	}

	function refreshCustomerView(page = $('#page').val()){
		var formData		= new FormData();
		formData.append("sales", <?= $sales->id ?>);
		formData.append("page", page);
		formData.append("term", $('#searchBar').val());
		$.each(includedAreas, function(index, area){
			formData.append("includedAreas[]", area);
		});

		$.ajax({
			url:"<?= site_url('CustomerSales/getBySales') ?>",
			data:formData,
			processData: false,
			contentType: false,
			type:"POST",
			success:function(response){
				var items		= response.items;
				var itemCount	= 0;
				$('#customerTableContent').html("");
				$.each(items, function(index, customer){
					var complete_address		= '';
					var customer_name			= customer.name;
					complete_address		+= customer.address;
					var customer_city			= customer.city;
					var customer_number			= customer.number;
					var customer_rt				= customer.rt;
					var customer_rw				= customer.rw;
					var customer_postal			= customer.postal_code;
					var customer_block			= customer.block;
					var customer_id				= customer.id;
		
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

					if(customer.status == 1 || includedCustomers.includes(parseInt(customer_id))){
						if(!includedCustomers.includes(parseInt(customer_id))){
							includedCustomers.push(parseInt(customer_id));
						}

						$('#customerTableContent').append("<tr><td>" + customer_name + "</td><td><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><input type='checkbox' value='1' name='customer[" + customer_id + "]' checked onchange='updateIncludedCustomer(" + customer_id + ")'></td></tr>");
					} else {
						if(!removedCustomers.includes(parseInt(customer_id))){
							removedCustomers.push(parseInt(customer_id));
						}

						$('#customerTableContent').append("<tr><td>" + customer_name + "</td><td><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><input type='checkbox' value='1' name='customer[" + customer_id + "]'onchange='updateIncludedCustomer(" + customer_id + ")'></td></tr>");
					}

					itemCount++;
				});

				$('#page').html("");
				var pages		= response.pages;
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}					
				}

				if(itemCount > 0){
					$('#customerTable').show();
					$('#customerTableText').hide();
				} else {
					$('#customerTable').hide();
					$('#customerTableText').show();
				}
			}
		});
	}

	function updateIncludedCustomer(n){
		if($('input[name="customer[' + n + ']"]').is(":checked")){
			if(!includedCustomers.includes(n)){
				includedCustomers.push(n);
			}

			if(removedCustomers.includes(n)){
				var index		= removedCustomers.indexOf(n);
				removedCustomers.splice(index, 1);
			}
		} else {
			var index		= includedCustomers.indexOf(n);
			includedCustomers.splice(index, 1);

			if(!removedCustomers.includes(n)){
				removedCustomers.push(n);
			}
		}
	}

	function submitChanges(){
		$.ajax({
			url:"<?= site_url('CustomerSales/updateBySales') ?>",
			data:{
				salesId: <?= $sales->id ?>,
				includedCustomers: includedCustomers,
				removedCustomers: removedCustomers
			},
			type:"POST",
			beforeSend:function(){
				$('button').attr('disabled', true);
				$('input').attr('readonly', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				$('input').attr('readonly', false);
			}
		})
	}
</script>