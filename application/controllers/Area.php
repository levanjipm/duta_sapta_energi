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
		$this->load->view('head');
		$this->load->view('sales/header');
		
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
}