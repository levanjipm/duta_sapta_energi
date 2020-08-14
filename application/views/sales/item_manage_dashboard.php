<head>
	<title>Manage items</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /Items</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<input type='text' class='form-control' id='search_bar'>
			<div class='input_group_append'>
				<button type='button' class='button button_default_dark' id='add_item_button'><i class='fa fa-plus'></i> Add item</button>
			</div>
		</div>
		<br>
		<div id='itemTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Reference</th>
					<th>Name</th>
					<th>Action</th>
				</tr>
				<tbody id='itemTableContent'></tbody>
			</table>
			<select class='form-control' id='page' style='width:100px'>
				<option value='1' selected>1</option>
			</select>
		</div>

		<p id='itemTableText'>There is no item found.</p>
	</div>
</div>

<div class='alert_wrapper' id='addItemWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<form id='addItemForm'>
			<h3 style='font-family:bebasneue'>Add item form</h3>
			<hr>		
			<label>Reference</label>
			<input type='text' class='form-control' id='itemReference' name='itemReference' required>
			
			<label>Name</label>
			<textarea class='form-control' id='itemName' name='itemName' rows='3' style='resize:none' required></textarea>
			
			<label>Type</label>
			<select class='form-control' id='itemClass' name='itemClass'>
<?php
	foreach($classes as $class){
?>
				<option value='<?= $class->id ?>'><?= $class->name ?></option>
<?php
	}
?>
			</select>
			
			<label>Pricelist</label>
			<input type='number' class='form-control' id='priceList' name='priceList' required min='0'>

			<label>Confidence Level</label>
			<input type='number' class='form-control' min='0' max='100' required id='confidenceLevel' name='confidenceLevel'>
			<br>
			<button type='button' class='button button_default_dark' id='submitAddItemButton'><i class='fa fa-long-arrow-right'></i></button>
			<div class='notificationText danger' id='failedInsertItem'>Failed to insert item.</p></div>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='deleteItemWrapper'>
	<div class='alert_box_confirm_wrapper'>
		<div class='alert_box_confirm_icon'><i class='fa fa-trash'></i></div>
		<div class='alert_box_confirm'>
			<input type='hidden' id='delete_item_id'>
			<h3>Delete confirmation</h3>
			
			<p>You are about to delete this data.</p>
			<p>Are you sure?</p>
			<button class='button button_default_dark' onclick="$('#deleteItemWrapper').fadeOut()">Cancel</button>
			<button class='button button_danger_dark' onclick='delete_item()'>Delete</button>
			
			<br><br>
			
			<p style='font-family:museo;background-color:#f63e21;width:100%;padding:5px;color:white;position:relative;bottom:0;left:0;opacity:0' id='error_delete_item'>Deletation failed.</p>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='editItemWrapper'>
	<button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<form id='updateItemForm'>
			<h3 style='font-family:bebasneue'>Edit item</h3>
			<hr>
			<input type='hidden' id='idEdit' name='id'>
			<label>Reference</label>
			<input type='text' class='form-control' id='referenceEdit' name='referenceEdit' required>
			
			<label>Description</label>
			<textarea class='form-control' id='descriptionEdit' name='descriptionEdit' required></textarea>
			
			<label>Price list</label>
			<input type='number' class='form-control' id='priceListEdit' name='priceListEdit' min='1'>
			
			<label>Type</label>
			<select class='form-control' id='typeEdit' name='typeEdit' required>
<?php
	foreach($classes as $class){
?>
				<option value='<?= $class->id ?>'><?= $class->name ?></option>
<?php
	}
?>
			</select>
			
			<input type='checkbox' id='isNotifiedEdit'>
			<label>Notify if stock reaches minimum</label>
			
			<br>
			
			<label>Confidence level</label>
			<input type='number' class='form-control' min='0' max='100' id='confidenceLevelEdit'>
			<br>
			<button type='button' class='button button_default_dark' type='button' id='submitEditButton'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<script>
	$(document).ready(function(){
		refresh_view();
	});
	
	$('#page').change(function(){
		refresh_view();
	});
	
	$('#search_bar').change(function(){
		refresh_view(1);
	});

	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Item/showItems') ?>',
			data:{
				term:$('#search_bar').val(),
				page:page
			},
			type:'GET',
			beforeSend:function(){
				$('#itemTableContent').html('');
			},
			success:function(response){
				var itemCount = 0;
				var item_array	= response.items;
				$.each(item_array, function(index, item){
					var reference	= item.reference;
					var description	= item.name;
					var id			= item.item_id;
					$('#itemTableContent').append("<tr><td>" + reference + "</td><td>" + description + "</td><td><button type='button' class='button button_success_dark' onclick='viewItem(" + id + ")' title='Edit " + reference + "'><i class='fa fa-pencil'></i></button> <button type='button' class='button button_danger_dark' onclick='confirm_delete(" + id + ")' title='Delete " + reference + "'><i class='fa fa-trash'></i></button> <button type='button' class='button button_default_dark' title='View " + reference + "'><i class='fa fa-eye'></i></button>");
					itemCount++;
				});

				if(itemCount > 0){
					$('#itemTableText').hide();
					$('#itemTable').show();
				} else {
					$('#itemTableText').show();
					$('#itemTable').hide();
				}
				
				
				$('#page').html('');
				var pages		= response.pages;
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			},
			
		});
	};

	$('#add_item_button').click(function(){
		$('#addItemWrapper').fadeIn(300, function(){
			$('#addItemWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	});

	$('#addItemForm').validate();

	$('#addItemForm input').on('keypress', function(e) {
		return e.which !== 13;
	});

	$('#submitAddItemButton').click(function(){
		if($('#addItemForm').valid()){
			if($('#isNotifiedEdit').attr('checked') == true){
				var isNotified = 1;
			} else {
				var isNotified = 0;
			}
			$.ajax({
				url:'<?= site_url('Item/insertItem') ?>',
				data:{
					name: $('#itemName').val(),
					reference: $('#itemReference').val(),
					class: $('#itemClass').val(),
					notify: isNotified,
					priceList: $('#priceList').val(),
					confidenceLevel: $('#confidenceLevel').val()
				},
				type:'POST',
				beforeSend:function(){
					$("button").attr('disabled', true);
				},
				success:function(response){
					$("button").attr('disabled', false);
					refresh_view();
					if(response == 1){
						$('#addItemWrapper .slide_alert_close_button').click();
						$('#addItemForm').trigger("reset");
					} else {
						$('#failedInsertItem').fadeIn(250);
						setTimeout(function(){
							$('#failedInsertItem').fadeOut(250);
						}, 1000)	
					}
				}
			})
		}
	})

	$('#updateItemForm').validate();
	
	$('#updateItemForm input').on('keypress', function(e) {
		return e.which !== 13;
	});
	
	$('#submitEditButton').click(function(){
		if($('#updateItemForm').valid()){
			if($('#isNotifiedEdit').attr('checked', true)){
				var isNotified = 1;
			} else {
				var isNotified = 0;
			}

			$.ajax({
				url:'<?= site_url('Item/updateById') ?>',
				data:{
					id: $('#idEdit').val(),
					reference: $('#referenceEdit').val(),
					name: $('#descriptionEdit').val(),
					priceList: $('#priceListEdit').val(),
					type: $('#typeEdit').val(),
					isNotified: isNotified,
					confidenceLevel: $('#confidenceLevelEdit').val()					
				},
				type:'POST',
				success:function(response){
					refresh_view();
					$('#editItemWrapper .slide_alert_close_button').click();
				}
			});
		};
	});
	
	function viewItem(item_id){
		$.ajax({
			url:'<?= site_url('Item/showById') ?>',
			type:'GET',
			data:{
				id: item_id
			},
			beforeSend:function(){
				$('button').attr('disabled',true);
			},
			success:function(response){
				$('button').attr('disabled',false);
				$('#idEdit').val(item_id);
				var reference				= response.reference;
				var name					= response.name;
				var price_list				= response.price_list;
				var type					= response.type;
				var confidenceLevelEdit 	= response.confidence_level;
				var isNotifiedEdit_stock 	= response.is_notified_stock;
				
				if(isNotifiedEdit_stock == 1){
					$('#isNotifiedEdit').attr("checked", true);
				} else {
					$('#isNotifiedEdit').attr('checked', false);
				}
				
				$('#confidenceLevelEdit').val(confidenceLevelEdit);
				
				$('#referenceEdit').val(reference);
				$('#descriptionEdit').val(name);
				$('#priceListEdit').val(price_list);
				$('#typeEdit').val(type);
				
				$('#editItemWrapper').fadeIn(300, function(){
					$('#editItemWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	};
	
	
	
	function confirm_delete(n){
		$('#delete_item_id').val(n);
		$('#deleteItemWrapper').fadeIn();
	}
	
	function delete_item(){
		$.ajax({
			url:'<?= site_url('Item/deleteById') ?>',
			data:{
				id: $('#delete_item_id').val()
			},
			type:'POST',
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				if(response == 1){
					refresh_view();
					$('#deleteItemWrapper').fadeOut();
				} else {
					$('#error_delete_item').fadeTo(250, 1);
					setTimeout(function(){
						$('#error_delete_item').fadeTo(250, 0);
					}, 1000);
				}
			}
		});
	}
</script>