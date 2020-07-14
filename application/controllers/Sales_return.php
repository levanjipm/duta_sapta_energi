<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_return extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->has_userdata('user_id') == FALSE){
			redirect(site_url('login'));

		}
	}
	
	public function create()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		$this->load->view('sales/return_dashboard');
	}
	
	public function authenticate()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		
		$data = array();
		
		$delivery_order_id			= $this->input->post('id');
		$this->load->model('Delivery_order_model');
		$delivery_order = $this->Delivery_order_model->show_by_id($delivery_order_id);
		$data['general'] = $delivery_order;
		
		$this->load->model('Delivery_order_detail_model');
		$data['items'] = $this->Delivery_order_detail_model->show_by_code_delivery_order_id($delivery_order_id);
		
		$this->load->view('sales/return_validation', $data);
	}
	
	public function input()
	{
		$total_quantity = array_sum($this->input->post('return_quantity'));
		if($total_quantity > 0)
		{
			$this->load->model('Sales_return_model');
			$code_sales_return_id = $this->Sales_return_model->insert_from_post();
			
			if($code_sales_return_id != null)
			{
				$this->load->model('Sales_return_detail_model');
			}
		}
		
		redirect(site_url('Sales_return/create'));
	}
	
	public function confirm()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
	}
}
