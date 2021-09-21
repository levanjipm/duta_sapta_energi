<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends CI_Controller {
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
		$this->load->view('finance/header', $data);
		$this->load->view('finance/Schedule/dashboard');
	}

	public function getItems(){
		$term		= $this->input->get('term');
		$page		= $this->input->get('page');

		$offset		= ($page - 1) * 10;
		$this->load->model("Schedule_model");
		$items		= $this->Schedule_model->getItems($term, $offset);
		$pages		= max(1, ceil($this->Schedule_model->countItems($term)/10));

		$data				= array();
		$data['items']		= $items;
		$data['pages']		= $pages;

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getByCustomerId(){
		$id			= $this->input->get('id');
		$this->load->model("Schedule_model");
		$data		= $this->Schedule_model->getByCustomerId($id);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function editByCustomerId(){
		$customer_id	= $this->input->post('customer_id');
		$schedule		= $this->input->post('schedule');

		$this->load->model("Schedule_model");
		$result = $this->Schedule_model->editByCustomerId($customer_id, $schedule);
		echo $result;
	}

	public function print(){
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);
		$this->load->view('finance/Schedule/print');
	}

	public function getByDay(){
		$day		= $this->input->get('day');
		$this->load->model("Schedule_model");
		$result		= $this->Schedule_model->getByDay($day);

		header('Content-Type: application/json');
		echo json_encode($result);
	}
}
