<head>
    <title>Sales analytics</title>
	<style>
		#valueSidePane{
			height:100%;
			background-color:#fff;
			box-shadow:3px 3px 3px 3px rgba(100,100,100,0.3);
			border-radius:10px;
		}
		#valueSidePane button{
			outline:none!important;
			background-color:transparent;
			width:100%;
			padding:0px 15px;
			text-align:left;
			border:none;
			border-radius:5px;
			transition:0.3s all ease;
			margin-bottom:20px;
		}

		#valueSidePane button:hover{
			padding-left:15px;
			background-color:rgba(225, 155, 60,0.8);
			color:white;
		}

		#valueSidePane button.active{
			background-color:rgba(225, 155, 60,1);
			color:white;
		}

		#valueSidePane button.active:hover{
			padding-left:15px;
		}

		.progressBarWrapper{
			width:100%;
			height:30px;
			background-color:white;
			border-radius:10px;
			padding:5px;
			position:relative;
		}

		.progressBar{
			width:0;
			height:20px;
			background-color:#01bb00;
			position:relative;
			border-radius:10px;
			cursor:pointer;
			opacity:0.4;
			transition:0.3s all ease;
		}

		.overAchive{
			background-color:#3c82e1!important;
		}

		.progressBar:hover{
			opacity:1;
			transition:0.3s all ease;
		}

		.progressBarWrapper p{
			font-family:museo;
			color:black;
			font-weight:700;
			z-index:50;
			position:absolute;
			right:10px;
		}

		.salesOrderBox{
			width:30px;
			height:30px;
			margin-top:0;
			margin-bottom:0;
			margin-right:5px;
			border:1px solid rgba(1, 187, 0, 0.3);
			background-color:#01bb00;
			opacity:0.3;
			display:inline-block;
			cursor:pointer;
			border-radius:3px;
		}

		.salesOrderBox.active{
			opacity:1;
		}
	</style>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Inventory'><i class='fa fa-line-chart'></i></a> /Analytics</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<button class='button button_mini_tab' id='valueButton'>Value Analytic</button>
		<button class='button button_mini_tab' id='customerButton'>Customer Analytic</button>
		<button class='button button_mini_tab' id='nooButton'>New Outlet Orders</button>
		<button class='button button_mini_tab' id='paymentButton'>Payment Analytic</button>
		<hr>
		<div class='row' id='valueViewPane' style='display:none'>
			<div class='col-md-2 col-sm-3 col-xs-4' id='valueSidePane'>
				<br>
				<label style='padding-left:5px'>Category</label><hr>
				<button id='sales'><p style='margin-bottom:0'>Salesman</p></button><br>
				<button id='area'><p style='margin-bottom:0'>Area</p></button><br>
				<button id='type'><p style='margin-bottom:0'>Type</p></button><br>
				<button id='brand'><p style='margin-bottom:0'>Brand</p></button><br>
			</div>
			<div class='col-md-10 col-sm-9 col-xs-8'>
				<div class='input_group'>
					<select class='form-control' id='month' placeholder='Month'>
					<?php for($i = 1; $i <= 12; $i++){ ?>
						<option value='<?= $i ?>' <?= ($i == date('m')) ? "selected" : "" ?>><?= date('F', mktime(0,0,0,$i, 1, date('Y'))) ?></option>
					<?php } ?>
					</select>
					<select class='form-control' id='year'>
					<?php for($i = 2020; $i <= date("Y"); $i++){ ?>
						<option value='<?= $i ?>' <?= ($i == date("Y"))? "selected": "" ?>><?= $i ?></option>
					<?php } ?>
					</select>
					<div class='input_group_append'>
						<button type='button' class='button button_default_dark' onclick='refreshView()'><i class='fa fa-long-arrow-right'></i></button>
					</div>
				</div>
				<br>
				<div id='valueAnalyticTable'>
					<table class='table table-bordered'>
						<thead id='tableAnalyticHeader'></thead>
						<tbody id='tableAnalyticBody'></tbody>
					</table>
				</div>
			</div>
		</div>
		<div class='row' id='customerViewPane' style='display:none'>
			<div class='col-sm-12'>
				<form id='customerAnalyticForm'>
					<div class='input_group'>
						<select class='form-control' id='customerMonth' placeholder='Month'>
						<?php for($i = 1; $i <= 12; $i++){ ?>
							<option value='<?= $i ?>' <?= ($i == date('m')) ? "selected" : "" ?>><?= date('F', mktime(0,0,0,$i, 1, date('Y'))) ?></option>
						<?php } ?>
						</select>
						<select class='form-control' id='customerYear'>
						<?php for($i = 2020; $i <= date("Y"); $i++){ ?>
							<option value='<?= $i ?>' <?= ($i == date("Y"))? "selected": "" ?>><?= $i ?></option>
						<?php } ?>
						</select>
						<select class='form-control' id='customerBrand' placeholder='Brand'>
						<?php foreach($brands as $brand){ ?>
							<option value='<?= $brand->id ?>'><?= $brand->name ?></option>
						<?php } ?>
						</select>
						<div class='input_group_append'>
							<button type='button' class='button button_default_dark' onclick='getCustomerItems()'><i class='fa fa-search'></i></button>
						</div>
						<div class='input_group_append'>
							<button type='button' class='button button_success_dark' id='reportButton' title="Generate report" style='display:none'><i class='fa fa-file-o'></i></button>
						</div>
					</div>
				</form>
				<br>
				<div class='row'>
					<div class='col-sm-12'>
						<input type='text' class='form-control' id='customerSearchBar'>
						<br>
						<div id='customerTable' style='display:none'>
							<table class='table'>
								<tr>
									<th>Customer</th>
									<th>Achivement | Target</th>
									<th>Action</th>
								</tr>
								<tbody id='customerTableContent'></tbody>
							</table>

							<select class='form-control' id='customerPage' style='width:100px'>
								<option value='1'>1</option>
							</select>
						</div>
						<p id='customerTableText'>There is no customer found.</p>
					</div>
				</div>
			</div>
		</div>
		<div class='row' id='nooViewPane' style='display:none'>
			<div class='col-sm-12'>
				<form id='nooForm'>
					<div class='input_group'>
						<select class='form-control' id='nooMonth' placeholder='Month'>
						<?php for($i = 1; $i <= 12; $i++){ ?>
							<option value='<?= $i ?>' <?= ($i == date('m')) ? "selected" : "" ?>><?= date('F', mktime(0,0,0,$i, 1, date('Y'))) ?></option>
						<?php } ?>
						</select>
						<select class='form-control' id='nooYear'>
						<?php for($i = 2020; $i <= date("Y"); $i++){ ?>
							<option value='<?= $i ?>'><?= $i ?></option>
						<?php } ?>
						</select>
						<select class='form-control' id='nooBrand' placeholder='Brand'>
						<?php foreach($brands as $brand){ ?>
							<option value='<?= $brand->id ?>'><?= $brand->name ?></option>
						<?php } ?>
						</select>
						<div class='input_group_append'>
							<button type='button' class='button button_default_dark' onclick='getNooItems()'><i class='fa fa-search'></i></button>
						</div>
					</div>
				</form>
			</div>
			<div class='col-sm-12'>
				<br>
				<label>Action</label>
				<br>
				<button class='button button_mini_tab' id='saveNooTableButton' style='display:none'><i class="fa fa-file-excel-o"></i> Save As CSV</button>
				<br><br>
				<table class='table table-bordered' id='nooTable' style='display:none'>
					<tr>
						<th>Name</th>
						<th>Address</th>
						<th>Action</th>
					</tr>
					<tbody id='nooTableContent'></tbody>
				</table>
				<p id='nooTableText'>There is no new outlet order found.</p>
			</div>
		</div>
		<div class='row' id='paymentViewPane' style='display:none'>
			<table class='table table-bordered'>
				<tr>
					<th>Property</th>
					<th>Value - Weighted Average</th>
					<th>Plain Average</th>
					<th>Value</th>
				</tr>
				<tr>
					<td>3 Months</td>
					<td id='vwa-0'></td>
					<td id='pa-0'></td>
					<td id='v-0'></td>
				</tr>
				<tr>
					<td>6 Months</td>
					<td id='vwa-1'></td>
					<td id='pa-1'></td>
					<td id='v-1'></td>
				</tr>
				<tr>
					<td>12 Months</td>
					<td id='vwa-2'></td>
					<td id='pa-2'></td>
					<td id='v-2'></td>
				</tr>
				<tr>
					<td>Overall</td>
					<td id='vwa-3'></td>
					<td id='pa-3'></td>
					<td id='v-3'></td>
				</tr>
			</table>
		</div>
    </div>
</div>

<div class='alert_wrapper' id='customerTargetWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Adjust Customer Target</h3>
		<hr>
		<form id='customerTargetForm'>
			<label>Customer</label>
			<p id='customerName_p'></p>
			<p id='customerAddress_p'></p>
			<p id='customerCity_p'></p>

			<label>Current Target</label>
			<input type='number' class='form-control' id='target' required min='1'>

			<label>Effective Date</label>
			<input type='month' class='form-control' id='effectiveDate' required value='<?= date('Y-m') ?>'>

			<label>Brand</label>
			<select class='form-control' id='targetBrand' required>
			<?php foreach($brands as $brand){ ?>
				<option value="<?= $brand->id ?>"><?= $brand->name ?></option>
			<?php } ?>
			</select>
			<p style='font-famliy:museo;color:#555;font-size:0.8em;' id='updateTargetDate'></p>
			<br>
			<button type='button' class='button button_default_dark' id='submitCustomerTargetButton'><i class='fa fa-long-arrow-right'></i></button>
			<div class='notificationText danger' id='failedUpdateData'><p>Failed to update data.</p></div><br>
			<hr>
			<h4 style='font-family:bebasneue'>Target History</h4>
			<div id='targetTable'>
				<table class='table table-bordered'>
					<tr>
						<th>Date</th>
						<th>Value</th>
						<th>Brand</th>
					</tr>
					<tbody id='targetTableContent'></tbody>
				</table>
			</div>
			<p id='targetTableText'>There is no history of target</p>
			<div class='notificationText danger' id='failedDeleteTarget'><p>Failed to delete customer's target</p></div>

			<h4 style='font-family:bebasneue'>Sales Orders</h4>
			<div class='table-responsive-lg'>
			<table class='table'>
				<tr>
					<th>Week / Year</th>
					<th id='salesOrderWeek-0'></th>
					<th id='salesOrderWeek-1'></th>
					<th id='salesOrderWeek-2'></th>
					<th id='salesOrderWeek-3'></th>
					<th id='salesOrderWeek-4'></th>
					<th id='salesOrderWeek-5'></th>
					<th id='salesOrderWeek-6'></th>
					<th id='salesOrderWeek-7'></th>
					<th id='salesOrderWeek-8'></th>
					<th id='salesOrderWeek-9'></th>
					<th id='salesOrderWeek-10'></th>
					<th id='salesOrderWeek-11'></th>
				</tr>
				<tr>
					<td>Sales orders</td>
					<td><div class='salesOrderBox' id='salesOrderBox-0' data-toggle="tooltip"></div></td>
					<td><div class='salesOrderBox' id='salesOrderBox-1' data-toggle="tooltip"></div></td>
					<td><div class='salesOrderBox' id='salesOrderBox-2' data-toggle="tooltip"></div></td>
					<td><div class='salesOrderBox' id='salesOrderBox-3' data-toggle="tooltip"></div></td>
					<td><div class='salesOrderBox' id='salesOrderBox-4' data-toggle="tooltip"></div></td>
					<td><div class='salesOrderBox' id='salesOrderBox-5' data-toggle="tooltip"></div></td>
					<td><div class='salesOrderBox' id='salesOrderBox-6' data-toggle="tooltip"></div></td>
					<td><div class='salesOrderBox' id='salesOrderBox-7' data-toggle="tooltip"></div></td>
					<td><div class='salesOrderBox' id='salesOrderBox-8' data-toggle="tooltip"></div></td>
					<td><div class='salesOrderBox' id='salesOrderBox-9' data-toggle="tooltip"></div></td>
					<td><div class='salesOrderBox' id='salesOrderBox-10' data-toggle="tooltip"></div></td>
					<td><div class='salesOrderBox' id='salesOrderBox-11' data-toggle="tooltip"></div></td>
				</tr>
				<tr>
					<td>Sales orders count</td>
					<th id='salesOrderCount-0'></th>
					<th id='salesOrderCount-1'></th>
					<th id='salesOrderCount-2'></th>
					<th id='salesOrderCount-3'></th>
					<th id='salesOrderCount-4'></th>
					<th id='salesOrderCount-5'></th>
					<th id='salesOrderCount-6'></th>
					<th id='salesOrderCount-7'></th>
					<th id='salesOrderCount-8'></th>
					<th id='salesOrderCount-9'></th>
					<th id='salesOrderCount-10'></th>
					<th id='salesOrderCount-11'></th>
				</tr>
			</table>
			</div>	
		</form>
		<form id='visitForm'>
			<label>Visit Frequency</label>
			<label>Current</label>
			<div class='input_group'>
				<input type='number' class='form-control' id='visit' required>
				<div class='input_group_append'>
					<button class='button button_default_dark' onclick='updateCustomerVisit()'><i class='fa fa-pencil'></i></button>
				</div>
			</div>

			<label>Recommended Visit Frequency</label>
			<p id='visitFrequencyRecommendedP'></p>

			<div class='notificationText danger' id='updateVisitFailed'><p>Failed to update visit frequency</p></div>
			<div class='notificationText success' id='updateVisitSuccess'><p>Successfully update visit frequency</p></div>
		</form>

		<button onclick='viewCustomerDetail()' class='button button_default_dark'><i class='fa fa-eye'></i></button>
	</div>
</div>

<script>
	var aspect;
	var selectedSellers = [];
	var selectedAreas = [];
	var customerTargetId;
	var customerIdSelected = null;

	$('#customerAnalyticForm').validate();
	$('#nooForm').validate();

	$(document).ready(function(){
		$('#valueButton').click();
		$('#sales').click();
		calculatePayments();
		$('[data-toggle="tooltip"]').tooltip();

		refreshView();
	})

	function viewCustomerDetail(){
		window.open('<?= site_url("Customer/viewCustomerDetail/") ?>' + customerIdSelected,
		'_blank');
	}

	$('#valueButton').click(function(){
		$('.button_mini_tab').attr('disabled', false);
		$('.button_mini_tab').removeClass('active');

		$('#valueButton').attr('disabled', true);
		$('#valueButton').addClass('active');

		$('#paymentViewPane, #customerViewPane, #nooViewPane').fadeOut(250, function(){
			setTimeout(function(){
				$('#valueViewPane').fadeIn(250);
			}, 252)
		})

		$('#sales').click();
	});

	$('#customerButton').click(function(){
		$('.button_mini_tab').attr('disabled', false);
		$('.button_mini_tab').removeClass('active');

		$('#customerButton').attr('disabled', true);
		$('#customerButton').addClass('active');

		$('#paymentViewPane, #valueViewPane, #nooViewPane').fadeOut(250, function(){
			setTimeout(function(){
				$('#customerViewPane').fadeIn(250);
			}, 252)
		})
	})

	$('#nooButton').click(function(){
		$('.button_mini_tab').attr('disabled', false);
		$('.button_mini_tab').removeClass('active');

		$('#nooButton').attr('disabled', true);
		$('#nooButton').addClass('active');

		$('#paymentViewPane, #valueViewPane, #customerViewPane').fadeOut(250, function(){
			setTimeout(function(){
				$('#nooViewPane').fadeIn(250);
			}, 252)
		})
	})

	$('#paymentButton').click(function(){
		$('.button_mini_tab').attr('disabled', false);
		$('.button_mini_tab').removeClass('active');

		$('#paymentButton').attr('disabled', true);
		$('#paymentButton').addClass('active');

		$('#customerViewPane, #valueViewPane, #nooViewPane').fadeOut(250, function(){
			setTimeout(function(){
				$('#paymentViewPane').fadeIn(250);
			}, 252)
		})
	})

	$('#valueSidePane button').click(function(){
		$('#valueSidePane button').attr('disabled', false);
		$('#valueSidePane button').removeClass('active');

		$(this).addClass('active');
		$(this).attr('disabled', true);
		$('#valueAnalyticTable').hide();
		$('#tableAnalyticBody').html("");
		var id = $(this).attr('id');
		aspect = id;
		if(id == "sales"){
			$('#tableAnalyticHeader').html("<tr><th>Salesman</th><th>Value</th><th>Action</th></tr>");
		} else if(id == "area"){
			$('#tableAnalyticHeader').html("<tr><th>Area</th><th>Value</th></tr>");
		} else if(id == "type") {
			$('#tableAnalyticHeader').html("<tr><th>Type</th><th>Value</th></tr>");
		} else if(id == "brand"){
			$('#tableAnalyticHeader').html("<tr><th>Brand</th><th>Value</th></tr>");
		}

		$('#month').focus();
	});

	function viewSalesmanReport(salesId){
		if(salesId == null){
			window.open("<?= site_url("SalesAnalytics/salesmanDetailReport/") ?>" + $('#month').val() + "/" + $('#year').val());
		} else {
			window.open("<?= site_url("SalesAnalytics/salesmanDetailReport/") ?>" + $('#month').val() + "/" + $('#year').val() + "/" + salesId);
		}
	}

	function refreshView(){
		if($('#month').val() != "" && $('#year').val() != ""){
			$.ajax({
				url:"<?= site_url('Sales/getByAspect') ?>",
				data:{
					month: parseInt($('#month').val()),
					year: parseInt($('#year').val()),
					aspect: aspect
				},
				beforeSend:function(){
					$('button').attr('disabled', true);
					$('input').attr('readonly', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					$('input').attr('readonly', false);
					$('#tableAnalyticBody').html("");
					if(aspect == "sales"){
						var itemCount = 0;
						var totalValue = 0;
						$.each(response, function(index, item){
							var id = item.id;
							var salesman = (item.name == null)? "Office" : item.name;
							var value = parseFloat(item.value);
							var returnValue = parseFloat(item.returned);
							if(item.image_url == null){
								var imageUrl = "<?= base_url() . '/assets/ProfileImages/defaultImage.png' ?>";
							} else {
								var imageUrl = "<?= base_url() . '/assets/ProfileImages/' ?>" + item.image_url;
							}

							$('#tableAnalyticBody').append("<tr><td><img src='" + imageUrl + "' style='width:30px;height:30px;border-radius:50%'> " + salesman + "</td><td>Rp. " + numeral(value - returnValue).format("0,0.00") + "<div class='progressBarWrapper'><p></p><div class='progressBar' data-value='" + (value - returnValue) + "'></div></div></td><td><button class='button button_default_dark' onclick='viewSalesmanReport(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");
							
							itemCount++;
							totalValue += value - returnValue;
						});

						$('.progressBar').each(function(){
							var value = $(this).attr('data-value');
							var percentage = value * 100 / totalValue;
							$(this).siblings("p").html(numeral(percentage).format('0,0.00') + "%");
							$(this).animate({
								width: percentage + "%"
							}, 1000);
						});
					} else if(aspect == "area") {
						var itemCount = 0;
						var totalValue = 0;
						$.each(response, function(index, item){
							var id = item.id;
							var areaName = (item.name == null)? "Office" : item.name;
							var value = parseFloat(item.value);
							var returnValue = parseFloat(item.returned)
							if(item.image_url == null){
								var imageUrl = "<?= base_url() . '/assets/ProfileImages/defaultImage.png' ?>";
							} else {
								var imageUrl = "<?= base_url() . '/assets/ProfileImages/' ?>" + item.image_url;
							}

							$('#tableAnalyticBody').append("<tr><td>"+ areaName + "</td><td>Rp. " + numeral(value - returnValue).format("0,0.00") + "<div class='progressBarWrapper'><p></p><div class='progressBar' data-value='" + (value - returnValue) + "'></div></div></td></tr>");
							itemCount++;
							totalValue += value - returnValue;
						});

						$('.progressBar').each(function(){
							var value = $(this).attr('data-value');
							var percentage = value * 100 / totalValue;
							$(this).siblings("p").html(numeral(percentage).format('0,0.00') + "%");
							$(this).animate({
								width: percentage + "%"
							}, 1000);
						});
					} else if(aspect == "type"){
						var itemCount = 0;
						var totalValue = 0;
						$.each(response, function(index, item){
							var typeName	= (item.name == null)? "Unassigned" : item.name;
							var value		= parseFloat(item.value);
							var returnValue = parseFloat(item.returned);
							$('#tableAnalyticBody').append("<tr><td>"+ typeName + "</td><td>Rp. " + numeral(value - returnValue).format("0,0.00") + "<div class='progressBarWrapper'><p>" + (value - returnValue) + "</p><div class='progressBar' data-value='" + (value - returnValue) + "'></div></div></td></tr>");
							itemCount++;
							totalValue += value - returnValue;
						});

						$('#tableAnalyticBody .progressBar').each(function(){
							var value = $(this).attr('data-value');
							var percentage = value * 100 / totalValue;
							$(this).siblings("p").html(numeral(percentage).format('0,0.00') + "%");
							$(this).animate({
								width: percentage + "%"
							}, 1000);
						});
					} else if(aspect == "brand"){
						var totalValue = 0;
						$.each(response, function(index, item){
							var brandName	= item.name;
							var value		= parseFloat(item.value);
							var returnValue = parseFloat(item.returned);
							$('#tableAnalyticBody').append("<tr><td>"+ brandName + "</td><td>Rp. " + numeral(value - returnValue).format("0,0.00") + "<div class='progressBarWrapper'><p>" + (value - returnValue) + "</p><div class='progressBar' data-value='" + (value - returnValue) + "'></div></div></td></tr>");
							itemCount++;
							totalValue += (value - returnValue);
						});

						$('#tableAnalyticBody .progressBar').each(function(){
							var value = parseFloat($(this).attr('data-value'));
							console.log(value);
							console.log(totalValue);
							var percentage = value * 100 / totalValue;
							$(this).siblings("p").html(numeral(percentage).format('0,0.00') + "%");
							$(this).animate({
								width: percentage + "%"
							}, 1000);
						});
					}

					if(itemCount > 0){
						$('#valueAnalyticTable').fadeIn();
						$('#valueAnalyticTableText').hide();
					} else {
						$('#valueAnalyticTableText').hide();
						$('#valueAnalyticTable').fadeIn();
					}
				}
			})
		}
	}

	function getCustomerItems(page = $('#customerPage').val())
	{
		if($('#customerAnalyticForm').valid()){
			$('#reportButton').show();
			$.ajax({
				url:"<?= site_url('SalesAnalytics/getCustomers') ?>",
				data:{
					page: page,
					term: $('#customerSearchBar').val(),
					month: parseInt($('#customerMonth').val()),
					year: $('#customerYear').val(),
					brand: $('#customerBrand').val()
				},
				success:function(response){
					$('#customerTableContent').html("");
					var maxPercentage = 0;
					var itemCount		= 0;
					var items = response.items;
					$.each(items, function(index, item){
						var name = item.name;
						var complete_address		= item.address;
						var customer_city			= item.city;
						var customer_number			= item.number;
						var customer_rt				= item.rt;
						var customer_rw				= item.rw;
						var customer_postal			= item.postal_code;
						var customer_block			= item.block;
						var customer_id				= item.id;
		
						if(customer_number != null){
							complete_address	+= ' No. ' + customer_number;
						}
					
						if(customer_block != null && customer_block != "000"){
							complete_address	+= ' Blok ' + customer_block;
						}
				
						if(customer_rt != '000'){
							complete_address	+= ' RT ' + customer_rt;
						}
					
						if(customer_rw != '000' && customer_rt != '000'){
							complete_address	+= ' /RW ' + customer_rw;
						}
					
						if(customer_postal != null){
							complete_address	+= ', ' + customer_postal;
						}

						var value = parseFloat(item.value);
						var returned = parseFloat((item.returned == null)? 0 : item.returned);
						var target = parseFloat(item.target);
						var totalValue = value - returned;
						var accomplishment = (target == 0) ? 1 : (totalValue / target);
						console.log(totalValue);
						console.log(target);
						console.log(accomplishment);

						$('#customerTableContent').append("<tr><td><label>" + name + "</label><p>" + complete_address + "</p>" + customer_city + "</p></td><td><p>Rp. " + numeral(totalValue).format('0,0.00') + " | Rp. " + numeral(target).format('0,0.00') + " </p><div class='progressBarWrapper'><p>" + numeral(100 * accomplishment).format("0,0.00") + "%</p><div class='progressBar' id='progressCustomer-" + customer_id + "' data-value='" + (totalValue / target) + "'></div></div></td><td><button class='button button_default_dark' onclick='viewCustomerTarget(" + customer_id + ")'><i class='fa fa-eye'></i></button></td></tr>");
						itemCount++;
					});

					$('#customerTableContent .progressBar').each(function(){
						var value = $(this).attr('data-value');
						if(value > 1){
							$(this).addClass('overAchive');
							$(this).animate({
								width: "100%"
							}, 1000);
						} else {
							$(this).animate({
								width: (value * 100 / 1.2) + "%"
							}, 1000);
						}
						
					});

					if(itemCount > 0){
						$('#customerTable').show();
						$('#customerTableText').hide();
					} else {
						$('#customerTable').hide();
						$('#customerTableText').show();
					}

					var pages = response.pages;
					$('#customerPage').html("");
					for(i = 1; i <= pages; i++){
						if(i == page){
							$('#customerPage').append("<option value='" + i + "' selected>" + i + "</option>");
						} else {
							$('#customerPage').append("<option value='" + i + "'>" + i + "</option>");
						}
					}
				}
			})
		} else {
			$('#reportButton').hide();
		}
	}

	function getNooItems(){
		if($('#nooForm').valid()){
			$.ajax({
				url:"<?= site_url('SalesAnalytics/getNoo') ?>",
				data:{
					month: parseInt($('#nooMonth').val()),
					year: $('#nooYear').val(),
					brand: $('#nooBrand').val()
				},
				success:function(response){
					$('#nooTableContent').html("");
					var itemCount = 0;
					$.each(response, function(index, item){
						var name 					= item.name;
						var complete_address		= item.address;
						var customer_city			= item.city;
						var customer_number			= item.number;
						var customer_rt				= item.rt;
						var customer_rw				= item.rw;
						var customer_postal			= item.postal_code;
						var customer_block			= item.block;
						var customer_id				= item.id;
						var area					= item.area;
		
						if(customer_number != null){
							complete_address	+= ' No. ' + customer_number;
						}
					
						if(customer_block != null && customer_block != "000"){
							complete_address	+= ' Blok ' + customer_block;
						}
				
						if(customer_rt != '000'){
							complete_address	+= ' RT ' + customer_rt;
						}
					
						if(customer_rw != '000' && customer_rt != '000'){
							complete_address	+= ' /RW ' + customer_rw;
						}
					
						if(customer_postal != null){
							complete_address	+= ', ' + customer_postal;
						}

						$('#nooTableContent').append("<tr><td>" + name + "</td><td>" + complete_address + "<strong> ( " + area + " )</strong></td><td><button class='button button_default_dark' onclick='viewCustomerTarget(" + customer_id + ")'><i class='fa fa-eye'></i></button></td></tr>");
						itemCount++;
					});

					if(itemCount > 0){
						$('#nooTable').show();
						$('#nooTableText').hide();
						$('#saveNooTableButton').show();
					} else {
						$('#nooTableText').show();
						$('#nooTable').hide();
						$('#saveNooTableButton').hide();
					}
				}
			});
		}
	}

	$('#reportButton').click(function(){
		if($('#customerAnalyticForm').valid()){
			var month		= $('#customerMonth').val();
			var year		= $('#customerYear').val();
			var brand		= $('#customerBrand').val();

			window.location.href='<?= site_url('SalesAnalytics/salesReport/') ?>' + month + "/" + year + "/" + brand;
		}
	})

	$('#customerPage').change(function(){
		getCustomerItems();
	});

	$('#customerSearchBar').change(function(){
		getCustomerItems(1);
	});

	function viewCustomerTarget(n){
		$.ajax({
			url:"<?= site_url('Customer/getCurrentTarget') ?>",
			data:{
				id: n
			},
			success:function(response){
				var customer = response.customer;
				customerIdSelected			= customer.id;

				var complete_address		= '';
				var customer_name			= customer.name;
				complete_address			+= customer.address;
				var customer_city			= customer.city;
				var customer_number			= customer.number;
				var customer_rt				= customer.rt;
				var customer_rw				= customer.rw;
				var customer_postal			= customer.postal_code;
				var customer_block			= customer.block;
				var customer_id				= customer.id;
				var visiting_frequency		= parseInt(customer.visiting_frequency);
		
				if(customer_number != null){
					complete_address	+= ' No. ' + customer_number;
				}
					
				if(customer_block != null && customer_block != "000"){
					complete_address	+= ' Blok ' + customer_block;
				}
				
				if(customer_rt != '000'){
					complete_address	+= ' RT ' + customer_rt;
				}
					
				if(customer_rw != '000' && customer_rt != '000'){
					complete_address	+= ' /RW ' + customer_rw;
				}
					
				if(customer_postal != null){
					complete_address	+= ', ' + customer_postal;
				}

				$('#customerName_p').html(customer_name);
				$('#customerAddress_p').html(complete_address);
				$('#customerCity_p').html(customer_city);

				var target = response.target;
				var targetValue = target.value;
				var targetDate = target.dateCreated;

				$('#effectiveDate').attr('min', targetDate);

				$('#target').val(targetValue);
				$('#updateTargetDate').html((targetDate == null) ? "Last updated: <i>Never</i>" : "Last updated: " + my_date_format(targetDate));
				customerTargetId = customer_id;

				var items = response.items;
				var targetCount = 0;
				$('#targetTableContent').html("");
				$.each(items, function(index, item){
					var target		= item.value;
					var id			= item.id;
					var date		= item.dateCreated;
					var targetDate	= new Date(date);
					var todayDate	= new Date();
					var brand		= item.name;

					if(targetDate > todayDate){
						$('#targetTableContent').append("<tr><td id='targetItem-" + id + "'>" + my_date_format(date) + "</td><td>Rp. " + numeral(target).format('0,0.00') + "</td><td>" + brand + "</td><button class='button button_danger_dark' style='float:right' onclick='deleteTarget(" + id + ")'><i class='fa fa-trash'></i></button></td></tr>");
						targetCount++;
					} else {
						$('#targetTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>Rp. " + numeral(target).format('0,0.00') + "</td><td>" + brand + "</td></tr>");
						targetCount++;
					}
					
				});

				if(targetCount > 0){
					$('#targetTable').show();
					$('#targetTableText').hide();
				} else {
					$('#targetTableText').show();
					$('#targetTable').hide();
				}

				var salesOrders = response.salesOrders;
				var totalCount = 0;
				var monthArray = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
				$.each(salesOrders, function(index, salesOrder){
					var count = salesOrder.count;
					var week = salesOrder.week;
					var year = salesOrder.year;
					var id		= index;

					var Day10 = new Date( year,0,10,12,0,0);
					var Day4 = new Date( year,0,4,12,0,0);
					var weekmSec = Day4.getTime() - Day10.getDay() * 86400000;
					var startWeek = new Date(weekmSec + ((week - 1)  * 7 ) * 86400000);
					var endWeek = new Date(weekmSec + ((week)  * 7 - 1) * 86400000);

					$('#salesOrderBox-' + id).attr('title', startWeek.getDate() + " " + monthArray[startWeek.getMonth()] + " - " + endWeek.getDate() + " " + monthArray[endWeek.getMonth()])
					if(count > 0){
						$('#salesOrderBox-' + id).addClass('active');
						totalCount++;
					} else {
						$("#salesOrderBox-" + id).removeClass('active');
					}

					$('#salesOrderWeek-' + id).html("Week " + week + " / " + year);
					$('#salesOrderCount-' + id).html(count);
				});

				if(totalCount > 0){
					var recommended = 28 / Math.ceil(totalCount * 4 / 12);
				} else {
					var recommended = 28;
				}				

				$('#visit').val(visiting_frequency);
				$('#visitFrequencyRecommendedP').html(numeral(recommended).format('0,0') + " days");
			},
			complete:function(){
				$('#customerTargetWrapper').fadeIn(300, function(){
					$('#customerTargetWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}

	function deleteTarget(n){
		$.ajax({
			url:'<?= site_url('Customer/deleteTargetByid') ?>',
			data:{
				id: n
			},
			beforeSend:function(){
				$('buton').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				if(response == 1){
					$('#targetItem-' + n).remove();
				} else {
					$('#failedDeleteTarget').fadeIn(250);
					setTimeout(function(){
						$('#failedDeleteTarget').fadeOut(250);
					}, 1000);
				}
			}
		})
	}

	$('#submitCustomerTargetButton').click(function(){
		$.ajax({
			url:"<?= site_url('Customer/updateCustomerTarget') ?>",
			data:{
				customerId: customerTargetId,
				value: $('#target').val(),
				date: $('#effectiveDate').val(),
				brand: $('#targetBrand').val()
			},
			type:"POST",
			beforeSend:function(){
				$('button').attr('disabled', true);
				$('input').attr('readonly', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				$('input').attr('readonly', false);
				getCustomerItems();
				if(response == 1){
					$('#customerTargetWrapper .slide_alert_close_button').click();
					customerTargetId = null;
				} else {
					$('#failedUpdateData').fadeIn(250);
					setTimeout(function(){
						$('#failedUpdateData').fadeOut(250);
					}, 1000);
				}
			}
		})
	});

	function updateCustomerVisit()
	{
		if($('#visit').val() > 0){
			$.ajax({
				url:'<?= site_url('Customer/updateVisitFrequency') ?>',
				data:{
					id: customerIdSelected,
					visit: $('#visit').val()
				},
				beforeSend:function(){
					$('button').attr('disabled', true);
					$('input').attr('readonly', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					$('input').attr('readonly', false);	
					if(response != 1){
						$('#updateVisitFailed').fadeIn(250);
						setTimeout(function(){
							$('#updateVisitFailed').fadeOut(250);
						}, 1000)
					} else {
						$('#updateVisitSuccess').fadeIn(250);
						setTimeout(function(){
							$('#updateVisitSuccess').fadeOut(250);
						}, 1000)
					}
				}
			})
		}
	}

	function calculatePayments(){
		$.ajax({
			url:"<?= site_url('SalesAnalytics/calculatePayments') ?>",
			success:function(response){
				$.each(response, function(index, value){
					$('#vwa-' + index).html(numeral(value.vwa).format('0,0.0000') + " days");
					$('#pa-' + index).html(numeral(value.pa).format('0,0.0000') + " days");
					$('#v-' + index).html("Rp. " + numeral(value.value).format('0,0.00') + " ( " + numeral(value.count).format('0,0') + " )");
				})
			}
		})
	}

	$('#saveNooTableButton').click(function(){
		window.open("<?= site_url('SalesAnalytics/GetNooCsv') ?>" + "?month=" + $('#nooMonth').val() + "&year=" + $('#nooYear').val() + "&brand=" + $('#nooBrand').val(), '_blank');
	})
</script>
