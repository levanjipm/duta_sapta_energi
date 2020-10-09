<head>
	<title>Visit List - Create</title>
	<style>
        .dashboardBox{
            padding:8px;
            box-shadow:3px 3px 3px 3px rgba(50,50,50,0.3);
            border-radius:5px;
            margin-bottom:10px;
        }

        .dashboardBox .leftSide{
            width:50%;
            font-weight:bold;
            display:inline-block;
        }

        .dashboardBox .rightSide{
            width:45%;
            float:right;
            display:inline-block;
            text-align:center;
            margin:0 auto;
            top: 50%;
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
            position:absolute;
            border-left:2px solid #ccc;
        }

        .dashboardBox .rightSide h3{
            
            font-weight:bold;
            color:#E19B3C;            
        }

        .subtitleText{
            font-size:0.8em;
            color:#555;
            text-align:right;
        }
    </style>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> Visit List / Create Visit List</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label>Date</label>
		<p><?= date('d M Y', strtotime($date)); ?></p>

		<button class='button button_default_dark' id='createVisitButton' disabled><i class='fa fa-plus'></i> Create (<span id='countCustomerSpan'>0</span>)</button>
		<br><br>

		<button class='button button_mini_tab' onclick='fetchUrgentCustomer(1)' id='urgentButton'>Urgent</button>
		<button class='button button_mini_tab' onclick='fetchRecommendedCustomer(1)' id='recommendedButton'>Recommended</button>
		<button class='button button_mini_tab' onclick='fetchInactiveCustomer(1)' id='inactiveButton'>Inactive</button>
		<button class='button button_mini_tab' onclick='fetchCustomer(1)' id='searchButton'>Search</button>
		<br><br>
		<div class='row'>
			<div class='col-sm-12'>
				<input type='text' class='form-control' id='searchBar'>
				<br>
				<div id='customerTable'>
					<table class='table table-bordered'>
						<tr>
							<th>Customer</th>
							<th>Information</th>
							<th>Last visited</th>
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
	</div>
</div>

<div class='alert_wrapper' id='viewVisitListWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Visit List</h3>
		<hr>
		<label>Date</label>
		<p><?= date('d M Y', strtotime($date)); ?></p>

		<label>Salesman</label>
		<p><?= $sales->name ?></p>

		<label>Customers</label>
		<table class='table table-bordered'>
			<tr>
				<th>Name</th>
				<th>Information</th>
			</tr>
			<tbody id='viewCustomerTableContent'></tbody>
		</table>

		<button class='button button_default_dark' id='submitVisitListButton'><i class='fa fa-long-arrow-right'></i></button>

		<div class='notificationText danger' id='failedInsertVisitList'><p>Failed to insert visit list.</p></div>
	</div>
</div>

<script>
	var includedCustomer = [];
	var mode;

	$(document).ready(function(){
		fetchUrgentCustomer(1);
	})

	function fetchUrgentCustomer(page = $('#page').val()){
		$('.button_mini_tab').removeClass('active');
		$('.button_mini_tab').attr('disabled', false);

		$('#urgentButton').addClass('active');
		$('#urgentButton').attr('disabled', true);

		$('#searchBar').val("");
		mode = 1;
		refreshView(page);
	}

	function fetchRecommendedCustomer(page = $('#page').val()){
		$('.button_mini_tab').removeClass('active');
		$('.button_mini_tab').attr('disabled', false);

		$('#recommendedButton').addClass('active');
		$('#recommendedButton').attr('disabled', true);

		$('#searchBar').val("");
		mode = 2;
		refreshView(page);
	}

	function fetchInactiveCustomer(page = $('#page').val()){
		$('.button_mini_tab').removeClass('active');
		$('.button_mini_tab').attr('disabled', false);

		$('#inactiveButton').addClass('active');
		$('#inactiveButton').attr('disabled', true);

		$('#searchBar').val("");
		mode = 3;
		refreshView(page);
	}

	function fetchCustomer(page = $('#page').val()){
		$('.button_mini_tab').removeClass('active');
		$('.button_mini_tab').attr('disabled', false);

		$('#searchButton').addClass('active');
		$('#searchButton').attr('disabled', true);

		$('#searchBar').val("");
		mode = 4;
		refreshView(page);
	}

	function refreshView(page){
		$.ajax({
			url:"<?= site_url('Visit_list/getCustomerVisitList') ?>",
			data:{
				mode: mode,
				page: page,
				term: $('#searchBar').val()
			},
			success:function(response){
				var items		= response.items;
				const idArray = includedCustomer.map(el => el.id);
				$('#customerTableContent').html("");
				var itemCount		= 0;
				$.each(items, function(index, item){
					var name		= item.name;
					var id			= item.id;

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

					var lastVisited		= (item.lastVisited == null) ? "<i>Never</i>" : my_date_format(item.lastVisited);

					$('#customerTableContent').append("<tr><td>" + name + "</td><td>" + complete_address + "</td><td>" + lastVisited + "</td><td><button class='button button_default_dark' id='addCustomerButton-" + id + "'><i class='fa fa-plus'></i></button><button class='button button_danger_dark' id='removeCustomerButton-" + id + "'><i class='fa fa-minus'></i></button></tr>");

					if(idArray.includes(item.id)){
						$('#removeCustomerButton-' + id).show();
						$('#addCustomerButton-' + id).hide();
					} else {
						$('#removeCustomerButton-' + id).hide();
						$('#addCustomerButton-' + id).show();
					}

					$('#removeCustomerButton-' + id).click(function(){
						removeCustomerFromList(id);
					});

					$('#addCustomerButton-' + id).click(function(){
						includedCustomer.push(item);
						$('#removeCustomerButton-' + id).show();
						$('#addCustomerButton-' + id).hide();
						checkIncludedcustomerArray();
					})

					itemCount++;
				});

				if(itemCount > 0){
					$('#customerTable').show();
					$('#customerTableText').hide();
				} else {
					$('#customerTable').hide();
					$('#customerTableText').show();
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

	function removeCustomerFromList(n){
		const idArray = includedCustomer.map(el => el.id);
		var id = idArray.indexOf(n);
		includedCustomer.splice(id, 1);
		$('#removeCustomerButton-' + n).hide();
		$('#addCustomerButton-' + n).show();
		checkIncludedcustomerArray();
	}

	function checkIncludedcustomerArray(){
		$('#countCustomerSpan').html(numeral(includedCustomer.length).format('0,0'));
		if(includedCustomer.length > 0){
			$('#createVisitButton').attr('disabled', false);
		} else {
			$('#createVisitButton').attr('disabled', true);
		}
	}

	$('#page').change(function(){
		refreshView();
	})

	$('#searchBar').change(function(){
		refreshView(1);
	})

	$('#createVisitButton').click(function(){
		$('#viewCustomerTableContent').html("");
		$.each(includedCustomer, function(index, item){
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

			$('#viewCustomerTableContent').append("<tr><td>" + name + "</td><td><p style='margin-bottom:0'>" + complete_address + "</p><p>" + customer_city + "</p></td></tr>");
		});

		$('#viewVisitListWrapper').fadeIn(300, function(){
			$('#viewVisitListWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	})

	$('#submitVisitListButton').click(function(){
		if(includedCustomer.length > 0){
			var	formData			= new FormData();
			formData.append("date", "<?= date('Y-m-d', strtotime($date)) ?>");
			formData.append("sales", <?= $sales->id ?>);

			var indexForm		= 0;
			$.each(includedCustomer, function(index, value){
				formData.append("customer[" + indexForm + "]", value.id);
				indexForm++;
			});

			$.ajax({
				url:"<?= site_url('Visit_list/insertItem') ?>",
				processData: false,
				contentType: false,
				data:formData,
				type:"POST",
				beforeSend:function(){
					$('button').attr('disabled', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					if(response == 1){
						window.location.href='<?= site_url('Visit_list/createDashboard') ?>';
					} else {
						$('#failedInsertVisitList').fadeIn(250);
						setTimeout(function(){
							$('#failedInsertVisitList').fadeOut(250);
						}, 1000)
					}
				}
			})
		}
		
	})
</script>
