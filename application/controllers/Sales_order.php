<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_order extends CI_Controller {
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
		
		$this->load->view('sales/sales_order');
	}
	
	public function view_unconfirmed_sales_order()
	{
		$page		= $this->input->get('page');
		$term		= $this->input->get('term');
		$offset		= ($page - 1) * 25;
		$this->load->model('Sales_order_model');
		$data['sales_orders'] 	= $this->Sales_order_model->show_unconfirmed_sales_order($offset, $term);
		$data['pages'] 			= max(1, ceil($this->Sales_order_model->count_unconfirmed_sales_order($term)/25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function create()
	{
		$this->load->view('head');
		$this->load->view('sales/header');
		
		$this->load->model('Sales_order_model');
		$guid = $this->Sales_order_model->create_guid();
		$data['guid'] = $guid;
		
		$this->load->model('Customer_model');
		$data['customers'] = $this->Customer_model->show_items();
		
		$this->load->model('User_model');
		$data['users'] = $this->User_model->show_all();
		
		$this->load->view('sales/sales_order_create_dashboard',$data);
	}
	
	public function add_item_to_cart()
	{
		$this->load->model('Sales_order_model');
		$this->Sales_order_model->add_item_to_cart();
	}
	
	public function input_sales_order()
	{	
		$this->load->model('Sales_order_model');
		$id = $this->Sales_order_model->insert_from_post();
		if($id != NULL){
			$this->load->model('Sales_order_detail_model');
			$this->Sales_order_detail_model->insert_from_post($id);
		};
		
		if($id == null){
			redirect(site_url('Sales_order'));
		} else {
			redirect(site_url('Sales_order/sales_order_check_out/') . $id);
		}
	}
	
	public function sales_order_check_out($id)
	{
		$this->load->model('Sales_order_model');
		$sales_order 	= $this->Sales_order_model->show_by_id($id);
		$result 		= $sales_order;
		
		$data['general'] = $result;
		
		$customer_id = $data['general']->customer_id;
		$this->load->model('Customer_model');
		$data['customer'] = $this->Customer_model->show_by_id($customer_id);
		
		$this->load->model('Sales_order_detail_model');
		$data['details'] = $this->Sales_order_detail_model->show_by_code_sales_order_id($id);
		
		$this->load->view('head');
		$this->load->view('sales/header');
		$this->load->view('sales/sales_order_check_out', $data);
	}
	
	public function track_sales_order()
	{
		$this->load->model('Sales_order_detail_model');
		$this->load->view('head');
		$this->load->view('sales/header');
	}
	
	public function view_sales_order()
	{
		$sales_order_id		= $this->input->get('id');
		$this->load->model('Sales_order_model');
		$result = $this->Sales_order_model->show_by_id($sales_order_id);
		$data['general']	= $result;
		
		$this->load->model('Sales_order_detail_model');
		$data['detail'] = $this->Sales_order_detail_model->show_by_code_sales_order_id($sales_order_id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function confirm()
	{
		$sales_order_id		= $this->input->post('id');
		$this->load->model('Sales_order_model');
		$this->Sales_order_model->confirm($sales_order_id);
		
		redirect(site_url('Sales_order'));
	}
	
	public function delete()
	{
		$sales_order_id		= $this->input->post('id');
		$this->load->model('Sales_order_model');
		$this->Sales_order_model->delete($sales_order_id);
	}
}
