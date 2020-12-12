<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-usd'></i></a> / Recommendation list</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<input type='text' class='form-control' id='search_bar'><br>
		<table class='table table-bordered'>
			<tr>
				<th>Customer</th>
				<th>Information</th>
				<th>Action</th>
			</tr>
			<tbody id='recommended_table'></tbody>
		</table>
	</div>
</div>
<script>
	view_recommendation_list();
	
	function view_recommendation_list(){
		$.ajax({
			url:'<?= site_url('Finance/view_recommendation_list') ?>',
			success:function(response){
				$.each(response, function(index, value){
					var date_difference		= parseFloat(value.date_difference);
					var term_of_payment		= parseFloat(value.term_of_payment);
					var difference			= date_difference - term_of_payment;
					if(date_difference > term_of_payment){
						var complete_address		= '';
						var customer_name			= value.name;
						complete_address			+= value.address;
						var customer_city			= value.city;
						var customer_number			= value.number;
						var customer_rt				= value.rt;
						var customer_rw				= value.rw;
						var customer_postal			= value.postal_code;
						var customer_block			= value.block;
						var invoice_id				= value.invoice_id;
						var customer_id				= value.customer_id;
			
						if(customer_number != null){
							complete_address	+= ' No. ' + customer_number;
						}
						
						if(customer_block != null){
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
						
						$('#recommended_table').append("<tr><td><label>" + customer_name + "</label><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><p>" + numeral(date_difference).format('0,0') + " days since delivery</p><p> (" + numeral(difference).format('0,0') + " days since due date)</p></td><td><button type='button' class='button button_default_dark' onclick='view(" + customer_id + "," + invoice_id + ")'><i class='fa fa-eye'></i></button></tr>");
					}
				});
			}
		});
	}
	
	function view(customer_id, invoice_id){
		$.ajax({
			url:'<?= site_url('Finance/view_recommendation_by_customer_id') ?>',
			data:{
				customer_id: customer_id,
				invoice_id: invoice_id
			},
			success:function(response){
				var status_array		= response.status;
				var status				= status_array.type;
				if(status == 0){
					var new_status = 1;
				} else {
					var new_status	= status + 1;
				}
				
				if(new_status == 3){
				}
			}
		});
	}
</script>