<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item_return extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->has_userdata('user_id') == FALSE){
			redirect(site_url('login'));

		}
	}
	
	public function purchasing()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->show_by_id($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('purchasing/header', $data);
		
		$this->load->model('Supplier_model');
		$data['suppliers']	= $this->Supplier_model->show_items();
		
		$this->load->view('purchasing/return', $data);
	}
	
	public function purchasing_input()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('Purchase_return_model');
		$result			= $this->Purchase_return_model->insert_from_post();
		
		if($result != NULL){
			$this->load->model('Purchase_return_detail_model');
			$this->Purchase_return_detail_model->insert_from_post($result);
		}
		
		redirect(site_url('Item_return/purchasing'));
	}
	
	public function confirm($department)
	{
		if($department == 'purchasing')
		{
			$user_id		= $this->session->userdata('user_id');
			$this->load->model('User_model');
			$data['user_login'] = $this->User_model->show_by_id($user_id);
			
			$this->load->model('Authorization_model');
			$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
			
			$this->load->view('head');
			$this->load->view('purchasing/header', $data);
			
			$this->load->view('purchasing/return_confirm');
		}
	}
	
	public function view_purchase_return()
	{
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 25;
		$this->load->model('Purchase_return_model');
		$data['items']	= $this->Purchase_return_model->view_items($offset);
		$data['pages']	= max(1, ceil($this->Purchase_return_model->count_items()/25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
