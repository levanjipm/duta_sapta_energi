<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /Return</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Delivery order</label>
		<input type='text' class='form-control' id='delivery_order_name' placeholder="Delivery order number">
		<div style='padding:2px 10px;background-color:#ffc107;width:100%;display:none;' id='error_delivery_order'><p style='font-family:museo'><i class='fa fa-exclamation-triangle'></i> Delivery order not found</p></div><br>
		<button class='button button_default_dark' onclick="check_delivery_order()"><i class='fa fa-long-arrow-right'></i></button>
	</div>
</div>
<form action='<?= site_url('Sales_return/authenticate') ?>' method='POST' id='return_form'>
	<input type='hidden' id='delivery_order_id' name='id'>
</form>
<script>
	function check_delivery_order(){
		$.ajax({
			url:'<?= site_url('Delivery_order/getByName') ?>',
			data:{
				name: $('#delivery_order_name').val()
			},
			type:'POST',
			success:function(response){
				if(response == null){
					$('#error_delivery_order').fadeIn(250);
					setTimeout(function(){
						$('#error_delivery_order').fadeOut(250);
					}, 1000);
				} else {
					$('#delivery_order_id').val(response.id);
					$('#return_form').submit();
				}
			}
		});
	}
</script>