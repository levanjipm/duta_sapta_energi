<head>
    <title>Route - Assign Customer</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Inventory') ?>' title='Inventory'><i class='fa fa-th'></i></a> /<a href='<?= site_url('Route') ?>'>Routes</a> /Assign Customer</p>
	</div>
	<br>
	<div class='dashboard_in'>
        <div class="input_group">
            <input class="form-control" id='search' />
        </div>
        <br>

        <p id='customerTableText'>There is no customer found.</p>
        <div id='customerTable'>
            <table class='table table-bordered'>
                <tr>
                    <th>Name</th>
                    <th>Address</th>
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

<script>
    var unassign = [];
    var assign = [];

    $(document).ready(function(){
        refreshView();
    })
    
    $('#page').change(function(){
        refreshView();
    })
    
    $('#search').change(function(){
        refreshView(1);
    })

    function refreshView(page = $('#page').val()){
        $.ajax({
			url:'<?= site_url('Route/getCustomers/') . $routeId ?>',
			data:{
				term:$('#search').val(),
				page:page
			},
			type:'GET',
			beforeSend:function(){
				$('input').attr('disabled', true);
			},
			success:function(response){
                $('input').attr('disabled', false);

				var customerCount = 0;
				var customers	= response.customers;
				var pages		= response.pages;
				$('#page').html('');
				$('#customerTableContent').html('');
				
				$.each(customers, function(index, customer){
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

					var customer_assigned = parseInt(customer.assigned);
					if(customer_assigned == 0){
						$('#customerTableContent').append("<tr><td>" + customer_name + "</td><td><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><input type='checkbox' id='customer-" + customer_id + "'></td></tr>");
					} else {
						$('#customerTableContent').append("<tr><td>" + customer_name + "</td><td><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><input type='checkbox' id='customer-" + customer_id + "' checked></td></tr>");
					}

					$('#customer-' + customer_id).change(function(){
						if($('#customer-' + customer_id).prop('checked')){
							$.ajax({
								url:"<?= site_url('Route/assignCustomer') ?>",
								data:{
									customer_id: customer_id,
									route_id: <?= $routeId ?>
								},
								success:function(response){
									console.log(response);
								}
							})
						} else {
							console.log("unchecked");
						}
					})
									
					
					customerCount++;
				});

				if(customerCount > 0){
					$('#customerTableText').hide();
					$('#customerTable').show();
				} else {
					$('#customerTableText').show();
					$('#customerTable').hide();
				}
				
				for(i = 1; i <= pages; i++){
					if(page == i){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			},
			
		});
    }
</script>