<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Purchasing') ?>' title='Purchasing'><i class='fa fa-briefcase'></i></a> /Supplier</p>
	</div>
	<div class='dashboard_in'>
		<div class='input_group'>
			<input type='text' class='form-control input-lg' id='search_bar' placeholder="Search supplier">
			<div class='input_group_append'>
				<button type='button' class='button button_default_dark' id='add_supplier_button'>Add supplier</button>
			</div>
		</div>
		<br>
		<table class='table table-bordered'>
			<tr>
				<th>Supplier name</th>
				<th>Address</th>
				<th>Action</th>
			</tr>
			<tbody id='supplier_table'></tbody>
		</table>
	<select class='form-control' id='page' style='width:100px'>
		<option value='1'>1</option>
	</select>
	</div>
</div>

<div class='alert_wrapper' id='add_supplier_wrapper'>
	<button class='slide_alert_close_button'>&times </button>
	<div class='alert_box_slide'>
		<form action='<?= site_url('Supplier/insert_new_supplier/') ?>' method='POST'>
			<h2 style='font-family:bebasneue'>Add supplier form</h2>
			<hr>
			
			<label>Supplier name</label>
			<input type='text' class='form-control' name='supplier_name' required>
			
			<label>Address</label>
			<textarea class='form-control' name='supplier_address' rows='3' style='resize:none' required></textarea>
			
			<label>Number</label>
			<input type='text' class='form-control' name='address_number'>
			
			<label>Block</label>
			<input type='text' class='form-control' name='address_block'>
			
			<label>Neighbourhood (RT)</label>
			<input type='text' class='form-control' name='address_rt' minlength='3' maxlength='3' required>
			
			<label>Hamlet (RW)</label>
			<input type='text' class='form-control' name='address_rw' minlength='3' maxlength='3' required>
			
			<label>Postal code</label>
			<input type='number' class='form-control' name='address_postal' minlength='3'>
			
			<label>City</label>
			<input type='text' class='form-control' name='supplier_city' required>
			
			<label>Phone number</label>
			<input type='text' class='form-control' name='supplier_phone' required>
			
			<label>PIC</label>
			<input type='text' class='form-control' name='supplier_pic'>
			
			<label>Tax identification number</label>
			<input type='text' class='form-control' name='supplier_npwp' id='supplier_npwp'>
			<script>
				$("#supplier_npwp").inputmask("99.999.999.9-999.999");
			</script>
			<br>
			<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='delete_confirmation_wrapper'>
	<div class='alert_box_confirm'>
		<img src='<?= base_url('assets/exclamation.png') ?>' style='width:40%'></img>
		<br><br>
		<h4 style='font-family:museo'>Are you sure?</h4>
		<br><br>
		<button class='button button_danger_dark' onclick='close_alert("delete_confirmation_wrapper")'>Not sure</button>
		<button class='button button_success_dark' onclick='confirm_delete()'>Yes</button>
		
		<input type='hidden' id='supplier_delete_id'>
	</div>
</div>

<div class='alert_wrapper' id='edit_supplier_wrapper'>
	<button class='slide_alert_close_button'>&times </button>
	<div class='alert_box_slide'>
		<form action='<?= site_url('Supplier/update_supplier/') ?>' method='POST'>
			<h2 style='font-family:bebasneue'>Add supplier form</h2>
			<hr>
			<input type='hidden' id='edit_supplier_id' name='id'>
			
			<label>Supplier name</label>
			<input type='text' class='form-control' name='supplier_name' id='edit_supplier_name' required>
			
			<label>Address</label>
			<textarea class='form-control' name='supplier_address' id='edit_supplier_address' rows='3' style='resize:none' required></textarea>
			
			<label>Number</label>
			<input type='text' class='form-control' name='address_number' id='edit_address_number'>
			
			<label>Block</label>
			<input type='text' class='form-control' name='address_block' id='edit_address_block'>
			
			<label>Neighbourhood (RT)</label>
			<input type='text' class='form-control' name='address_rt' id='edit_address_rt' minlength='3' maxlength='3' required>
			
			<label>Hamlet (RW)</label>
			<input type='text' class='form-control' name='address_rw' id='edit_address_rw' minlength='3' maxlength='3' required>
			
			<label>Postal code</label>
			<input type='number' class='form-control' name='address_postal' id='edit_address_postal' minlength='3'>
			
			<label>City</label>
			<input type='text' class='form-control' name='supplier_city' id='edit_supplier_city' required>
			
			<label>Phone number</label>
			<input type='text' class='form-control' name='supplier_phone' id='edit_supplier_phone' required>
			
			<label>PIC</label>
			<input type='text' class='form-control' name='supplier_pic' id='edit_supplier_pic'>
			
			<label>Tax identification number</label>
			<input type='text' class='form-control' name='supplier_npwp' id='edit_supplier_npwp'>
			<script>
				$("#edit_supplier_npwp").inputmask("99.999.999.9-999.999");
			</script>
			<br>
			<button class='button button_default_dark'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<script>
	refresh_view();
	
	$('#add_supplier_button').click(function(){
		$('#add_supplier_wrapper').fadeIn(300, function(){
			$('#add_supplier_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
	});
	
	function open_delete_confirmation(n){
		$('#delete_confirmation_wrapper').fadeIn();
		$('#supplier_delete_id').val(n);
	};
	
	function confirm_delete(){
		$.ajax({
			url:'<?= site_url('supplier/delete_supplier') ?>',
			type:'POST',
			data:{
				supplier_id: $('#supplier_delete_id').val()
			},
			beforeSend:function(){
				$('button').attr('disabled',true);
			},
			success:function(){
				window.location.reload();
			}
		})
	};
	
	function open_edit_form(n){
		$.ajax({
			url:'<?= site_url('supplier/select_by_id') ?>',
			data:{
				id: n
			},
			success:function(response){
				var id				= response.id;
				var name			= response.name;
				var rt				= response.rt;
				var rw				= response.rw;
				var city			= response.city;
				var postal_code		= response.postal_code;
				var block			= response.block;
				var npwp			= response.npwp;
				var number			= response.number;
				var address			= response.address;
				var phone			= response.phone_number;
				var pic_name		= response.pic_name;
				
				$('#edit_address_block').val(block);
				$('#edit_address_number').val(number);
				$('#edit_address_postal').val(postal_code);
				$('#edit_address_rt').val(rt);
				$('#edit_address_rw').val(rw);
				$('#edit_supplier_address').val(address);
				$('#edit_supplier_city').val(city);
				$('#edit_supplier_id').val(id);
				$('#edit_supplier_name').val(name);
				$('#edit_supplier_npwp').val(npwp);
				$('#edit_supplier_phone').val(phone);
				$('#edit_supplier_pic').val(pic_name);
				
				$('#edit_supplier_wrapper').fadeIn(300, function(){
					$('#edit_supplier_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	};
	
	$('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	});
	
	function refresh_view(page = $('#page').val())
	{
		$.ajax({		
			url:'<?= site_url('Supplier/view_items') ?>',
			data:{
				page: page,
				term: $('#search_bar').val()
			},
			success:function(response){
				var pages		= response.pages;
				$('#page').html('');
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
				
				var items		= response.suppliers;
				$.each(items, function(index, item){
					var complete_address		= '';
					var supplier_name			= item.name;
					complete_address			+= item.address;
					var supplier_city			= item.city;
					var supplier_number			= item.number;
					var supplier_rt				= item.rt;
					var supplier_rw				= item.rw;
					var supplier_postal			= item.postal_code;
					var supplier_block			= item.block;
					var supplier_id				= item.id;
			
					if(supplier_number != null){
						complete_address	+= ' No. ' + supplier_number;
					}
					
					if(supplier_block != null){
						complete_address	+= ' Blok ' + supplier_block;
					}
					
					if(supplier_rt != '000'){
						complete_address	+= ' RT ' + supplier_rt;
					}
					
					if(supplier_rw != '000' && supplier_rt != '000'){
						complete_address	+= ' /RW ' + supplier_rw;
					}
					
					if(supplier_postal != null){
						complete_address	+= ', ' + supplier_postal;
					}
					
					$('#supplier_table').append("<tr><td>" + supplier_name + "</td><td><p>" + complete_address + "</p><p>" + supplier_city + "</p></td><td><button type='button' class='button button_success_dark' onclick='open_edit_form(" + supplier_id + ")'><i class='fa fa-pencil'></i></button> <button type='button' class='button button_danger_dark' onclick='open_delete_confirmation(" + supplier_id + ")'><i class='fa fa-trash'></i></button> <button type='button' class='button button_default_dark'><i class='fa fa-eye'></i></button></td></tr>");
				});
			}
		});
	}
</script>