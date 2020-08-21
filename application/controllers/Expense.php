<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense extends CI_Controller {
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
		$this->load->view('finance/header', $data);
		
		$this->load->view('finance/Expense/classDashboard');
	}
	
	public function getItems()
	{
		$this->load->model('Expense_class_model');
		
		$data['classes'] = $this->Expense_class_model->getItems();
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function insertItem()
	{
		$this->load->model('Expense_class_model');
		$result = $this->Expense_class_model->insertItem();
		echo $result;
	}
	
	public function reportDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);
		
		$this->load->model('Petty_cash_model');
		$data['years']	= $this->Petty_cash_model->show_years();
		
		$this->load->view('finance/expense/reportDashboard', $data);
	}
	
	public function getById()
	{
		$id			= $this->input->get('id');
		$this->load->model('Expense_class_model');
		$data = $this->Expense_class_model->getById($id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function updateById()
	{
		$class_id	= $this->input->post('id');
		$this->load->model('Expense_class_model');
		$data = $this->Expense_class_model->getById($class_id);
		
		if($data->parent_id == null){
			$name		= $this->input->post('name');
			$information	= $this->input->post('information');
			$type			= $this->input->post('type');
			
			$result = $this->Expense_class_model->updateById($name, $information, null, $class_id);
		} else {
			$name		= $this->input->post('name');
			$information	= $this->input->post('information');
			$type			= $this->input->post('type');
			$parent_id		= $this->input->post('parent_id');
			
			$null_check		= $this->input->post('null_check');
			
			if($null_check	== 'on'){
				$result = $this->Expense_class_model->updateById($name, $information, null, $class_id);
			} else {
				$result = $this->Expense_class_model->updateById($name, $information, $type, $class_id, $parent_id);
			}
		}

		echo $result;
	}

	public function deleteById()
	{
		$id			= $this->input->post('id');
		$this->load->model('Expense_class_model');
		$result		= $this->Expense_class_model->deleteById($id);
		echo $result;
	}
}
