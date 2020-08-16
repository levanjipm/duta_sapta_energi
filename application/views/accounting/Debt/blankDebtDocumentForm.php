<form id='debt_document_form'>
	<label>Date</label>
	<input type='date' class='form-control' id='date' name='date' required>
	
	<label>Supplier</label>
	<button type='button' class='form-control' id='supplierPickButton' style='text-align:left!important'></button>			
	<input type='hidden' id='supplier_id' name='supplier_id'>

	<label>Supplier detail</label>
	<div class='information_box' id='supplierDetail'></div>
	
	<label>Value</label>
	<input type='number' class='form-control' id='value' required min='1'>
	
	<label>Information</label>
	<textarea class='form-control' style='resize:none' id='information' minlength='25'></textarea>

	<label>Taxing</label>
	<select class='form-control' id='taxing'>
		<option value='0'>Non-taxable</option>
		<option value='1'>Taxable</option>
	</select>

	<label>Invoice</label>
	<input type='text' class='form-control' id='invoiceName' required>

	<div id='taxInvoiceWrapper' style='display:none'>
		<label>Tax invoice</label>
		<input type='text' class='form-control' id='taxInvoiceName'>
		<script>
			$("#taxInvoiceName").inputmask("999.999-99.99999999");
		</script>
	</div>

	<label>Type</label>
	<button type='button' class='form-control' id='debtTypePickButton' style='text-align:left!important'></button>
	<input type='hidden' id='debtType' name='debtType' required>
	
	<br>
	<button class='button button_default_dark' id='submitButton'><i class='fa fa-long-arrow-right'></i></button>
</form>

<div class='alert_wrapper' id='selectSupplierWrapper'>
	<div class='alert_box_full'>		
		<button type='button' class='button alert_full_close_button' title='Close select supplier session'>&times;</button>
		<h3 style='font-family:bebasneue'>Select supplier</h3>
		<hr>
		<button class='button button_mini_tab' id='supplierTabButton'>Supplier</button>
		<button class='button button_mini_tab' id='opponentTabButton'>Opponents</button>
		<hr>
		<div id='supplierView'>
			<input type='text' class='form-control' id='searchSupplierBar'>
			<br>
			<div id='supplierTable'>
				<table class='table table-bordered'>
					<tr>
						<th>Name</th>
						<th>Address</th>
						<th>Action</th>
					</tr>
					<tbody id='supplierTableContent'></tbody>
				</table>
				
				<select class='form-control' id='supplierPage' style='width:100px'>
					<option value='1'>1</option>
				</select>
			</div>
			<p id='supplierTableText'>There is no supplier found.</p>
		</div>
		<div id='opponentView'>
			<input type='text' class='form-control' id='searchOpponentBar'>
			<br>
			<div id='opponentTable'>
				<table class='table table-bordered'>
					<tr>
						<th>Name</th>
						<th>Type</th>
						<th>Action</th>
					</tr>
					<tbody id='opponentTableContent'></tbody>
				</table>
				
				<select class='form-control' id='opponentPage' style='width:100px'>
					<option value='1'>1</option>
				</select>
			</div>
			<p id='opponentTableText'>There is no opponent found.</p>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='selectTypeWrapper'>
	<div class='alert_box_full'>		
		<button type='button' class='button alert_full_close_button' title='Close select type session'>&times;</button>
		<h3 style='font-family:bebasneue'>Select debt type</h3>
		<br>
		<input type='text' class='form-control' id='searchTypeBar'>
		<br>
		<table class='table table-bordered'>
			<tr>
				<th>Name</th>
				<th>Description</th>
				<th>Action</th>
			</tr>
			<tbody id='debtTypeTableContent'></tbody>
		</table>
		
		<select class='form-control' id='debtTypePage' style='width:100px'>
			<option value='1'>1</option>
		</select>
	</div>
</div>

<div class='alert_wrapper' id='debtDocumentWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Create debt document</h3>
		<hr>
		<label>Supplier</label>
		<p id='supplier_name_p'></p>
		<p id='supplier_address_p'></p>
		<p id='supplier_city_p'></p>

		<label>Invoice</label>
		<p id='invoice_name_p'></p>
		<p id='tax_invoice_name_p'></p>
		<p id='invoice_value_p'></p>

		<label>Taxing</label>
		<p id='taxing_p'></p>

		<label>Information</label>
		<p id='information_p'></p>

		<label>Type</label>
		<p id='debtTypeText'></p>

		<button class='button button_default_dark' id='submitFormButton'><i class='fa fa-long-arrow-right'></i></button>
		<br>
		<div class='notificationText danger' id='failedInsertNotification'><p>Failed to insert debt document.</p></div>
	</div>
</div>

<script>
	var supplier_name;
	var complete_address;
	var supplier_city;
	var supplier_id;

	var information;
	var value;
	var taxInvoiceName;
	var invoiceName;
	var date;

	var debtType;

	var taxing;

	$(document).ready(function(){
		refreshType();
	})

	$('#debt_document_form').validate({
		ignore: '',
		rules: {"hidden_field": {required: true}}
	});

	$('#debt_document_form').on('submit', function(){
		return false;
	});

	$('.alert_full_close_button').click(function(){
		$(this).parent().parent().fadeOut();
	})

	$('#supplierPickButton').click(function(){
		$('#supplierTabButton').click();
		refreshSupplier(1);
		$('#selectSupplierWrapper').fadeIn();
	})

	$('#debtTypePickButton').click(function(){
		refreshSupplier(1);
		$("#selectTypeWrapper").fadeIn();
	})

	$('#searchSupplierBar').change(function(){
		refreshSupplier(1);
	});

	$('#supplierPage').change(function(){
		refreshSupplier();
	})

	$('#taxing').change(function(){
		if($('#taxing').val() == 1){
			$('#taxInvoiceWrapper').show();
		} else {
			$('#taxInvoiceWrapper').hide();
		}
	});

	$('#submitButton').click(function(){
		if($('#debt_document_form').valid()){
			taxing = $('#taxing').val();
			supplier_id = $('#supplier_id').val();
			date = $('#date').val();
			value = $('#value').val();
			information = $('#information').val();

			invoiceName = $('#invoiceName').val();

			$('#supplier_name_p').html(supplier_name);
			$('#supplier_address_p').html(complete_address);
			$('#supplier_city_p').html(supplier_city);

			if(taxing == 1){
				var taxingText = "Taxable";
				taxInvoiceName = $('#taxInvoiceName').val();
				
			} else {
				var taxingText = "Non-taxable";
				taxInvoiceName = "";
			}

			$('#tax_invoice_name_p').html(taxInvoiceName);
			$('#invoice_name_p').html(invoiceName);
			$('#invoice_value_p').html("Rp. " + numeral(value).format("0,0.00"));

			$('#information_p').html(information);
			$('#taxing_p').html(taxingText);

			$('#debtDocumentWrapper').fadeIn(300, function(){
				$('#debtDocumentWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
			});
		}
	});

	$('#submitFormButton').click(function(){
		if($('#debt_document_form').valid()){
			$.ajax({
				url:"<?= site_url('Debt/insertBlankItem') ?>",
				data:{
					taxing: taxing,
					name: invoiceName,
					taxInvoiceName: taxInvoiceName,
					invoiceName: invoiceName,
					information: information,
					value: value,
					date: $('#date').val(),
					supplier_id: $('#supplier_id').val(),
					debtType: $('#debtType').val()
				},
				type:'POST',
				beforeSend:function(){
					$('button').attr('disabled', true);
				},
				success:function(response){
					$('button').attr('disabled', false);
					if(response == 1){
						$('#debt_document_form').trigger("reset");
						$('#supplierDetail').html("");
						$('#supplierPickButton').text("");

						$('#debtDocumentWrapper .slide_alert_close_button').click();
					} else {
						$('#failedInsertNotification').fadeIn();
						setTimeout(function(){
							$('#failedInsertNotification').fadeOut();
						}, 1000)
					}
				}
			})
		};
	})
	
	function refreshSupplier(page = $('#supplierPage').val()){
		$.ajax({
			url:'<?= site_url('Supplier/showItems') ?>',
			data:{
				page: page,
				term: $('#searchSupplierBar').val()
			},
			success:function(response){
				var suppliers = response.suppliers;
				$('#supplierTableContent').html("");
				$.each(suppliers, function(index, supplier){
					var id						= supplier.id;
					var name 					= supplier.name;
					var complete_address		= '';
					complete_address			+= supplier.address;
					var supplier_city			= supplier.city;
					var supplier_number			= supplier.number;
					var supplier_rt				= supplier.rt;
					var supplier_rw				= supplier.rw;
					var supplier_postal			= supplier.postal_code;
					var supplier_block			= supplier.block;
		
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

					$('#supplierTableContent').append("<tr><td>" + name + "</td><td>" + complete_address + "</td><td><button class='button button_success_dark' onclick='selectSupplier(" + id + ")'><i class='fa fa-check'></i></button>");
				})

				$('#supplierPage').html("");
				var pages = response.pages;
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#page').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#page').append("<option value='" + i + "'>" + i + "</option>");
					}
				}
			}
		});
	}

	function refreshOpponent(page = $('#opponentPage').val()){

	}

	function refreshType(page = $('#debtTypePage').val()){
		$.ajax({
			url:"<?= site_url('Debt_type/getItems') ?>",
			data:{
				page: page,
				term: $('#searchTypeBar').val()
			},
			success:function(response){
				$('#debtTypeTableContent').html("");
				var items = response.items;
				$.each(items, function(index, item){
					var id = item.id;
					var name = item.name;
					var description = item.description;

					$('#debtTypeTableContent').append("<tr><td>" + name + "</td><td>" + description + "</td><td><button class='button button_success_dark' id='typeButton-" + id + "'><i class='fa fa-check'></i></button></td></tr>");

					$('#typeButton-' + id).click(function(){
						$('#debtTypePickButton').text(name);
						$('#debtType').val(id);
						$('#debtTypeText').html(name);
						$('#selectTypeWrapper').fadeOut();
					})
				})

				var pages = response.pages;
				$('#debtTypePage').html("");
				for(i = 1; i <= pages; i++){
					if(i == page){
						$('#debtTypePage').append("<option value='" + i + "' selected>" + i + "</option>");
					} else {
						$('#debtTypePage').append("<option value='" + i + "'>" + i + "</option>")
					}
				}
			}
		})
	}

	$('#searchTypeBar').change(function(){
		refreshType(1);
	});

	$('#debtTypePage').change(function(){
		refreshType();
	})

	function selectSupplier(n){
		$.ajax({
			url:'<?= site_url("Supplier/getById") ?>',
			data:{
				id: n
			},
			success:function(response){
				supplier_name 					= response.name;
				complete_address		= '';
				complete_address			+= response.address;
				supplier_city			= response.city;
				var supplier_number			= response.number;
				var supplier_rt				= response.rt;
				var supplier_rw				= response.rw;
				var supplier_postal			= response.postal_code;
				var supplier_block			= response.block;
	
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

				$('#supplierPickButton').text(supplier_name);
				$('#supplier_id').val(n);

				$('#supplierDetail').html("<label>Name</label><p>" + supplier_name + "</p><label>Address</label><p>" + complete_address + "</p><p>" + supplier_city + "</p>");

				$('#selectSupplierWrapper').fadeOut();
			}
		})
	}

	$('#supplierTabButton').click(function(){
		$('.button_mini_tab').attr('disabled', true);
		$('.button_mini_tab').removeClass('active');
		$('#supplierTabButton').addClass('active');
		$('#opponentTabButton').attr('disabled', false);

		$('#opponentView').fadeOut(250);
		setTimeout(function(){
			$('#supplierView').fadeIn(250);
		}, 250);		
	});

	$('#opponentTabButton').click(function(){
		$('.button_mini_tab').attr('disabled', true);
		$('.button_mini_tab').removeClass('active');
		$('#opponentTabButton').addClass('active');
		$('#supplierTabButton').attr('disabled', false);

		$('#supplierView').fadeOut(250);
		setTimeout(function(){
			$('#opponentView').fadeIn(250);
		}, 250);	
	})

	$('.slide_alert_close_button').click(function(){
		$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
			$(this).parent().fadeOut();
		});
	})	
</script>