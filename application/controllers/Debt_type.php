<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debt_type extends CI_Controller {
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

		$this->load->view('accounting/debt/typeDashboard');
	}

	public function getItems()
	{
		$page = $this->input->get('page');
		$term = $this->input->get('term');
		$offset = ($page - 1) * 10;

		$this->load->model('Debt_type_model');
		$data['items'] = $this->Debt_type_model->getItems($offset, $term);
		$data['pages'] = max(1, ceil($this->Debt_type_model->countItems($term)));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function insertItem()
	{
		$name = $this->input->post('name');
		$description = $this->input->post('description');

		$this->load->model('Debt_type_model');

		$result = $this->Debt_type_model->insertItem($name, $description);
		echo $result;
	}

	public function deleteById()
	{
		$typeId			= $this->input->post('id');
		$this->load->model('Debt_type_model');
		$result = $this->Debt_type_model->deleteById($typeId);
		echo $result;
	}
}
