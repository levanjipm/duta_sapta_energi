<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asset extends CI_Controller {
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
		
		$this->load->view('accounting/asset');
	}
	
	public function fixed()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		
		$this->load->model('Asset_type_model');
		$result				= $this->Asset_type_model->show_all();
		$data['types']		= $result;
		
		$this->load->view('accounting/fixed_asset', $data);
	}
	
	public function show_items()
	{
		$term		= $this->input->get('term');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 25;
		$this->load->model('Asset_model');
		$data['assets']		= $this->Asset_model->show_items($offset, $term);
		$data['pages']		= max(1, ceil($this->Asset_model->count_items($term)/25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function class_dashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		
		$this->load->view('accounting/fixed_asset_class');
	}
	
	public function input_type_from_post()
	{
		$name		= $this->input->post('name');
		$description	= $this->input->post('description');
		
		$this->load->model('Asset_type_model');
		$this->Asset_type_model->input_from_post($name, $description);
		
		redirect(site_url('Asset/class_dashboard'));
	}
	
	public function show_type_limited()
	{
		$term		= $this->input->get('term');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 25;
		
		$this->load->model('Asset_type_model');
		$data['types']		= $this->Asset_type_model->show_items($offset, $term);
		$data['pages']		= max(1, ceil($this->Asset_type_model->count_items($term)/25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function show_type_by_id()
	{
		$id		= $this->input->get('id');
		$this->load->model('Asset_type_model');
		$data	= $this->Asset_type_model->show_by_id($id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function update_type()
	{
		$id				= $this->input->post('id');
		$name			= $this->input->post('name');
		$description	= $this->input->post('description');
		
		$this->load->model('Asset_type_model');
		$data	= $this->Asset_type_model->update($id, $name, $description);
		
		redirect(site_url('Asset/class_dashboard'));
	}
	
	public function input_from_post()
	{
		$this->load->model('Asset_model');
		$this->Asset_model->insert_from_post();
		
		redirect('Asset/fixed');
	}
	
	public function show_by_id()
	{
		$id			= $this->input->get('id');
		
		$this->load->model('Asset_model');
		$data		= $this->Asset_model->show_by_id($id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function update_from_post()
	{
		$id			= $this->input->post('id');
		
		$this->load->model('Asset_model');
		$this->Asset_model->update_from_post($id);
		
		redirect('Asset/fixed');
	}

	public function getAllTypes()
	{
		$this->load->model('Asset_type_model');
		$data = $this->Asset_type_model->getAllItems();

		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
