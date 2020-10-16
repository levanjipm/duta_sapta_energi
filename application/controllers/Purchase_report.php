<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_report extends CI_Controller {
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
		$this->load->view('purchasing/header', $data);
		$this->load->view('purchasing/Report/dashboard');
	}

	public function getReport()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('purchasing/header', $data);

		$data			= array();

		$supplierId			= $this->input->post('supplierId');
		$this->load->model("Supplier_model");
		$data['supplier']		= $this->Supplier_model->getById($supplierId);

		$data['month']			= $this->input->post('month');
		$data['year']			= $this->input->post('year');

		if($this->input->post('month') == 0){
			$month			= NULL;
		} else {
			$month			= $this->input->post('month');
		}
		$year			= $this->input->post('year');

		$this->load->model("Debt_model");
		$data['purchaseInvoice']	= $this->Debt_model->getBySupplierIdPeriod($supplierId, $month, $year);

		$this->load->model("Debt_other_model");
		$data['otherPurchaseInvoice']	= $this->Debt_other_model->getBySupplierIdPeriod($supplierId, $month, $year);
		$this->load->view('purchasing/Report/report', $data);
	}
}
