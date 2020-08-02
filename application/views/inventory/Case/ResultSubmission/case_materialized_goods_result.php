<style>
.logo_wrapper{
	position:relative; 
	height:200px;
}

.circle_wrapper {
    opacity: 1;
    transform: scale(0);
    color: white;
	border-radius:50%;
	border:2px solid #fff;
    -webkit-text-stroke: 0;
    animation: background .3s cubic-bezier(1.000, 0.008, 0.565, 1.650) .1s 1 forwards;
	width:200px;
	height:200px;
	position:absolute;
	text-align:center;
}

.check{
	position:relative;
	top:5%;
	opacity:0;
	color:white;
	font-size:10rem;
	animation: icon .5s cubic-bezier(0.895, 0.030, 0.685, 0.220) forwards;
}

@keyframes background {
	from {
		border:2px solid white;
		opacity: 0;
		transform: scale(0.3);
		background-color:transparent;
	}
	to {
		border:2px solid transparent;
		opacity: 1;
		transform: scale(1);
		background-color:#00d478;
	}
}

@keyframes icon {
	from {
		opacity: 0;
		transform: scale(0.3);
	}
	to {
		opacity: 1;
		transform: scale(1)
	}
}

#copy_text_span{
	cursor:pointer;
}
	
</style>
<div class='dashboard'>
	<br>
	<div class='dashboard_in'>
		<div class='logo_wrapper'>
			<div class='circle_wrapper'>
				<div class='check'><i class='fa fa-check'></i></div>
			</div>
		</div>
		<br>
		<div class='row'>
			<div class='col-sm-12 col-xs-12'>
				<label>Event</label>
				<p>Dematerialized goods event</p>

				<label>Date</label>
				<p><?= date('d M Y', strtotime($general->date)) ?></p>

				<label>Created by</label>
				<p><?= $general->created_by ?></p>
			</div>
		</div>
		
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
                <th>Unit price</th>
				<th>Quantity</th>
				<th>Total price</th>
				<th>Transaction</th>
			</tr>
<?php
    $eventValue = 0;
	foreach($items as $item){
		$reference = $item->reference;
		$name		= $item->name;
        $quantity	= $item->quantity;
		$unitPrice  = $item->price;
		$transaction	= $item->transaction;
		$totalPrice = $unitPrice * $quantity;
		if($transaction == 'OUT'){
			$eventValue -= $totalPrice;
		} else if($transaction == 'IN'){
			$eventValue += $totalPrice;
		}
?>
			<tr class="<?= ($transaction == 'OUT') ? 'danger' : ''?>">
				<td><?= $reference ?></td>
				<td><?= $name ?></td>
                <td>Rp. <?= number_format($unitPrice,2) ?></td>
				<td><?= number_format($quantity) ?></td>
				<td>Rp. <?= number_format($totalPrice, 2) ?></td>
				<td><?= $transaction ?></td>
			</tr>
<?php
	}
?>
            <td>
                <td colspan='2'></td>
                <td>Total</td>
                <td colspan='2'>Rp. <?= number_format($eventValue, 2) ?></td>
            </tr>
		</table>
		
		<a href='<?= site_url('Inventory_case') ?>'><button class='button button_default_dark'><i class='fa fa-long-arrow-left'></i></button></a>
	</div>
</div>
<script>
	$(document).ready(function(){
		var window_width	= $(document).width() - 200;
		var difference		= window_width * 0.5 - 200;		
		$('.logo_wrapper').css('margin-left', difference, 'important');
	});
</script>