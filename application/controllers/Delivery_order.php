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
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('inventory/header', $data);
		
		$this->load->model('Delivery_order_model');
		$result	= $this->Delivery_order_model->show_unconfirmed_delivery_order();
		$data['delivery_orders'] = $result;
		
		$result	= $this->Delivery_order_model->show_unsent_delivery_order();
		$data['unsent_delivery_orders'] = $result;
		
		$this->load->view('inventory/delivery_order', $data);
	}
	
	public function create_dashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('inventory/header', $data);
		
		$this->load->model('Sales_order_model');
		$data['sales_orders'] = $this->Sales_order_model->show_uncompleted_sales_order();
		
		if(!empty($data['sales_order'])){
			$customer_ids = array();
			foreach($data['sales_orders'] as $sales_order){
				array_push($customer_ids, $sales_order->customer_id);
			}
			
			$this->load->model('Customer_model');
			$data['customers'] = $this->Customer_model->show_all_by_id($customer_ids);
		}
		
		$this->load->view('inventory/delivery_order_create_dashboard', $data);
	}
	
	public function view_sales_order()
	{
		$id		= $this->input->get('sales_order_id');
		
		$this->load->model('Sales_order_detail_model');
		$result	= $this->Sales_order_detail_model->show_by_code_sales_order_id($id);
		$data['details'] = $result;
		
		$this->load->model('Delivery_order_model');
		$data['guid'] = $this->Delivery_order_model->create_guid();
		
		$user_id			= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user']		= $this->User_model->getById($user_id);

		$this->load->model('Sales_order_model');
		$result 			= $this->Sales_order_model->show_by_id($id);
		$data['general']	= $result;
		
		$customer_id		= $result->customer_id;
		
		$this->load->model('Customer_model');
		$data['customer']	= $this->Customer_model->getById($customer_id);
		
		$this->load->model('Sales_order_detail_model');
		$data['pending_value']	= $this->Sales_order_detail_model->show_pending_value($customer_id);
		
		$this->load->model('Bank_model');
		$data['pending_bank_data']	= $this->Bank_model->getPendingValueByOpponentId('customer', $customer_id);
		
		$this->load->model('Invoice_model');
		$data['receivable'] = $this->Invoice_model->view_maximum_by_customer($customer_id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
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
		
		redirect(site_url('Delivery_order'));
	}
	
	public function show_by_code_delivery_order_id($id)
	{
		$this->load->model('Delivery_order_detail_model');
		$this->load->model('Delivery_order_model');
		
		$data['invoice'] = $this->Delivery_order_model->show_by_id($id);
		$data['general'] = $this->Delivery_order_detail_model->show_by_code_delivery_order_id($id);
		
		$delivery_order_array 	= $this->Delivery_order_detail_model->get_batch_by_code_delivery_order_id($id);
		
		$this->load->model('Stock_in_model');
		$result					= $this->Stock_in_model->check_stock($delivery_order_array);
		if($result){
			$data['info'] = '';
		} else {
			$data['info'] = 'Stock';
		}
		
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
		$this->load->model('Delivery_order_model');
		$result = $this->Delivery_order_model->show_by_id($delivery_order_id);
		$data['general']	= $result;
		
		$this->load->model('Delivery_order_detail_model');
		$data['items'] = $this->Delivery_order_detail_model->show_by_code_delivery_order_id($delivery_order_id);
		
		$this->load->view('head');
		$this->load->view('Inventory/delivery_order_print', $data);
	}
	
	public function send()
	{
		$delivery_order_id		= $this->input->post('id');
		
		$this->load->model('Delivery_order_detail_model');
		$delivery_order_array 	= $this->Delivery_order_detail_model->get_batch_by_code_delivery_order_id($delivery_order_id);
		
		$this->load->model('Stock_in_model');
		$result					= $this->Stock_in_model->check_stock($delivery_order_array);
		
		if($result){
			$this->load->model('Delivery_order_model');
			$check 				= $this->Delivery_order_model->send($delivery_order_id);
			if($check){			
				$this->load->model('Stock_out_model');
				$this->Stock_out_model->send_delivery_order($delivery_order_array);
			}
		}
		
		redirect(site_url('Delivery_order'));
	}
	
	public function show_by_sales_order()
	{
		$sales_order_id		= $this->input->get('sales_order_id');
		
		$this->load->model('Delivery_order_detail_model');
		$data 				= $this->Delivery_order_detail_model->show_by_code_sales_order_id($sales_order_id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function archive()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('inventory/header', $data);
		
		$this->load->model('Delivery_order_model');
		$data['years']	= $this->Delivery_order_model->show_years();
		
		$this->load->view('inventory/delivery_order_archive', $data);
	}
	
	public function view_archive()
	{
		$page			= $this->input->get('page');
		$term			= $this->input->get('term');
		$offset			= ($page - 1) * 25;
		$year			= $this->input->get('year');
		$month			= $this->input->get('month');
		
		$this->load->model('Delivery_order_model');
		$data['delivery_orders'] 	= $this->Delivery_order_model->show_items($year, $month, $offset, $term);
		$data['pages']				= max(1, ceil($this->Delivery_order_model->count_items($year, $month, $term)/25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function view_by_id()
	{
		$id				= $this->input->get('id');
		$this->load->model('Delivery_order_model');
		$data['general']	= $this->Delivery_order_model->show_by_id($id);
		
		$this->load->model('Delivery_order_detail_model');
		$data['items']		= $this->Delivery_order_detail_model->show_by_code_delivery_order_id($id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function cancel()
	{
		$id				= $this->input->post('id');
		$this->load->model('Delivery_order_detail_model');
		$sales_order_array		= $this->Delivery_order_detail_model->select_by_code_delivery_order_id($id);

		if(count($sales_order_array) > 0){
			$this->load->model('Sales_order_detail_model');
			$this->Sales_order_detail_model->update_from_delivery_order_cancelation($sales_order_array);
			
			$this->load->model('Delivery_order_model');
			$this->Delivery_order_model->delete_by_id($id);
		}
	}
	
	public function select_by_name()
	{
		$name			= $this->input->post('name');
		$this->load->model('Delivery_order_model');
		$data	= $this->Delivery_order_model->select_by_name($name);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}