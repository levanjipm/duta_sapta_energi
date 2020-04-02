<style>
	.recommendation_cards_wrapper{
		padding:20px;
		padding-top:0;
		position:relative;
	}
	
	.recommendation_cards_cover{
		background-image: linear-gradient(rgba(59, 64, 75, 0), rgba(59, 64, 75, 1));
		position:absolute;
		bottom:0;
		left:0;
		width:100%;
		z-index:20;
		height:80%;
		text-align:center;
	}
</style>
<div class='dashboard'>
	<div style='padding:20px'><h3 style='font-family:bebasneue;color:white'>Recommended suspend customers</h3><hr style='border-top:2px solid #E19B3C;margin:0'></div>
	<div class='recommendation_cards_wrapper'>
		<p style='font-family:museo;color:white' id='warning_text'>No customer in the recommended list</p>
		
		<table class='table table-bordered' style='color:white;display:none' id='recommended_table_wrapper'>
			<tr>
				<th>Customer</th>
				<th>Time</th>
			</tr>
			<tbody id='recommended_table'></tbody>
		</table>
		<div class='recommendation_cards_cover'>
			<button type='button' class='button button_rounded_dark' onclick='window.location.href="<?= site_url('Finance/recommendation_list') ?>"'>View complete recommended list</button>
		</div>
	</div>
</div>
<script>
	view_recommendation_list();
	
	function view_recommendation_list()
	{
		$.ajax({
			url:'<?= site_url('Finance/view_recommendation_list') ?>',
			success:function(response){
				var count				= 1;
				$.each(response, function(index, value){
					if(count > 3){
						return false;
					} else {
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
							var customer_id				= value.id;
				
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
							
							$('#recommended_table').append("<tr><td><label>" + customer_name + "</label><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><p>" + numeral(date_difference).format('0,0') + " days since delivery</p><p> (" + numeral(difference).format('0,0') + " days since due date)</p></td></div>");
							
							$('#warning_text').hide();
							$('#recommended_table_wrapper').show();
							count++;
						}
					}
				});
			}
		});
	}
</script>