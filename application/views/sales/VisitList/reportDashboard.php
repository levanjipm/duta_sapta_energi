<head>
	<title>Visit List - Report</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> / Report Visit List   </p>
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
		<p id='visitListTableText'>There is no visit list to be reported.</p>
	</div>
</div>

<div class='alert_wrapper' id='visitListWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<form id='reportForm'>
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
					<th>Customer</th>
					<th>Result</th>
				</tr>
				<tbody id='customerTableContent'></tbody>
			</table>

			<button type='button' class='button button_default_dark' onclick='submitReport()'><i class='fa fa-long-arrow-right'></i></button>

			<div class='notificationText danger' id='failedUpdateReport'><p>Failed to update report.</p></div>
		</form>
	</div>
</div>

<script>
	$('#reportForm').validate();

	var visitListId;
	$(document).ready(function(){
		refreshView();
	});

	function refreshView(page = $('#page').val()){
		$.ajax({
			url:"<?= site_url('Visit_list/getUnreportedItems') ?>",
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

					$('#visitListTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>" + visit + "</td><td><button class='button button_default_dark' onclick='viewVisitList(" + id + ")'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
					itemCount++;
				});

				if(itemCount > 0){
					$('#visitListTableText').hide();
					$('#visitListTable').show();
				} else {
					$('#visitListTable').hide();
					$('#visitListTableText').show();
				}
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
					var id					= item.id;
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

					$('#customerTableContent').append("<tr><td><label>" + name + "</label><p>" + complete_address + "</p></td><td><label>Result</label><select class='form-control' name='result[" + id + "]'><option value='0'>Failed</option><option value='1'>Success</option></select><label>Note</label><textarea class='form-control' name='note[" + id + "]' rows='3' style='resize:none' required minlength='10'></textarea></td></tr>");
				});
			},
			complete:function(){
				$('#visitListWrapper').fadeIn(300, function(){
					$('#visitListWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	function submitReport(){
		if($('#reportForm').valid()){
			formData		= $('#reportForm').serializeArray();
			formData.push({name: "id", value: visitListId});
			$.ajax({
				url:"<?= site_url('Visit_list/submitReport') ?>",
				type:"POST",
				data:formData,
				beforeSend:function(){
					$('textarea').attr('readonly', true);
					$('button').attr('disabled', true);
				},
				success:function(response){
					$('textarea').attr('readonly', false);
					$('button').attr('disabled', false);
					refreshView();

					if(response == 0){
						$('#failedUpdateReport').fadeIn(250);
						setTimeout(function(){
							$('#failedUpdateReport').fadeOut(250);
						}, 1000);
					} else if(response == 1){
						$('#visitListWrapper .slide_alert_close_button').click();
					}
				}
			})
		}
	}
</script>
