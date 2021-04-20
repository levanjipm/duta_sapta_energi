<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brand extends CI_Controller {
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
		$this->load->view('sales/header', $data);
		$this->load->view('sales/Brand/dashboard');
	}

	public function getItems()
	{
		$term		= $this->input->get('term');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 25;

		$this->load->model('Brand_model');
		$data['brands'] = $this->Brand_model->showItems($offset, $term);
		$item = $this->Brand_model->countItems($term);
		$data['pages'] = max(1, ceil($item / 25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function insertItem()
	{
		$name		= $this->input->post('name');
		$this->load->model("Brand_model");
		$result = $this->Brand_model->insertItem($name);
		echo $result;
	}

	public function deleteItem()
	{
		$id			= $this->input->post('id');
		$this->load->model("Brand_model");
		$result = $this->Brand_model->deleteItem($id);
		echo $result;
	}

	public function editItem()
	{
		$id			= $this->input->post('id');
		$name		= $this->input->post('name');

		$this->load->model("Brand_model");
		$result		= $this->Brand_model->updateItem($id, $name);
		echo $result;
	}
}
