<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_return extends CI_Controller {
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
		$this->load->view('purchasing/Return/createDashboard');
	}

	public function insertItem()
	{
		$this->load->model('Purchase_return_model');
		$result = $this->Purchase_return_model->insertItem();
		if($result != null){
			$this->load->model('Purchase_return_detail_model');
			$this->Purchase_return_detail_model->insertItem($result);
			echo 1;
		} else {
			echo 0;
		}
	}

	public function confirmDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('purchasing/header', $data);
		$this->load->view('purchasing/Return/confirmDashboard');
	}

	public function getUnconfirmedItems()
	{
		$page			= $this->input->get("page");
		$term			= $this->input->get("term");
		$offset			= ($page - 1) * 10;
		$this->load->model("Purchase_return_model");
		$data['items'] = $this->Purchase_return_model->getUnconfirmedItems($offset, $term);
		$data['pages'] = max(1, ceil($this->Purchase_return_model->countUnconfirmedItems($term)/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getById()
	{
		$id		= $this->input->get('id');
		$this->load->model("Purchase_return_model");
		$purchaseReturn = $this->Purchase_return_model->getById($id);

		$data['general'] = $purchaseReturn;
		$supplierId = $purchaseReturn->supplier_id;
		$this->load->model("Supplier_model");
		
		$data['supplier'] = $this->Supplier_model->getById($supplierId);
		
		$this->load->model("Purchase_return_detail_model");
		$data['items'] = $this->Purchase_return_detail_model->getByCodeId($id);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function receiveDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('purchasing/header', $data);
		$this->load->view('inventory/Return/purchaseReturnDashboard');
	}

	public function loadForm($event)
	{
		if($event == "create")
		{
			$this->load->view('inventory/Return/purchaseReturnCreate');
		} else {
			$this->load->view('inventory/Return/purchaseReturnConfirm');
		}
	}

	public function getIncompletedReturn()
	{
		$page	= $this->input->get('page');
		$term	= $this->input->get('term');
		$offset	= ($page - 1) * 10;
		$this->load->model("Purchase_return_model");
		$data['items'] = $this->Purchase_return_model->getIncompletedReturn($offset, $term);
		$data['pages'] = max(1, ceil($this->Purchase_return_model->countIncompletedReturn($term)/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
?>
