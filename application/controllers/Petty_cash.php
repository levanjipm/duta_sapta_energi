<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Petty_cash extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->has_userdata('user_id') == FALSE){
			redirect(site_url('login'));

		}
	}
	
	public function transaction()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);
		
		$this->load->view('finance/PettyCash/dashboard');
	}
	
	public function insertItem()
	{
		$this->load->model('Petty_cash_model');
		$result = $this->Petty_cash_model->insertItem();
		echo $result;
	}
	
	public function mutation()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);

		$this->load->model('Petty_cash_model');
		$data['currentBalance']	= $this->Petty_cash_model->getCurrentBalance();
		
		$this->load->view('finance/PettyCash/mutationDashboard', $data);
	}
	
	public function getMutation()
	{
		$month		= $this->input->get('month');
		$year		= $this->input->get('year');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 25;
		
		$this->load->model('Petty_cash_model');
		$data['balance']		= $this->Petty_cash_model->calculateBalance($month, $year, $offset);
		$data['transactions'] 	= $this->Petty_cash_model->view_mutation($month, $year, $offset);
		$data['pages'] 			= max(1, ceil($this->Petty_cash_model->count_mutation($month, $year)/25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function resetByBankId()
	{
		$bankId			= $this->input->post('id');
		$this->load->model("Bank_model");
		$this->load->model("Petty_cash_model");
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

		$result		= $this->Petty_cash_model->deleteByBankId($bankId);
		if($result == 1){
			$this->Bank_model->deleteById($bankId);
		}

		if($status){
			$this->Bank_model->mergeByParentId($parentId);
		}
	}

	public function deleteDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		if($data['user_login']->access_level > 3){
			$this->load->model('Authorization_model');
			$data['departments']	= $this->Authorization_model->getByUserId($user_id);
			
			$this->load->view('head');
			$this->load->view('administrator/header', $data);
			$this->load->view('administrator/PettyCash/deleteDashboard');
		} else {
			redirect(site_url('Welcome'));
		}	
	}

	public function deleteById()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		if($data['user_login']->access_level > 3){
			$id			= $this->input->post('id');
			$this->load->model("Petty_cash_model");
			$result		= $this->Petty_cash_model->deleteById($id);
			echo $result;
		} else {
			echo 0;
		}
	}	
}
