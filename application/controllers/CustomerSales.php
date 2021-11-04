<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CustomerSales extends CI_Controller {
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

		if($data['user_login']->access_level > 2){
			$this->load->model('Authorization_model');
			$data['departments']	= $this->Authorization_model->getByUserId($user_id);
			
			$this->load->view('head');
			$this->load->view('sales/header', $data);
			$this->load->view('sales/Customer/assignSalesDashboard');
		} else {
			redirect(site_url("Welcome"));
		}
	}

	public function assignForm()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);

		$this->load->view('head');
		$this->load->view('sales/header', $data);

		$data			= array();
		$sales			= $this->input->get('sales');
		$data['sales']		= $this->User_model->getById($sales);
		
		$this->load->view('sales/Customer/assignSalesForm', $data);
	}

	public function getBySales()
	{
		$salesId			= $this->input->post('sales');
		$page				= $this->input->post('page');
		$offset				= ($page - 1) * 10;
		$term				= $this->input->post('term');
		$includedAreas		= (empty($this->input->post('includedAreas'))) ? array() : $this->input->post('includedAreas');

		$this->load->model("Customer_sales_model");
		$data['items']		= $this->Customer_sales_model->getBySales($salesId, $offset, $term, $includedAreas);
		$data['pages']		= max(1, ceil($this->Customer_sales_model->countBySales($salesId, $term, $includedAreas)/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function updateBySales()
	{
		$salesId			= $this->input->post('salesId');
		$includedCustomers	= (!empty($this->input->post('includedCustomers'))) ? $this->input->post('includedCustomers') : array();
		$removedCustomers	= (!empty($this->input->post('removedCustomers'))) ? $this->input->post('removedCustomers') : array();

		$this->load->model("Customer_sales_model");

		if(count($removedCustomers) > 0){
			$this->Customer_sales_model->updateCustomerList($removedCustomers, 0);
		}

		if(count($includedCustomers) > 0){
			$this->Customer_sales_model->updateCustomerList($includedCustomers, 1, $salesId);
		}
	}

	public function viewBySales($salesId)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);

		$this->load->view('head');
		$this->load->view('sales/header', $data);

		$data		= array();
		$this->load->model("Customer_sales_model");
		$data['sales']			= $this->User_model->getById($salesId);
		$data['assignment']		= $this->Customer_sales_model->getBySales($salesId, 0, "", array(), 0);
		$this->load->view('sales/Customer/assignSales', $data);
	}
}
