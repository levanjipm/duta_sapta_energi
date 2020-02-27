<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_order extends CI_Controller {
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
		
		$this->load->model('Purchase_order_model');
		$result = $this->Purchase_order_model->show_unconfirmed_purchase_order();
		$data['purchase_orders'] = $result;
		$this->load->view('Purchasing/purchase_order', $data);
	}
	
	public function create()
	{
		$this->session->unset_userdata('purchase_cart_products');
		$this->load->model('Supplier_model');
		$result = $this->Supplier_model->show_all();
		$data['suppliers'] = $result;
		
		$this->load->model('Purchase_order_model');
		$guid	= $this->Purchase_order_model->create_guid();
		
		$data['guid'] = $guid;
		
		$this->load->view('head');
		$this->load->view('purchasing/header');
		$this->load->view('Purchasing/purchase_order_create_dashboard', $data);
	}
	
	public function add_item_to_cart()
	{
		$item_id	= $this->input->post('item_id');
		$this->load->model('Purchase_order_model');
		$this->Purchase_order_model->add_item_to_cart($item_id);
	}
	
	public function update_cart_view()
	{
		if($this->session->has_userdata('purchase_cart_products')){
			$detail = array();
			$products = $this->session->userdata('purchase_cart_products');
			
			$this->load->model('Item_model');
			$data['carts'] = $this->Item_model->show_purchase_cart($products);
			
			$this->load->view('purchasing/shopping_cart_list',$data);
		}
	}
	
	public function remove_item_from_cart()
	{
		$this->load->model('Purchase_order_model');
		$this->Purchase_order_model->remove_item_from_cart();
		
		if($this->session->has_userdata('purchase_cart_products')){
			$detail = array();
			$products = $this->session->userdata('purchase_cart_products');
			
			$this->load->model('Item_model');
			$data['carts'] = $this->Item_model->show_cart($products);
			
			$this->load->view('purchasing/shopping_cart_list',$data);
		}
	}
	
	public function input_purchase_order()
	{
		$this->load->model('Purchase_order_model');
		$purchase_order_id = $this->Purchase_order_model->input_from_post();
		
		$this->load->model('Purchase_order_detail_model');
		$this->Purchase_order_detail_model->insert_from_post($purchase_order_id);
		
		redirect(site_url('Purchase_order'));
	}
	
	public function get_incomplete_purchase_order_detail()
	{
		$purchase_order_id	= $this->input->get('purchase_order');
		$date				= $this->input->get('date');
		$this->load->model('Purchase_order_model');
		$result 			= $this->Purchase_order_model->show_by_id($purchase_order_id);
		
		$data['general'] = $result;
		$result				= $this->Purchase_order_model->create_guid();
		$data['guid']		= $result;
		
		$this->load->model('View_purchase_order_detail_model');
		$result = $this->View_purchase_order_detail_model->show_by_code_purchase_order_id($purchase_order_id);
		
		$data['purchase_orders'] = $result;
		
		$this->load->view('Inventory/view_incomplete_purchase_order', $data);
	}
}