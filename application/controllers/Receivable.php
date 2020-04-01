<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receivable extends CI_Controller {
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
		$this->load->view('accounting/header', $data);
		
		$this->load->view('accounting/receivable_dashboard');
	}
	
	public function view_receivable()
	{	
		$date_1		= $this->input->get('date_1');
		$date_2		= $this->input->get('date_2');
		
		$this->load->model('Invoice_model');
		$data	= $this->Invoice_model->view_receivable_chart($date_1, $date_2);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
