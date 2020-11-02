<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Human_resource extends CI_Controller {
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
		$data = array();

		$data['activeUser'] = $this->User_model->countActiveUser();

		$this->load->model("Attendance_model");
		$data['pendingAttendance']	= $this->Attendance_model->getPendingAttendance(date("Y-m-d"));

		$accessLevelArray = $this->User_model->getAccessLevelRatio();
		$attendanceArray				= array();
		$attendanceDashboardHistory		= $this->Attendance_model->getDashboardHistory();

		foreach($attendanceDashboardHistory as $attendanceDashboard){
			$difference		= $attendanceDashboard->difference;
			if(!array_key_exists($difference, $attendanceArray)){
				$attendanceArray[$difference]	= array();
			}

			$id			= $attendanceDashboard->id;
			$status		= $attendanceDashboard->name;
			$count		= $attendanceDashboard->count;

			$attendanceArray[$difference][$id] = array(
				"status" => $status,
				"count" => (int)$count
			);
		}
		for($i = 0; $i <= 6; $i++){
			if(!array_key_exists($i, $attendanceArray)){
				$attendanceArray[$i]	= array();
			}
		}

		ksort($attendanceArray);

		for($i = 1; $i <= 5; $i++){
			if(!array_key_exists($i, $accessLevelArray)){
				$accessLevelArray[$i] = 0;
			};
		}

		$data['accessLevelRatio']	= $accessLevelArray;
		$data['attendanceItems']	= $attendanceArray;


		$this->load->view('human_resource/dashboard', $data);
	}
}
