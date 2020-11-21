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
		$this->load->view('accounting/Receivable/dashboard');
	}

	public function finance()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);
		$this->load->view('finance/Receivable/dashboard');
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

		$this->load->model("Bank_model");
		$data['pendingBank'] = $this->Bank_model->getUnassignedByCustomerId($customerId);
		
		$this->load->model('Customer_model');
		$data['customer'] = $this->Customer_model->getById($customerId);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getCompleteReceivableByCustomerId()
	{
		$customerId = $this->input->get('id');
		$this->load->model('Invoice_model');
		$data['invoices'] = $this->Invoice_model->viewCompleteReceivableByCustomerId($customerId);

		$this->load->model("Receivable_model");
		$data['receivables'] = $this->Receivable_model->viewReceivableByCustomerId($customerId);

		$this->load->model("Bank_model");
		$data['pendingBank'] = $this->Bank_model->getUnassignedByCustomerId($customerId);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getCompleteReceivableByOpponentId()
	{
		$opponentId		= $this->input->get('id');
		$this->load->model("Invoice_model");
		$data['invoices'] = $this->Invoice_model->viewCompleteReceivableByOpponentId($opponentId);

		$this->load->model("Receivable_model");
		$data['receivables'] = $this->Receivable_model->viewReceivableByOpponentId($opponentId);

		$this->load->model("Bank_model");
		$data['pendingBank'] = $this->Bank_model->getUnassignedByOpponentId($opponentId);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getReceivableByOpponentId()
	{
		$opponentId = $this->input->get('id');

		$this->load->model("Invoice_model");
		$data['invoices'] = $this->Invoice_model->viewCompleteReceivableByOpponentId($opponentId);

		$this->load->model("Bank_model");
		$data['pendingBank'] = $this->Bank_model->getUnassignedByOpponentId($opponentId);

		$this->load->model("Opponent_model");
		$data['opponent'] = $this->Opponent_model->getById($opponentId);

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

	public function viewByCustomerId($customerId)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);

		$data = array();
		$this->load->model("Customer_model");
		$data['customer'] = $this->Customer_model->getById($customerId);

		$this->load->view('accounting/Receivable/customerReceivable', $data);
	}

	public function viewFinanceByCustomerId($customerId)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);

		$data = array();
		$this->load->model("Customer_model");
		$data['customer'] = $this->Customer_model->getById($customerId);

		$this->load->view('finance/Receivable/customerReceivable', $data);
	}

	public function viewByOpponentId($opponentId)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);

		$data = array();
		$this->load->model("Opponent_model");
		$data['opponent'] = $this->Opponent_model->getById($opponentId);

		$this->load->view('accounting/Receivable/opponentReceivable', $data);
	}

	public function viewFinanceByOpponentId($opponentId)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);

		$data = array();
		$this->load->model("Opponent_model");
		$data['opponent'] = $this->Opponent_model->getById($opponentId);

		$this->load->view('finance/Receivable/opponentReceivable', $data);
	}

	public function getCustomerOpponentItems(){
		$page			= $this->input->get('page');
		$term			= $this->input->get('term');
		$offset			= ($page - 1) * 25;
		$this->load->model("Receivable_model");
		$data['items'] = $this->Receivable_model->getCustomerOpponentItems($offset, $term);
		$data['pages'] = max(1, ceil($this->Receivable_model->countCustomerOpponentItems($term)/25));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function setInvoiceAsDone()
	{
		$date			= $this->input->post('date');
		$id				= $this->input->post('id');
		$this->load->model("Receivable_model");
		$result = $this->Receivable_model->setInvoiceAsDone($id, $date);
		if($result == 1){
			$this->load->model("Invoice_model");
			$this->Invoice_model->setInvoiceAsDone($id);
			echo 1;
		} else {
			echo 0;
		}
	}

	public function deleteBlankById()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$user			= $this->User_model->getById($user_id);

		if($user->access_level > 2){
			$receivableId			= $this->input->post('id');
			$this->load->model("Receivable_model");
			$result = $this->Receivable_model->deleteBlankById($receivableId);

			if($result != NULL){
				$this->load->model("Invoice_model");
				$this->Invoice_model->updateDoneStatusByIdArray($result, 0);
				echo 1;
			}
		} else {
			echo 0;
		}		
	}

	public function resetByBankId()
	{
		$bankId			= $this->input->post('id');
		$this->load->model("Bank_model");
		$this->load->model("Receivable_model");
		$bank		= $this->Bank_model->getById($bankId);
		$parentId	= $bank->bank_transaction_major;
		if($parentId == NULL){
			$status = false;
		} else {
			$bankChildData		= $this->Bank_model->getChildByParentId($parentId, $bankId);
			$status				= true;
			foreach($bankChildData as $bankChild){
				if($bankChild->is_done != 0 || $bankChild->is_delete != 0){
					$status		= false;
					break;
				}
				next($bankChildData);
			}
		}

		$this->Receivable_model->deleteByBankId($bankId);
		$this->Bank_model->updateUndoneById($bankId);

		if($status){
			$this->Bank_model->mergeByParentId($parentId);
		}
	}

	public function viewCompleteByCustomerId($customerId)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);

		$data = array();
		$this->load->model("Customer_model");
		$data['customer'] = $this->Customer_model->getById($customerId);

		$this->load->view('accounting/Receivable/customerCompleteReceivable', $data);
	}

	public function viewFinanceCompleteByCustomerId($customerId)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);

		$data = array();
		$this->load->model("Customer_model");
		$data['customer'] = $this->Customer_model->getById($customerId);

		$this->load->view('finance/Receivable/customerCompleteReceivable', $data);
	}
}
