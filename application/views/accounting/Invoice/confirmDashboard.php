<head>
    <title>Invoice - Confirm invoice</title>
</head>
<div class='dashboard'>
    <div class='dashboard_head'>
    <p style='font-family:museo'><a href='<?= site_url('Accounting') ?>' title='Accounting'><i class='fa fa-bar-chart'></i></a> /<a href='<?= site_url('Invoice') ?>'>Invoice </a> /Confirm invoice</p>
    </div>
    <br>
    <div class='dashboard_in'>
        <div id='retailInvoiceTable'>
            <table class='table table-bordered'>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Customer</th>
                    <th>Action</th>
                </tr>
                <tbody id='retailInvoiceTableContent'></tbody>
            </table>
        </div>
        <p id='retailInvoiceTableText'>There is no invoice to be confirmed.</p>
    </div>
</div>
<div class='alert_wrapper' id='invoice_alert'>
    <button class='button slide_alert_close_button'>&times;</button>
    <div class='alert_box_slide'>
        <h3 style='font-family:bebasneue'>Confirm invoice</h3>
        <hr>
        <form id='invoiceForm'>
            <div id='taxInvoiceWrapper'>
                <label>Tax invoice</label>
                <input type='text' class='form-control' id='taxInvoice'>
            </div>
            <script>
                $('#taxInvoice').inputmask("999.999-99.99999999");
            </script>

            <label>Customer</label>
            <p style='cursor:pointer' id='customer_name_p'></p>
            <p style='cursor:pointer' id='customer_address_p'></p>
            <p id='customer_city_p'></p>

            <label>Invoice</label>
            <p id='invoice_name_p'></p>
            <p id='invoice_date_p'></p>

			<div id='nonBlankWrapper'>
				<label>Delivery order</label>
				<p id='delivery_order_name_p'></p>

				<label>Other</label>
				<p id='invoicing_method_p'></p>
				<p id='taxing_p'></p>

				<label>Items</label>
				<table class='table table-bordered'>
					<tr>
						<th>Reference</th>
						<th>Name</th>
						<th>Price list</th>
						<th>Discount</th>
						<th>Net price</th>
						<th>Quantity</th>
						<th>Total price</th>
					</tr>
					<tbody id='deliveryOrderTableContent'></tbody>
				</table>
			</div>
			<div id='blankWrapper'>
				<label>Value</label>
				<p id='invoice_value_p'></p>
			</div>
			<label>Information</label>
			<p id='invoice_note_p'></p>

			<label>Delivery Order Status</label>
			<p id='deliveryOrderStatus'></p>

            <input type='hidden' id='invoice_id'>
            <button type='button' class='button button_default_dark' title='Confirm invoice' onclick='confirmInvoice()' id='confirmButton'><i class='fa fa-long-arrow-right'></i></button>
            <button type='button' class='button button_danger_dark' title='Delete invoice' onclick='deleteInvoice()'><i class='fa fa-trash'></i></button>
        </form>
        <div class='notificationText danger' id='confirmFailedNotification'><p>Failed to confirm invoice.</p></div>
	</div>
</div>
<script>
    $('#invoiceForm').validate();

    $(document).ready(function(){
        refresh_view();
    });

    function refresh_view(){
        $.ajax({
            url:'<?= site_url("Invoice/getUnconfirmedinvoice") ?>',
            success:function(response){
                var invoiceCount = 0;
                $('#retailInvoiceTableContent').html('');

                $.each(response, function(index, value){
                    var id			= value.id;
                    var date		= value.date;
                    var name		= value.name;
                    var customer	= value.customer;
                    var opponent	= value.opponent;
					if(value.customer_id != null){
						var customer_name = customer.name;
						var complete_address = customer.address;
						var customer_number = customer.number;
						var customer_block = customer.block;
						var customer_rt = customer.rt;
						var customer_rw = customer.rw;
						var customer_city = customer.city;
						var customer_postal = customer.postal;
						var customer_kelurahan	= customer.kelurahan;
						var customer_kecamatan	= customer.kecamatan;
                    
						if(customer_number != null){
							complete_address	+= ' No. ' + customer_number;
						}
                    
						if(customer_block != null && customer_block != "" && customer_block != "000"){
							complete_address	+= ' Blok ' + customer_block;
						}
                
						if(customer_rt != '000'){
							complete_address	+= ' RT ' + customer_rt;
						}
                    
						if(customer_rw != '000' && customer_rt != '000'){
							complete_address	+= ' /RW ' + customer_rw;
						}
                    
						if(customer_postal != null){
							complete_address	+= ', ' + customer_postal;
						}

						complete_address += ", Kel. " + customer_kelurahan;
						complete_address += ", Kec. " + customer_kecamatan;
					} else {
						var customer_name = opponent.name;
						var complete_address = opponent.description;
						var customer_city = opponent.type;
					}

					if(value.deliveryOrder.length > 0){
						var isSent = deliveryOrder.is_sent;
						if(isSent == 1){
							$('#retailInvoiceTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>  " + name + "</td><td><label>" + customer_name + "</label><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><button class='button button_default_dark' onclick='viewInvoiceById(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");

							invoiceCount++;
						};
					} else {
						$('#retailInvoiceTableContent').append("<tr><td>" + my_date_format(date) + "</td><td>  " + name + "</td><td><label>" + customer_name + "</label><p>" + complete_address + "</p><p>" + customer_city + "</p></td><td><button class='button button_default_dark' onclick='viewInvoiceById(" + id + ")'><i class='fa fa-eye'></i></button></td></tr>");

						invoiceCount++;
					}
                });

                if(invoiceCount > 0){
                    $('#retailInvoiceTable').show();
                    $('#retailInvoiceTableText').hide();
                } else {
                    $('#retailInvoiceTable').hide();
                    $('#retailInvoiceTableText').show();
                }
            }
        })
    }

    function viewInvoiceById(n){
        $.ajax({
            url:"<?= site_url('Invoice/getById') ?>",
            data:{
                id: n
            },
            success:function(response){
                $('#invoice_id').val(n);
				$('#deliveryOrderStatus').html("You are good to go.");
				$("#confirmButton").attr('disabled', false);
                var customer = response.customer;      
				if(customer != null){
					var customer_name = customer.name;
					var complete_address = customer.address;
					var customer_number = customer.number;
					var customer_block = customer.block;
					var customer_rt = customer.rt;
					var customer_rw = customer.rw;
					var customer_city = customer.city;
					var customer_postal = customer.postal;
					var customer_kelurahan = customer.kelurahan;
					var customer_kecamatan = customer.kecamatan;
					var customer_npwp	= (customer.npwp == null) ? "00.000.000.0-000.000" : customer.npwp;
                
					if(customer_number != null){
						complete_address	+= ' No. ' + customer_number;
					}
                
					if(customer_block != null && customer_block != "" && customer_block != "000"){
						complete_address	+= ' Blok ' + customer_block;
					}
            
					if(customer_rt != '000'){
						complete_address	+= ' RT ' + customer_rt;
					}
                
					if(customer_rw != '000' && customer_rt != '000'){
						complete_address	+= ' /RW ' + customer_rw;
					}
                
					if(customer_postal != null){
						complete_address	+= ', ' + customer_postal;
					}

					complete_address += ", Kel. " + customer_kelurahan;
					complete_address += ", Kec. " + customer_kecamatan;

					$('#customer_npwp_p').html(customer_npwp);
					$('#customer_npwp_p').on('click', function(){
						navigator.writeText(customer_npwp);
					})
				} else {
					var opponent = response.opponent;
					var customer_name = opponent.name;
					var complete_address = opponent.description;
					var customer_city = opponent.type;

					$('#customer_npwp_p').html("");
				}

                $('#customer_name_p').html(customer_name);
                $('#customer_address_p').html(complete_address);
                $('#customer_city_p').html(customer_city);

				$('#customer_name_p').on('click', function(){
					navigator.clipboard.writeText(customer_name);
				})
				
				$('#customer_address_p').on('click', function(){
					navigator.clipboard.writeText(complete_address + "\n" + customer_city);
				})

                var invoice = response.invoice;
                var name = invoice.name;
                var date = invoice.date;
				var note = invoice.note;
				var invoiceDelivery	= parseFloat(invoice.delivery);
				var invoiceDiscount	= parseFloat(invoice.discount);
                
                $('#invoice_name_p').html(name);
                $('#invoice_date_p').html(my_date_format(date));
				$('#invoice_note_p').html((note == "" || note == null) ? "<i>Not available</i>" : note);

				if(response.delivery_order != null){
					$('#nonBlankWrapper').show();
					$('#blankWrapper').hide();
					var delivery_order = response.delivery_order;
					$('#delivery_order_name_p').html(delivery_order.name);

					var sales_order			= response.sales_order;
					var invoicing_method	= sales_order.invoicing_method;
					if(invoicing_method == 1){
						var invoicing_text = "Retail method";
					} else if(invoicing_method == 2){
						var invoicing_text = "Coorporate method";
					};

					if(delivery_order.is_delete == 1){
						$('#deliveryOrderStatus').html("Delivery order has been deleted by warehouse. Please delete the following invoice.");
						$("#confirmButton").attr('disabled', true);
					} else if(delivery_order.is_confirm == 0){
						$('#deliveryOrderStatus').html("Delivery order has not been confirmed by warehouse. Please ask warehouse to confirm.");
						$("#confirmButton").attr('disabled', true);
					}

					if(invoicing_method == 2 && delivery_order.is_sent == 0)
					{
						$('#deliveryOrderStatus').html("Delivery order has not been confirmed by warehouse. Please ask warehouse to confirm.");
						$("#confirmButton").attr('disabled', true);
					}

					var taxing = sales_order.taxing;
					if(taxing == 1){
						var taxing_text = "Taxable sales";
					} else if(taxing == 0){
						var taxing_text = "Non-taxable sales";
					}

					$('#taxInvoiceWrapper').show();
					$('#taxInvoice').attr('required', true);

					$('#invoicing_method_p').html(invoicing_text);
					$('#taxing_p').html(taxing_text);

					var items = response.items;
					var invoiceValue = 0;
					$('#deliveryOrderTableContent').html('');
					$.each(items, function(index, item){
						var name = item.name;
						var reference = item.reference;
						var quantity = parseInt(item.quantity);
						var price_list = parseFloat(item.price_list);
						var discount = parseFloat(item.discount);
						var net_price = price_list * (100 - discount) / 100;
						var total_price = quantity * net_price;
						var id		= item.id;

						invoiceValue += total_price;

						$('#deliveryOrderTableContent').append("<tr><td style='cursor:pointer' id='reference-" + id + "'>" + reference + "</td><td>" + name + "</td><td>Rp. " + numeral(price_list).format('0,0.00') + "</td><td>" + numeral(discount).format('0,0.00') + "%</td><td style='cursor:pointer'  id='price-" + id + "'>Rp. " + numeral(net_price).format('0,0.00') + "</td><td style='cursor:pointer'  id='quantity-" + id + "'>" + numeral(quantity).format('0,0') + "</td><td>Rp. " + numeral(total_price).format('0,0.00') + "</td></tr>");

						$('#reference-' + id).on('click', function(){
							navigator.clipboard.writeText(reference);
						})

						$('#price-' + id).on('click', function(){
							navigator.clipboard.writeText((net_price / 1.1).toFixed(3).toString().replace(".", ","));
						})

						$('#quantity-' + id).on('click', function(){
							navigator.clipboard.writeText(quantity);
						})
					});

					$('#deliveryOrderTableContent').append("<tr><td colspan='4'></td><td colspan='2'><strong>Sub Total</strong></td><td>Rp. " + numeral(invoiceValue).format('0,0.00') + "</td></tr><tr><td colspan='4'></td><td colspan='2'><strong>Discount</strong></td><td>Rp. " + numeral(invoiceDiscount).format('0,0.00') + "</td></tr><tr><td colspan='4'></td><td colspan='2'><strong>Delivery</strong></td><td>Rp. " + numeral(invoiceDelivery).format('0,0.00') + "</td></tr><tr><td colspan='4'></td><td colspan='2'><strong>Total</strong></td><td>Rp. " + numeral(invoiceValue - invoiceDiscount + invoiceDelivery).format('0,0.00') + "</td></tr>");

				} else {
					$('#taxInvoice').attr('required', false);
					$('#nonBlankWrapper').hide();
					$('#blankWrapper').show();
					$('#invoice_value_p').html("Rp. " + numeral(invoice.value).format('0,0.00'));
				}

                $('#invoice_alert').fadeIn(300, function(){
                    $('#invoice_alert .alert_box_slide').show("slide", { direction: "right" }, 250);
                });
            }
        })
    }

    function confirmInvoice(){
        if($('#invoiceForm').valid()){
            $.ajax({
                url:"<?= site_url('Invoice/confirmById') ?>",
                data:{
                    id: $('#invoice_id').val(),
                    taxInvoice: $('#taxInvoice').val()
                },
                type:'POST',
                beforeSend:function(){
                    $('button').attr('disabled', true);
                },
                success:function(response){
                    $('button').attr('disabled', false);
                    if(response == 1){
                        refresh_view();
                        $('#invoice_alert .slide_alert_close_button').click();
                    } else {
                        refresh_view();
                        $('#confirmFailedNotification').fadeTo(250, 1);
                        setTimeout(function(){
                            $('#confirmFailedNotification').fadeTo(250, 0);
                        }, 1000)
                    }
                },
				complete:function(){
					$('#taxInvoice').val("");
				}
            })
        }
    }

    function deleteInvoice(){
        $.ajax({
            url:"<?= site_url('Invoice/deleteById') ?>",
            data:{
                id: $('#invoice_id').val(),
            },
            type:'POST',
            beforeSend:function(){
                $('button').attr('disabled', true);
            },
            success:function(response){
                $('button').attr('disabled', false);
                if(response == 1){
                    refresh_view();
                    $('#invoice_alert .slide_alert_close_button').click();
                } else {
                    refresh_view();
                    $('#deleteFailedNotification').fadeTo(250, 1);
                    setTimeout(function(){
                        $('#deleteFailedNotification').fadeTo(250, 0);
                    }, 1000)
                }
            }
        })
    }    
</script>
