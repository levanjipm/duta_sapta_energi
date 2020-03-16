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
		$this->load->view('head');
		$this->load->view('finance/header');
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
		$this->load->view('head');
		$this->load->view('finance/header');
		
		$this->load->model('Internal_bank_account_model');
		$data['accounts'] = $this->Internal_bank_account_model->show_all();
		$this->load->view('finance/add_transaction_dashboard', $data);
	}
	
	public function assign()
	{
		$this->load->view('head');
		$this->load->view('accounting/header');
		
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
		if($type == 1 && $customer_id != null){
			$this->load->model('Invoice_model');
			$data['invoices'] = $this->Invoice_model->view_incompleted_transaction($customer_id);
			$data['opponent'] = 'Customer';
		}
		$this->load->view('head');
		$this->load->view('accounting/header');
		$this->load->view('accounting/assign_bank', $data);
	}
}
