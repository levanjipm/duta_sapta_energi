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
		$this->load->view('head');
		$this->load->view('finance/header');
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
	
	public function input()
	{
		$account		= $this->input->post('account');
		$date			= $this->input->post('date');
		$value			= $this->input->post('value');
		$transaction	= $this->input->post('transaction');
		$type			= $this->input->post('type');
		$opponent_id	= $this->input->post('id');
		$petty_cash		= $this->input->post('petty_cash_transfer');
		if($petty_cash	== 'on'){		
			$opponent_id	= 1;
			$type			= 'other';
		}
		
		$this->load->model('Bank_model');
		$insert_id		= $this->Bank_model->input($date, $value, $transaction, $type, $opponent_id, $account);
		
		if($petty_cash	== 'on'){
			$this->load->model('Petty_cash_model');
			$this->Petty_cash_model->insert_income($insert_id, $value, $date);
		}
		
		redirect(site_url('Bank/transaction'));
	}
}
