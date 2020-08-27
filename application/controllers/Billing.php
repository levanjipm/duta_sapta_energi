<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->has_userdata('user_id') == FALSE){
			redirect(site_url('login'));

		}
	}
	
	public function createDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);
		$this->load->view('finance/Billing/createDashboard');
	}

	public function createForm()
	{
		$date		= $this->input->get('date');
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);

		$data = array();
		$data['date'] = $date;
		$this->load->view('finance/Billing/createForm', $data);
	}

	public function getRecommendationList()
	{
		$date		= $this->input->get('date');
		$page		= $this->input->get('page');
		$term		= $this->input->get('term');
		$offset		= ($page - 1) * 10;

		$this->load->model("Invoice_model");
		$data['items'] = $this->Invoice_model->getRecommendationList($date, $offset, $term);
		$data['pages'] = max(1, ceil($this->Invoice_model->countRecommendationList($date, $offset, $term)/10));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getBillingData()
	{
		$page		= $this->input->get('page');
		$term		= $this->input->get('term');
		$offset		= ($page - 1) * 10;

		$this->load->model("Invoice_model");
		$data['items'] = $this->Invoice_model->getBillingData($offset, $term);
		$data['pages'] = max(1, ceil($this->Invoice_model->countBillingData($term)/10));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getByCustomerId()
	{
		$customerId = $this->input->get('id');
		$this->load->model("Invoice_model");
		$data		= $this->Invoice_model->getReceivableByCustomerId($customerId);

		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
