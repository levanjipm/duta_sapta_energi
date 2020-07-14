<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchasing extends CI_Controller {
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
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('purchasing/header', $data);
		$this->load->view('purchasing/index_page');
	}
	
	public function calculate_needs()
	{
		$this->load->model('Sales_order_detail_model');
		$data		= $this->Sales_order_detail_model->calculate_incomplete();
		
		print_r($data);
	}
}
