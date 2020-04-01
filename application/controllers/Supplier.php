<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	
class Supplier extends CI_Controller {
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
		$this->load->view('purchasing/header', $data);
		
		$this->load->view('purchasing/supplier_manage_dashboard');
	}
	
	public function view_items()
	{
		$page		= $this->input->get('page');
		$term		= $this->input->get('term');
		$offset		= ($page - 1) * 25;
		
		$this->load->model('Supplier_model');
		$data['suppliers']	= $this->Supplier_model->show_items($offset, $term);
		$data['pages']		= $this->Supplier_model->count_items($term);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function insert_new_supplier()
	{
		$this->load->model('supplier_model');
		$this->supplier_model->insert_from_post();
		
		redirect(site_url('supplier'));
	}
	
	public function delete_supplier()
	{
		$this->load->model('supplier_model');
		$this->supplier_model->delete_by_id();
	}
	
	public function update_supplier()
	{
		$this->load->model('supplier_model');
		$this->supplier_model->update_from_post();
		
		redirect(site_url('supplier'));
	}
	
	public function select_by_id()
	{
		$supplier_id	= $this->input->get('id');
		$this->load->model('Supplier_model');
		$item = $this->Supplier_model->select_by_id($supplier_id);
		
		header('Content-Type: application/json');
		echo json_encode($item);
	}
}