<div class='dashboard'>
	<div class='dashboard_head'>
		<p style='font-family:museo'><a href='<?= site_url('Inventory') ?>' title='Inventory'><i class='fa fa-briefcase'></i></a> / Cases</p>
	</div>
	<br>
	<div class='dashboard_in'>
		<a href='<?= site_url('Inventory_case/confirm_dashboard') ?>'>Confirm</a>
		<div class='row'>
			<div class='col-md-3 col-sm-4 col-xs-6'>
				<a href='<?= site_url('Inventory_case/lost_goods') ?>'>Lost goods</a>
			</div>
			<div class='col-md-3 col-sm-4 col-xs-6'>
				<a href='<?= site_url('Inventory_case/found_goods') ?>'>Found goods</a>
			</div>
		</div>
	</div>
</div>