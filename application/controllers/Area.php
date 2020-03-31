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
		
		$this->load->model('Area_model');
		$items = $this->Area_model->show_limited();
		$data['areas'] = $items;
		$this->load->view('sales/area_manage_dashboard',$data);
	}

	public function insert_new_area()
	{
		$this->load->model('Area_model');
		$this->Area_model->insert_from_post();
		
		redirect('Area');
	}
	
	public function update_area()
	{
		$area_id	= $this->input->post('id');
		$area_name	= $this->input->post('name');
		
		$this->load->model('Area_model');
		$this->Area_model->update_from_post($area_id, $area_name);
		
		redirect('Area');
	}
	
	public function delete()
	{
		$area_id	= $this->input->post('id');
		
		$this->load->model('Area_model');
		$this->Area_model->delete_area($area_id);
		
		redirect('Area');
	}
	
	public function view_customer()
	{
		$area_id	= $this->input->get('id');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 25;
		$term		= $this->input->get('term');
		
		$this->load->model('Customer_model');
		$data['customers']		= $this->Customer_model->view_by_area_id($area_id, $offset, $term);
		$data['pages']			= max(1, ceil($this->Customer_model->count_by_area_id($area_id)/25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}