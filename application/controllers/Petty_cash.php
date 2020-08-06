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
		
		$this->load->view('finance/petty_cash_dashboard');
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
		
		$this->load->view('finance/petty_cash_mutation', $data);
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
}
