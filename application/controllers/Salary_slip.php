<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salary_slip extends CI_Controller {
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
		$this->load->view('human_resource/SalarySlip/dashboard');
	}

	public function insertItem()
	{
		$this->load->model("Salary_slip_model");
		$result = $this->Salary_slip_model->insertItem();
		if($result != null){
			$this->load->model("Salary_benefit_model");
			$this->Salary_benefit_model->insertItem($result);

			$this->load->model("Salary_attendance_model");
			$this->Salary_attendance_model->insertItem($result);

			echo 1;
		} else {
			echo 0;
		}
	}
	public function archiveDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('human_resource/header', $data);
		$this->load->view('human_resource/SalarySlip/archiveDashboard');
	}

	public function getItems()
	{
		$page			= $this->input->get('page');
		$offset			= ($page - 1) * 10;
		$month			= $this->input->get('month');
		$year			= $this->input->get('year');

		$this->load->model("Salary_slip_model");
		$data['items'] = $this->Salary_slip_model->getItems($month, $year, $offset);
		$data['pages'] = max(1, ceil($this->Salary_slip_model->countItems($month, $year))/10);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getById()
	{
		$id			= $this->input->get('id');
		$this->load->model("Salary_slip_model");
		$data['general']		= $this->Salary_slip_model->getById($id);

		$userId					= $data['general']->user_id;
		$this->load->model("User_model");
		$data['user']			= $this->User_model->getById($userId);

		$this->load->model("Salary_attendance_model");
		$data['absentee']		= $this->Salary_attendance_model->getByCodeId($id);

		$this->load->model("Salary_benefit_model");
		$data['benefit']		= $this->Salary_benefit_model->getByCodeId($id);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function deleteById()
	{
		$id				= $this->input->post('id');

		$this->load->model("Salary_attendance_model");
		$this->Salary_attendance_model->deleteByCodeId($id);

		$this->load->model("Salary_benefit_model");
		$this->Salary_benefit_model->deleteByCodeId($id);
		
		$this->load->model("Salary_slip_model");
		$result		= $this->Salary_slip_model->deleteById($id);
		echo $result;
	}

	public function getArchiveByUserId()
	{
		$userId			= $this->input->get('id');
		$page			= $this->input->get('page');
		$offset			= ($page - 1) * 10;

		$this->load->model("Salary_slip_model");
		$data['items']		= $this->Salary_slip_model->getItemsByUserId($userId, $offset);
		$data['pages']		= max(1, ceil($this->Salary_slip_model->countItemsByUserId($userId)));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
