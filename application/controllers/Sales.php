<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales extends CI_Controller {
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

		$data = array();

		$this->load->model('Sales_order_model');
		$data['incompleteSalesOrder'] = $this->Sales_order_model->countIncompletedSalesOrder();

		$this->load->model('Customer_model');
		$data['activeCustomer'] = $this->Customer_model->countActiveCustomer(date('m'), date('Y'));

		$data['customer'] = $this->Customer_model->countItems();

		$this->load->model('Plafond_model');
		$data['plafondSubmission'] = $this->Plafond_model->countUnconfirmedSubmission();

		$this->load->view('sales/dashboard', $data);
	}

	public function viewSalesByMonth()
	{
		$month_end = $this->input->get('month');
		$range = $this->input->get('range');

		$this->load->model('Invoice_model');
		$result = array();
		for($i = 0; $i <= $range; $i++){
			$monthObserved = date('m', strtotime("-" . $i . " months"));
			$yearObserved = date('Y', strtotime("-" . $i . " months"));

			$value = $this->Invoice_model->getValueByMonthYear($monthObserved, $yearObserved);
			$valueArray = array(
				'label' => date('F Y', strtotime("-" . $i . " months")),
				'value' => $value
			);

			array_push($result, $valueArray);
		};

		header('Content-Type: application/json');
		echo json_encode(array_reverse($result));
	}

	public function viewSalesByCustomer()
	{
		$month		= $this->input->get('month');
		$year		= $this->input->get('year');
		$offset		= $this->input->get('offset');

		$this->load->model('Invoice_model');
		$result = $this->Invoice_model->getByMonthYear($month, $year, $offset);

		header('Content-Type: application/json');
		echo json_encode(array_reverse($result));
	}

	public function getByAspect()
	{
		$aspect			= $this->input->get('aspect');
		$month			= $this->input->get('month');
		$year			= $this->input->get('year');
		if($aspect == "sales")
		{
			$this->load->model("Invoice_model");
			$data		= $this->Invoice_model->calculateAspect(1, $month, $year);
		}

		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
