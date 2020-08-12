<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item_class extends CI_Controller {
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
		
		$this->load->view('sales/item_class_manage_dashboard');
	}
	
	public function insertItem()
	{
		$this->load->model('Item_class_model');
		$result = $this->Item_class_model->insertItem();
		
		echo $result;
	}
	
	public function updateById()
	{		
		$this->load->model('Item_class_model');
		$result = $this->Item_class_model->updateById();
		
		echo $result;
	}
	
	public function showAllItems()
	{
		$this->load->model('Item_class_model');
		$data = $this->Item_class_model->showAllItems();
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function showItems()
	{
		$term			= $this->input->get('term');
		$page			= $this->input->get('page');
		$offset			= ($page - 1) * 25;
		$this->load->model('Item_class_model');
		$data['items']			= $this->Item_class_model->showItems($offset, $term);
		$data['pages']			= max(0, ceil($this->Item_class_model->countItems($term)/25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function showById()
	{
		$this->load->model('Item_class_model');
		$id 	= $this->input->get('id');
		$data	= $this->Item_class_model->showById($id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function deleteById()
	{
		$id			= $this->input->post('id');
		$this->load->model('Item_class_model');
		$result = $this->Item_class_model->deleteById($id);
		
		echo $result;
	}
}