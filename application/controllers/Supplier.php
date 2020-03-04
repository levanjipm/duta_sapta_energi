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
		$this->load->view('head');
		$this->load->view('purchasing/header');
		
		$this->load->model('Supplier_model');
		$items = $this->Supplier_model->show_limited(25,0);
		$data['suppliers'] = $items;
		
		$this->load->model('Supplier_model');
		$data['pages'] = ceil($this->Supplier_model->count_page() / 25);
		
		$this->load->view('purchasing/supplier_manage_dashboard',$data);
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
	
	public function update_supplier_view()
	{
		$this->load->model('supplier_model');
		$data['supplier'] = $this->supplier_model->show_by_id();
		
		$this->load->model('Area_model');
		$data['areas'] = $this->Area_model->show_all();
		
		$this->load->view('sales/supplier_edit_form',$data);
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