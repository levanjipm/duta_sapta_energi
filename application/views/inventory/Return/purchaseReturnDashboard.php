<head>
    <title>Return - Purchase</title>
</head>
<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Inventory') ?>' title='Inventory'><i class='fa fa-th'></i></a> /Return/ Purchase</p>
	</div>
	<br>
	<div class='dashboard_in'>
        <button class='button button_mini_tab' id='createReturnButton'>Create</button>
        <button class='button button_mini_tab' id='confirmReturnButton'>Confirm</button>
        <hr>
        <div class='row'>
            <div class='col-xs-12' id='viewPane'>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#createReturnButton').click();
    })

    $('#createReturnButton').click(function(){
        $.ajax({
            url:"<?= site_url('Purchase_return/loadForm/create') ?>",
            success:function(response){
                $('.button_mini_tab').attr('disabled', false);
                $('#confirmReturnButton').removeClass('active');
                $('#createReturnButton').attr('disabled', true);
                $('#createReturnButton').addClass('active');

                $('#viewPane').html(response);
            }
        })
    });

    $('#confirmReturnButton').click(function(){
        $.ajax({
            url:"<?= site_url('Purchase_return/loadForm/confirm') ?>",
            success:function(response){
                $('.button_mini_tab').attr('disabled', false);
                $('#createReturnButton').removeClass('active');
                $('#confirmReturnButton').attr('disabled', true);
                $('#confirmReturnButton').addClass('active');

                $('#viewPane').html(response);
            }
        })
    })
</script>
