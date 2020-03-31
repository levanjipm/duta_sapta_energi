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
		$this->session->unset_userdata('purchase_cart_bonus_products');
		
		$this->load->model('Supplier_model');
		$result = $this->Supplier_model->show_items();
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
		$this->load->model('Item_model');
		$item = $this->Item_model->select_by_id($item_id);
		
		header('Content-Type: application/json');
		echo json_encode($item);
	}
	
	public function add_item_to_cart_as_bonus()
	{
		$item_id	= $this->input->post('item_id');
		$this->load->model('Item_model');
		$item = $this->Item_model->select_by_id($item_id);
		
		header('Content-Type: application/json');
		echo json_encode($item);
	}
	
	public function update_cart_view()
	{
		if($this->session->has_userdata('purchase_cart_products')){
			$detail = array();
			$products = $this->session->userdata('purchase_cart_products');
			$bonus_products = $this->session->userdata('purchase_cart_bonus_products');
			
			$this->load->model('Item_model');
			$data['carts'] = $this->Item_model->show_purchase_cart($products);
			$data['bonus_carts'] = $this->Item_model->show_purchase_cart($bonus_products);
			
			header('Content-Type: application/json');
			echo json_encode($data);
		}
	}
	
	public function input_purchase_order()
	{
		$this->load->model('Purchase_order_model');
		$purchase_order_id = $this->Purchase_order_model->input_from_post();
		
		if($purchase_order_id != null){
			$this->load->model('Purchase_order_detail_model');
			$this->Purchase_order_detail_model->insert_from_post($purchase_order_id);
		}
		
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
		
		$this->load->model('Purchase_order_detail_model');
		$result = $this->Purchase_order_detail_model->show_by_purchase_order_id($purchase_order_id);
		
		$data['purchase_orders'] = $result;
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function get_purchase_order_detail_by_id($id)
	{
		$this->load->model('Purchase_order_model');
		$data['general']	= $this->Purchase_order_model->show_by_id($id);
		
		$this->load->model('Purchase_order_detail_model');
		$data['detail']		= $this->Purchase_order_detail_model->show_by_purchase_order_id($id);
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function confirm()
	{
		$id		= $this->input->post('id');
		$this->load->model('Purchase_order_model');
		$this->Purchase_order_model->confirm_purchase_order($id);
		
		redirect(site_url('Purchase_order/print/') . $id);
	}
	
	public function delete()
	{
		$id		= $this->input->get('id');
		$this->load->model('Purchase_order_model');
		$this->Purchase_order_model->delete_purchase_order($id);
		
		redirect(site_url('Purchase_order'));
	}
	
	public function print($purchase_order_id)
	{
		$this->load->model('Purchase_order_model');
		$data['general']	= $this->Purchase_order_model->show_by_id($purchase_order_id);
		
		$this->load->model('Purchase_order_detail_model');
		$data['detail']		= $this->Purchase_order_detail_model->show_by_purchase_order_id($purchase_order_id);
		
		$this->load->view('head');
		$this->load->view('purchasing/purchase_order_print', $data);
		
		print_r($data);
	}
}