<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Finance') ?>' title='Finance'><i class='fa fa-usd'></i></a> /Bank/ <a href='<?= site_url('Bank/Opponent') ?>'>Opponent</a> / <?= $opponent->name ?></p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Type</label>
		<p><?= $type ?></p>

		<label>Information</label>
<?php
	if($type == "Customer"){
		$complete_address		= '';
		$customer_name			= $opponent->name;
		$complete_address		= $opponent->address;
		$customer_city			= $opponent->city;
		$customer_number		= $opponent->number;
		$customer_rt			= $opponent->rt;
		$customer_rw			= $opponent->rw;
		$customer_postal		= $opponent->postal_code;
		$customer_block			= $opponent->block;
		$customer_id			= $opponent->id;
	
		if($customer_number != NULL){
			$complete_address	.= ' No. ' . $customer_number;
		}
	
		if($customer_block != NULL && $customer_block != "000"){
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
			<p><?= $customer_name ?></p>
			<p><?= $complete_address ?></p>
			<p><?= $customer_city ?></p>
<?php
	} else if($type == "Supplier"){
		$supplier_name			= $opponent->name;
		$complete_address		= '';
		$complete_address		.= $opponent->address;
		$supplier_number		= $opponent->number;
		$supplier_block			= $opponent->block;
		$supplier_rt			= $opponent->rt;
		$supplier_rw			= $opponent->rw;
		$supplier_postal_code	= $opponent->postal_code;
		$supplier_city			= $opponent->city;
		
		$complete_address		.= 'No. ' . $supplier_number;
		
		if($supplier_block		== '' && $supplier_block == '000'){
			$complete_address	.= 'Block ' . $supplier_block;
		};
		
		if($supplier_rt != '' && $supplier_rt != '000'){
			$complete_address	.= 'RT ' . $supplier_rt . '/ RW ' . $supplier_rw;
		}
		
		if($supplier_postal_code != ''){
			$complete_address	.= ', ' . $supplier_postal_code;
		}
?>
		<p><?= $supplier_name ?></p>
		<p><?= $complete_address ?></p>
		<p><?= $supplier_city ?></p>
<?php
	} else {
		$other_name			= $opponent->name;
		$other_description	= $opponent->description;
		$other_type			= $opponent->type;
?>
		<p><?= $other_name ?></p>
		<p><?= $other_description ?></p>
		<p><?= $other_type ?></p>
<?php
	}
?>
		<div  id='transactionTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Account</th>
					<th>Debit</th>
					<th>Credit</th>
				</tr>
				<tbody id='transactionTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='transactionTableText'>There is no transaction found.</p>
	</div>
</div>
<script>
	$(document).ready(function(){
		refreshView();
	});

	$('#page').change(function(){
		refreshView();
	})

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Bank/getMutationByOpponent') ?>",
			data:{
				page:page,
				id: <?= $opponent->id ?>,
				type:"<?= $type ?>"
			},
			success:function(response){
				var pages		= response.pages;
				$('#page').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}

				var items			= response.items;
				var itemCount = 0;
				$('#transactionTableContent').html("");
				$.each(items, function(index, item){
					var date		= item.date;
					var accountName	= item.accountName;
					var transaction	= item.transaction;
					var value		= parseFloat(item.value);

					if(transaction == 1){
						$('#transactionTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + accountName + "</td><td style='width:20%'></td><td style='width:20%'>Rp. " + numeral(value).format('0,0.00') + "</td></tr>");
					} else {
						$('#transactionTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + accountName + "</td><td style='width:20%'>Rp. " + numeral(value).format('0,0.00') + "</td><td style='width:20%'></td></tr>");
					}
					itemCount++;
				});

				if(itemCount > 0){
					$('#transactionTable').show();
					$('#transactionTableText').hide();
				} else {
					$('#transactionTable').hide();
					$('#transactionTableText').show();
				}
			}
		})
	}
</script>
