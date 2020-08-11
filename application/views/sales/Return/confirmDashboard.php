<head>
	<title>Sales return - Confirm</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /Return</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div id='returnTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Customer</th>
					<th>Document</th>
					<th>Action</th>
				</tr>
				<tbody id='returnTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		refreshView();
	})

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Sales_return/getUnconfirmedDocuments') ?>',
			data:{
				page: page
			},
			success:function(response){
				$('#returnTableContent').html("");
				var items = response.items;
				$.each(items, function(index, item){
					var customer = item.customer;

					
				})
				$('#page').html("");
			}
		})
	}
</script>