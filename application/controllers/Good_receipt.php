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
		$this->load->model('Good_receipt_model');
		$id		= $this->Good_receipt_model->input_from_post();
		
		if($id != null){
			$quantity_array		= $this->input->post('quantity');
			$price_array		= $this->input->post('net_price');
			$this->load->model('Good_receipt_detail_model');
			$this->Good_receipt_detail_model->insert_from_post($id, $quantity_array, $price_array);
			
			$this->load->model('Purchase_order_detail_model');
			$this->Purchase_order_detail_model->update_purchase_order_received($quantity_array);
		}
		
		redirect(site_url('Good_receipt'));
	}
	
	public function view_complete_good_receipt()
	{
		$good_receipt_id		= $this->input->get('id');
		$this->load->model('Good_receipt_model');
		$data['general'] = $this->Good_receipt_model->show_by_id($good_receipt_id);
		
		$this->load->model('Good_receipt_detail_model');
		$data['detail'] = $this->Good_receipt_detail_model->show_by_code_good_receipt_id($good_receipt_id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function confirm()
	{
		$id		= $this->input->post('id');
		$this->load->model('Good_receipt_model');
		if ($this->Good_receipt_model->confirm($id))
		{
			$this->load->model('Good_receipt_detail_model');
			$batch = $this->Good_receipt_detail_model->get_batch_by_code_good_receipt_id($id);

			$this->load->model('Stock_in_model');
			$this->Stock_in_model->input_from_code_good_receipt($batch);
		}
		
		redirect(site_url('Good_receipt'));
	}
	
	public function delete()
	{
		$id		= $this->input->post('id');
		$this->load->model('Good_receipt_model');
		$result = $this->Good_receipt_model->delete($id);
		
		if($result){
			$this->load->model('Good_receipt_detail_model');
			$batch = $this->Good_receipt_detail_model->show_by_code_good_receipt_id($id);
			
			$this->load->model('Purchase_order_detail_model');
			$this->Purchase_order_detail_model->delete_from_good_receipt($batch);
		}
	}
	
	public function archive()
	{
		$this->load->view('head');
		$this->load->view('inventory/header');
		$this->load->view('inventory/good_receipt_archive');
	}
	
	public function view_archive($offset, $year, $month, $limit)
	{
	}
	
	public function select_by_invoice_id($invoice_id)
	{
		$this->where('invoice_id', $invoice_id);
		$query = $this->db->get($this->table_good_receipt);
		$item	= $query->result();
		return $item;
	}
}