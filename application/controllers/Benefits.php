<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Benefits extends CI_Controller {
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
		$data['user_login'] = $this->User_model->show_by_id($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('human_resource/header', $data);
		$this->load->view('human_resource/benefit_dashboard');
	}
	
	public function get_benefits()
	{
		$page = $this->input->get('page');
		$term = $this->input->get('term');
		$offset = ($page - 1) * 10;
		
		$this->load->model('Benefit_model');
		$data['benefits'] = $this->Benefit_model->get_benefits($offset, $term);
		$data['pages'] = $this->Benefit_model->count_benefits($term);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
