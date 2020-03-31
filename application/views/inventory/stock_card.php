<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Inventory') ?>' title='Inventory'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Stock/view/Inventory') ?>'>Stock </a> /<?= $items->reference ?></p>
	</div>
	<br>
	<div class='dashboard_in'>
		<table class='table table-bordered'>
			<tr>
				<th>Date</th>
				<th>Document</th>
				<th>Opponent</th>
				<th>Transaction</th>
				<th>Balance</th>
			</tr>
			<tbody id='stock_table'></tbody>
		</table>
		
		<select class='form-control' id='page' style='width:100px'>
			<option value='1'>1</option>
		</select>
	</div>
</div>
<script>
	function refresh_view(page = $('#page').val()){
		url:'<?= site_url('Stock/card_view') ?>',
		data:{
			page:page,
			item_id:'<?= $items->id ?>',
		},
		success:function(response){
			console.log(response);
		}
	}
</script>