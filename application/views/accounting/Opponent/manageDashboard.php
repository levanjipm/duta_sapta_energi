<head>
	<title>Opponent</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Sales'><i class='fa fa-bar-chart'></i></a> /Opponent</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<div class='input_group'>
			<input type='text' class='form-control' id='search_bar'>
			<div class='input_group_append'>
				<button class='button button_default_dark' id='addOpponentButton'><i class='fa fa-plus'></i> Add new</button>
			</div>
		</div>
		<br>
		<div id='opponentTable'>
			<table class='table table-bordered'>	
				<tr>
					<th>Name</th>
					<th>Description</th>
                    <th>Type</th>
					<th>Action</th>
				</tr>
				<tbody id='opponentTableContent'></tbody>
			</table>

			<select class='form-control' id='page' style='width:100px'>
				<option value='1'>1<option>
			</select>
		</div>
		<p id='opponentTableText'>There is no opponent found.</p>
	</div>
</div>

<div class='alert_wrapper' id='addOpponentWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Add Opponent</h3>
		<hr>
		<form id='opponentForm'>
			<label>Name</label>
			<input type='text' class='form-control' name='name' required>

			<label>Description</label>
			<textarea class='form-control' rows='3' style='resize:none' name='description' required minlength='25'></textarea><br>

            <label>Type</label>
			<button type='button' class='form-control' id='opponentTypeButton' style='text-align:left!important'></button>
			<input type='hidden' id='opponentType' name='type' required>

			<button type='button' class='button button_default_dark' id='submitOpponentButton'><i class='fa fa-long-arrow-right'></i></button>

			<div class='notificationText danger' id='failedInsertNotification'><p>Failed to insert data.</p></div>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='opponentTypeWrapper'>
	<div class='alert_box_full'>
		<button class='button alert_full_close_button'>&times;</button>
		<h3 style='font-family:bebasneue'>Select Opponent Type</h3>
		<hr>
		<input type='text' class='form-control' id='typeSearchBar'><br>
		<div id='opponentTypeTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Name</th>
					<th>Description</th>
					<th>Action</th>
				</tr>
				<tbody id='opponentTypeTableContent'></tbody>
			</table>

			<select class='form-control' id='opponentTypePage' style='width:100px'>
				<option value='1'>1</option>
			</select>
		</div>
		<p id='opponentTypeTableText'>There is no opponent type found.</p>
	</div>
</div>

<div class='alert_wrapper' id='updateOpponentWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Update Opponent Type</h3>
		<hr>
		<form id='updateOpponentForm'>
			<input type='hidden' id='editId' name='id'>
			<label>Name</label>
			<input type='text' class='form-control' id='editName' name='name' required>

			<label>Description</label>
			<textarea class='form-control' rows='3' style='resize:none' id='editDescription' name='description' required minlength='25'></textarea><br>

            <label>Type</label>
            <select class='form-control' name='type' id='editOpponentType'>
            </select>

			<button type='button' class='button button_default_dark' id='submitUpdateOpponentTypeButton'><i class='fa fa-long-arrow-right'></i></button>

			<div class='notificationText danger' id='failedUpdateNotification'><p>Failed to insert data.</p></div>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='delete_opponent_wrapper'>
	<div class='alert_box_confirm_wrapper'>
		<div class='alert_box_confirm_icon'><i class='fa fa-trash'></i></div>
		<div class='alert_box_confirm'>
			<input type='hidden' id='delete_opponent_id'>
			<h3>Delete confirmation</h3>
			
			<p>You are about to delete this data.</p>
			<p>Are you sure?</p>
			<button class='button button_default_dark' onclick="$('#delete_opponent_wrapper').fadeOut()">Cancel</button>
			<button class='button button_danger_dark' onclick='delete_opponent()'>Delete</button>
			
			<br><br>
			
			<p style='font-family:museo;background-color:#f63e21;width:100%;padding:5px;color:white;position:relative;bottom:0;left:0;opacity:0' id='error_delete_opponent'>Deletation failed.</p>
		</div>
	</div>
</div>

<script>
	$('#opponentForm').validate();
	$('#updateOpponentForm').validate();

	$(document).ready(function(){
        refreshView();
	});

	$('#search_bar').change(function(){
		refreshView(1);
	})

	$('#page').change(function(){
		refreshView();
    });

	$("#opponentTypeButton").click(function(){
		$('#typeSearchBar').val("");
		refreshOpponentType(1);
		$('#opponentTypeWrapper').fadeIn();
	});

	$('#typeSearchBar').change(function(){
		refreshOpponentType(1);
	});

	$('#opponentTypePage').change(function(){
		refreshOpponentType();
	});
    
    function refreshOpponentType(page = $('#opponentTypePage').val()){
        $.ajax({
            url:"<?= site_url('Opponent_type/getItems') ?>",
			data:{
				page: page,
				term: $('#typeSearchBar').val()
			},
            success:function(response){
                var items = response.items;
				var itemCount = 0;
				$('#opponentTypeTableContent').html("");
				$.each(items, function(index, item){
					var id = item.id;
					var name = item.name;
					var description = item.description;

					$('#opponentTypeTableContent').append("<tr><td>" + name + "</td><td>" + description + "</td><td><button class='button button_default_dark' type='button' id='selectOpponentTypeButton-" + id + "'><i class='fa fa-long-arrow-right'></i></button>");
					$('#selectOpponentTypeButton-' + id).click(function(){
						$('#opponentTypeButton').html(name);
						$('#opponentType').val(id);
						$('#opponentTypeWrapper .alert_full_close_button').click();
					});
					itemCount++;
				});

				var pages = response.pages;
				$('#opponentTypePage').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#opponentTypePage').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#opponentTypePage').append("<option value='" + i + "'>" + i + "</option>");
					}
				}

				if(itemCount > 0){
					$('#opponentTypeTable').show();
					$('#opponentTypeTableText').hide();
				} else {
					$('#opponentTypeTable').hide();
					$('#opponentTypeTableText').show();
				}
            }
        })
    }

	$('.alert_full_close_button').click(function(){
		$(this).parent().parent().fadeOut();
	})

	$('#addOpponentButton').click(function(){
        $('#opponentForm').trigger("reset");
		$('#addOpponentWrapper').fadeIn(300, function(){
			$('#addOpponentWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	});	

	$('#submitOpponentButton').click(function(){
		if($('#opponentForm').valid()){
			$.ajax({
				url:"<?= site_url('Opponent/insertItem') ?>",
				data:$('#opponentForm').serialize(),
				type:'POST',
				beforeSend:function(){
					$('button').attr('disabled', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					refreshView();
					if(response == 0){
						$('#failedInsertNotification').fadeIn(250);
						setTimeout(function(){
							$('#failedInsertNotification').fadeOut(250);
						}, 1000)
					} else {
						$('#addOpponentWrapper .slide_alert_close_button').click();
					}
				}
			})
		}
	});

	function refreshView(page = $('#page').val()){
		var typeCount = 0;
		$.ajax({
			url:'<?= site_url('Opponent/getItems') ?>',
			data:{
				page: page,
				term: $('#search_bar').val()
            },
			success:function(response){
				var items = response.items;
				$('#opponentTableContent').html("");
				$.each(items, function(index, item){
					var name = item.name;
                    var description = item.description;
                    var type = item.type;
                    var id = item.id;
					
					$('#opponentTableContent').append("<tr><td>" + name + "</td><td>" + description + "</td><td>" + type + "</td><td><button class='button button_success_dark' onclick='viewOpponent(" + id + ")'><i class='fa fa-pencil'></i></button> <button class='button button_danger_dark' onclick='confirmDelete(" + id + ")'><i class='fa fa-trash'></i></button></td></tr>");
					typeCount++;
				})

				if(typeCount > 0){
					$('#opponentTable').show();
					$('#opponentTableText').hide();
				} else {
					$('#opponentTable').hide();
					$('#opponentTableText').show();
				}

				var pages = response.pages;
				$('#page').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>")
					}
				}
			}
		})
	}

	function confirmDelete(id){
		$('#delete_opponent_id').val(id);
		$('#delete_opponent_wrapper').fadeIn();
	};
	
	function delete_opponent(){
		$.ajax({
			url:"<?= site_url('Opponent/deleteById') ?>",
			data:{
				id: $('#delete_opponent_id').val()
			},
			type:'POST',
			beforeSend:function(){
				$('button').attr('disabled', true);
			},
			success:function(response){
				$('button').attr('disabled', false);
				if(response == 1){
					refreshView();
					$('#delete_opponent_wrapper').fadeOut();
				} else {
					$('#error_delete_opponent').fadeTo(250, 1);
					setTimeout(function(){
						$('#error_delete_opponent').fadeTo(250, 0);
					}, 1000);
				}
			}
		});
	}

	function viewOpponent(id){
		$('#editId').val(id);
		$.ajax({
			url:'<?= site_url('Opponent/getById') ?>',
			data:{
				id: id
            },
            beforeSend:function(){
                getOpponentType("editOpponentType")
            },
			success:function(response){
				var name = response.name;
                var description = response.description;
                var type = response.type;
				
				$("#editName").val(name);
                $("#editDescription").val(description);
                $('#editOpponentType').val(type);

				$('#updateOpponentWrapper').fadeIn(300, function(){
					$('#updateOpponentWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}

	$('#submitUpdateOpponentTypeButton').click(function(){
		if($('#updateOpponentForm').valid()){
			$.ajax({
				url:'<?= site_url('Opponent/updateById') ?>',
				data:$('#updateOpponentForm').serialize(),
				type:'POST',
				beforeSend:function(){
					$('button').attr('disabled', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					refreshView();
					if(response == 1){
						$('#updateOpponentWrapper .slide_alert_close_button').click();
					} else {
						$('#failedUpdateNotification').fadeIn(250);
						setTimeout(function(){
							$('#failedUpdateNotification').fadeOut(250);
						}, 1000);
					}
				}
			})
		}
	});
</script>
