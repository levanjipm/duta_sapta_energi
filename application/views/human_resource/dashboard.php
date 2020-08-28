<head>
    <title>Human Resource</title>
    <style>
        .dashboardBox{
            padding:8px;
            box-shadow:3px 3px 3px 3px rgba(50,50,50,0.3);
            border-radius:5px;
            margin-bottom:10px;
        }

        .dashboardBox .leftSide{
            width:50%;
            font-weight:bold;
            display:inline-block;
        }

        .dashboardBox .rightSide{
            width:45%;
            float:right;
            display:inline-block;
            text-align:center;
            margin:0 auto;
            top: 50%;
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
            position:absolute;
            border-left:2px solid #ccc;
        }

        .dashboardBox .rightSide h3{
            
            font-weight:bold;
            color:#E19B3C;            
        }

        .subtitleText{
            font-size:0.8em;
            color:#555;
            text-align:right;
        }
    </style>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<div class='dashboard'>
    <br>
    <div class='dashboard_in'>
        <div class='row'>
            <div class='col-md-4 col-sm-12 col-xs-12'>
                <div class='dashboardBox clickable' onclick='getUsers()' >
                    <div class='leftSide'>
                        <h4><b>Active</b></h4>
                        <p>User</p>
                    </div>
                    <div class='rightSide'>
                        <h3><?= number_format($activeUser) ?></h3>
                        <p>&nbsp;</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class='alert_wrapper' id='userWrapper'>
	<button class='slide_alert_close_button'>&times;</button>
	<div class='alert_box_slide'>
		<h3 style='font-family:bebasneue'>Active users</h3>
		<hr>
		<div id='userTable'>
			<table class='table table-bordered'>
				<tr>
					<th>Name</th>
					<th>Information</th>
				</tr>
				<tbody id='userTableContent'></tbody>
			</table>
		</div>
		<p id='userTableText'>There is no active user found.</p>
	</div>
</div>

<script>
	function getUsers(){
		$.ajax({
			url:"<?= site_url('Users/getActiveUser') ?>",
			success:function(response){
				$('#userTableContent').html("");
				$.each(response, function(index, item){
					var name = item.name;
					var address = item.address;
					var email = item.email;
					$('#userTableContent').append("<tr><td>" + name + "</td><td><p>" + address + "</p><p>" + email + "</p></td></tr>")
				});

				if(response.length == 0){
					$('#userTable').hide();
					$('#userTableText').show();
				} else {
					$('#userTable').show();
					$('#userTableText').hide();
				}

				$('#userWrapper').fadeIn(300, function(){
					$('#userWrapper .alert_box_slide').show("slide", { direction: "right" }, 250);
				});
			}
		})
	}
</script>
