<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Income extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->has_userdata('user_id') == FALSE){
			redirect(site_url('login'));

		}
	}
	
	public function class()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);
		
		$this->load->view('finance/Income/classDashboard');
	}
	
	public function getItems()
	{
		$term		= $this->input->get('term');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 25;
		
		$this->load->model('Income_class_model');
		$data['classes']	= $this->Income_class_model->getItems($offset, $term);
		$data['pages']		= max(1, ceil($this->Income_class_model->countItems($term)/25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function insertItem()
	{
		$this->load->model('Income_class_model');
		$result = $this->Income_class_model->insertItem();
		echo $result;
	}
	
	public function getById()
	{
		$this->load->model('Income_class_model');
		$id = $this->input->post('id');
		$data = $this->Income_class_model->getById($id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function updateById()
	{
		$id = $this->input->post('id');
		$name = $this->input->post('name');
		$description = $this->input->post('information');
		
		$this->load->model('Income_class_model');
		$result = $this->Income_class_model->updateById($id, $name, $description);
		echo $result;
	}

	public function deleteById()
	{
		$id = $this->input->post('id');
		$this->load->model('Income_class_model');
		$result = $this->Income_class_model->deleteById($id);
		echo $result;
	}
}
