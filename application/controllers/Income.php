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
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);
		
		$this->load->view('finance/income_class_dashboard');
	}
	
	public function show_all()
	{
		$term		= $this->input->get('term');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 25;
		
		$this->load->model('Income_class_model');
		$data['classes']	= $this->Income_class_model->show_all($offset, $term);
		$data['pages']		= max(1, ceil($this->Income_class_model->count_all($term)/25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function add_class()
	{
		$this->load->model('Income_class_model');
		$this->Income_class_model->insert_from_post();
		redirect(site_url('Income/class'));
	}
	
	public function get_by_id()
	{
		$this->load->model('Income_class_model');
		$id = $this->input->post('id');
		$data = $this->Income_class_model->get_by_id($id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function update_class()
	{
		$id = $this->input->post('id');
		$name = $this->input->post('name');
		$description = $this->input->post('information');
		
		$this->load->model('Income_class_model');
		$this->Income_class_model->update_class_by_id($id, $name, $description);
		
		redirect(site_url('Income/class'));
	}
}
