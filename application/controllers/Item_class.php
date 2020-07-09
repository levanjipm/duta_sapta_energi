<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item_class extends CI_Controller {
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
		$this->load->view('sales/header', $data);
		
		$this->load->view('sales/item_class_manage_dashboard');
	}
	
	public function insert_from_post()
	{
		$this->load->model('Item_class_model');
		$result = $this->Item_class_model->insert_from_post();
		
		echo $result;
	}
	
	public function delete_item_class()
	{
		$this->load->model('Item_class_model');
		$this->Item_class_model->delete_by_id();
		
		redirect(site_url('Item_class'));
	}
	
	public function update_from_post()
	{		
		$this->load->model('Item_class_model');
		$result = $this->Item_class_model->update_from_post();
		
		echo $result;
	}
	
	public function show_items()
	{
		$term			= $this->input->get('term');
		$page			= $this->input->get('page');
		$offset			= ($page - 1) * 25;
		$this->load->model('Item_class_model');
		$data['items']			= $this->Item_class_model->show_items($offset, $term);
		$data['pages']			= max(0, ceil($this->Item_class_model->count_items($term)/25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function get_by_id()
	{
		$this->load->model('Item_class_model');
		$id 	= $this->input->get('id');
		$data	= $this->Item_class_model->get_by_id($id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}