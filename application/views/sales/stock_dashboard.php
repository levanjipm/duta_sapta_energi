<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /Check stock</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<input type='text' class='form-control' id='search_bar' placeholder='Search'>
		<br>
		<table class='table table-bordered' id='stock_table_wrapper'>
			<tr>
				<th>Reference</th>
				<th>Name</th>
				<th>Stock</th>
				<th>Action</th>
			</tr>
			<tbody id='stock_table'></tbody>
		</table>
		
		<p id='stock_status' style='display:none'>There is no stock data found</p>
		
		<select class='form-control' id='page' style='width:100px'>
			<option value='1'>1</option>
		</select>
	</div>
</div>
<script>
	$('#page').change(function(){
		refresh_view();
	});
	
	$('#search_bar').change(function(){
		refresh_view(1);
	});
	
	refresh_view();
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Stock/search') ?>',
			data:{
				page:page,
				term:$('#search_bar').val()
			},
			success:function(response){
				var pages	= response.pages;
				var page	= page;
				$('#page').html('');
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
				
				$('#stock_table').html('');
				
				var stocks	= response.stocks;
				$.each(stocks, function(index, stock){
					var name			= stock.name;
					var reference		= stock.reference;
					var current_stock	= stock.stock;
					
					$('#stock_table').append("<tr><td>" + reference + "</td><td>" + name + "</td><td>" + numeral(current_stock).format('0,0') + "</td><td><button type='button' class='button button_default_dark'><i class='fa fa-eye'></i></button></tr>");
				});
				
				if($('#stock_table tr').length > 0){
					$('#stock_status').hide();
					$('#stock_table_wrapper').show();
				} else {
					$('#stock_status').show();
					$('#stock_table_wrapper').hide();
				}
			},
		});
	}
</script>
