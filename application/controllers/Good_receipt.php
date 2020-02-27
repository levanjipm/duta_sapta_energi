<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Good_receipt extends CI_Controller {
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
		
		$this->load->model('Good_receipt_model');
		$result	= $this->Good_receipt_model->show_unconfirmed_good_receipt();
		$data['good_receipts'] = $result;
		
		$this->load->view('inventory/good_receipt', $data);
	}
	
	public function create_dashboard()
	{
		$this->load->view('head');
		$this->load->view('inventory/header');
		
		$this->load->model('Supplier_model');
		$result = $this->Supplier_model->show_all();
		$data['suppliers'] = $result;
		$this->load->view('inventory/good_receipt_create_dashboard', $data);
	}
	
	public function get_incompleted_purchase_order()
	{
		$supplier_id = $this->input->get('supplier_id');
		$this->load->model('Purchase_order_model');
		$data = $this->Purchase_order_model->get_incompleted_purchase_order($supplier_id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	
	public function input()
	{
		print_r($_POST);
		$this->load->model('Good_receipt_model');
		$id		= $this->Good_receipt_model->input_from_post();
		
		if($id != null){
			$this->load->model('Good_receipt_detail_model');
			$this->Good_receipt_detail_model->insert_from_post($id);
			
			$quantity_array		= $this->input->post('quantity');
			
			$this->load->model('Purchase_order_detail_model');
			$this->Purchase_order_detail_model->update_purchase_order_received($quantity_array);
		}
		
		redirect(site_url('Good_receipt'));
	}
	
	public function view_complete_good_receipt()
	{
		$good_receipt_id		= $this->input->get('id');
		$this->load->model('View_complete_good_receipt');
		$data = $this->View_complete_good_receipt->show_by_id($good_receipt_id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}