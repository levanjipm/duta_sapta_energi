<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receivable extends CI_Controller {
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
		
		$this->load->view('accounting/receivable_dashboard');
	}
	
	public function viewReceivable()
	{	
		$date_1		= $this->input->get('date_1');
		$date_2		= $this->input->get('date_2');
		
		$this->load->model('Invoice_model');
		$data	= $this->Invoice_model->viewReceivableChart($date_1, $date_2);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function getReceivableByCustomerId()
	{
		$customerId = $this->input->get('id');

		$this->load->model('Invoice_model');
		$data['receivable'] = $this->Invoice_model->viewReceivableByCustomerId($customerId);
		
		$this->load->model('Customer_model');
		$data['customer'] = $this->Customer_model->getById($customerId);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
