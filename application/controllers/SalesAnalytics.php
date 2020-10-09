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
}
