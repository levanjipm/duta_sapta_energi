<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SalesAnalytics extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->has_userdata('user_id') == FALSE){
			redirect(site_url('login'));

		}
	}
	
	public function index()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);

		$data					= array();
		$this->load->model("Brand_model");
		$data['brands']			= $this->Brand_model->getItems();

		$this->load->view('sales/Analytics/dashboard', $data);
	}

	public function getCustomers()
	{
		$page		= $this->input->get('page');
		$term		= $this->input->get('term');
		$offset		= ($page - 1) * 25;
		$month		= $this->input->get('month');
		$year		= $this->input->get('year');
		$brand		= $this->input->get('brand');

		$this->load->model("Customer_target_model");
		$data['items'] = $this->Customer_target_model->getItems($month, $year, $brand, $offset, $term);

		$this->load->model("Customer_model");
		$data['pages'] = max(1, ceil($this->Customer_target_model->countItems($month, $year, $brand, $term)/25));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getSalesOrderByCustomerId()
	{
		$customerId			= $this->input->get('id');
		$salesOrderArray = array();
		$this->load->model("Sales_order_model");
		$salesOrders = $this->Sales_order_model->getCountByCustomerId($customerId);
		foreach($salesOrders as $salesOrder){
			$month		= $salesOrder->month;
			$year		= $salesOrder->year;
			$date		= date("Ym", mktime(0,0,0,$month, 1, $year));
			$count		= $salesOrder->count;
			$value		= $salesOrder->value;
			$salesOrderArray[$date] = array(
				"value" => $value,
				"count" => $count
			);
		}

		for($i = 0; $i < 12; $i++){
			$date = date('Ym', strtotime("-" . (11 - $i) . "months"));
			if(!array_key_exists($date, $salesOrderArray)){
				$salesOrderArray[$date] = array(
					"value" => 0,
					"count" => 0
				);
			};
		}

		$data = (object) $salesOrderArray;
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getByCustomerId()
	{
		$customerId			= $this->input->get('id');
		$brandId			= $this->input->get('brand');
		
		$this->load->model("Customer_target_model");
		$data['target'] = $this->Customer_target_model->getByCustomerId($customerId, $brandId);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getValueByItemType()
	{
		$customerId			= $this->input->get('customerId');
		$type				= $this->input->get("type");

		$this->load->model("Invoice_model");
		$data				= $this->Invoice_model->getByItemType($customerId, $type);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function calculatePayments()
	{
		$this->load->model("Invoice_model");
		$data[0] = $this->Invoice_model->calculatePayments(1);
		$data[1] = $this->Invoice_model->calculatePayments(2);
		$data[2] = $this->Invoice_model->calculatePayments(3);
		$data[3] = $this->Invoice_model->calculatePayments(4);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function salesmanDetailReport($month, $year, $salesId = NULL)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);

		$this->load->view('head');
		$this->load->view('sales/header', $data);

		$data			= array();
		if($salesId != NULL){
			$data['salesman']	= $this->User_model->getByid($salesId);
		} else {
			$data['salesman']	= (object) array(
				"id" => 0,
				"name" => "Office",
				"address" => "",
				"bank_account" => "",
				"email" => "",
				"access_level" => ""
			);
		}
		
		$data['month']	= $month;
		$data['year']	= $year;

		$this->load->model("Invoice_model");
		$data['sales']	= $this->Invoice_model->getBySalesmanMonthYear($month, $year, $salesId);

		$this->load->model("Sales_order_model");
		$data['salesOrders']	= $this->Sales_order_model->getBySalesmanMonthYear($month, $year, $salesId);

		$this->load->view('sales/Analytics/salesmanReport', $data);
	}

	public function salesReport($month, $year, $brand)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);

		$data			= array();
		$this->load->model("Brand_model");
		$data['brand']	= $this->Brand_model->getById($brand);

		$this->load->model("Customer_target_model");
		$data['month']	= $month;
		$data['year']	= $year;
		$data['customers'] = $this->Customer_target_model->getItems($month, $year, $brand, 0, "", 0);

		$this->load->view('sales/Analytics/salesReport', $data);
	}

	public function exportSalesReportCSV()
	{
		$month			= $this->input->post('month');
		$year			= $this->input->post('year');
		$brand			= $this->input->post('brand');
		$areaArray		= array();

		$file_name = 'customerSalesReport' . $month . $year . '.csv'; 
		header("Content-Description: File Transfer"); 
		header("Content-Disposition: attachment; filename=$file_name"); 
		header("Content-Type: application/csv;");
   
		$this->load->model("Customer_target_model");
		$customers		= $this->Customer_target_model->getItems($month, $year, $brand, 0, "", 0);

		$file = fopen('php://output', 'w');
		$headerInformation = array("Period", date("m", mktime(0, 0, 0, $month, 1,$year)), date("Y", mktime(0,0, 0,$month, 1, $year)));
		fputcsv($file, $headerInformation, ';');

		$header = array("Name","Address", "City", "Area", "PreviousTarget", "CurrentTarget", "PreviousAchivement", "CurrentAchivement"); 
		fputcsv($file, $header, ';');

		foreach ($customers as $customer)
		{
			$complete_address	= $customer->address;
			$customer_number	= $customer->number;
			$customer_block		= $customer->block;
			$customer_rt		= $customer->rt;
			$customer_rw		= $customer->rw;
			$customer_city		= $customer->city;
			$customer_postal	= $customer->postal_code;
			$areaName			= $customer->areaName;
			$areaId				= $customer->area_id;

			if($customer_number != null && $customer_number != ''){
				$complete_address	.= ' no. ' . $customer_number;
			};
			
			if($customer_block != null && $customer_block != '' && $customer_block != "000"){
				$complete_address	.= ', blok ' . $customer_block;
			};
			
			if($customer_rt != '000'){
				$complete_address	.= ', RT ' . $customer_rt . ', RW ' . $customer_rw;
			}
			
			if($customer_postal != ''){
				$complete_address .= ', ' . $customer_postal;
			}

			$value			= $customer->value;
			$returned		= $customer->returned;
			$totalValue		= $value - $returned;
			$currentTarget	= $customer->target;

			$prevValue		= $customer->previousValue;
			$prevReturned	= $customer->previousReturned;
			$prevTotalValue	= $prevValue - $prevReturned;
			$prevTarget		= $customer->previousTarget;

			$valueArray		= array(
				$customer->name,
				$complete_address,
				$customer_city,
				$areaName,
				$prevTarget,
				$currentTarget,
				$prevTotalValue,
				$totalValue				
			);

			if(!array_key_exists($areaId, $areaArray)){
				$areaArray[$areaId]		= array(
					"name" => $areaName,
					"value" => $totalValue,
					"target" => $currentTarget
				);
			} else {
				$areaArray[$areaId]['value'] += $totalValue;
				$areaArray[$areaId]['target'] += $currentTarget;
			}

			fputcsv($file, $valueArray, ';');
		}

		$header = array("Area", "Target", "Value"); 
		fputcsv($file, $header, ';');
		foreach($areaArray as $area){
			$value		= $area['value'];
			$target		= $area['target'];
			$name		= $area['name'];
			$rowData	= array($name, $target, $value);
			fputcsv($file, $rowData, ';');
		}

		fclose($file); 
		exit;
	}

	public function getNoo()
	{
		$month			= $this->input->get('month');
		$year			= $this->input->get('year');
		$brand			= $this->input->get('brand');

		$this->load->model("Customer_model");
		$data		= $this->Customer_model->getNoo($month, $year, $brand);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function GetNooCsv()
	{
		$month			= $this->input->get('month');
		$year			= $this->input->get('year');
		$brand			= $this->input->get('brand');

		$file_name = 'nooReport' . $month . $year . '-' . $brand . '.csv'; 
		header("Content-Description: File Transfer"); 
		header("Content-Disposition: attachment; filename=$file_name"); 
		header("Content-Type: application/csv;");
   
		$this->load->model("Customer_target_model");
		$customers		= $this->Customer_target_model->getItems($month, $year,$brand, 0, "");
 
		$file = fopen('php://output', 'w');
		$headerInformation = array("Period", date("m", mktime(0, 0, 0, $month, 1, $year)), date("Y", mktime(0,0, 0,$month, 1, $year)));
		fputcsv($file, $headerInformation, ';');

		$header = array("Name","Address", "City", "Area"); 
		fputcsv($file, $header, ';');

		$this->load->model("Customer_model");
		$customers		= $this->Customer_model->getNoo($month, $year, $brand);

		foreach ($customers as $customer)
		{
			$complete_address	= $customer->address;
			$customer_name		= $customer->name;
			$customer_number	= $customer->number;
			$customer_block		= $customer->block;
			$customer_rt		= $customer->rt;
			$customer_rw		= $customer->rw;
			$customer_city		= $customer->city;
			$customer_postal	= $customer->postal_code;
			$areaName			= $customer->area;
			$areaId				= $customer->area_id;

			if($customer_number != null && $customer_number != ''){
				$complete_address	.= ' no. ' . $customer_number;
			};
			
			if($customer_block != null && $customer_block != '' && $customer_block != "000"){
				$complete_address	.= ', blok ' . $customer_block;
			};
			
			if($customer_rt != '000'){
				$complete_address	.= ', RT ' . $customer_rt . ', RW ' . $customer_rw;
			}
			
			if($customer_postal != ''){
				$complete_address .= ', ' . $customer_postal;
			}

			$valueArray		= array(
				"name" => $customer_name,
				"address" => $complete_address,
				"city" => $customer_city,
				"area" => $areaName
			);

			fputcsv($file, $valueArray, ';');
			continue;
		}

		fclose($file); 
		exit;
	}
}
