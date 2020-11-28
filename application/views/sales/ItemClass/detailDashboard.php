<head>
	<title>Manage item classes</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Item_class') ?>'>Item classes</a> / <?= $general->name ?></p>
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
	</div>
</div>

<script>
	$(document).ready(function(){
		getItemsByClass(1);
		$('#itemButton').click();
	});

	$('#itemPage').change(function(){
		getItemsByClass();
	});

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

					$('#itemTableContent').append("<tr><td>" + reference + "</td><td>" + name + "</td><td><button class='button button_default_dark'><i class='fa fa-eye'></i></button></td></tr>");
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
</script>