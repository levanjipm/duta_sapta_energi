<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense extends CI_Controller {
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
		
		$this->load->view('finance/expense_class_dashboard');
	}
	
	public function view_class()
	{
		$this->load->model('Expense_class_model');
		
		$data['classes'] = $this->Expense_class_model->show_all();
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function add_class()
	{
		$this->load->model('Expense_class_model');
		$this->Expense_class_model->insert_from_post();
		redirect(site_url('Expense/class'));
	}
	
	public function report()
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
		
		$this->load->view('finance/expense_report', $data);
	}
	
	public function view_update_form($id)
	{
		$this->load->model('Expense_class_model');
		$data = $this->Expense_class_model->view_by_id($id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function update_class()
	{
		$class_id	= $this->input->post('id');
		$this->load->model('Expense_class_model');
		$data = $this->Expense_class_model->view_by_id($class_id);
		
		if($data->parent_id == null){
			$name		= $this->input->post('name');
			$information	= $this->input->post('information');
			$type			= $this->input->post('type');
			
			$this->Expense_class_model->update_from_post($name, $information, $type, $class_id);
		} else {
			$name		= $this->input->post('name');
			$information	= $this->input->post('information');
			$type			= $this->input->post('type');
			$parent_id		= $this->input->post('parent_id');
			
			$null_check		= $this->input->post('null_check');
			
			if($null_check	== 'on'){
				$this->Expense_class_model->update_from_post($name, $information, $type, $class_id);
				echo 'itu';
			} else {
				$this->Expense_class_model->update_from_post($name, $information, $type, $class_id, $parent_id);
			}
		}
		
		redirect(site_url('Expense/class'));
	}
}