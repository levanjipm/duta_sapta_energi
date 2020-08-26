<head>
	<title>Asset class</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-briefcase'></i></a> /<a href='<?= site_url('Asset') ?>'>Asset</a> / Class</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<input type='text' class='form-control' id='search_bar'>
			<div class='input_group_append'>
				<button type='button' class='button button_default_dark' id='addAssetFormButton'><i class='fa fa-plus'></i> Add class</button>
			</div>
		</div>
		<br>
		<div id='assetTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Name</th>
					<th>Description</th>
					<th>Action</th>
				</tr>
				<tbody id='assetTableContent'></tbody>
			</table>
			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='assetTableText'>There is no asset class found.</p>
	</div>
</div>

<div class='alert_wrapper' id='addAssetWrapper'>
	<button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Add new asset form</h3>
		<hr>
		<form id='addAssetForm'>
			<label>Name</label>
			<input type='text' class='form-control' name='name' required>
			
			<label>Description</label>
			<textarea class='form-control' name='description' required minlength='25'></textarea>
			<br>
			<button type='button' id='addAssetButton' class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
			<div class='notificationText danger' id='insertItemFailedNotification'><p>Failed to insert item.</p></div>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='editAssetWrapper'>
	<button type='button' class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Edit asset form</h3>
		<hr>
		<form id='editAssetForm'>
			<input type='hidden' id='edit_id' name='id'>
			
			<label>Name</label>
			<input type='text' class='form-control' name='name' id='name_edit' required>
			
			<label>Description</label>
			<textarea class='form-control' name='description' id='description_edit' required minlength='25' rows='3' style='resize:none'></textarea>
			<br>
			<button type='button' id='updateAssetButton' class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button><br>

			<div class='notificationText danger' id='updateItemFailedNotification'><p>Failed to update item.</p></div>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='deleteClassWrapper'>
	<div class='alert_box_confirm_wrapper'>
		<div class='alert_box_confirm_icon'><i class='fa fa-trash'></i></div>
		<div class='alert_box_confirm'>
			<input type='hidden' id='deleteClassId'>
			<h3>Delete confirmation</h3>
			
			<p>You are about to delete this data.</p>
			<p>Are you sure?</p>
			<button class='button button_default_dark' onclick="$('#deleteClassWrapper').fadeOut()">Cancel</button>
			<button class='button button_danger_dark' onclick='deleteClass()'>Delete</button>
			
			<br><br>
			
			<p style='font-family:museo;background-color:#f63e21;width:100%;padding:5px;color:white;position:relative;bottom:0;left:0;opacity:0' id='errorDeleteClass'>Deletation failed.</p>
		</div>
	</div>
</div>

<script>
	$('#addAssetForm').validate();
	$('#editAssetForm').validate();
	
	$(document).ready(function(){
		refresh_view();
	})
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Asset/show_type_limited') ?>',
			data:{
				page:page,
				term:$('#search_bar').val()
			},
			success:function(response){
				var assetCount = 0;
				$('#assetTableContent').html('');
				var types	= response.types;
				$.each(types, function(index, type){
					var name		= type.name;
					var description	= type.description;
					var id			= type.id;
					
					$('#assetTableContent').append("<tr><td>" + name + "</td><td>" + description + "</td><td><button type='button' class='button button_success_dark' onclick='viewClass(" + id + ")'><i class='fa fa-pencil'></i></button> <button class='button button_danger_dark' onclick='confirmDelete(" + id + ")'><i class='fa fa-trash'></i></button></tr>");
					assetCount++;
				});

				if(assetCount > 0){
					$('#assetTableText').hide();
					$('#assetTable').show();
				} else {
					$('#assetTableText').show();
					$('#assetTable').hide();
				}
				
				$('#page').html('');
				
				var pages		= response.pages;
				for(i = 1; i <= pages; i ++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		});
	}
	
	function viewClass(n){
		$.ajax({
			url:'<?= site_url('Asset/getClassById') ?>',
			data:{
				id:n
			},
			success:function(response){
				var id		= response.id;
				var name	= response.name;
				var description	= response.description;

				$('#name_edit').val(name);
				$('#description_edit').val(description);
				$('#edit_id').val(id);
				
				$('#editAssetWrapper').fadeIn(300, function(){
					$('#editAssetWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		});
	}
	
	$('#addAssetFormButton').click(function(){
		$('#addAssetWrapper').fadeIn(300, function(){
			$('#addAssetWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	});

	$('#page').change(function(){
		refresh_view();
	});
	
	$('#search_bar').change(function(){
		refresh_view(1);
	});

	$("#addAssetButton").click(function(){
		if($('#addAssetForm').valid()){
			$.ajax({
				url:'<?= site_url("Asset/insertClassItem") ?>',
				data:$('#addAssetForm').serialize(),
				type:"POST",
				beforeSend:function(){
					$('button').attr('disabled', true);
				},
				success:function(response){
					$("button").attr('disabled', false);
					refresh_view();
					if(response == 1){
						$("#addAssetForm").trigger("reset");
						$('#addAssetWrapper .slide_alert_close_button').click();
					} else {
						$("#insertItemFailedNotification").fadeIn(250);
						setTimeout(function(){
							$('#insertItemFailedNotification').fadeOut(250);
						}, 1000)
					}
				}
			})
		}
	})

	function confirmDelete(n){
		$('#deleteClassId').val(n);
		$('#deleteClassWrapper').fadeIn();
	}

	function deleteClass(){
		$.ajax({
			url:'<?= site_url('Asset/deleteClassById') ?>',
			data:{
				id: $('#deleteClassId').val()
			},
			type:"POST",
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('buton').attr('disabled', false);
				refresh_view();
				if(response == 1){
					$('#deleteClassWrapper').fadeOut(250);
				} else {
					$('#errorDeleteClass').fadeTo(250, 1);
					setTimeout(function(){
						$('#errorDeleteClass').fadeTo(250, 0);
					})
				}
			}
		})
	}

	$('#updateAssetButton').click(function(){
		if($('#editAssetForm').valid()){
			$.ajax({
				url:'<?= site_url("Asset/updateClassById") ?>',
				data:$("#editAssetForm").serialize(),
				type:'POST',
				beforeSend:function(){
					$('button').attr('disabled', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					refresh_view();
					if(response == 1){
						$('#editAssetWrapper .slide_alert_close_button').click();
					} else {
						$('#updateItemFailedNotification').fadeIn(250);
						setTimeout(function(){
							$('#updateItemFailedNotification').fadeOut(250);
						}, 1000)
					}
				}
			})
		}
	})

	
</script>
