<style>	
	.receivable_line{
		height:30px;
		background-color:#014886;
		border:none;
		transition:0.3s all ease;
		width:0;
		cursor:pointer;
	}
	
	.receivable_line:hover{
		background-color:#013663;
		transition:0.3s all ease;
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
</style>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> / Payable</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div id='payable_view_pane'></div>
	</div>
</div>
<script>
	refresh_view();
	
	function refresh_view(date_1 = 0, date_2 = 0){
		$.ajax({
			url:'<?= site_url('Payable/view_payable') ?>',
			data:{
				date_1:date_1,
				date_2:date_2
			},
			success:function(response){
				$('#payable_view_pane').html('');
				var max_payable		= 0;
				$.each(response, function(index,value){
					var id			= value.id;
					var name 		= value.name;
					var debt		= value.value;
					var city		= value.city;
					var paid		= value.paid;
					
					var payable		= debt - paid;
					
					if(payable > max_payable){
						max_payable = payable;
						$('#payable_view_pane').prepend("<div class='row'><div class='col-sm-3 col-xs-3 center'><p>" + name + ", " + city + "</p></div><div class='col-sm-7 col-xs-6'><div class='receivable_line' id='receive-" + id + "'></div></div><div class='col-sm-2 col-xs-3 center' style='text-align:right'><p>Rp. " + numeral(payable).format('0,0.00') + "</p></div></div><br>");
					} else {
						$('#payable_view_pane').append("<div class='row'><div class='col-sm-3 col-xs-3 center'><p>" + name + ", " + city + "</p></div><div class='col-sm-7 col-xs-6'><div class='receivable_line' id='receive-" + id + "'></div></div><div class='col-sm-2 col-xs-3 center' style='text-align:right'><p>Rp. " + numeral(payable).format('0,0.00') + "</p></div></div><br>");
					}								
				});
				
				$.each(response, function(index,value){
					var id			= value.id;
					var debt		= value.value;
					var paid		= value.paid;
					
					var payable		= debt - paid;
					var percentage	= payable * 100 / max_payable;
					$('#receive-' + id).animate({
						'width': percentage + "%"
					},300);
				});
			}
		});
	}	
</script>