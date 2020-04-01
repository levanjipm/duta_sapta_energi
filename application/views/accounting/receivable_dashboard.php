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
<script>
	function adjust_grid()
	{
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
	
	refresh_view();

	function refresh_view(date_1 = 0, date_2 = 0){
		$.ajax({
			url:'<?= site_url('Receivable/view_receivable') ?>',
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
						$('#receivable_chart').prepend("<div class='row' id='receivable-" + id + "'><div class='col-sm-3 col-xs-3 center'><p><strong>" + name + "</strong>, " + city + "</p></div><div class='col-sm-7 col-xs-6'><div class='receivable_line' id='receive-" + id + "'></div></div><div class='col-sm-2 col-xs-3 center' style='text-align:right'><p>Rp. " + numeral(receivable).format('0,0.00') + "</p></div></div><br>");
					} else {
						$('#receivable_chart').append("<div class='row' id='receivable-" + id + "'><div class='col-sm-3 col-xs-3 center'><p>" + name + ", " + city + "</p></div><div class='col-sm-7 col-xs-6'><div class='receivable_line' id='receive-" + id + "'></div></div><div class='col-sm-2 col-xs-3 center' style='text-align:right'><p>Rp. " + numeral(receivable).format('0,0.00') + "</p></div></div><br>");
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
</script>