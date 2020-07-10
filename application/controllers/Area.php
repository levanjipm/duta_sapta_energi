<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Area extends CI_Controller {
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
		$data['user_login'] = $this->User_model->show_by_id($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		$this->load->view('sales/area_manage_dashboard');
	}

	public function insert_new_area()
	{
		$this->load->model('Area_model');
		$result = $this->Area_model->insert_from_post();
		echo $result;
	}
	
	public function update_area()
	{
		$area_id	= $this->input->post('id');
		$area_name	= $this->input->post('name');
		
		$this->load->model('Area_model');
		$result = $this->Area_model->update_from_post($area_id, $area_name);
		
		echo $result;
	}
	
	public function delete_area()
	{
		$area_id	= $this->input->post('id');
		
		$this->load->model('Area_model');
		$result = $this->Area_model->delete_area($area_id);
		
		echo $result;
	}
	
	public function get_areas()
	{
		$page = $this->input->get('page');
		$term = $this->input->get('term');
		$offset = ($page - 1) * 25;
		
		$this->load->model('Area_model');
		$data['areas'] = $this->Area_model->show_limited($offset, $term);
		
		$data['pages'] = max(1, ceil($this->Area_model->count_areas($term)/10));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function get_area_by_id()
	{
		$id = $this->input->get('id');
		$this->load->model('Area_model');
		$data = $this->Area_model->get_area_by_id($id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}