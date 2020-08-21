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
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		$this->load->view('accounting/payable_dashboard');
	}
	
	public function viewPayable()
	{
		$this->load->model('Debt_model');
		$data	= $this->Debt_model->viewPayableChart();

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function viewPayableBySupplierId()
	{
		$supplierId = $this->input->get('id');
		$this->load->model('Debt_model');
		$data['items'] = $this->Debt_model->getPayableBySupplierId($supplierId);

		$this->load->model('Supplier_model');
		$data['supplier'] = $this->Supplier_model->getById($supplierId);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function viewPayableByOtherId()
	{
		$supplierId = $this->input->get('id');
		$this->load->model('Debt_model');
		$data['items'] = $this->Debt_model->getPayableByOtherId($supplierId);

		$this->load->model('Opponent_model');
		$data['supplier'] = $this->Opponent_model->getById($supplierId);

		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
