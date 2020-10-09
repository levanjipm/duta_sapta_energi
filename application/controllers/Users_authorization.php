<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_authorization extends CI_Controller {
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
		$this->load->view('human_resource/header', $data);
		$this->load->view('human_resource/Users/authorizationDashboard');
	}

	public function getByUserId()
	{
		$userId			= $this->input->get('id');
		$this->load->model("User_model");
		$data['user'] = $this->User_model->getById($userId);

		$this->load->model("Authorization_model");
		$data['authorization'] = $this->Authorization_model->getAllFilterByUserId($userId);

		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function updateByUserId()
	{
		$userId			= $this->input->post('userId');
		$departmentArray		= (array)$this->input->post('departments');
		$accessLevel			= $this->input->post('access_level');

		$this->load->model("Authorization_model");
		$this->Authorization_model->updateByUserId($userId, $departmentArray);

		$this->load->model("User_model");
		$this->User_model->updateAccessLevelById($userId, $accessLevel);
	}
}
