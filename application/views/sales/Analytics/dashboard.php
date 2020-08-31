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
	</style>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sale') ?>' title='Inventory'><i class='fa fa-briefcase'></i></a> /Analytics</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<button class='button button_mini_tab' id='valueButton'>Value Analytic</button>
		<button class='button button_mini_tab' id='customerButton'>Customer Analytic</button>
		<hr>
		<div class='row' id='valueViewPane' style='display:none'>
			<div class='col-md-2 col-sm-3 col-xs-4' id='valueSidePane'>
				<br>
				<label style='padding-left:5px'>Category</label><hr>
				<button id='sales'><p>Salesman</p></button><br>
				<button id='area'><p>Area</p></button><br>
				<button id='type'><p>Type</p></button><br>
				<button id='customer'><p>Customer</p></button><br>
			</div>
			<div class='col-md-10 col-sm-9 col-xs-8'>
				<div class='input_group'>
					<input type='number' class='form-control' id='month' placeholder='Month'>
					<input type='number' class='form-control' id='year' placeholder='Year'>
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
		</div>
    </div>
</div>

<script>
	$('#valueButton').click(function(){
		$('.button_mini_tab').attr('disabled', false);
		$('.button_mini_tab').removeClass('active');

		$('#valueButton').attr('disabled', true);
		$('#valueButton').addClass('active');

		$('#customerViewPane').fadeOut(250, function(){
			$('#valueViewPane').fadeIn(250);
		})
	})
	var aspect;

	$('#valueSidePane button').click(function(){
		$('#valueSidePane button').attr('disabled', false);
		$('#valueSidePane button').removeClass('active');

		$(this).addClass('active');
		$(this).attr('disabled', true);

		$('#month').val("");
		$('#year').val("");
		$('#valueAnalyticTable').hide();
		$('#tableAnalyticBody').html("");
		var id = $(this).attr('id');
		aspect = id;
		if(id == "sales"){
			$('#tableAnalyticHeader').html("<tr><th>Salesman</th><th>Value</th></tr>");
		} else if(id == "area"){
			$('#tableAnalyticHeader').html("<tr><th>Area</th><th>Value</th></tr>");
		} else if(id == "type") {
			$('#tableAnalyticHeader').html("<tr><th>Type</th><th>Value</th></tr>");
		} else if(id == "customer"){
			$('#tableAnalyticHeader').html("<tr><th>Customer</th><th>Value</th></tr>");
		}

		$('#month').focus();
	});

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
					if(aspect == "sales"){
						var itemCount = 0;
						var totalValue = 0;
						$.each(response, function(index, item){
							var id = item.id;
							var salesman = (item.name == null)? "Office" : item.name;
							var value = parseFloat(item.value);
							var returnValue = parseFloat(item.returned)
							if(item.image_url == null){
								var imageUrl = "<?= base_url() . '/assets/ProfileImages/defaultImage.png' ?>";
							} else {
								var imageUrl = "<?= base_url() . '/assets/ProfileImages/' ?>" + item.image_url;
							}

							$('#tableAnalyticBody').append("<tr><td><img src='" + imageUrl + "' style='width:30px;height:30px;border-radius:50%'> " + salesman + "</td><td>Rp. " + numeral(value - returnValue).format("0,0.00") + "<div class='progressBarWrapper'><p></p><div class='progressBar' data-value='" + (value - returnValue) + "'></div></div></td></tr>");
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

						if(itemCount > 0){
							$('#valueAnalyticTable').fadeIn();
							$('#valueAnalyticTableText').hide();
						} else {
							$('#valueAnalyticTableText').hide();
							$('#valueAnalyticTable').fadeIn();
						}
						
					}
				}
			})
		}
	}
</script>
