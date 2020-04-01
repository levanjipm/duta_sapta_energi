<style>
	.box_dashboard{
		margin-top:20px;
		margin-left:20px;
		width:30%;
		background-color:transparent;
		border:2px solid #E19B3C;
	}
</style>
<div class='dashboard'>
	<div class='box_dashboard'><h2 id='needs'></h2></div>
</div>

<script>
	calculate_needs();
	
	function calculate_needs(){
		$.ajax({
			url:'<?= site_url('Purchasing/calculate_needs') ?>',
			success:function(response){
				var needs		= response.length;
				console.log(needs);
			}
		});
	};
</script>