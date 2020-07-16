<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finance extends CI_Controller {
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
		$this->load->view('finance/header', $data);
		
		$this->load->view('finance/index_page');
	}
	
	public function view_recommendation_list()
	{
		$this->load->model('Invoice_model');
		$data	= $this->Invoice_model->create_recommendation_list();
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function recommendation_list()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);
		
		$this->load->view('finance/recommended_dashboard');
	}
	
	public function view_recommendation_by_customer_id()
	{
		$customer_id		= $this->input->get('customer_id');
		$invoice_id			= $this->input->get('invoice_id');
		$this->load->model('Reminder_model');
		$data['status']		= $this->Reminder_model->show_status_by_customer_id($customer_id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
