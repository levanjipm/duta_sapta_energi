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
}
