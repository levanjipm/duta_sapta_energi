<head>
	<title>Customer - Sales</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> / Assign Customers to Sales</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Sales</label>
		<p><?= $sales->name ?></p>

		<div class='row' id='areaWrapper'>
		</div>

		<input type='text' class='form-control' id='searchBar'>
		<br>
		<div id='customerTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Name</th>
					<th>Information</th>
					<th>Action</th>
				</tr>
				<tbody id='customerTableContent'></tbody>
			</table>
			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='customerTableText'>There is no customer found.</p>
	</div>
</div>

<div class='alert_wrapper' id='selectSalesWrapper'>
	<div class='alert_box_full'>
		<h3 style='font-family:bebasneue'>Choose an account</h3>
		<button type='button' class='button alert_full_close_button' title='Close select account session'>&times;</button>
		<input type='text' class='form-control' id='searchBar'>

		<br>
		<div id='userTable'>
			<table class='table table-bordered'>
				<tr>
					<th>User</th>
					<th>Action</th>
				</tr>
				<tbody id='userTableContent'></tbody>
			</table>
			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='userTableText'>There is no user found.</p>
	</div>
</div>

<script>
	var includedAreas = [];
	$(document).ready(function(){
		getAreas();
	})

	function getAreas(callback = null){
		$.ajax({
			url:"<?= site_url('Area/getAllItems') ?>",
			success:function(response){
				$.each(response, function(index, value){
					var id		= value.id;
					var name	= value.name;
					$('#areaWrapper').append("<div class='col-sm-4'><label><input type='checkbox' checked> " + name + "</label></div>")
					includedAreas.push(value.id);
				})
			}
		})
	}

	function refreshCustomerView(page = $('#page').val()){
		var formData		= new FormData();
		formData.append("sales", <?= $sales->id ?>);
		formData.append("page", page);
		formData.append("term", $('#searchBar').val());
		$.each(includedAreas, function(index, area){
			formData.append("includedAreas[]", area);
		});

		$.ajax({
			url:"<?= site_url('CustomerSales/getBySales') ?>",
			data:formData,
			processData: false,
			contentType: false,
			type:"POST",
			success:function(response){
				console.log(response);
			}
		});
	}
</script>