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
		$this->load->view('sales/Analytics/dashboard');
	}

	public function getCustomers()
	{
		$page		= $this->input->get('page');
		$term		= $this->input->get('term');
		$offset		= ($page - 1) * 25;
		$month		= $this->input->get('month');
		$year		= $this->input->get('year');

		$this->load->model("Customer_target_model");
		$data['items'] = $this->Customer_target_model->getItems($offset, $term, $month, $year);

		$this->load->model("Customer_model");
		$data['pages'] = max(1, ceil($this->Customer_model->countItems($term)/25));

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
		$this->load->model("Customer_target_model");
		$data['target'] = $this->Customer_target_model->getByCustomerId($customerId);

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

	public function salesReport($month, $year)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);

		$data			= array();
		$this->load->model("Customer_target_model");
		$data['month']	= $month;
		$data['year']	= $year;
		$data['customers'] = $this->Customer_target_model->getItems(0, "", $month, $year, 0);

		$this->load->view('sales/Analytics/salesReport', $data);
	}

	public function exportSalesReportCSV()
	{
		$month			= $this->input->post('month');
		$year			= $this->input->post('year');
		
		$file_name = 'salesReport' . $month . $year . '.csv'; 
		header("Content-Description: File Transfer"); 
		header("Content-Disposition: attachment; filename=$file_name"); 
		header("Content-Type: application/csv;");
   
		$this->load->model("Customer_target_model");
		$customers		= $this->Customer_target_model->getItems(0, "", $month, $year, 0);
 
		$file = fopen('php://output', 'w');
		$header = array("Name","Address", "City", "Area", "Previous Target", "Current Target", "Previous Achivement", "Current Achivement"); 
		fputcsv($file, $header);
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

			if($customer_number != null && $customer_number != ''){
				$complete_address	.= ' no. ' . $customer_number;
			};
			
			if($customer_block != null && $customer_block != ''){
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

			$value		= array(
				$customer->name,
				$complete_address,
				$customer_city,
				$areaName,
				$prevTarget,
				$currentTarget,
				$prevTotalValue,
				$totalValue				
			);

			fputcsv($file, $value); 
		}
		fclose($file); 
		exit; 
	}

	public function getNoo()
	{
		$month			= $this->input->get('month');
		$year			= $this->input->get('year');
		$this->load->model("Customer_model");
		$data		= $this->Customer_model->getNoo($month, $year);

		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
