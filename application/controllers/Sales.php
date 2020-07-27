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
}
