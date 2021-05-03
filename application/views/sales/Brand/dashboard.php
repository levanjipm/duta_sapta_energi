<head>
	<title>Brand</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Sales') ?>' title='Sales'><i class='fa fa-line-chart'></i></a> /Brand</p>
	</div>
	<br>
	<div class='dashboard_in'>
        <div class='input_group'>
        <input for="brand" type='text' class='form-control' id='search_bar' style='border-radius:0' placeholder="Search brand">
			<div class='input_group_append'>
				<button type='button' class='button button_default_dark' id='add_brand_button'><i class='fa fa-plus'></i> Add brand</button>
			</div>
		</div>
        <br>
        <div id='brandTable'>
            <table class="table table-bordered">
                <tr>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
                <tbody id='brandTableContent'></tbody>
            </table>

            <select class='form-control' id='page' style='width:100px'>
				<option value='1'>1</option>
			</select>
        </div>
        <p id='brandTableText'>There is no brand found.</p>
    </div>
</div>

<div class='alert_wrapper' id='add_brand_wrapper'>
    <button class='slide_alert_close_button'>&times</button>
	<div class='alert_box_slide'>
        <form id='add_brand_form'>
            <h3 style='font-family:bebasneue'>Add brand form</h3>
			<hr>
            <label>Brand name</label>
			<input type='text' class='form-control' id='brand_name' required>
            <br>

            <button class='button button_default_dark' type='button' id='submit_add_brand_button'><i class='fa fa-long-arrow-right'></i></button>
        </form>
    </div>
</div>

<div class='alert_wrapper' id='edit_brand_wrapper'>
    <button class='slide_alert_close_button'>&times</button>
	<div class='alert_box_slide'>
        <form id='add_brand_form'>
            <h3 style='font-family:bebasneue'>Edit brand form</h3>
			<hr>
            <input type='hidden' id='edit_brand_id'/>
            <label>Brand name</label>
			<input type='text' class='form-control' id='edit_brand_name' required>
            <br>

            <button class='button button_default_dark' type='button' id='submit_edit_brand_button'><i class='fa fa-long-arrow-right'></i></button>
        </form>
    </div>
</div>

<div class='alert_wrapper' id='delete_brand_wrapper'>
	<div class='alert_box_confirm_wrapper'>
		<div class='alert_box_confirm_icon'><i class='fa fa-trash'></i></div>
		<div class='alert_box_confirm'>
			<input type='hidden' id='delete_brand_id'>
			<h3>Delete confirmation</h3>
			
			<p>You are about to delete this data.</p>
			<p>Are you sure?</p>
			<button class='button button_default_dark' onclick="$('#delete_brand_wrapper').fadeOut()">Cancel</button>
			<button class='button button_danger_dark' onclick='delete_brand()'>Delete</button>
			
			<br><br>
			
			<p style='font-family:museo;background-color:#f63e21;width:100%;padding:5px;color:white;position:relative;bottom:0;left:0;opacity:0' id='error_delete_brand'>Deletation failed.</p>
		</div>
	</div>
</div>

<script>
    $('#add_brand_form').validate();

    $('#search_bar').change(function(){
        refresh_view(1);
    })

    $('#page').change(function(){
        refresh_view();
    })

    $(document).ready(function(){
        refresh_view(1);
    })

    function refresh_view(page = $('#page').val()){
        $.ajax({
            url:'<?= site_url('Brand/getItems') ?>',
            data:{
				term:$('#search_bar').val(),
				page:page
			},
			type:'GET',
			beforeSend:function(){
				$('#brandTableContent').html('');
			},
            success:function(responseData){
                if(responseData.brands.length == 0){
                    $('#brandTable').hide();
                    $('#brandTableText').show();
                } else {
                    var brands = responseData.brands;
                    brands.forEach(brand => {
                        $('#brandTableContent').append("<tr><td>" + brand.name + "</td><td><button class='button button_default_dark' onclick='viewItem(" + brand.id + ")'><i class='fa fa-eye'></i></button></button> <button class='button button_success_dark' onclick='editItem(`" + brand.id + "`,`" + brand.name + "`)'><i class='fa fa-pencil'></i></button> <button class='button button_danger_dark' onclick='deleteItem(" + brand.id + ")'><i class='fa fa-trash'></i></button></td></tr>");
                    });

                    $('#brandTable').show();
                    $('#brandTableText').hide();
                }

                $('#page').html("");
                var pages = responseData.pages;
                for(var i = 1; i <= pages; i++){
                    $('#page').append("<option value='" + i + "'>" + i + "</option>");
                };
            }
        })
    }

    $('#add_brand_button').click(function(){
        $('#add_brand_wrapper').fadeIn(300, function(){
			$('#add_brand_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
    });

    $('#submit_add_brand_button').click(function(){
        if($('#add_brand_form').valid()){
            $.ajax({
                url:"<?= site_url('Brand/insertItem') ?>",
                data:{
                    name: $('#brand_name').val()
                },
                type:"POST",
                beforeSend:function(){
                    $('button').attr('disabled', true);
                    $('#brand_name').attr('readonly', true);
                },
                success:function(responseData){
                    $('button').attr('disabled', false);
                    $('#brand_name').attr('readonly', false);

                    if(responseData != null){
                        $('#add_brand_form').trigger("reset");
                        refresh_view();
                        $('#add_brand_wrapper .slide_alert_close_button').click();
                    }
                }
            })
        }
    })

    function viewItem(id){
        window.open("<?= site_url('Brand/viewDetail/') ?>" + id);
    }

    function deleteItem(id){
        $('#delete_brand_id').val(id);
        $('#delete_brand_wrapper').fadeIn();
    }

    function delete_brand(){
        $.ajax({
            url:"<?= site_url('Brand/deleteItem') ?>",
            data:{
                id: $('#delete_brand_id').val()
            },
            type:"POST",
            beforeSend:function(){
                $('button').attr('disabled', true);
            },
            success:function(response){
                $('button').attr('disabled', false);
                if(response != null){
                    refresh_view();

                    $('#delete_brand_wrapper').fadeOut();
                } else {
                    $('#error_delete_brand').fadeIn(300);
                    setTimeout(() => {
                        $('#error_delete_brand').fadeOut(300);
                    }, 1000);
                }
            }
        })
    }

    function editItem(id, name){
        $('#edit_brand_wrapper').fadeIn(300, function(){
            $('#edit_brand_id').val(id);
            $('#edit_brand_name').val(name);

			$('#edit_brand_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
    }

    $('#submit_edit_brand_button').click(function(){
        $.ajax({
                url:"<?= site_url('Brand/editItem') ?>",
                data:{
                    name: $('#edit_brand_name').val(),
                    id: $('#edit_brand_id').val()
                },
                type:"POST",
                beforeSend:function(){
                    $('button').attr('disabled', true);
                    $('#brand_name').attr('readonly', true);
                },
                success:function(responseData){
                    $('button').attr('disabled', false);
                    $('#brand_name').attr('readonly', false);

                    if(responseData != 0){
                        $('#edit_brand_wrapper').trigger("reset");
                        refresh_view();
                        $('#edit_brand_wrapper .slide_alert_close_button').click();
                    }
                }
            })
    })
</script>