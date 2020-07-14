<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payable extends CI_Controller {
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
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		$this->load->view('accounting/payable_dashboard');
	}
	
	public function view_payable()
	{
		$this->load->model('Debt_model');
		$data	= $this->Debt_model->view_payable_chart();

		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
