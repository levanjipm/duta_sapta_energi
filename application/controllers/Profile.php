<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {
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

		$this->load->model("Salary_slip_model");
		$data['salary']			= $this->Salary_slip_model->getItemsByUserId($user_id, 0, 10);
		
		$this->load->view('head');
		$this->load->view('profile/header', $data);
	}

	public function getUserAttendance()
	{
		$id			= $this->input->get('id');
		$month		= $this->input->get('month');
		$year		= $this->input->get('year');
		$this->load->model("Attendance_model");
		$data		= $this->Attendance_model->getChartItems($id, $month, $year);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function editUserProfileById()
	{
		$data		= json_decode(file_get_contents('php://input'),1);
		$name		= $data[0]['value'];
		$email		= $data[1]['value'];
		$address	= $data[2]['value'];
		$bank		= $data[3]['value'];
		$password	= ($data[4]['value'] == "") ? NULL : $data[4]['value'];

		$this->load->model("User_model");
		$result		= $this->User_model->updateById($this->session->userdata('user_id'), $name, $email, $bank, $address, $password);
		echo $result;
	}

	public function updateProfileImage()
	{
		$userId			= $this->session->userdata('user_id');
		$config['upload_path']= "./assets/ProfileImages";
		$config['allowed_types']='jpeg|jpg|png';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload',$config);
		$this->upload->initialize($config);
		if($this->upload->do_upload("file")){
			$data = array('upload_data' => $this->upload->data());
			$image= $data['upload_data']['file_name'];

			$this->load->model("User_model");
			$result= $this->User_model->updateProfilePicture($userId,$image);
		}
	}
}
