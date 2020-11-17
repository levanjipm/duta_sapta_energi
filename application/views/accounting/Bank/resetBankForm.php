<title>Reset bank</title>
<style>
	.subtitleLabel{
		color:black;
		font-family:museo;
		font-size:14px;
	}
	.subtitleText{
		color:#777;
		font-family:museo;
		font-size:14px;
	}
</style>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> / Bank/ Reset bank</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Account</label>
		<p><?= $account->name ?></p>
		<p><?= $account->number ?></p>
		<p><?= $account->bank ?></p>
		<p><?= $account->branch ?></p>
		
		<label>Transaction</label>
		<p><?= date('d M Y', strtotime($bank->date)) ?></p>
		<p>Rp. <?= number_format($bank->value, 2) ?></p>

		<label>Opponent</label>
		<p><?= ($opponent['name']) ?></p>
		<p><?= ($opponent['address']) ?></p>
		<p><?= ($opponent['city']) ?></p>

		<label>Assignment</label>
		<p>Assigned as <?= $bank->type ?></p>

<?php if($type == "receivable"){ ?>
		<table class='table table-bordered'>
			<tr>
				<th>Date</th>
				<th>Invoice</th>
				<th>Information</th>
				<th>Value</th>
			</tr>
<?php foreach($receivable as $item){ ?>
			<tr>
				<td><?= date('d M Y', strtotime($item->paidDate)) ?></td>
				<td>
					<p><?= $item->name ?></p>
					<p><?= ($item->taxInvoice == "" || $item->taxInvoice == NULL) ? "Not available" : $item->taxInvoice; ?></p>
					<p><?= date('d M Y', strtotime($item->date)) ?></p>
				</td>
				<td><p><?= ($item->information == "" || $item->information == NULL) ? "Not available" : $item->information; ?></p></td>
				<td>Rp. <?= number_format($item->paidValue) ?></td>
<?php } ?>
		</table>

		<button class='button button_danger_dark' onclick='resetReceivable()'>Reset this Transaction</button>

		<script>
			function resetReceivable(){
				$.ajax({
					url:"<?= site_url('Receivable/resetByBankId') ?>",
					data:{
						id: <?= $bank->id ?>
					},
					type:"POST",
					beforeSend:function(){
						$('button').attr('disabled', true);
					},
					success:function(response){
						$('button').attr('disabled', false);
						window.location.href='<?= site_url('Bank/resetDashboard') ?>';
					}
				})
			}
		</script>
<?php } else if($type == "payable"){ ?>
	<table class='table table-bordered'>
			<tr>
				<th>Date</th>
				<th>Invoice</th>
				<th>Information</th>
				<th>Value</th>
			</tr>
<?php foreach($payable as $item){ ?>
			<tr>
				<td><?= date('d M Y', strtotime($item->paidDate)) ?></td>
				<td>
					<p><?= $item->invoice_document ?></p>
					<p><?= ($item->tax_document == "" || $item->tax_document == NULL) ? "Not available" : $item->tax_document; ?></p>
					<p><?= date('d M Y', strtotime($item->date)) ?></p>
				</td>
				<td><p><?= ($item->information == "" || $item->information == NULL) ? "Not available" : $item->information; ?></p></td>
				<td>Rp. <?= number_format($item->paidValue) ?></td>
<?php } ?>
		</table>

		<button class='button button_danger_dark' onclick='resetReceivable()'>Reset this Transaction</button>

		<script>
			function resetReceivable(){
				$.ajax({
					url:"<?= site_url('Payable/resetByBankId') ?>",
					data:{
						id: <?= $bank->id ?>
					},
					type:"POST",
					beforeSend:function(){
						$('button').attr('disabled', true);
					},
					success:function(response){
						$('button').attr('disabled', false);
						window.location.href='<?= site_url('Bank/resetDashboard') ?>';
					}
				})
			}
		</script>
<?php } else if($type == "pettyCash"){ ?>
		<label class='subtitleLabel'>Warning | Peringatan</label>
		<p class='subtitleText'>This operation not only delete the assignment of bank data but ultimately delete the bank data itself. Please be cautious to execute this operation.</p>
		<p class='subtitleText'>Operasi ini bukan hanya menghapus penempatan dari data bank namun juga akhirnya menghapus data bank itu sendiri. Mohon untuk berhati - hati dalam melakukan operasi ini.</p><br>
		<button class='button button_danger_dark' onclick='resetPettyCash()'>Reset this Transaction</button>
		<script>
			function resetPettyCash(){
				$.ajax({
					url:"<?= site_url('Petty_cash/resetByBankId') ?>",
					data:{
						id: <?= $bank->id ?>
					},
					type:"POST",
					beforeSend:function(){
						$('button').attr('disabled', true);
					},
					success:function(response){
						$('button').attr('disabled', false);
						window.location.href='<?= site_url('Bank/resetDashboard') ?>';
					}
				})
			}
		</script>
<?php } else if($type == "assignment"){ ?>
	<button class='button button_danger_dark' onclick='resetAssignment()'>Reset this Transaction</button>
	<script>
		function resetAssignment(){
			$.ajax({
				url:"<?= site_url('Bank/resetByBankId') ?>",
				data:{
					id: <?= $bank->id ?>
				},
				type:"POST",
				beforeSend:function(){
					$('button').attr('disabled', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					window.location.href='<?= site_url('Bank/resetDashboard') ?>';
				}
			})
		}
	</script>
<?php
	} else if($type == "salesReturn"){ 
		if($balancer->is_done == 0){
?>
		<label class='subtitleLabel'>Warning | Peringatan</label>
		<p class='subtitleText'>This operation not only delete the assignment of bank data but ultimately delete the bank data itself. Please be cautious to execute this operation.</p>
		<p class='subtitleText'>Operasi ini bukan hanya menghapus penempatan dari data bank namun juga akhirnya menghapus data bank itu sendiri. Mohon untuk berhati - hati dalam melakukan operasi ini.</p><br>
		<button class='button button_danger_dark' onclick='resetSalesReturn()'>Reset this transaction</button>
		<script>
			function resetSalesReturn(){
				$.ajax({
					url:"<?= site_url('Sales_return/resetByBankId') ?>",
					data:{
						id: <?= $bank->id ?>
					},
					type:"POST",
					beforeSend:function(){
						$('button').attr('disabled', true);
					},
					success:function(response){
						$('button').attr('disabled', false);
						window.location.href='<?= site_url('Bank/resetDashboard') ?>';
					}
				})
			}
		</script>
<?php
		}
?>
<?php  
	} else if($type == "purchaseReturn"){  
?>
		<label class='subtitleLabel'>Warning | Peringatan</label>
		<p class='subtitleText'>This operation not only delete the assignment of bank data but ultimately delete the bank data itself. Please be cautious to execute this operation.</p>
		<p class='subtitleText'>Operasi ini bukan hanya menghapus penempatan dari data bank namun juga akhirnya menghapus data bank itu sendiri. Mohon untuk berhati - hati dalam melakukan operasi ini.</p><br>
		<button class='button button_danger_dark' onclick='resetSalesReturn()'>Reset this transaction</button>
		<script>
			function resetSalesReturn(){
				$.ajax({
					url:"<?= site_url('Purchase_return/resetByBankId') ?>",
					data:{
						id: <?= $bank->id ?>
					},
					type:"POST",
					beforeSend:function(){
						$('button').attr('disabled', true);
					},
					success:function(response){
						$('button').attr('disabled', false);
						window.location.href='<?= site_url('Bank/resetDashboard') ?>';
					}
				})
			}
		</script>
<?php
	}
?>
	</div>
</div>
