<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->has_userdata('user_id') == FALSE){
			redirect(site_url('login'));

		}
	}
	public function accountDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);
		$this->load->view('finance/Bank/accountDashboard');
	}
	
	public function transactionDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);

		$this->load->view('finance/Bank/transactionDashboard');
	}
	
	public function assignDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		
		$this->load->view('accounting/Bank/assignBankDashboard', $data);
	}
	
	public function getUnassignedTransactions($department)
	{
		if($department == 'accounting'){
			$type		= $this->input->get('type');
			$account	= $this->input->get('account');
			$page		= $this->input->get('page');
			$offset		= ($page - 1) * 10;
			$this->load->model('Bank_model');
			$data['banks'] = $this->Bank_model->getUnassignedTransactions($account, $type, $offset);
			$data['pages'] = max(1, ceil($this->Bank_model->countUnassignedTransactions($account, $type)/10));
		}
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function assign_do()
	{
		$bank_transaction_id	= $this->input->post('id');
		$this->load->model('Bank_model');
		$result			= $this->Bank_model->show_by_id($bank_transaction_id);
		$data['bank']	= $result;
		$type			= $result->transaction;
		$customer_id	= $result->customer_id;
		$supplier_id	= $result->supplier_id;
		$other_id		= $result->other_id;
		
		if($type == 1 && $customer_id != null){
			$this->load->model('Invoice_model');
			$data['invoices'] = $this->Invoice_model->getIncompletedTransaction($customer_id);
			$data['opponent'] = 'Customer';
		} else  if($type == 2 && $supplier_id != null){
			$this->load->model('Debt_model');
			$data['invoices'] = $this->Debt_model->getIncompletedTransaction($supplier_id);
			$data['opponent'] = 'Supplier';
		} else if($type == 2 && $other_id != null){
			$this->load->model('Debt_other_model');
			$data['invoices'] = $this->Debt_other_model->getIncompletedTransaction($other_id);
			$data['opponent'] = 'Supplier';
		}
		
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		$this->load->view('accounting/Bank/assignBank', $data);
	}
	
	public function insertAssign()
	{
		$bank_id		= $this->input->post('bank_id');
		
		$this->load->model('Bank_model');
		$result 		= $this->Bank_model->show_by_id($bank_id);

		$type			= $result->transaction;
		$customer_id	= $result->customer_id;
		$supplier_id	= $result->supplier_id;
		
		if($type == 1 && $customer_id != null){
			$this->Bank_model->assign_receivable($result);
		} else if($type == 2 && $supplier_id != null){
			$this->Bank_model->assign_payable($result);
		}
		
		redirect(site_url('Bank/assignDashboard'));
	}
	
	public function mutationDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);
		$this->load->view('finance/Bank/mutationDashboard', $data);
	}
	
	public function getMutation()
	{
		$account_id		= $this->input->get('account');
		$date_start		= $this->input->get('start');
		$date_end		= $this->input->get('end');
		$page			= $this->input->get('page');
		$offset			= ($page - 1) * 25;
		
		$this->load->model('Bank_model');
		$data['balance']	= $this->Bank_model->getBalance($account_id, $date_start);
		$data['mutations'] 	= $this->Bank_model->getMutation($account_id, $date_start, $date_end, $offset);
		$data['pages'] 		= max(1, ceil($this->Bank_model->countMutation($account_id, $date_start, $date_end, $offset)/25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function opponent()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);
		$this->load->view('finance/Opponent/dashboard');
	}
	
	public function showOpponent()
	{
		$page		= $this->input->get('page');
		$term		= $this->input->get('term');
		$offset		= ($page - 1) * 10;
		
		$type		= $this->input->get('type');
		if($type == 'customer'){ //customer
			$this->load->model('Customer_model');
			$data['opponents']			= $this->Customer_model->showItems($offset, $term);
			$data['pages']				= max(1, ceil($this->Customer_model->countItems($term)/10));
		} else if($type == 'supplier'){
			$this->load->model('Supplier_model');
			$data['opponents']			= $this->Supplier_model->showItems($offset, $term);
			$data['pages']				= max(1, ceil($this->Supplier_model->countItems($term)/10));
		} else if($type == 'other'){
			$this->load->model('Opponent_model');
			$data['opponents']			= $this->Opponent_model->getItems($offset, $term);
			$data['pages']				= max(1, ceil($this->Opponent_model->countItems($term)/10));
		}
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function add_other_opponent()
	{
		$this->load->model('Other_bank_account_model');
		$this->Other_bank_account_model->insert_from_post();
		
		redirect(site_url('Bank/opponent'));
	}
	
	public function view_transaction($type, $id)
	{
		$data['type'] 	= $type;
		$data['id']		= $id;
		$this->load->view('head');
		$this->load->view('finance/header');
		$this->load->view('finance/opponent_mutation', $data);
	}
	
	public function show_opponent()
	{
		$opponent_type	= $this->input->get('type');
		$term			= $this->input->get('term');
		$page			= $this->input->get('page');
		$offset			= ($page - 1) * 25;

		if($opponent_type	== 'customer'){
			$this->load->model('Customer_model');
			$data['opponents'] = $this->Customer_model->show_items($offset, $term);
			$data['pages'] = max(1, ceil($this->Customer_model->count_items($term)/25));
			
		} else if($opponent_type == 'supplier'){
			$this->load->model('Supplier_model');
			$data['opponents'] = $this->Supplier_model->show_items($offset, $term);
			$data['pages'] = $this->Supplier_model->count_items($term);
			
		} else if($opponent_type == 'other'){
			$this->load->model('Other_bank_account_model');
			$data['opponents'] = $this->Other_bank_account_model->show_items($offset, $term);
			$data['opponents'] = $this->Other_bank_account_model->count_items($term);
		}
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function insertItem()
	{
		$account		= $this->input->post('account');
		$date			= $this->input->post('date');
		$value			= $this->input->post('value');
		$transaction	= $this->input->post('transaction');
		$type			= $this->input->post('type');
		$opponent_id	= $this->input->post('id');
		$petty_cash		= $this->input->post('petty_cash_transfer');
		if($petty_cash	== 'on'){
			$opponent_id	= NULL;
			$type			= 'other';
		}
		
		$this->load->model('Bank_model');
		$insert_id		= $this->Bank_model->insertItem($date, $value, $transaction, $type, $opponent_id, $account);
		
		if($petty_cash	== 'on'){
			$this->load->model('Petty_cash_model');
			$this->Petty_cash_model->insert_income($insert_id, $value, $date);
		}
	}
}
