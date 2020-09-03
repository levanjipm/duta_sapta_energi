<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_order extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->has_userdata('user_id') == FALSE){
			redirect(site_url('login'));
		}
	}
	
	public function confirmDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('purchasing/header', $data);
		$this->load->view('Purchasing/PurchaseOrder/confirmDashboard');
	}
	
	public function createDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('purchasing/header', $data);
		
		$this->load->model('Purchase_order_model');
		$guid	= $this->Purchase_order_model->create_guid();
		
		$data['guid'] = $guid;
		$this->load->view('Purchasing/PurchaseOrder/createDashboard', $data);
	}
	
	public function addItemToCart()
	{
		$item_id	= $this->input->post('item_id');
		$this->load->model('Item_model');
		$item = $this->Item_model->showById($item_id);
		
		header('Content-Type: application/json');
		echo json_encode($item);
	}
	
	public function addBonusItemToCart()
	{
		$item_id	= $this->input->post('item_id');
		$this->load->model('Item_model');
		$item = $this->Item_model->showById($item_id);
		
		header('Content-Type: application/json');
		echo json_encode($item);
	}
	
	public function insertItem()
	{
		$this->load->model('Purchase_order_model');
		$purchase_order_id = $this->Purchase_order_model->insertItem();
		
		if($purchase_order_id != null){
			$this->load->model('Purchase_order_detail_model');
			$this->Purchase_order_detail_model->insert_from_post($purchase_order_id);
		}
		
		redirect(site_url('Purchase_order/createDashboard'));
	}
	
	public function getById($id)
	{
		$this->load->model('Purchase_order_model');
		$purchaseOrder	= $this->Purchase_order_model->showById($id);
		$data['general'] = $purchaseOrder;
		$supplierId = $purchaseOrder->supplier_id;

		$this->load->model("Supplier_model");
		$data['supplier'] = $this->Supplier_model->getById($supplierId);
		
		
		$this->load->model('Purchase_order_detail_model');
		$data['detail']		= $this->Purchase_order_detail_model->getByCodeId($id);
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function confirmById()
	{
		$id		= $this->input->post('id');
		$this->load->model('Purchase_order_model');
		$result = $this->Purchase_order_model->confirmById($id);
		
		echo $result;
	}
	
	public function deleteById()
	{
		$id		= $this->input->post('id');
		$this->load->model('Purchase_order_model');
		$result = $this->Purchase_order_model->deleteById($id);
		
		echo $result;
	}
	
	public function print($purchase_order_id)
	{
		$this->load->model('Purchase_order_model');
		$purchaseOrder	= $this->Purchase_order_model->showById($purchase_order_id);
		$data['general']	= $purchaseOrder;

		$this->load->model("Supplier_model");
		$supplierId			= $purchaseOrder->supplier_id;
		$data['supplier']	= $this->Supplier_model->getById($supplierId);
		
		$this->load->model('Purchase_order_detail_model');
		$data['detail']		= $this->Purchase_order_detail_model->getByCodeId($purchase_order_id);
		
		$this->load->view('head');
		$this->load->view('purchasing/PurchaseOrder/print', $data);
	}
	
	public function archiveDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('purchasing/header', $data);
		
		$this->load->model('Purchase_order_model');
		$data['years']	= $this->Purchase_order_model->show_years();
		
		$this->load->view('purchasing/PurchaseOrder/archiveDashboard', $data);
	}
	
	public function showArchive()
	{
		$page			= $this->input->get('page');
		$term			= $this->input->get('term');
		$offset			= ($page - 1) * 10;
		$year			= $this->input->get('year');
		$month			= $this->input->get('month');
		
		$this->load->model('Purchase_order_model');
		$data['purchase_orders'] 	= $this->Purchase_order_model->getItems($year, $month, $offset, $term);
		$data['pages']				= max(1, ceil($this->Purchase_order_model->count_items($year, $month, $term)/10));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function view_by_id()
	{
		$id				= $this->input->get('id');
		$this->load->model('Purchase_order_model');
		$data['general']	= $this->Purchase_order_model->show_by_id($id);
		
		$this->load->model('Purchase_order_detail_model');
		$data['items']		= $this->Purchase_order_detail_model->show_by_purchase_order_id($id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function pending()
	{		
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('purchasing/header', $data);
		
		$this->load->model('Purchase_order_detail_model');
		$data['suppliers']	= $this->Purchase_order_detail_model->show_supplier_for_incomplete_purchase_orders();
		
		$this->load->view('purchasing/PurchaseOrder/pendingDashboard', $data);
	}

	public function getPendingPurchaseOrder()
	{
		$supplier_id = $this->input->get('supplier_id');
		$page = $this->input->get('page');

		$offset = ($page - 1) * 10;
		$this->load->model('Purchase_order_model');
		$data['items'] = $this->Purchase_order_model->getIncompletePurchaseOrder($offset, $supplier_id);

		$data['pages'] = max(1, ceil($this->Purchase_order_model->countIncompletePurchaseOrder($supplier_id)/10));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getAllPendingPurchaseOrder()
	{
		$supplier_id = $this->input->get('supplier_id');
		$this->load->model('Purchase_order_model');
		$data = $this->Purchase_order_model->getAllIncompletePurchaseOrder($supplier_id);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getAllPendingSupplier()
	{
		$this->load->model('Purchase_order_model');
		$data = $this->Purchase_order_model->getAllIncompletePurchaseOrderSupplier();

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getUnconfirmedPurchaseOrders()
	{
		$this->load->model('Purchase_order_model');
		$this->load->model('Supplier_model');

		$data = $this->Purchase_order_model->getUnconfirmedPurchaseOrders();
		$result = array();
		$items = (array) $data;
		foreach($items as $item){
			$array = (array) $item;
			$supplierId = $item->supplier_id;
			$supplier = $this->Supplier_model->getById($supplierId);

			$array['supplier'] = $supplier;

			array_push($result, $array);
		}

		header('Content-Type: application/json');
		echo json_encode($result);
	}

	public function createFromDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('purchasing/header', $data);
		$data = array();
		$data = $_POST;

		$this->load->view('purchasing/PurchaseOrder/createFromDashboard', $data);
	}
}
