<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Sales_order') ?>'>Sales order</a> /Close</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<table class='table table-bordered'>
			<tr>
				<th>Request date</th>
				<th>Request</th>
				<th>Sales order</th>
				<th>Action</th>
			</tr>
		</table>
	</div>
	<script>
		$(document).ready(function(){
			get_sales_orders();
		});
		function get_sales_orders(){
			$.ajax({
				url:'<?= site_url('Sales_order/get_unconfirmed_closed_sales_order') ?>',
				type:'GET',
				success:function(response){
					$.each(response, function(index, value){
						var date = value.date;
					});
				}
			});
		}
	</script>
</div>