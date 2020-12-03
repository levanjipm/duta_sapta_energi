<head>
	<title>Administrators</title>
	<style>
		.departmentBoxWrapper{
			margin-bottom:20px;
		}
		.departmentBox{
			border-radius:10px;
			background-color:white;
			width:100%;
			margin-bottom:20px;
			height:100%;
		}

		.departmentBoxHeader{
			color:white;
			background-color:#E19B3C;
			padding:10px;
			border-top-left-radius:10px;
			border-top-right-radius:10px;
		}
		
		.departmentBoxFunctionsWrapper{
			padding:10px;
		}
	</style>
</head>
<?php
	$departmentArray		= array();
	foreach($departments as $department){
		array_push($departmentArray, (int)$department->department_id);
	}
?>
<div class='row' style='padding:20px; padding-top:120px; margin:0;'>
	<?php if(in_array(1, $departmentArray)){ ?>
	<div class='col-md-3 col-sm-4 col-xs-6 departmentBoxWrapper'>
		<div class='departmentBox'>
			<div class='departmentBoxHeader'>
				<label>Accounting</label>
			</div>
			<div class='departmentBoxFunctionsWrapper'>
				<a href='<?= site_url('Invoice/deleteDashboard') ?>'><p style='font-family:museo'>Delete Invoice</p></a>
				<a href='<?= site_url('Debt/deleteDashboard') ?>'><p style='font-family:museo'>Delete Debt</p></a>
			</div>
		</div>
	</div>
	<?php } ?>
	<?php if(in_array(5, $departmentArray)){ ?>
	<div class='col-md-3 col-sm-4 col-xs-6 departmentBoxWrapper'>
		<div class='departmentBox'>
			<div class='departmentBoxHeader'>
				<label>Finance</label>
			</div>
			<div class='departmentBoxFunctionsWrapper'>
			<a href='<?= site_url('Bank/deleteDashboard') ?>'><p style='font-family:museo'>Delete Bank Data</p></a>
			<a href='<?= site_url('Petty_cash/deleteDashboard') ?>'><p style='font-family:museo'>Delete Petty Cash Data</p></a>
			<a href='<?= site_url('Billing/deleteDashboard') ?>'><p style='font-family:museo'>Delete Delete Billing</p></a>
			</div>
		</div>
	</div>
	<?php } ?>
	<?php if(in_array(4, $departmentArray)){ ?>
	<div class='col-md-3 col-sm-4 col-xs-6 departmentBoxWrapper'>
		<div class='departmentBox'>
			<div class='departmentBoxHeader'>
				<label>Inventory</label>
			</div>
			<div class='departmentBoxFunctionsWrapper'>
				<a href='<?= site_url('Delivery_order/deleteDashboard') ?>'><p style='font-family:museo'>Delete Delivery Order</p></a>
				<a href='<?= site_url('Good_receipt/deleteDashboard') ?>'><p style='font-family:museo'>Delete Good Receipt</p></a>
				<a href='<?= site_url('Sales_return/deleteDashboard') ?>'><p style='font-family:museo'>Delete Sales Return</p></a>
				<a href='<?= site_url('Purchase_return/deleteDashboard') ?>'><p style='font-family:museo'>Delete Purchase Return</p></a>
			</div>
		</div>
	</div>
	<?php } ?>
	<?php if(in_array(3, $departmentArray)){ ?>
	<div class='col-md-3 col-sm-4 col-xs-6 departmentBoxWrapper'>
		<div class='departmentBox'>
			<div class='departmentBoxHeader'>
				<label>Purchasing</label>
			</div>
			<div class='departmentBoxFunctionsWrapper'>
				<a href='<?= site_url('Purchase_order/editDashboard') ?>'><p style='font-family:museo'>Edit Purchase Order</p></a>
				<a href='<?= site_url('Purchase_order/closeDashboard') ?>'><p style='font-family:museo'>Close Purchase Order</p></a>
				<a href='<?= site_url('Purchase_return/cancelDashboard') ?>'><p style='font-family:museo'>Cancel Purchase Return</p></a>
			</div>
		</div>
	</div>
	<?php } ?>
	<?php if(in_array(2, $departmentArray)){ ?>
	<div class='col-md-3 col-sm-4 col-xs-6 departmentBoxWrapper'>
		<div class='departmentBox'>
			<div class='departmentBoxHeader'>
				<label>Sales</label>
			</div>
			<div class='departmentBoxFunctionsWrapper'>
			<a href='<?= site_url('Visit_list/cancelDashboard') ?>'><p style='font-family:museo'>Cancel Visit List</p></a>
			<a href='<?= site_url('Sales_return/cancelDashboard') ?>'><p style='font-family:museo'>Cancel Sales Return</p></a>
			</div>
		</div>
	</div>
	<?php } ?>
</div>