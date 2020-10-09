<head>
	<title>Visit List - Confirm</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> / Confirm Visit List   </p>
	</div>
	<br>
	<div class='dashboard_in'>

	<div id='visitListTable'>
		<table class='table table-bordered'>
			<tr>
				<th>Date</th>
				<th>Salesman</th>
				<th>Action</th>
			</tr>
			<tbody id='visitListTableContent'></tbody>
		</table>

		<select class='form-control' id='page' style='width:100px'>
			<option value='1'>1</option>
		</select>
	</div>
</div>

<div class='alert_wrapper' id='visitListWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>View Visit List</h3>
		<hr>
		<label>Date</label>
		<p id='dateP'></p>

		<label>Sales</label>
		<p id='salesP'></p>

		<label>Created By</label>
		<p id='creatorP'></p>
		<p id='createdDateP'></p>

		<label>Customers</label>
		<table class='table table-bordered'>
			<tr>
				<th>Name</th>
				<th>Information</th>
			</tr>
			<tbody id='customerTableContent'></tbody>
		</table>

		<button class='button button_danger_dark' onclick='deleteVisitList()'><i class='fa fa-trash'></i></button>
		<button class='button button_default_dark' onclick='confirmVisitList()'><i class='fa fa-long-arrow-right'></i></button>

		<div class='notificationText danger' id='visitListNotification'><p>Failed to update data.</p></div>
	</div>
</div>

<script>
	var visitListId;
	$(document).ready(function(){
		refreshView();
	});

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Visit_list/getUnconfirmedItems') ?>",
			data:{
				page: page
			},
			success:function(response){
				var pages = response.pages;
				$('#page').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}

				var items = response.items;
				var itemCount = 0;
				$('#visitListTableContent').html("");
				$.each(items, function(index, item){
					var visit		= item.visited_by;
					var date		= item.date;
					var id			= item.id;

					$('#visitListTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + visit + "</td><td><button class='button button_default_dark' onclick='viewVisitList(" + id + ")'><i class='fa fa-long-arrow-right'></i></button></td></tr>")
				})
			}
		})
	}

	$('#page').change(function(){
		refreshView();
	});

	function viewVisitList(n){
		$.ajax({
			url:"<?= site_url('Visit_list/getByid') ?>",
			data:{
				id: n
			},
			success:function(response){
				visitListId		= n;
				var general		= response.general;
				var date		= general.date;
				var created_date	= general.created_date;
				var createdBy		= general.created_by;
				var visited_by		= general.visited_by;

				$('#dateP').html(my_date_format(date));
				$('#salesP').html(visited_by);
				$('#creatorP').html(createdBy);
				$('#createdDateP').html(my_date_format(created_date));

				var items		= response.items;
				$('#customerTableContent').html("");
				$.each(items, function(index, item){
					var name				= item.name;
					var customer_number		= item.number;
					var customer_block		= item.block;
					var customer_rt			= item.rt;
					var customer_rw			= item.rw;
					var customer_city		= item.city;
					var customer_postal		= item.postal_code;
					var customer_pic		= item.pic_name;
					var complete_address	= item.address;
					if(customer_number != null && customer_number != ''){
						complete_address	+= ' no. ' + customer_number;
					};
					
					if(customer_block != null && customer_block != ''){
						complete_address	+= ', blok ' + customer_block;
					};
					
					if(customer_rt != '000'){
						complete_address	+= ', RT ' + customer_rt + ', RW ' + customer_rw;
					}
					
					if(customer_postal != ''){
						complete_address += ', ' + customer_postal;
					}

					$('#customerTableContent').append("<tr><td>" + name + "</td><td><p>" + complete_address + "</p><p>" + customer_city + "</p></td></tr>");
				})
			},
			complete:function(){
				$('#visitListWrapper').fadeIn(300, function(){
					$('#visitListWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function confirmVisitList(){
		$.ajax({
			url:"<?= site_url('Visit_list/confirmById') ?>",
			data:{
				id: visitListId
			},
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				refreshView();
				if(response == 1){
					visitListId = null;
					$('#visitListWrapper .slide_alert_close_button').click();
				} else {
					$('#visitListNotification').fadeIn(250);
					setTimeout(function(){
						$('#visitListNotification').fadeOut(250);
					},1000);
				}
			}
		});
	}

	function deleteVisitList(){
		$.ajax({
			url:"<?= site_url('Visit_list/deleteById') ?>",
			data:{
				id: visitListId
			},
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				refreshView();
				if(response == 1){
					visitListId = null;
					$('#visitListWrapper .slide_alert_close_button').click();
				} else {
					$('#visitListNotification').fadeIn(250);
					setTimeout(function(){
						$('#visitListNotification').fadeOut(250);
					},1000);
				}
			}
		});
	}
</script>
