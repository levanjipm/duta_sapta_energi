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
		$this->load->model('Internal_bank_account_model');
		$data['accounts'] = $this->Internal_bank_account_model->show_all();
		
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('purchasing/header', $data);
		
		$this->load->model('Purchase_order_model');
		$result = $this->Purchase_order_model->show_unconfirmed_purchase_order();

		$data['purchase_orders'] = $result;
		$this->load->view('Purchasing/purchase_order', $data);
	}
	
	public function create()
	{
		$this->load->model('Internal_bank_account_model');
		$data['accounts'] = $this->Internal_bank_account_model->show_all();
		
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('purchasing/header', $data);
		
		$this->load->model('Supplier_model');
		$result = $this->Supplier_model->showItems();
		$data['suppliers'] = $result;
		
		$this->load->model('Purchase_order_model');
		$guid	= $this->Purchase_order_model->create_guid();
		
		$data['guid'] = $guid;
		$this->load->view('Purchasing/purchase_order_create_dashboard', $data);
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
	
	public function inputItem()
	{
		$this->load->model('Purchase_order_model');
		$purchase_order_id = $this->Purchase_order_model->inputItem();
		
		if($purchase_order_id != null){
			$this->load->model('Purchase_order_detail_model');
			$this->Purchase_order_detail_model->insert_from_post($purchase_order_id);
		}
		
		redirect(site_url('Purchase_order'));
	}
	
	public function getDetailById($id)
	{
		$this->load->model('Purchase_order_model');
		$data['general']	= $this->Purchase_order_model->showById($id);
		
		$this->load->model('Purchase_order_detail_model');
		$data['detail']		= $this->Purchase_order_detail_model->getByCodeId($id);
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
		$data['general']	= $this->Purchase_order_model->showById($purchase_order_id);
		
		$this->load->model('Purchase_order_detail_model');
		$data['detail']		= $this->Purchase_order_detail_model->getByCodeId($purchase_order_id);
		
		$this->load->view('head');
		$this->load->view('purchasing/purchase_order_print', $data);
	}
	
	public function archive()
	{
		$this->load->model('Internal_bank_account_model');
		$data['accounts'] = $this->Internal_bank_account_model->show_all();
		
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('purchasing/header', $data);
		
		$this->load->model('Purchase_order_model');
		$data['years']	= $this->Purchase_order_model->show_years();
		
		$this->load->view('purchasing/purchase_order_archive', $data);
	}
	
	public function showArchive()
	{
		$page			= $this->input->get('page');
		$term			= $this->input->get('term');
		$offset			= ($page - 1) * 25;
		$year			= $this->input->get('year');
		$month			= $this->input->get('month');
		
		$this->load->model('Purchase_order_model');
		$data['purchase_orders'] 	= $this->Purchase_order_model->show_items($year, $month, $offset, $term);
		$data['pages']				= max(1, ceil($this->Purchase_order_model->count_items($year, $month, $term)/25));
		
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
		
		$this->load->view('purchasing/pending_purchase_order', $data);
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
}