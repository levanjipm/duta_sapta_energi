<head>
    <title>Route</title>
    <style>
        #map {
			height: 400px;
			width:100%;
		}
    </style>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBX4UnIiCLVbq-LPeTA__c3NKIEZA1rhAw&callback=initMap&libraries=&v=weekly" defer></script>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Inventory') ?>' title='Inventory'><i class='fa fa-th'></i></a> /Routes</p>
	</div>
	<br>
	<div class='dashboard_in'>
        <div class="input_group">
            <input class="form-control" id='search' />
            <button id='addRouteButton' class="button button_default_dark">Add Route</button>
        </div>
        <br>

        <p id='routeTableText'>There is no route found.</p>
        <div id='routeTable'>
            <table class='table table-bordered'>
                <tr>
                    <th>Name</th>
                    <th>Customers</th>
                    <th>Action</th>
                </tr>
                <tbody id='routeTableContent'></tbody>
            </table>

            <select class='form-control' id='page' style='width:100px'>
                <option value='1'>1</option>
            </select>
        </div>
    </div>
</div>

<div class='alert_wrapper' id='delete_route_wrapper'>
	<div class='alert_box_confirm_wrapper'>
		<div class='alert_box_confirm_icon'><i class='fa fa-trash'></i></div>
		<div class='alert_box_confirm'>
			<input type='hidden' id='delete_route_id'>
			<h3>Delete confirmation</h3>
			
			<p>You are about to delete this data.</p>
			<p>Are you sure?</p>
			<button class='button button_default_dark' onclick="$('#delete_route_wrapper').fadeOut()">Cancel</button>
			<button class='button button_danger_dark' onclick='delete_route()'>Delete</button>
			<br><br>
			
			<p style='font-family:museo;background-color:#f63e21;width:100%;padding:5px;color:white;position:relative;bottom:0;left:0;opacity:0' id='error_delete_route'>Deletation failed.</p>
		</div>
	</div>
</div>

<div class='alert_wrapper' id='add_route_wrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Insert route</h3>
		<hr>
		<form id='insert_route_form'>
			<label>Route name</label>
			<input type='text' class='form-control' id='route' required>
			<div style='padding:2px 10px;background-color:#ffc107;width:100%;display:none;' id='error_insert_route'><p style='font-family:museo'><i class='fa fa-exclamation-triangle'></i> Insert data failed.</p></div><br>
			
			<button class='button button_default_dark' type='button' id='insertRouteButton'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<div class='alert_wrapper' id='view_route_wrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
        <div id='map'></div>
	</div>
</div>

<div class='alert_wrapper' id='edit_route_wrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Edit route</h3>
		<hr>
		<form id='edit_route_form'>
			<label>Route name</label>
            <input type='hidden' id='editRouteId'>
			<input type='text' class='form-control' id='editRouteName' required>
			<div style='padding:2px 10px;background-color:#ffc107;width:100%;display:none;' id='error_edit_route'><p style='font-family:museo'><i class='fa fa-exclamation-triangle'></i> Edit data failed.</p></div><br>
			
			<button class='button button_default_dark' type='button' id='editRouteButton'><i class='fa fa-long-arrow-right'></i></button>
		</form>
	</div>
</div>

<script>
    $(document).ready(function(){
        refreshView();
    })

    $('#page').change(function(){
        refreshView();
    })

    $('#search').change(function(){
        refreshView(1);
    })

    $('#addRouteButton').click(function(){
        $('#add_route_wrapper').fadeIn(300, function(){
			$('#add_route_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
    })

    $('#insert_route_form').validate();
    $('#edit_route_form').validate();

    $("#insert_route_form").submit(function(e){
		return false;
	});

    $('#edit_route_form').submit(function(e){
        return false;
    })

    $('#search').change(function(){
        refreshView(1);
    })

    $('#insertRouteButton').click(function(){
        if($('#insert_route_form').valid()){
            $.ajax({
                url:"<?= site_url('Route/insertItem') ?>",
                data:{
                    route: $('#route').val()
                },
                type:"POST",
                beforeSend:function(){
                    $('input').attr('disabled', true);
                },
                success:function(response){
                    $('input').attr('disabled', false);
                    if(response == 1){
                        $('#route').val("");
                        refreshView();
                        $('#add_route_wrapper .slide_alert_close_button').click();
                    } else {
                        $('#error_insert_route').show();
						setTimeout(function(){
							$('#error_insert_route').fadeOut()
						}, 1000);
                    }
                }
            })
        }
    })

    $('#editRouteButton').click(function(){
        $.ajax({
            url:"<?= site_url('Route/EditById') ?>",
            data:{
                id: $('#editRouteId').val(),
                name: $('#editRouteName').val()
            },
            type:"POST",
            beforeSend:function(){
                $('button').attr('disabled', true);
                $('input').attr('disabled', true);
            },
            success:function(response){
                $('button').attr('disabled', false);
                $('input').attr('disabled', false);

                if(response == 1){
                    $('#editRouteId').val("");
                    $('#editRouteName').val("");

                    refreshView();
                    $('#edit_route_wrapper .slide_alert_close_button').click();
                } else {
                    $('#error_edit_route').fadeTo(500, 1);
                    setTimeout(function(){
                        $('#error_edit_route').fadeTo(500, 0);
                    }, 1000)
                }
            }
        })
    })

    function refreshView(page = $('#page').val()){
        $.ajax({
            url:"<?= site_url('Route/getItems') ?>",
            data:{
                page: page,
                term: $('#search').val()
            },
            beforeSend:function(){
                $('input').attr('disabled', true);
                $('#routeTableContent').html("");
            },
            success:function(response){
                $('input').attr('disabled', false);

                if(response.items.length == 0){
                    $('#routeTable').hide();
                    $('#routeTableText').show();
                } else {
                    $('#routeTable').show();
                    $('#routeTableText').hide();
                }
                $.each(response.items, function(i, item){
                    $('#routeTableContent').append("<tr><td>" + item.name + "</td><td>" + numeral(item.count).format('0,0') + "</td><td><button class='button default_button_dark' onclick='openMap(" + item.id + ")'><i class='fa fa-map-marker'></i></button><button class='button button_success_dark' onclick='openEditRoute(" + item.id + ", `" + item.name + "`)'><i class='fa fa-pencil'></i></button> <button class='button button_danger_dark' onclick='openDeleteRoute(" + item.id + ")'><i class='fa fa-trash'></i></button> <button class='button button_default_dark' onclick='assignCustomers(" + item.id + ")'><i class='fa fa-keyboard-o'></i></button></td></tr>");
                })
                
                $('#page').html("");
                for(i = 1; i <= response.pages; i++){
                    if(i == page){
                        $('#page').append("<option value='" + i + "' selected>" + i + "</option>");
                    } else {
                        $('#page').append("<option value='" + i + "'>" + i + "</option>");
                    }
                }
            }
        })
    }

    function assignCustomers(id){
        window.location.href="<?= site_url('Route/AssignCustomer/') ?>" + id;
    }

    function openDeleteRoute(id){
        $('#delete_route_wrapper').fadeIn();
        $('#delete_route_id').val(id);
    }

    function openEditRoute(id, name){
        $('#editRouteId').val(id);
        $('#editRouteName').val(name);
        $('#edit_route_wrapper').fadeIn(300, function(){
			$('#edit_route_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
		});
    }

    function delete_route(){
        $.ajax({
            url:'<?= site_url('Route/deleteById') ?>',
            data:{
                id: $('#delete_route_id').val()
            },
            type:"POST",
            beforeSend:function(){
                $('button').attr('disabled', true);
            },
            success:function(res){
                $('button').attr('disabled', false);
                if(res == 1){
                    $('#delete_route_wrapper').fadeOut();
                    $('#delete_route_id').val("");
                    refreshView();
                } else {
                    $('#error_delete_route').fadeIn();
                    setTimeout(function(){
                        $('#error_delete_route').fadeOut();
                    }, 1000)
                }
            }
        })
    }

    let map;
	let markers = [];

	function initMap() {
		map = new google.maps.Map(document.getElementById("map"), {
			center: { lat: -34.397, lng: 150.644 },
			zoom: 12,
		});
	}

    function openMap(i){
        $.ajax({
            url:"<?= site_url('Route/getCustomerById') ?>",
            data:{
                route_id: i
            },
            beforeSend:function(){
                setMapOnAll(map);
                markers = [];
            },
            success:function(response){
                $.each(response, function(index, customer){
                    if(customer.longitude != 0 && customer.longitude != null && customer.latitude != 0 && customer.latitude != null){
                        var place = new google.maps.LatLng(parseFloat(customer.latitude), parseFloat(customer.longitude));
                        var marker = new google.maps.Marker({
                            position: place,
                            map: map,
                            title: customer.name,
                            icon:'<?= base_url('assets/Icons/location.png') ?>'
                        });

                        var complete_address		= '';
                        complete_address		+= customer.address;
                        var customer_city			= customer.city;
                        var customer_number			= customer.number;
                        var customer_rt				= customer.rt;
                        var customer_rw				= customer.rw;
                        var customer_postal			= customer.postal_code;
                        var customer_block			= customer.block;
                        var customer_id				= customer.id;

                        if(customer_number != null){
                            complete_address	+= ' No. ' + customer_number;
                        }
                        
                        if(customer_block != null && customer_block != "000"){
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

                        const contentString =
                            '<div id="content"><h4 class="headerText">' + customer.name + '</h4>' +
                            '<div id="bodyContent">' +
                            "<p class='bodyText'>" + complete_address + "</p>" +
                            "<p class='bodyText'>" + customer_city + "</p>" +
                            "</div>" +
                            "</div>";
                        const infowindow = new google.maps.InfoWindow({
                            content: contentString,
                        });

                        marker.addListener("click", () => {
                            infowindow.open(map, marker);
                        });

                        markers.push(marker);
                        marker.setMap(map);
                    }
                })

                $('#view_route_wrapper').fadeIn(300, function(){
                    var bounds = new google.maps.LatLngBounds();

                    $.each(markers, function(index, marker){
                        marker.setMap(map);
                        bounds.extend(marker.position);
                    });
                    var listener = google.maps.event.addListener(map, "idle", function() {
                        map.setZoom(12); 
                        google.maps.event.removeListener(listener); 
                    });

                    map.fitBounds(bounds);

                    $('#view_route_wrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
                });
            }
        })
    }

	function setMapOnAll(map){
		for (let i = 0; i < markers.length; i++) {
			markers[i].setMap(null);
		};
	}

</script>