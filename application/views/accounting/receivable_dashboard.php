<style>	
	.receivable_line{
		height:30px;
		background-color:#014886;
		border:none;
		transition:0.3s all ease;
		width:0;
		cursor:pointer;
		opacity:0.7;
	}
	
	.receivable_line:hover{
		background-color:#013663;
		transition:0.3s all ease;
		opacity:1;
	}
	
	.center{
		position: relative;
	}
	
	.center p{
		position:absolute;
		margin:0;
		top:50%;
		left:15px;
		transform: translate(0, -50%);
		text-align:left
	}

	#receivable_chart{
		position:relative;
		z-index:5;
	}

	#receivable_view_pane{
		position:relative;
	}
	
	#receivable_grid{
		position:absolute;
		top:0;
		left:0;
		width:100%;
		height:100%;
		padding:0;
		z-index:0;
	}
	
	.grid{
		-ms-flex-preferred-size: 100%;
		box-sizing: border-box;
		height:100%;
		border-left:1px solid black;
		position:relative;
		padding:0;
		margin:0;
	}
	
	#grid_wrapper{
		display:-webkit-box;
		display:-ms-flexbox;
		display:flex;
		opacity:0;
	}
</style>

<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> /Receivable</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='row'>
			<div class='col-xs-4'>
				<select class='form-control' id='category'>
					<option value='1'>Show all</option>
					<option value='1'>Past due date</option>
					<option value='2'>Less than 30 days</option>
					<option value='3'>30 - 45 days</option>
					<option value='4'>45 - 60 days</option>
					<option value='5'>More than 60 days</option>
				</select>
			</div>
			<div class='col-xs-12'>
				<hr>
			</div>
		</div>
		<div id='receivable_view_pane'>
			<div id='receivable_chart'></div>
			<div id='receivable_grid'>
				<div class='row' style='height:100%'>
					<div class='col-sm-7 col-xs-6 col-sm-offset-3 col-xs-offset-3' id='grid_wrapper'>
						<div class='grid' style='margin-left:0!important'></div>
						<div class='grid'></div>
						<div class='grid'></div>
						<div class='grid'></div>
						<div class='grid'></div>
						<div class='grid'></div>
						<div class='grid'></div>
						<div class='grid'></div>
						<div class='grid'></div>
						<div class='grid' style='border-right:1px solid black'></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='receivable_detail_wrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<p><strong><span id='customer_name_p'></span></strong></p>
		<p id='customer_address_p'></p>
		<p id='customer_city_p'></p>
		<hr>
		
		<table class='table table-bordered'>
			<tr>
				<th>Date</th>
				<th>Name</th>
				<th>Value</th>
				<th>Paid</th>
				<th>Balance</th>
			</tr>
			<tbody id='receivable_table'></tbody>
		</table>
	</div>
</div>
<script>
	function adjust_grid(){
		var width		= $('#grid_wrapper').width();
		var each		= (width) / 10;
		$('.grid').width(each);
		
		$('#grid_wrapper').fadeTo(500, 1);
	}
	
	function calculate_receivable(){
		if($('#date_1').val() != '' && $('#date_2').val() != '' && ($('#date_2').val() > $('#date_1').val()) && ($('#date_1').val() >= 0 && $('#date_2').val() >= 0)){
			var date_1	= $('#date_1').val();
			var date_2	= $('#date_2').val();
			refresh_view(date_1, date_2);
		};
	};
	
	$(document).ready(function(){
		refresh_view();
	});
	
	$(window).resize(function(){
		adjust_grid();
	});

	function refresh_view(date_1 = 0, date_2 = 0){
		$.ajax({
			url:'<?= site_url('Receivable/viewReceivable') ?>',
			data:{
				date_1:date_1,
				date_2:date_2
			},
			success:function(response){
				$('#receivable_chart').html('');
				var max_receivable		= 0;
				$.each(response, function(index,value){
					var id			= value.id;
					var name 		= value.name;
					var receivable	= value.value;
					var city		= value.city;
					if(receivable > max_receivable){
						max_receivable = receivable;
						$('#receivable_chart').prepend("<div class='row' id='receivable-" + id + "'><div class='col-sm-3 col-xs-3 center'><p><strong>" + name + "</strong>, " + city + "</p></div><div class='col-sm-7 col-xs-6'><div class='receivable_line' id='receive-" + id + "' onclick='view_receivable_detail(" + id + ")'></div></div><div class='col-sm-2 col-xs-3 center' style='text-align:right'><p>Rp. " + numeral(receivable).format('0,0.00') + "</p></div></div><br>");
					} else {
						$('#receivable_chart').append("<div class='row' id='receivable-" + id + "'><div class='col-sm-3 col-xs-3 center'><p>" + name + ", " + city + "</p></div><div class='col-sm-7 col-xs-6'><div class='receivable_line' id='receive-" + id + "' onclick='view_receivable_detail(" + id + ")'></div></div><div class='col-sm-2 col-xs-3 center' style='text-align:right'><p>Rp. " + numeral(receivable).format('0,0.00') + "</p></div></div><br>");
					}
				});
				
				$.each(response, function(index,value){
					var id			= value.id;
					var receivable	= value.value;
					var percentage	= receivable * 100 / max_receivable;
					$('#receive-' + id).animate({
						'width': percentage + "%"
					},300);
				});
			}
		});
		setTimeout(function(){
			adjust_grid();
		},300);
	}

	function view_receivable_detail(n){
		$.ajax({
			url:'<?= site_url('Receivable/getReceivableByCustomerId') ?>',
			data:{
				id: n
			},
			success:function(response){
				var customer = response.customer;
				var customer_name = customer.name;
				var complete_address = customer.address;
				var customer_number = customer.number;
				var customer_block = customer.block;
				var customer_rt = customer.rt;
				var customer_rw = customer.rw;
				var customer_city = customer.city;
				var customer_postal = customer.postal;
				
				if(customer_number != null){
					complete_address	+= ' No. ' + customer_number;
				}
				
				if(customer_block != null){
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
				
				$('#customer_name_p').html(customer_name);
				$('#customer_address_p').html(complete_address);
				$('#customer_city_p').html(customer_city);
				
				$('#receivable_table').html('');
				var receivable_array = response.receivable;
				$.each(receivable_array, function(index, receivable){
					var invoice_name = receivable.name;
					var todayDate = new Date().toISOString().slice(0,10);
					var date = receivable.date;
					var paid = parseFloat(receivable.paid);
					var value = parseFloat(receivable.value);
					
					var date_diff = Math.ceil((Date.parse(todayDate) - Date.parse(date))/(1000 * 60 * 60 * 24));
					
					var residue = value - paid;
					
					$('#receivable_table').append("<tr><td>" + my_date_format(date) + " (" + numeral(date_diff).format('0,0') + " days)</td><td>" + invoice_name + "</td><td>Rp. " + numeral(value).format('0,0.00') + "</td><td>Rp. " + numeral(paid).format('0,0.00') + "</td><td>Rp. " + numeral(residue).format('0,0.00') + "</td></tr>");
				});

				$('#receivable_detail_wrapper').fadeIn(300, function(){
					$('#receivable_detail_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}
	
	$('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
</script>