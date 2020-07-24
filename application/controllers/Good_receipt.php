<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Good_receipt extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->has_userdata('user_id') == FALSE){
			redirect(site_url('login'));
		}
	}
	
	public function createDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('Inventory/header', $data);
		
		$this->load->view('Inventory/goodReceipt/goodReceiptCreate');
	}

	public function confirmDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('Inventory/header', $data);

		$this->load->view('Inventory/goodReceipt/goodReceiptConfirm');
	}
	
	public function getIncompletePurchaseOrder()
	{
		$supplier_id = $this->input->get('supplier_id');
		$this->load->model('Purchase_order_model');
		$data = $this->Purchase_order_model->getIncompletePurchaseOrder($supplier_id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getAllIncompletePurchaseOrderSupplier()
	{
		$this->load->model('Purchase_order_model');
		$data = $this->Purchase_order_model->getAllIncompletePurchaseOrderSupplier();

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getPurchaseOrderDetailById()
	{
		$id					= $this->input->get('purchase_order');
		$date				= $this->input->get('date');

		$this->load->model('Purchase_order_model');
		$result 			= $this->Purchase_order_model->showById($id);
		
		$data['general'] = $result;
		$result				= $this->Purchase_order_model->create_guid();
		$data['guid']		= $result;
		
		$this->load->model('Purchase_order_detail_model');
		$result = $this->Purchase_order_detail_model->getByCodeId($id);
		
		$data['purchase_orders'] = $result;
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function insertItem()
	{
		$this->load->model('Good_receipt_model');
		$id		= $this->Good_receipt_model->insertItem();
		
		if($id != null){
			$quantity_array		= $this->input->post('quantity');
			$price_array		= $this->input->post('net_price');
			
			$this->load->model('Good_receipt_detail_model');
			$this->Good_receipt_detail_model->insertItem($id, $quantity_array, $price_array);
			
			$this->load->model('Purchase_order_detail_model');
			$this->Purchase_order_detail_model->updatePurchaseOrderReceivedArray($quantity_array);
		}
		
		redirect(site_url('Good_receipt/createDashboard'));
	}
	
	public function view_complete_good_receipt()
	{
		$good_receipt_id		= $this->input->get('id');
		$this->load->model('Good_receipt_model');
		$data['general'] = $this->Good_receipt_model->showById($good_receipt_id);
		
		$this->load->model('Good_receipt_detail_model');
		$data['detail'] = $this->Good_receipt_detail_model->show_by_code_good_receipt_id($good_receipt_id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function confirmById()
	{
		$id		= $this->input->post('id');
		$this->load->model('Good_receipt_model');
		if ($this->Good_receipt_model->updateStatusById(1, $id) == 1)
		{
			$this->load->model('Good_receipt_detail_model');

			$batch = $this->Good_receipt_detail_model->getStockBatchByCodeGoodReceiptId($id);

			$expectedInput = count($batch);

			$this->load->model('Stock_in_model');
			$result = $this->Stock_in_model->insertItemFromGoodReceipt($batch);

			if($result == $expectedInput){
				echo 1;
			} else {
				$this->Good_receipt_detail_model->deleteByCodeGoodReceiptId($id);
				$this->Stock_in_model->deleteItemFromGoodReceipt($id);
				$this->Good_receipt_model->updateStatusById(-1, $id);

				echo 0;
			}
		} else {
			echo 0;
		}
	}
	
	public function deleteById()
	{
		$id		= $this->input->post('id');
		// $this->load->model('Good_receipt_model');
		// $result = $this->Good_receipt_model->updateStatusById($id);
		
		// if($result){
			$this->load->model('Good_receipt_detail_model');
			$batch = $this->Good_receipt_detail_model->getBatchByCodeGoodReceiptId($id);

			$this->load->model('Purchase_order_detail_model');
			$this->Purchase_order_detail_model->updatePurchaseOrderReceivedArray($batch);
		// }
	}
	
	public function archiveDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('Inventory/header', $data);
		
		$this->load->model('Good_receipt_model');
		$data['years']	= $this->Good_receipt_model->show_years();
		
		$this->load->view('Inventory/goodReceipt/goodReceiptArchive', $data);
	}
	
	public function view_archive()
	{
		$page			= $this->input->get('page');
		$term			= $this->input->get('term');
		$offset			= ($page - 1) * 25;
		$year			= $this->input->get('year');
		$month			= $this->input->get('month');
		
		$this->load->model('Good_receipt_model');
		$data['good_receipts'] 	= $this->Good_receipt_model->show_items($year, $month, $offset, $term);
		$data['pages']				= max(1, ceil($this->Good_receipt_model->count_items($year, $month, $term)/25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function showById()
	{
		$id			= $this->input->get('id');
		$this->load->model('Good_receipt_model');
		$data['general']	= $this->Good_receipt_model->showById($id);
		
		$this->load->model('Good_receipt_detail_model');
		$data['items']		= $this->Good_receipt_detail_model->showByCodeGoodReceiptId($id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function getByInvoiceId($invoice_id)
	{
		// $this->where('invoice_id', $invoice_id);
		// $query = $this->db->get($this->table_good_receipt);
		// $item	= $query->result();
		// return $item;
	}

	public function showUnconfirmedItems()
	{
		$page = $this->input->get('page');
		$offset = ($page - 1) * 10;
		$this->load->model('Good_receipt_model');
		$data['items'] = $this->Good_receipt_model->showUnconfirmedGoodReceipts($offset);
		$data['pages'] = max(1, ceil($this->Good_receipt_model->countUnconfirmedGoodReceipts()/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}
}