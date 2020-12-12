<head>
	<title>Customer - Sales</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-line-chart'></i></a> / Assign Customers to Sales</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Sales</label>
		<p><?= $sales->name ?></p>
		<p><?= $sales->email ?></p>
		<br>
		<label>Assigned Customers</label>
		<p id='assignedCustomers'>0</p>
		<br>
		<div id='customerTable'>
		<?php if(count($assignment) > 0){ ?>
			<table class='table table-bordered'>
				<tr>
					<th>Name</th>
					<th>Information</th>
				</tr>
			<?php 
				$count		= 0;
				foreach($assignment as $item){ 
					$complete_address		= $item->address;
					$customer_city			= $item->city;
					$customer_number		= $item->number;
					$customer_rt			= $item->rt;
					$customer_rw			= $item->rw;
					$customer_postal		= $item->postal_code;
					$customer_block			= $item->block;
					
					if($customer_number != null){
						$complete_address	.= ' No. ' . $customer_number;
					}
								
					if($customer_block != null && $customer_block != "000"){
						$complete_address	.= ' Blok ' . $customer_block;
					}
							
					if($customer_rt != '000'){
						$complete_address	.= ' RT ' . $customer_rt;
					}
								
					if($customer_rw != '000' && $customer_rt != '000'){
						$complete_address	.= ' /RW ' . $customer_rw;
					}
								
					if($customer_postal != null){
						$complete_address	.= ', ' . $customer_postal;
					}
					$count++;
			?>
				<tr>
					<td><?= $item->name ?></td>
					<td><?= $complete_address ?></td>
				</tr>
			<?php } ?>
			</table>
			<script>
				$('#assignedCustomers').html(<?= $count ?>)
			</script>
		<?php } else { ?>
			<p>There is no customer assigned</p>
		<?php } ?>
		</div>
	</div>
</div>
