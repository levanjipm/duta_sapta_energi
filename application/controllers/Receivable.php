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
		$this->load->view('accounting/receivableDashboard');
	}
	
	public function viewReceivable()
	{	
		$category		= $this->input->get('category');
		$this->load->model('Invoice_model');
		$data	= $this->Invoice_model->viewReceivableChart($category);
		
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

	public function getReceivables()
	{
		$page = $this->input->get('page');
		$term	= $this->input->get('term');

		$offset = ($page - 1) * 10;
		$this->load->model('Invoice_model');
		$data['items'] = $this->Invoice_model->getBillingData($offset, $term);
		$data['pages'] = max(1, ceil($this->Invoice_model->countBillingData($term)/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getReceivablesSuggestions()
	{
		$date = $this->input->get('date');
		$this->load->model('Customer_model');

		$dataArray = array();

		$this->load->model('Invoice_model');
		$result = (array)$this->Invoice_model->getBillingSuggestionData($date);
		foreach($result as $item)
		{
			$resultArray = (array) $item;
			$customerId = $item->customer_id;
			$customerObject = $this->Customer_model->getById($customerId);
			$customerArray = (array) $customerObject;
			$resultArray['customer'] = $customerArray;
			array_push($dataArray, $resultArray);
		}

		$data = (object) $dataArray;

		header('Content-Type: application/json');
		echo json_encode($data);
	}
}