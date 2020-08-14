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
		
		$this->load->view('accounting/Asset/dashboard');
	}
	
	public function getItems()
	{
		$term		= $this->input->get('term');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 10;

		$this->load->model('Asset_model');
		$data['items']		= $this->Asset_model->getItems($offset, $term);
		$data['pages']		= max(1, ceil($this->Asset_model->countItems($term)/10));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function classDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		
		$this->load->view('accounting/Asset/classDashboard');
	}
	
	public function insertItem()
	{
		$this->load->model('Asset_model');
		$result = $this->Asset_model->insertItem();

		echo $result;
	}
	
	public function show_type_limited()
	{
		$term		= $this->input->get('term');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 10;
		
		$this->load->model('Asset_type_model');
		$data['types']		= $this->Asset_type_model->show_items($offset, $term);
		$data['pages']		= max(1, ceil($this->Asset_type_model->count_items($term)/10));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function getClassById()
	{
		$id		= $this->input->get('id');
		$this->load->model('Asset_type_model');
		$data	= $this->Asset_type_model->getById($id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function updateClassById()
	{
		$id				= $this->input->post('id');
		$name			= $this->input->post('name');
		$description	= $this->input->post('description');
		
		$this->load->model('Asset_type_model');
		$result	= $this->Asset_type_model->updateById($id, $name, $description);
		echo $result;
	}
	
	public function insertClassItem()
	{
		$name			= $this->input->post('name');
		$description	= $this->input->post('description');
		$this->load->model('Asset_type_model');
		$result = $this->Asset_type_model->insertItem($name, $description);

		echo $result;
	}
	
	public function show_by_id()
	{
		$id			= $this->input->get('id');
		
		$this->load->model('Asset_model');
		$data		= $this->Asset_model->show_by_id($id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getAllTypes()
	{
		$this->load->model('Asset_type_model');
		$data = $this->Asset_type_model->getAllItems();

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function deleteClassById()
	{
		$id			= $this->input->post('id');
		$this->load->model('Asset_type_model');
		$result = $this->Asset_type_model->deleteById($id);
		echo $result;
	}
}
