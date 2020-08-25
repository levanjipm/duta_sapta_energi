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
		$page			= $this->input->get('page');
		$term			= $this->input->get('term');
		$offset			= ($page - 1) * 10;
        $this->load->model('Attendance_status_model');
		$data['items'] = $this->Attendance_status_model->getItems($offset, $term);
		$data['pages'] = max(1, ceil($this->Attendance_status_model->countItems($term)/10));
        
        header('Content-Type: application/json');
		echo json_encode($data);
    }

    public function insertStatusItem()
    {
        $this->load->model('Attendance_status_model');
        $result = $this->Attendance_status_model->insertItem();

        echo $result;
	}

	public function getStatusById()
	{
		$id	= $this->input->get('id');
		$this->load->model("Attendance_status_model");
		$data		= $this->Attendance_status_model->getById($id);

		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function deleteStatusById()
	{
		$id = $this->input->post('id');
		$this->load->model('Attendance_status_model');
		$result = $this->Attendance_status_model->deleteById($id);

		echo $result;
	}

	public function updateStatusById()
	{
		$this->load->model('Attendance_status_model');
        $result = $this->Attendance_status_model->updateById();

        echo $result;
	}

	public function historyDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
        $this->load->view('human_resource/header', $data);
	}

	public function insertItem()
	{
		$userId			= $this->input->post('userId');
		$status			= $this->input->post('status');
		$this->load->model("Attendance_model");
		$result			= $this->Attendance_model->insertItem($userId, $status);

		echo $result;
	}

	public function getItemSalary()
	{
		$userId			= (int) $this->input->post('user');
		$month			= (int) $this->input->post('month');
		$year			= (int) $this->input->post('year');

		$this->load->model("Attendance_model");
		$this->load->model("Salary_slip_model");
		$data['items']			= $this->Attendance_model->getAttendanceSalary($month, $year, $userId);
		$result = $this->Salary_slip_model->checkUser($month, $year, $userId);
		if($result){
			$data['status'] = 1;
		} else {
			$data['status'] = 0;
		}
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
?>
