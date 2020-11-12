<head>
	<title>Customer - Sales</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> / Assign Customers to Sales</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<form id="assignForm">
		<label>Salesman</label>
			<button type='button' class='form-control' id='selectSales' style='text-align:left'></button>
			<input type='hidden' id='sales' name='sales' required>
			<br>
			<button type='button' id='assignButton' class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
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
	$('#assignForm').validate({
		ignore:"",
		rules: {"hidden_field": {required: true}}
	});

	$('#assignButton').click(function(){
		if($('#assignForm').valid()){
			window.location.href='<?= site_url('CustomerSales/assignForm') ?>' + "?sales=" + $('#sales').val();
		}
	})

	$('#selectSales').click(function(){
		refreshView();
		$('#selectSalesWrapper').fadeIn();
	})

	$('#searchBar').change(function(){
		refreshView(1);
	});

	$('#page').change(function(){
		refreshView();
	})

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Users/getSalesItems') ?>",
			data:{
				page:page,
				term: $('#searchBar').val()
			},
			success:function(response){
				var items = response.users;
				$('#userTableContent').html("");
				var userCount = 0;
				$.each(items, function(index, item){
					var name = item.name;
					var id = item.id;
					if(item.image_url == null){
						var imageUrl = "<?= base_url() . '/assets/ProfileImages/defaultImage.png' ?>";
					} else {
						var imageUrl = "<?= base_url() . '/assets/ProfileImages/' ?>" + item.image_url;
					}

					$('#userTableContent').append("<tr><td><img src='" + imageUrl + "' style='width:30px;height:30px;border-radius:50%'> " + name + "</td><td><button class='button button_default_dark' type='button' id='selectUserButton-" + id + "'><i class='fa fa-long-arrow-right'></i></button> <button class='button button_success_dark' onclick='viewSalesAssignment(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");

					$('#selectUserButton-' + id).click(function(){
						$("#selectSales").html(name);
						$('#sales').val(id);
						$('#selectSalesWrapper .alert_full_close_button').click();
					})
					userCount++;
				});

				if(userCount > 0){
					$('#userTable').show();
					$('#userTableText').hide();
				} else {
					$('#userTable').hide();
					$('#userTableText').show();
				}

				var pages = response.pages;
				$('#page').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				};
			}
		})
	};

	$('.alert_full_close_button').click(function(){
		$(this).parent().parent().fadeOut();
	});

	function viewSalesAssignment(n){
		window.location.href='<?= site_url('CustomerSales/viewBySales/') ?>' + n;
	}
</script>
