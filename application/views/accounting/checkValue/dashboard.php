<head>
	<title>Check Value</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Sales'><i class='fa fa-bar-chart'></i></a> /Check Value</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<button class='button button_default_dark' id='addItemButton'><i class='fa fa-plus'></i> Add Item</button>
		<br><br>
		<div id='formTable' style='display:none'>
			<form action='<?= site_url('Accounting/checkValue') ?>' method="POST" id='checkValueForm'>
				<table class='table table-bordered'>
					<tr>
						<th>Reference</th>
						<th>Name</th>
						<th>Quantity</th>
						<th>Stock</th>
						<th>Action</th>
					</tr>
					<tbody id='formTableContent'></tbody>
				</table>
				<br>
				<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
			</form>
		</div>
		<p id='formTableText'>Please select item(s) to be reviewed.</p>
	</div>
</div>

<div class='alert_wrapper' id='addItemWrapper'>
	<div class='alert_box_full'>
		<button type='button' class='button alert_full_close_button' title='Close select item session'>&times;</button>
		<h3 style='font-family:bebasneue'>Select Item</h3>
		<input type='text' class='form-control' id='itemSearch'>
		<br>
		<table class='table table-bordered'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Stock</th>
				<th>Action</th>
			</tr>
			<tbody id='itemTableContent'></tbody>
		</table>
		<select class='form-control' id='page' style='width:100px'>
			<option value='1'>1</option>
		</select>
	</div>
</div>

<script>
	$('#checkValueForm').validate();

	$('#addItemButton').click(function(){
		$('#itemSearch').val("");
		getItems(1);
		$('#addItemWrapper').fadeIn();
	});

	function getItems(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url("Item/showItems") ?>',
			data:{
				page: page,
				term: $('#itemSearch').val()
			},
			success:function(response){
				$('#itemTableContent').html("");
				var items		= response.items;
				$.each(items, function(index, item){
					var id				= item.item_id;
					var reference		= item.reference;
					var name			= item.name;
					var stock 			= parseInt(item.stock);

					if(stock > 0){
						$('#itemTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(stock).format('0,0') + "</td><td><button class='button button_default_dark' id='addItemButton-" + id + "'><i class='fa fa-plus'></i></button></td></tr>");
					} else {
						$('#itemTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(stock).format('0,0') + "</td><td><button class='button button_default_dark' id='addItemButton-" + id + "' disabled><i class='fa fa-plus'></i></button></td></tr>");
					}
					

					$('#addItemButton-' + id).click(function(){
						if($('#itemRow-' + id).length == 0 && stock > 0){
							$('#formTableContent').append("<tr id='itemRow-" + id + "'><td>" + reference + "</td><td>" + name + "</td><td><input type='number' class='form-control' name='quantity[" + id + "]' min='1' max='" + stock + "'></td><td>" + numeral(stock).format('0,0') + "</td><td><button class='button button_danger_dark'><i class='fa fa-trash' onclick='deleteItemRow(" + id + ")'></i></button></td></tr>")
						}
						$('#addItemWrapper .alert_full_close_button').click();
						refreshTable();
					})
				});
			
				var pages		= response.pages;
				$('#page').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		})
	}

	$('.alert_full_close_button').click(function(){
		$(this).parent().parent().fadeOut();
	});

	$('#itemSearch').change(function(){
		getItems(1);
	});

	$('#page').change(function(){
		getItems();
	})

	function deleteItemRow(n){
		$('#itemRow-' + n).remove();
		refreshTable();
	}

	function refreshTable(){
		if($('tr[id^="itemRow-"]').length == 0){
			$('#formTable').hide();
			$('#formTableText').show();
		} else {
			$('#formTable').show();
			$('#formTableText').hide();
		}
	}
</script>