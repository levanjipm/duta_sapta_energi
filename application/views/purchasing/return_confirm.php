<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Purchasing') ?>' title='Purchasing'><i class='fa fa-briefcase'></i></a> / Return / Create</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<table class='table table-bordered'>
			<tr>
				<th>Supplier</th>
				<th>Detail</th>
				<th>Action</th>
			</tr>
			<tbody id='return_table'></tbody>
		</table>
		
		<select class='form-control' id='page' style='width:100px'>
			<option value='1'>1</option>
		</select>
	</div>
</div>
<script>
	refresh_view();
	
	$('#page').change(function(){
		refresh_view();
	});
	
	function refresh_view(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Item_return/view_purchase_return') ?>',
			data:{
				page:page,
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
				
				var items					= response.items;
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
					
					var created_by			= item.created_by;
					var created_date		= item.created_date;
					var invoice_reference	= item.invoice_reference;
					var return_id			= item.id;
					
					$('#return_table').append("<tr><td><label>" + supplier_name + "</label><p>" + complete_address + "</p><p>" + supplier_city + "</p></td><td><label>Created by</label><p>" + created_by + "</p><p>" + my_date_format(created_date) + "</p><label>Invoice reference</label><p>" + invoice_reference + "</p></td><td><button type='button' class='button button_default_dark' onclick='view(" + return_id + ")'><i class='fa fa-long-arrow-right'></i></button></td></tr>");
				});
			}
		});
	}
</script>