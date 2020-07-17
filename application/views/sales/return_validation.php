<?php
	$complete_address = $general->address;
	if($general->number != null){
		$complete_address .= " No. " . $general->number;
	}
	
	if($general->block != NULL){
		$complete_address	.= ' Blok ' . $general->block;
	}
	
	if($general->rt != '000'){
		$complete_address	.= ' RT ' . $customer_rt;
	}
	
	if($general->rw != '000' && $general->rt != '000'){
		$complete_address	.= ' /RW ' . $general->rw;
	}
	
	if($general->postal_code != NULL){
		$complete_address	.= ', ' . $general->postal_code;
	}
	
?>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /Sales return/ Create </p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='row'>
			<div class='col-sm-12 col-xs-12'>
				<label>Customer</label>
				<p><?= $general->customer_name ?></p>
				<p><?= $complete_address ?></p>
				<p><?= $general->city ?></p>
			</div>
			<div class='col-sm-6 col-xs-12'>
				<label>Delivery order</label>
				<p><?= date('d M Y',strtotime($general->date)) ?></p>
				<p><?= $general->name ?></p>
			</div>
			<div class='col-sm-6 col-xs-12'>
				<label>Sales order</label>
				<p><?= date('d M Y',strtotime($general->sales_order_date)) ?></p>
				<p><?= $general->sales_order_name ?></p>
			</div>
			<div class='col-sm-12 col-xs-12'>
				<form action='<?= site_url('Sales_return/input') ?>' method='POST' id='return_form'>
					<table class='table table-bordered'>
						<tr>
							<th>Reference</th>
							<th>Name</th>
							<th>Quantity</th>
							<th>Unit price</th>
							<th>Action</th>
						</tr>
						<?php
							foreach($items as $item){
								$price_list = $item->price_list;
								$discount = $item->discount;
								$net_price = $price_list * (100 - $discount) / 100;
						?>
							<tr>
								<td><?= $item->reference ?></td>
								<td><?= $item->name ?></td>
								<td><?= number_format($item->quantity, 0) ?></td>
								<td>Rp. <?= number_format($net_price,2) ?></td>
								<td><input type='number' class='form-control' name='return_quantity[<?= $item->id ?>]' max='<?= $item->quantity ?>' value='0' min='0' onchange='calculate_total()'></td>
							</tr>
						<?php } ?>
					</table>
					<input type='hidden' id='total_quantity' min='1' required value='0'><br>
					<button class='button button_default_dark'>Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#return_form').validate({
			ignore: ""
		});
	});
	
	function calculate_total(){
		var total_quantity = 0;
		$.each($('input[name^="return_quantity"]'), function(){
			total_quantity += parseInt($(this).val());
		});
		
		$('#total_quantity').val(total_quantity);
	}
	
	$('#validate_form_button').click(function(){
	});
</script>