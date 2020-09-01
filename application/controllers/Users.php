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
		$this->load->view('human_resource/usersDashboard');
	}
	
	public function getItems()
	{
		$page = $this->input->get('page');
		$term = $this->input->get('term');
		$offset = ($page - 1) * 10;
		
		$this->load->model('User_model');
		$data['users'] = $this->User_model->getItems($offset, $term);
		$data['pages'] = max(1, ceil($this->User_model->countItems($term) / 10));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getSalesItems()
	{
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 10;
		$this->load->model("User_model");

		$data['users'] = $this->User_model->getSalesItems($offset);
		$data['pages'] = max(1, ceil($this->User_model->countSalesItems() / 10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getAccountantItems()
	{
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 10;
		$this->load->model("User_model");

		$data['users'] = $this->User_model->getAccountantItems($offset);
		$data['pages'] = max(1, ceil($this->User_model->countAccountantItems() / 10));

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
	
	public function insertItem()
	{
		$this->load->model('User_model');
		$result = $result = $this->User_model->insertItem();

		if($result != null){
			$config['upload_path']= "./assets/ProfileImages";
			$config['allowed_types']='jpeg|jpg|png';
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload',$config);
			$this->upload->initialize($config);
			if($this->upload->do_upload("image")){
				$data = array('upload_data' => $this->upload->data());
				$image= $data['upload_data']['file_name'];
				$result= $this->User_model->updateProfilePicture($result,$image);
			}
		}
	}

	public function removePorfilePicture()
	{
		$id			= $this->input->post('id');
		$this->load->model("User_model");

		$user		= $this->User_model->getById($id);
		$imageUrl	= $user->image_url;
		$this->load->helper("file");
		delete_files(base_url() . "/assets/profileImages/" . $imageUrl);

		$this->User_model->updateProfilePicture($id, null);
	}

	public function getActiveUser()
	{
		$this->load->model("User_model");
		$data		= $this->User_model->getActiveUser();
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
