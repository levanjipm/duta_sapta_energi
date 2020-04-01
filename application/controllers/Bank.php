<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->has_userdata('user_id') == FALSE){
			redirect(site_url('login'));

		}
	}
	public function account()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->show_by_id($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);
		$this->load->model('Internal_bank_account_model');
		
		$data['accounts'] = $this->Internal_bank_account_model->show_all();
		$this->load->view('finance/bank_account', $data);
	}
	
	public function create()
	{
		$this->load->model('Internal_bank_account_model');
		$this->Internal_bank_account_model->create();
		
		redirect(site_url('Bank/account'));
	}
	
	public function transaction()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->show_by_id($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);
		
		$this->load->model('Internal_bank_account_model');
		$data['accounts'] = $this->Internal_bank_account_model->show_all();
		$this->load->view('finance/add_transaction_dashboard', $data);
	}
	
	public function assign()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->show_by_id($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		
		$this->load->model('Internal_bank_account_model');
		$data['accounts'] = $this->Internal_bank_account_model->show_all();
		
		$this->load->view('accounting/assign_bank_dashboard', $data);
	}
	
	public function view_unassigned_data()
	{
		$type		= $this->input->get('type');
		$account	= $this->input->get('account');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 25;
		$this->load->model('Bank_model');
		$data['banks'] = $this->Bank_model->view_unassigned_data($account, $type, $offset);
		$data['pages'] = max(1, ceil($this->Bank_model->count_unassigned_data($account, $type)/25));
		
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
		
		if($type == 1 && $customer_id != null){
			$this->load->model('Invoice_model');
			$data['invoices'] = $this->Invoice_model->view_incompleted_transaction($customer_id);
			$data['opponent'] = 'Customer';
		} else  if($type == 2 && $supplier_id != null){
			$this->load->model('Debt_model');
			$data['invoices'] = $this->Debt_model->view_incompleted_transaction($supplier_id);
			$data['opponent'] = 'Supplier';
		}
		
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->show_by_id($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		$this->load->view('accounting/assign_bank', $data);
	}
	
	public function assign_input()
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
		
		redirect(site_url('Bank/assign'));
	}
	
	public function mutation()
	{
		$this->load->model('Internal_bank_account_model');
		$data['accounts'] = $this->Internal_bank_account_model->show_all();
		
		$this->load->view('head');
		$this->load->view('finance/header');
		$this->load->view('finance/bank_mutation', $data);
	}
	
	public function view_mutation()
	{
		$account_id		= $this->input->get('account');
		$date_start		= $this->input->get('start');
		$date_end		= $this->input->get('end');
		$page			= $this->input->get('page');
		$offset			= ($page - 1) * 25;
		
		$this->load->model('Bank_model');
		$data['balance']	= $this->Bank_model->calculate_balance($account_id, $date_start);
		$data['mutations'] 	= $this->Bank_model->view_mutation($account_id, $date_start, $date_end, $offset);
		$data['pages'] 		= $this->Bank_model->count_mutation($account_id, $date_start, $date_end, $offset);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function opponent()
	{
		$this->load->view('head');
		$this->load->view('finance/header');
		$this->load->view('finance/opponent_dashboard');
	}
	
	public function view_opponent()
	{
		$page		= $this->input->get('page');
		$term		= $this->input->get('term');
		$offset		= ($page - 1) * 25;
		
		$type		= $this->input->get('type');
		if($type == 1){ //customer
			$this->load->model('Customer_model');
			$data['opponents']			= $this->Customer_model->show_items($offset, $term);
			$data['pages']				= max(1, ceil($this->Customer_model->count_items($term)/25));
		} else if($type == 2){
			$this->load->model('Supplier_model');
			$data['opponents']			= $this->Supplier_model->show_items($offset, $term);
			$data['pages']				= max(1, ceil($this->Supplier_model->count_items($term)/25));
		} else if($type == 3){
			$this->load->model('Other_bank_account_model');
			$data['opponents']			= $this->Other_bank_account_model->show_items($offset, $term);
			$data['pages']				= max(1, ceil($this->Other_bank_account_model->count_items($term)/25));
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
}
