<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opponent extends CI_Controller {
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
		$this->load->view('accounting/Opponent/manageDashboard');
	}

	public function getItems()
	{
		$page		= $this->input->get('page');
		$term		= $this->input->get('term');
		$offset		= ($page - 1) * 10;

		$this->load->model('Opponent_model');
		$data['items'] = $this->Opponent_model->getItems($offset, $term);
		$data['pages']	= max(1, ceil($this->Opponent_model->countItems($term)/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function deleteById()
	{
		$id			= $this->input->post('id');
		$this->load->model('Opponent_model');
		$result			= $this->Opponent_model->deleteById($id);
		if($result){
			echo 1;
		} else {
			echo 0;
		}
	}

	public function insertItem()
	{
		$this->load->model('Opponent_model');
		$result = $this->Opponent_model->insertItem();
		echo $result;
	}

	public function getById()
	{
		$id			= $this->input->get('id');
		$this->load->model('Opponent_model');
		$data		= $this->Opponent_model->getById($id);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function updateById()
	{
		$this->load->model('Opponent_model');
		$result = $this->Opponent_model->updateById();
		echo $result;
	}
}
