<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounting extends CI_Controller {
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
		$this->load->view('accounting/header', $data);
		$this->load->view('accounting/dashboard');
	}

	public function salesReturn()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		$this->load->view('accounting/return/salesReturnDashboard');
	}

	public function getPendingInvoice()
	{
		$this->load->model("Delivery_order_model");
		$result			= $this->Delivery_order_model->countUninvoicedDeliveryOrders(0);
		echo $result;
	}

	public function getPendingDebt()
	{
		$this->load->model("Good_receipt_model");
		$result			= $this->Good_receipt_model->count_uninvoiced_documents(0);
		echo $result;
	}

	public function getPendingCustomers()
	{
		$this->load->model("Customer_accountant_model");
		$result			= $this->Customer_accountant_model->countUnassignedCustomers();
		$data['unassigned'] = $result[0]->customerCount;
		$data['total'] = $result[1]->customerCount;

		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
