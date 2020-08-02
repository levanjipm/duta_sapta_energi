<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
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
		$this->load->view('human_resource/users_dashboard');
	}
	
	public function get_users()
	{
		$page = $this->input->get('page');
		$term = $this->input->get('term');
		$offset = ($page - 1) * 10;
		
		$this->load->model('User_model');
		$data['users'] = $this->User_model->get_users($offset, $term);
		$data['pages'] = min(1, ceil($this->User_model->count_users($term) / 10));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function get_by_id()
	{
		$id = $this->input->get('id');
		$this->load->model('User_model');
		$data = $this->User_model->getById($id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function salary_slip_dashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('human_resource/header', $data);
		$this->load->view('human_resource/salary_slip_dashboard');
	}
	
	public function update_status()
	{
		$user_id = $this->input->post('id');
		$this->load->model('User_model');
		$data = $this->User_model->getById($user_id);
		$is_active = $data->is_active;
		
		if($is_active == 1){
			$status = 0;
		} else {
			$status = 1;
		}
		
		$this->User_model->update_status($status, $user_id);
	}
	
	public function insert_from_post()
	{
		$this->load->model('User_model');
		$result = $result = $this->User_model->insert_from_post();
		
		echo $result;
	}

	public function attandance()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('human_resource/header', $data);
		$this->load->view('human_resource/attandanceList');
	}

	public function getTodayAbsentee()
	{
		$date = date('Y-m-d');
		$this->load->model('Attendance_model');
		$data = $this->Attendance_model->getUnattendedList($date);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
