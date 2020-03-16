<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->has_userdata('user_id') == FALSE){
			redirect(site_url('login'));

		}
	}
	
	public function index()
	{
		$this->load->view('head');
		$this->load->view('accounting/header');
		
		$this->load->view('accounting/create_invoice_dashboard');
	}
	
	public function view_uninvoiced_retail_delivery_orders()
	{
		$term		= $this->input->get('term');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 25;
		$this->load->model('Delivery_order_model');
		$result = $this->Delivery_order_model->show_uninvoiced_retail_delivery_order($offset, $term);
		$data['delivery_orders'] = $result;
		
		$result = $this->Delivery_order_model->count_uninvoiced_retail_delivery_order($term);
		$data['pages'] = max(1, ceil($result / 25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function create_retail()
	{
		$delivery_order_id		= $this->input->post('id');
		$this->load->model('Delivery_order_detail_model');
		$result = $this->Delivery_order_detail_model->show_by_code_delivery_order_id($delivery_order_id);
		$data['details'] = $result;
		
		$this->load->view('head');
		$this->load->view('accounting/header');
		
		$this->load->view('accounting/create_invoice', $data);
	}
	
	public function print_retail()
	{
		$delivery_order_id		= $this->input->post('id');
		$this->load->view('head');		
		$this->load->model('Delivery_order_detail_model');
		$result = $this->Delivery_order_detail_model->show_by_code_delivery_order_id($delivery_order_id);
		$data['details'] = $result;
		
		$customer_id	= $result[0]->customer_id;
		$this->load->model('Customer_model');
		$data['customer'] = $this->Customer_model->show_by_id($customer_id);
		
		$this->load->model('Invoice_model');
		$input_invoice = $this->Invoice_model->delivery_order_input($delivery_order_id, $result[0]);
		if($input_invoice != NULL){
			$this->load->model('Delivery_order_model');
			$this->Delivery_order_model->set_invoice_id($delivery_order_id, $input_invoice);
			
			$this->load->view('accounting/print_retail_invoice', $data);
		} else {
			redirect(site_url('Invoice'));
		}
	}
}
