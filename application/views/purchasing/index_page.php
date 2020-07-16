<style>
	.box_dashboard{
		margin-top:20px;
		margin-left:20px;
		width:30%;
		background-color:transparent;
		border:2px solid #E19B3C;
		text-align:center;
		color:white;
	}
</style>
<div class='dashboard'>
	<div class='box_dashboard'><h2><span id='needs'></span> <br>items need to be bought.</h2></div>
</div>

<script>
	calculate_needs();
	
	function calculate_needs(){
		$.ajax({
			url:'<?= site_url('Purchasing/calculateNeeds') ?>',
			success:function(response){
				var needs		= response.length;
				$('#needs').text(needs);
			}
		});
	};
</script>