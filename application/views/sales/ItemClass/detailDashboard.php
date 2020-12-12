<head>
	<title>Manage item classes</title>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-line-chart'></i></a> /<a href='<?= site_url('Item_class') ?>'>Item classes</a> / <?= $general->name ?></p>
	</div>
	<br>
	<div class='dashboard_in'>
		<label><?= $general->name ?></label>
		<p><?= $general->description ?></p>
		<hr>
		<button class='button button_mini_tab' id='itemButton'>Items</button>
		<button class='button button_mini_tab' id='analyticsButton'>Analytics</button>
		<br><br>
		<div class='viewPane' id='itemView' style='display:none'>
			<div id='itemTable' style='display:none'>
				<table class='table table-bordered'>
					<tr>
						<th>Reference</th>
						<th>Name</th>
						<th>Action</th>
					</tr>
					<tbody id='itemTableContent'></tbody>
				</table>
				<select class='form-control' id='itemPage' style='width:100px'>
					<option value='1'>1</option>
				</select>
			</div>
			<p id='itemTableText'>There is no item found.</p>
		</div>
		<div class='viewPane' id='analyticsView' style='display:none'>
			<div class="row">
				<div class="col-sm-8">
					<label>Monthly output</label>
					<canvas id='lineChart' width="100" height="40"></canvas'>
				</div>
				<div class="col-sm-4">
					<label>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	var myLineChart;

	$(document).ready(function(){
		getItemsByClass(1);
		$('#itemButton').click();
	});

	$('#itemPage').change(function(){
		getItemsByClass();
	});

	$("#analyticsButton").click(function(){
		getAnalyticsByClass();
	})

	function getItemsByClass(page = $('#itemPage').val()){
		$.ajax({
			url:"<?= site_url('Item_class/getItemsById') ?>",
			data:{
				page: page,
				id: <?= $general->id ?>
			},
			success:function(response){
				var items		= response.items;
				var itemCount	= 0;
				$('#itemTableContent').html("");
				$.each(items, function(index, item){
					var reference		= item.reference;
					var name			= item.name;
					var id				= item.id;

					$('#itemTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td><button class='button button_default_dark' id='detailButton-" + id + "'><i class='fa fa-eye'></i></button></td></tr>");

					$('#detailButton-' + id).click(function(){
						window.location.href='<?= site_url('Item/viewDetail/') ?>' + reference;
					})
					itemCount++;
				});

				if(itemCount > 0){
					$('#itemTable').show();
					$('#itemTableText').hide();
				} else {
					$('#itemTable').hide();
					$('#itemTableText').show();
				}

				var pages		= response.pages;
				$('#itemPage').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#itemPage').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#itemPage').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		})
	}

	function getAnalyticsByClass(){
		$.ajax({
			url:"<?= site_url('Item_class/getAnalyticsById') ?>",
			data:{
				id: <?= $general->id ?>
			},
			success:function(response){
				var ctx = document.getElementById('lineChart').getContext('2d');
				var labelArray		= [];
				var valueArray		= [];

				var data			= response.output;
				$.each(data, function(index, value){
					labelArray.push(value.label);
					valueArray.push(value.value);
				});

				valueArray.reverse();
				labelArray.reverse();

				myLineChart = new Chart(ctx, {
					type: 'line',
					data: {
						labels: labelArray,
						datasets: [{
							backgroundColor: 'rgba(225, 155, 60, 0.4)',
							borderColor: 'rgba(225, 155, 60, 1)',
							data: valueArray,
							click:function(e){
								alert(e);
							}
						}],
					},
					options: {
						legend:{
							display:false
						}
					}
				});
			} 
		})
	}

	$("#lineChart").click(function(e) {
		var activeBars = myLineChart.getElementsAtEvent(e); 
		console.log(activeBars[0]);
	});

	$('.button_mini_tab').click(function(){
		$('.viewPane').fadeOut(300);
		$('.button_mini_tab').removeClass('active');
		$('.button_mini_tab').attr('disabled', false);

		$(this).addClass('active');
		$(this).attr('disabled', true);
	});

	$('#itemButton').click(function(){
		setTimeout(function(){
			$('#itemView').fadeIn(300);
		}, 300);
	})

	$('#analyticsButton').click(function(){
		setTimeout(function(){
			$('#analyticsView').fadeIn(300);
		}, 300);
	})
</script>