<head>
	<title>Sales order - Close</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Sales_order') ?>'>Sales order</a>/ Close</p>
	</div>
	<br>
	<div class='dashboard_in'>

		<div id='salesOrderTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Customer</th>
					<th>Action</th>
				</tr>
				<tbody id='salesOrderTableContent'></tbody>
			</table>
		</div>
		<p id='salesOrderTableText'>There is no close sales order submission to be reviewed.</p>
	</div>
</div>

<script>
    $(document).ready(function(){
        refresh_view();
    })
    function refresh_view(){
        $.ajax({
            url:'<?= site_url('Sales_order/getUnconfirmedCloseSubmission') ?>',
            success:function(response){
                console.log(response);
            }
        })
    }
</script>