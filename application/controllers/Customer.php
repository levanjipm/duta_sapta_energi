<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	
class Customer extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->has_userdata('user_id') == FALSE){
			redirect(site_url('login'));

		}
	}
	
	public function index()
	{
		$this->load->view('head');
		$this->load->view('sales/header');
		
		$this->load->model('Customer_model');
		$items = $this->Customer_model->show_items();
		$data['customers'] = $items;
		
		$this->load->model('Area_model');
		$data['areas'] = $this->Area_model->show_all();
		
		$data['authorize'] = $this->session->userdata('user_role');
		
		$this->load->model('Customer_model');
		$data['pages'] = ceil($this->Customer_model->count_items() / 25);
		
		$this->load->view('sales/customer_manage_dashboard',$data);
	}
	
	public function insert_new_customer()
	{
		$this->load->model('Customer_model');
		$this->Customer_model->insert_from_post();
		
		redirect(site_url('customer'));
	}
	
	public function delete_customer()
	{
		$this->load->model('Customer_model');
		$this->Customer_model->delete_by_id();
	}
	
	public function update_customer_view()
	{
		$this->load->model('Customer_model');
		$data['customer'] = $this->Customer_model->show_by_id($this->input->get('customer_id'));
		
		$this->load->model('Area_model');
		$data['areas'] = $this->Area_model->show_all();
		
		$this->load->view('sales/customer_edit_form',$data);
	}
	
	public function update_customer()
	{
		$this->load->model('Customer_model');
		$this->Customer_model->update_from_post();
		
		redirect(site_url('customer'));
	}
	
	public function update_view_page()
	{
		$current_page	= $this->input->get('page') - 1;
		$filter			= $this->input->get('term');
		$this->load->model('Customer_model');
		$items			= $this->Customer_model->show_items($current_page * 25, $filter);
		$data['customers']	= $items;
		
		$data['pages']	= ceil($this->Customer_model->count_items($filter) / 25);
		
		$data['paging'] = max(1,$this->input->get('page'));
		
		$this->load->view('sales/customer_table_view',$data);
	}
	
	public function update_view_select()
	{
		$current_page	= $this->input->get('page') - 1;
		$filter			= $this->input->get('term');
		$this->load->model('Customer_model');
		$items			= $this->Customer_model->show_items($current_page * 25, $filter);
		$data['customers']	= $items;
		
		$data['pages']	= ceil($this->Customer_model->count_items($filter) / 25);
		
		$data['paging'] = max(1,$this->input->get('page'));
		
		$this->load->view('sales/customer_select_view',$data);
	}
}