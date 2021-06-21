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
		$date			= $this->input->get('date');
		$collector		= $this->input->get('collector');
		if(!isset($date) || !isset($collector) || $date < date('2020-01-01')){
			redirect(site_url("Billing/createDashboard"));
		} else {
			$user_id		= $this->session->userdata('user_id');
			$this->load->model('User_model');
			$data['user_login'] = $this->User_model->getById($user_id);
			
			$this->load->model('Authorization_model');
			$data['departments']	= $this->Authorization_model->getByUserId($user_id);
			
			$this->load->view('head');
			$this->load->view('finance/header', $data);

			$data = array();
			$data['date'] = $date;
			$data['collector'] = $collector;
			$this->load->view('finance/Billing/createForm', $data);
		}
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

	public function getUrgentList()
	{
		$date		= $this->input->get('date');
		$page		= $this->input->get('page');
		$term		= $this->input->get('term');
		$offset		= ($page - 1) * 10;

		$this->load->model("Invoice_model");
		$data['items'] = $this->Invoice_model->getUrgentList($date, $offset, $term);
		$data['pages'] = max(1, ceil($this->Invoice_model->countUrgentList($date, $offset, $term)/10));
		
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
		$data['items']		= $this->Invoice_model->viewCompleteReceivableByCustomerId($customerId);

		$this->load->model("Customer_model");
		$data['customer']	= $this->Customer_model->getById($customerId);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getByIdArray()
	{
		$invoiceArray = $_REQUEST['invoices'];
		$this->load->model("Invoice_model");
		$data = $this->Invoice_model->getByIdArray($invoiceArray);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function insertItem()
	{
		$this->load->model("Billing_model");
		$billingId = $this->Billing_model->insertItem($_REQUEST['date'], $_REQUEST['collector']);
		if($billingId != NULL){
			$this->load->model("Billing_detail_model");
			$this->Billing_detail_model->insertItem($billingId);

			echo 1;
		} else {
			echo 0;
		}
	}

	public function confirmDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);
		$this->load->view('finance/Billing/confirmDashboard');
	}

	public function getUnconfirmedBilling()
	{
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 10;
		$this->load->model("Billing_model");
		$data['items'] = $this->Billing_model->getUnconfirmedItems($offset);
		$data['pages'] = max(1, ceil($this->Billing_model->countUnconfirmedItems()/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getById()
	{
		$id			= $this->input->get('id');
		
		$this->load->model("Billing_model");
		$data['general'] = $this->Billing_model->getById($id);

		$this->load->model("Billing_detail_model");
		$data['items'] = $this->Billing_detail_model->getByCodeId($id);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function confirmById()
	{
		$id			= $this->input->post('id');
		$this->load->model("Billing_model");
		$result		= $this->Billing_model->updateById(1, $id);
		echo $result;
	}

	public function deleteById()
	{
		$id			= $this->input->post('id');
		$this->load->model("Billing_model");
		$result		= $this->Billing_model->updateById(0, $id);
		echo $result;
	}


	public function printBilling($billingId)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);

		$data = array();
		$this->load->model("Billing_model");
		$data['billing'] = $this->Billing_model->getById($billingId);
		
		$this->load->model("Billing_detail_model");
		$data['items'] = $this->Billing_detail_model->getByCodeId($billingId);

		$this->load->view('finance/Billing/printBilling', $data);
	}

	public function reportDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);
		$this->load->view('finance/Billing/reportDashboard');
	}

	public function getIncompletedItems()
	{
		$this->load->model("Billing_model");
		$data = $this->Billing_model->getIncompletedItems();

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function fileReport()
	{
		$id = $this->input->post('id');
		$this->load->model("Billing_model");
		$result = $this->Billing_model->updateReport($id);
		if($result == 1){
			$billing	= $this->Billing_model->getById($id);
			$date		= $billing->date;
			$this->load->model("Billing_detail_model");
			$this->Billing_detail_model->updateReport($date, $this->input->post('result'), $this->input->post('note'), $this->input->post('nextBillingDate'));
			echo 1;
		} else {
			echo 0;
		}	
	}

	public function archiveDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);
		$this->load->view('finance/Billing/archiveDashboard');
	}

	public function GetArchive()
	{
		$page		= $this->input->get('page');
		$month		= $this->input->get('month');
		$year		= $this->input->get('year');
		$offset		= ($page - 1) * 10;

		$this->load->model("Billing_model");
		$data['items'] = $this->Billing_model->getArchive($month, $year, $offset);
		$data['pages'] = max(1, ceil($this->Billing_model->countArchive($month, $year))/10);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function deleteDashboard(){
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);
		$this->load->view('administrator/Billing/deleteDashboard');
	}
}
