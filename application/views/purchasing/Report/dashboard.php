<head>
    <title>Purchasing - Report</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Purchasing') ?>' title='Sales'><i class='fa fa-briefcase'></i></a> /Report</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<form action='<?= site_url('Purchase_report/getReport') ?>' id='reportForm' method="POST">
			<label>Period</label>
			<div class='input_group'>
				<select class='form-control' name='month'>
					<option value='0'>Select all year</option>
<?php for($i = 1; $i <= 12; $i++){ ?>
					<option value='<?= $i ?>' <?= ($i == date('m')) ? "selected" : "" ?>><?= date('F', mktime(0,0,1, $i)) ?></option>
<?php } ?>
				</select>
				<select class='form-control' name='year'>
<?php for($i = 2020; $i <= date('Y'); $i++){ ?>
					<option value='<?= $i ?>' <?= ($i == date('Y')) ? "selected" : "" ?>><?= $i ?></option>
<?php } ?>
				</select>
			</div>

			<label>Supplier</label>
			<input type='text' class='form-control' id='searchBar'><br>
		
			<div id='supplierTable'>
				<table class='table table-bordered'>
					<tr>
						<th>Name</th>
						<th>Information</th>
						<th>Action</th>
					</tr>
					<tbody id='supplierTableContent'></tbody>
				</table>

				<select class='form-control' id='page' style='width:100px'>
					<option value='1'>1</option>
				</select>
			</div>
			<p id='supplierTableText'>There is no supplier found.</p>

			<input type='hidden' name='supplierId' id='supplierId'>
		</form>
	</div>
</div>
<script>
	$(document).ready(function(){
		refreshSupplierView();
	});

	$('#page').change(function(){
		refreshSupplierView();
	});

	$('#searchBar').change(function(){
		refreshSupplierView(1);
	})

	function refreshSupplierView(page = $('#page').val()){
		$.ajax({
			url:'<?= site_url('Supplier/showItems') ?>',
			data:{
				page: page,
				term: $('#searchBar').val()
			},
			success:function(response){
				var itemCount = 0;
				var items		=response.suppliers;
				$('#supplierTableContent').html("");
				$.each(items, function(index, item){
					var name					= item.name;
					var id						= item.id;
					var complete_address		= item.address;
					var supplier_number			= item.number;
					var supplier_block			= item.block;
					var supplier_rt				= item.rt;
					var supplier_rw				= item.rw;
					var supplier_city			= item.city;
					var supplier_postal_code	= item.postal_code;

					if(supplier_number != null && supplier_number != ''){
						complete_address	+= ' no. ' + supplier_number;
					};
				
					if(supplier_block != null && supplier_block != ''){
						complete_address	+= ', blok ' + supplier_block;
					};
				
					if(supplier_rt != '000'){
						complete_address	+= ', RT ' + supplier_rt + ', RW ' + supplier_rw;
					}
				
					if(supplier_postal_code != ''){
						complete_address += ', ' + supplier_postal_code;
					}

					$('#supplierTableContent').append("<tr><td>" + name + "</td><td><p>" + complete_address + "</p><p>" + supplier_city + "</p></td><td><button type='button'' class='button button_default_dark' onclick='selectSupplier(" + id + ")'><i class='fa fa-long-arrow-right'></i></button></tr>");
					itemCount++;

					var pages		= response.pages;
					$('#page').html("");
					for(i = 1; i <= pages; i++){
						if(i == page){
							$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
						} else {
							$('#page').append("<option value='" + i + "'>" + i + "</option>");
						}
					}
				})

				if(itemCount > 0){
					$('#supplierTable').show();
					$('#supplierTableText').hide();
				} else {
					$('#supplierTable').hide();
					$('#supplierTableText').show();
				}
			}
		})
	}

	function selectSupplier(n){
		$('#supplierId').val(n);
		$('#reportForm').submit();
	}
</script>
