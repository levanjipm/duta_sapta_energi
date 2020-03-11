<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Delivery_order extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->has_userdata('user_id') == FALSE){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$this->load->view('head');
		$this->load->view('inventory/header');
		
		$this->load->model('Delivery_order_model');
		$result	= $this->Delivery_order_model->show_unconfirmed_delivery_order();
		$data['delivery_orders'] = $result;
		
		$result	= $this->Delivery_order_model->show_unsent_delivery_order();
		$data['unsent_delivery_orders'] = $result;
		
		$this->load->view('inventory/delivery_order', $data);
	}
	
	public function create_dashboard()
	{
		$this->load->view('head');
		$this->load->view('inventory/header');
		
		$this->load->model('Sales_order_model');
		$data['sales_orders'] = $this->Sales_order_model->show_uncompleted_sales_order();
		$customer_ids = array();
		foreach($data['sales_orders'] as $sales_order){
			array_push($customer_ids, $sales_order->customer_id);
		}
		
		$this->load->model('Customer_model');
		$data['customers'] = $this->Customer_model->show_all_by_id($customer_ids);
		
		$this->load->view('inventory/delivery_order_create_dashboard', $data);
	}
	
	public function view_sales_order()
	{
		$id		= $this->input->get('sales_order_id');
		$this->load->model('Sales_order_model');
		$result	= $this->Sales_order_model->show_by_id($id);
		$data['sales_order'] = $result[0];
		
		$this->load->model('Sales_order_detail_model');
		$result	= $this->Sales_order_detail_model->show_by_code_sales_order_id($id);
		$data['details'] = $result;
		
		$this->load->model('Delivery_order_model');
		$data['guid'] = $this->Delivery_order_model->create_guid();
		
		$this->load->view('Inventory/sales_order_view',$data);
	}
	
	public function input_delivery_order()
	{
		$guid	= $this->input->post('guid');
		$this->load->model('Delivery_order_model');
		$result = $this->Delivery_order_model->check_guid($guid);
		
		if($result){
			$sales_order_array	= array_keys($this->input->post('quantity'));
			$quantity_array		= array_values($this->input->post('quantity'));
			
			$this->load->model('Sales_order_detail_model');
			$result = $this->Sales_order_detail_model->check_sales_order($sales_order_array, $quantity_array);
			
			if($result){
				$delivery_order_id = $this->Delivery_order_model->insert_from_post();
				
				$this->load->model('Delivery_order_detail_model');
				$result = $this->Delivery_order_detail_model->insert_from_post($delivery_order_id);
				
				if($result){
					$this->Sales_order_detail_model->update_sales_order_sent($sales_order_array, $quantity_array);
				}
			};
		}
		
		redirect(site_url('Inventory'));
	}
	
	public function show_by_code_delivery_order_id($id)
	{
		$this->load->model('Delivery_order_detail_model');
		$data = $this->Delivery_order_detail_model->show_by_code_delivery_order_id($id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function confirm()
	{
		$delivery_order_id			= $this->input->post('id');
		$this->load->model('Delivery_order_model');
		$result = $this->Delivery_order_model->confirm($delivery_order_id);
		if($result){
			$this->load->model('Sales_order_model');
			$sales_order = $this->Sales_order_model->show_invoicing_method_by_id($delivery_order_id);
			
			if($sales_order[0]->invoicing_method == 2){
				redirect(site_url('Delivery_order/print/') . $delivery_order_id);
			} else {
				redirect(site_url('Delivery_order'));
			}
		}
	}
	
	public function print($delivery_order_id)
	{
		$this->load->model('View_delivery_order_model');
		$data['datas'] = $this->View_delivery_order_model->show_by_id($delivery_order_id);
		
		$this->load->model('View_delivery_order_detail_model');
		$data['items'] = $this->View_delivery_order_detail_model->show_by_code_delivery_order_id($delivery_order_id);
		
		$this->load->view('head');
		$this->load->view('Inventory/delivery_order_print', $data);
	}
	
	public function send()
	{
		$delivery_order_array[]	= array(
			'item_id' => '1',				
			'quantity' => '25',				
			'code_delivery_order_id' => '5',	
			'customer_id' => 1
		);		
			
		$this->load->model('Stock_out_model');
		$this->Stock_out_model->send_delivery_order($delivery_order_array);
	}
}