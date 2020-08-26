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
		$this->load->view('human_resource/salarySlipDashboard');
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
}
