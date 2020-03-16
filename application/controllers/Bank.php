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
}
