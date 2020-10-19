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
			$this->load->view('sales/customer/assignSalesDashboard');
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
		
		$this->load->view('sales/customer/assignSalesForm', $data);
	}

	public function getBySales()
	{
		$salesId			= $this->input->post('sales');
		$page				= $this->input->post('page');
		$offset				= ($page - 1) * 10;
		$term				= $this->input->post('term');
		$includedAreas		= $this->input->post('includedAreas');
		$this->load->model("CustomerSalesModel");
		$this->CustomerSalesModel->
	}
}
