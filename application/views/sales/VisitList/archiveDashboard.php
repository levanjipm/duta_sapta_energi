<head>
	<title>Visit List - Archive</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-line-chart'></i></a> / Visit List / Archive</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<select class='form-control' id='month'>
<?php for($i = 1; $i <= 12; $i++){ ?>
				<option value='<?= $i ?>' <?= ($i == date('m')) ? "selected" : ""; ?>><?= date('F', mktime(0,0,0,$i, 1, 0)) ?></option>
<?php } ?>
			</select>
			<select class='form-control' id='year'>
<?php for($i = 2020; $i <= date('Y'); $i++){ ?>
				<option value='<?= $i ?>' <?= ($i == date('Y')) ? "selected" : ""; ?>><?= $i ?></option>
<?php } ?>
			</select>
		</div>
		<br>
		<div id='visitListTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Date</th>
					<th>Information</th>
					<th>Action</th>
				</tr>
				<tbody id='visitListTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>

		<p id='visitListTableText'>There is no visit list archive found.</p>
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

		<table class='table table-bordered'>
			<tr>
				<th>Customer</th>
				<th>Report</th>
			</tr>
			<tbody id='reportTableContent'></tbody>
		</table>
	</div>
</div>

<script>
	$(document).ready(function(){
		refreshView(1);
	});

	$('#month').change(function(){
		refreshView(1);
	});

	$('#year').change(function(){
		refreshView(1);
	});

	$('#page').change(function(){
		refreshView();
	});

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Visit_list/getItems') ?>",
			data:{
				page: page,
				month: $('#month').val(),
				year: $('#year').val()
			},
			success:function(response){
				var items		= response.items;
				var itemCount = 0;
				$('#visitListTableContent').html("");
				$.each(items, function(index, value){
					var id			= value.id;
					var date		= value.date;
					var visited_by	= value.visited_by;
					var created_by	= value.created_by;
					var confirmed_by	= value.confirmed_by;
					var is_confirm		= value.is_confirm;
					var is_reported		= value.is_reported;
					
					if(is_confirm == 1){
						if(is_reported == 1){
							$('#visitListTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><label>Salesman</label><p>" + visited_by + "</p><label>Created by</label><p>" + created_by + "</p></td><td><button class='button button_transparent' onclick='viewVisitList(" + id + ")'><i class='fa fa-eye'></i></button><button class='button button_verified' style='width:30px;height:30px' title='Confirmed by " + confirmed_by + "'><i class='fa fa-check'></i></button>&nbsp;&nbsp;<button class='button button_verified' style='width:30px;height:30px;opacity:0.5' title='Reported'><i class='fa fa-file-text-o'></i></button><br><br><p><strong>Confirmed by</strong> " + confirmed_by + "</p></td></tr>");
						} else {
							$('#visitListTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><label>Salesman</label><p>" + visited_by + "</p><label>Created by</label><p>" + created_by + "</p></td><td><button class='button button_transparent' onclick='viewVisitList(" + id + ")'><i class='fa fa-eye'></i></button><button class='button button_verified' style='width:30px;height:30px' title='Confirmed by " + confirmed_by + "'><i class='fa fa-check'></i></button><br><br><p><strong>Confirmed by</strong> " + confirmed_by + "</p></td></tr>");
						}
					} else {
						$('#visitListTableContent').append("<tr><td>" + my_date_format(date) + "</td><td><label>Salesman</label><p>" + visited_by + "</p><label>Created by</label><p>" + created_by + "</p></td><td><button class='button button_transparent' onclick='viewVisitList(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
					}
					
					itemCount++;
				});

				if(itemCount > 0){
					$('#visitListTable').show();
					$('#visitListTableText').hide();
				} else {
					$('#visitListTable').hide();
					$('#visitListTableText').show();
				}

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

	function viewVisitList(n){
		$.ajax({
			url:"<?= site_url('Visit_list/getById') ?>",
			data:{
				id: n
			},
			success:function(response){
				var general		= response.general;
				var date		= general.date;
				var created_by	= general.created_by;
				var visited_by	= general.visited_by;
				var created_date	= general.created_date;

				var is_reported		= general.is_reported;

				$('#dateP').html(my_date_format(date));
				$('#salesP').html(visited_by);
				$('#creatorP').html(created_by);
				$('#createdDateP').html(my_date_format(created_date));

				var items			= response.items;
				$('#reportTableContent').html("");
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
					
					if(is_reported == 1){
						var note			= item.note;
						var result			= (item.result == 1) ? "Success" : "Failed";
						$('#reportTableContent').append("<tr><td><label>" + name + "</label><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><label>Result</label><p>" + result + "</p><label>Note</label><p>" + note + "</p></td></tr>");
					} else {
						$('#reportTableContent').append("<tr><td><label>" + name + "</label><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><label>Result</label><p>-</p><label>Note</label><p><i>Not available</i></p></td></tr>");
					}
					

				})
			},
			complete:function(){
				$('#visitListWrapper').fadeIn(300, function(){
					$('#visitListWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}
</script>
