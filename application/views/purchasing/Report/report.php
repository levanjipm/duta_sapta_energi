<head>
    <title>Purchasing - Report</title>
	<style>
		.progressBarWrapper{
			width:100%;
			height:30px;
			background-color:white;
			border-radius:10px;
			padding:5px;
			position:relative;
		}

		.progressBar{
			width:0;
			height:20px;
			background-color:#01bb00;
			position:relative;
			border-radius:10px;
			cursor:pointer;
			opacity:0.4;
			transition:0.3s all ease;
		}

		.progressBar:hover{
			opacity:1;
			transition:0.3s all ease;
		}

		.progressBarWrapper p{
			font-family:museo;
			color:black;
			font-weight:700;
			z-index:50;
			position:absolute;
			right:10px;
		}

		@media only print {
			.progressBarWrapper{
				display:none;
			}

			body{
				visibility:hidden;
			}

			.dashboard_in{
				position:absolute;
				top:0;
				left:0;
				visibility:visible;
			}
		}
	</style>
</head>
<?php
	$totalInvoice			= 0;
	$totalOtherInvoice		= 0;
	$complete_address		= '';
	$complete_address		.= $supplier->address;
	$supplier_number		= $supplier->number;
	$supplier_block			= $supplier->block;
	$supplier_rt			= $supplier->rt;
	$supplier_rw			= $supplier->rw;
	$supplier_postal_code	= $supplier->postal_code;
		
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
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Purchasing') ?>' title='Sales'><i class='fa fa-shopping-cart'></i></a> /Report</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Supplier</label>
		<p><?= $supplier->name ?></p>
		<p><?= $complete_address ?></p>
		<p><?= $supplier->city ?></p>
		
		<label>Period</label>
		<p><?= ($month == 0) ? date('Y', mktime(0,0,0,1,1,$year)) : date('F Y', mktime(0,0, 0, $month, 1, $year)); ?></p>

		<label>Purchase Invoice</label>
<?php if(count($purchaseInvoice) > 0){ ?>
		<table class='table table-bordered'>
			<tr>
				<th>Item</th>
				<th>Value</th>
			</tr>
<?php foreach($purchaseInvoice as $invoice){ ?>
			<tr>
				<td>
					<label><?= $invoice->name ?></label>
					<p><?= $invoice->description ?></p>
				</td>
				<td>
					Rp. <?= number_format($invoice->value,2) ?>
					<div class='progressBarWrapper'>
						<p id='progressBarText-<?= $invoice->id ?>'></p>
						<div class='progressBar' data-progress='<?= $invoice->value ?>' id='progressBar-<?= $invoice->id ?>'></div>
						
					</div>
				</td>
			</tr>
<?php $totalInvoice += $invoice->value; } ?>
			<tr>
				<td><label>Total</label></td>
				<td>Rp. <?= number_format($totalInvoice,2) ?></td>
			</tr>
		</table>
		<script>
			$('div[id^="progressBar-"]').each(function(){
				var id			= $(this).attr('id');
				var uid			= parseInt(id.substring(12, 267));
				var value		= parseFloat($(this).attr('data-progress'));
				var percentage	= value / <?= $totalInvoice ?>;
				var progress	= percentage * 100;

				$(this).animate({
					width: progress + "%",
				}, 200)
				
				$('#progressBarText-' + uid).html(numeral(percentage).format('0,0.00%'));
			})
		</script>
<?php } else { ?>
		<p id='purchaseInvoiceTableText'>There is no purchase invoice found.</p>
<?php } ?>
		<label>Other Purchase Invoice</label>
<?php if(count($otherPurchaseInvoice) > 0){ ?>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Invoice</th>
					<th>Value</th>
				</tr>
<?php foreach($otherPurchaseInvoice as $invoice){ ?>
				<tr>
					<td><?= date('d M Y', strtotime($invoice->date)) ?></td>
					<td>
						<label><?= $invoice->invoice_document ?></label>
						<p><?= $invoice->tax_document ?></p>
					</td>
					<td>Rp. <?= number_format($invoice->value,2) ?></td>
				</tr>
<?php $totalOtherInvoice += $invoice->value; } ?>
				<tr>
					<td></td>
					<td><label>Total</label></td>
					<td>Rp. <?= number_format($totalOtherInvoice,2) ?></td>
				</tr>
			</table>
			<script>
				$('div[id^="progressBar-"]').each(function(){
					var id			= $(this).attr('id');
					var uid			= parseInt(id.substring(12, 267));
					var value		= parseFloat($(this).attr('data-progress'));
					var percentage	= value / <?= $totalInvoice ?>;
					var progress	= percentage * 100;

					$(this).animate({
						width: progress + "%",
					}, 200)
					
					$('#progressBarText-' + uid).html(numeral(percentage).format('0,0.00%'));
				})
			</script>
		</div>
<?php } else { ?>
		<p id='purchaseInvoiceTableText'>There is no purchase invoice found.</p>
<?php } ?>
	</div>
</div>
