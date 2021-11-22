<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	
class Supplier extends CI_Controller {
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
		
		$this->load->view('purchasing/Supplier/dashboard');
	}
	
	public function showItems()
	{
		$page		= $this->input->get('page');
		$term		= $this->input->get('term');
		$offset		= ($page - 1) * 25;
		
		$this->load->model('Supplier_model');
		$data['suppliers']	= $this->Supplier_model->showItems($offset, $term);
		$data['pages']		= max(1, ceil($this->Supplier_model->countItems($term)/10));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function insertItem()
	{
		$this->load->model('supplier_model');
		$this->supplier_model->insert_from_post();
		
		redirect(site_url('supplier'));
	}
	
	public function deleteById()
	{
		$this->load->model('supplier_model');
		$result = $this->supplier_model->deleteById();
		
		echo $result;
	}
	
	public function updateById()
	{
		$this->load->model('supplier_model');
		$this->supplier_model->updateById();
		
		redirect(site_url('supplier'));
	}
	
	public function getById()
	{
		$supplier_id	= $this->input->get('id');
		$this->load->model('Supplier_model');
		$item = $this->Supplier_model->getById($supplier_id);
		
		header('Content-Type: application/json');
		echo json_encode($item);
	}

	public function viewDetail($supplierId)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);

		$this->load->view('head');
		$this->load->view('purchasing/header', $data);
		
		$data					= array();
		$this->load->model("Supplier_model");
		$data['supplier']		= $this->Supplier_model->getById($supplierId);

		$this->load->view('purchasing/Supplier/detailDashboard', $data);		
	}

	public function getPurchaseBySupplierId()
	{
		$id			= $this->input->get("supplier_id");
		$this->load->model("Debt_model");
		$data		= $this->Debt_model->getValueByPeriod($id, date("Y-m-t", mktime(0,0,0, date("m"), 1, date("Y"))), date("Y-m-d", mktime(0,0,0,date('m'), 1, date("Y") - 1)));

		header("Content-type: application/json");
		echo json_encode($data);
	}

	public function getValueByDateRange()
	{
		$dateStart		= $this->input->post('start');
		$dateEnd		= $this->input->post('end');
		$supplierId		= $this->input->post('supplierId');
		$this->load->model("Debt_model");
		
		$data	= $this->Debt_model->getValueByDateRange($supplierId, $dateStart, $dateEnd);
		header("Content-type: application/json");
		echo json_encode($data);
	}
}
