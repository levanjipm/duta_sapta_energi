<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Director extends CI_Controller {
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

		if($data['user_login']->access_level < 5){
			redirect(site_url("Welcome"));
		} else {	
			$this->load->model('Authorization_model');
			$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
			$this->load->view('head');
			$this->load->view('director/header', $data);
		}
		
	}
}
