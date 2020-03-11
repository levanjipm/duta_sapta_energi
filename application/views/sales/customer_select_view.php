	<table class='table table-bordered'>
		<tr>
			<th>Customer name</th>
			<th>Address</th>
			<th>City</th>
			<th>Action</th>
		</tr>
<?php
	foreach($customers as $customer){
		$complete_address		= '';
		$customer_name			= $customer->name;
		$complete_address		.= $customer->address;
		$customer_city			= $customer->city;
		$customer_number		= $customer->number;
		$customer_rt			= $customer->rt;
		$customer_rw			= $customer->rw;
		$customer_postal		= $customer->postal_code;
		$customer_block			= $customer->block;
		$customer_id			= $customer->id;
		
		if($customer_number != NULL){
			$complete_address	.= ' No. ' . $customer_number;
		}
		
		if($customer_block != NULL){
			$complete_address	.= ' Blok ' . $customer_block;
		}
		
		if($customer_rt != '000'){
			$complete_address	.= ' RT ' . $customer_rt;
		}
		
		if($customer_rw != '000' && $customer_rt != '000'){
			$complete_address	.= ' /RW ' . $customer_rw;
		}
		
		if($customer_postal != NULL){
			$complete_address	.= ', ' . $customer_postal;
		}
			
?>
		<tr>
			<td><?= $customer_name ?></td>
			<td><?= $complete_address ?></td>
			<td><?= $customer_city ?></td>
			<td>
				<button type='button' class='button button_success_dark' onclick='select_customer(<?= $customer->id ?>, "<?= $customer->name ?>")'><i class='fa fa-check'></i></button>
			</td>
		</tr>
<?php
	}
?>
	</table>
	<select class='form-control' id='customer_page' onchange='update_view()' style='width:100px'>
<?php
	for($i = 1; $i <= $pages; $i++){
?>
		<option value='<?= $i ?>' <?php if($paging == $i){ echo 'selected';} ?>><?= $i ?></option>
<?php
	}
?>
	</select>