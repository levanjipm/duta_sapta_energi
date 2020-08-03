<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends CI_Controller {
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
		$this->load->view('human_resource/attandanceList');
    }
    
    public function statusDashboard()
    {
        $user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
        $this->load->view('human_resource/header', $data);
        $this->load->view('human_resource/attendanceStatusDashboard');
    }

	public function getTodayAbsentee()
	{
		$date = date('Y-m-d');
		$this->load->model('Attendance_model');
		$data = $this->Attendance_model->getUnattendedList($date);
		
		header('Content-Type: application/json');
		echo json_encode($data);
    }

    public function getAttendanceStatus()
    {
        $this->load->model('Attendance_status_model');
        $data = $this->Attendance_status_model->getItems();
        
        header('Content-Type: application/json');
		echo json_encode($data);
    }

    public function insertStatusItem()
    {
        $this->load->model('Attendance_status_model');
        $result = $this->Attendance_status_model->insertItem();

        echo $result;
    }
}
?>