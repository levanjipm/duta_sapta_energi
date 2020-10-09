<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
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
		if(count($data['departments']) > 1){
			$this->load->view('head');
			$this->load->view('header', $data);
		} else if(count($data['departments']) > 0) {
			redirect(site_url($data['departments'][0]->index_url));
		} else {
			redirect(site_url('Profile'));
		}
	}
}
