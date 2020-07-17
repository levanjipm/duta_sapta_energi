<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debt extends CI_Controller {
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
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		
		$this->load->view('accounting/debt_document');
	}
	
	public function showUnconfirmedDocuments()
	{
		$page		= $this->input->get('page');
		$term		= $this->input->get('term');
		$offset		= ($page - 1) * 25;
		
		$this->load->model('Debt_model');
		$data['invoices']	= $this->Debt_model->showUnconfirmedDocuments($offset, $term);
		$data['pages']		= max(ceil($this->Debt_model->countUnconfirmedDocuments($term)/25), 1);
		
		header('Content-Type: application/json');
		echo json_encode($data);	
	}
	
	public function view_uninvoiced_documents()
	{
		$this->load->model('Good_receipt_model');
		$items = $this->Good_receipt_model->view_uninvoiced_documents();
		$data['bills'] = $items;
		
		$items = $this->Good_receipt_model->count_uninvoiced_documents();
		$data['pages'] = max(ceil($items / 25), 1);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function create()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		
		$this->load->model('Good_receipt_model');
		$supplier = $this->Good_receipt_model->select_supplier_from_uninvoiced_document();
		$data['suppliers'] = $supplier;
		
		$this->load->view('accounting/create_debt_document_dashboard', $data);
	}
	
	public function createBlank()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		
		$this->load->view('accounting/create_blank_debt_document_dashboard');
	}
	
	public function view_uninvoiced_documents_by_supplier()
	{
		$term				= $this->input->get('term');
		$page				= $this->input->get('page');
		$offset				= ($page - 1) * 25;
		$supplier_id		= $this->input->get('supplier_id');
		
		$this->load->model('Good_receipt_model');
		$items = $this->Good_receipt_model->select_uninvoiced_documents_group_supplier($supplier_id, $offset, $term);
		$data['bills'] = $items;
		
		$items = $this->Good_receipt_model->count_uninvoiced_documents_group_supplier($supplier_id, $term);
		$data['pages'] = max(ceil($items / 25), 1);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function createDebtDocument()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		
		$supplier_id		= $this->input->post('supplier');
		$this->load->model('Supplier_model');
		$item				= $this->Supplier_model->getById($supplier_id);
		$data['supplier']	= $item;
		
		$date				= $this->input->post('date');
		$data['date']		= $date;
		
		$documents	= $this->input->post('document');
		$this->load->model('Good_receipt_model');
		$item = $this->Good_receipt_model->select_by_code_good_receipt_id_array($documents);
		$data['documents'] = $item;
		
		$this->load->model('Good_receipt_detail_model');
		$item	= $this->Good_receipt_detail_model->select_by_code_good_receipt_id_array($documents);
		$data['details']	= $item;
		
		$this->load->view('accounting/create_debt_document_validation', $data);
	}
	
	public function insertItem()
	{
		$document_array		= $this->input->post('document');
		foreach($document_array as $document){
			$code_good_receipt_id	= key($document_array);
			$good_receipt_array[]	= $code_good_receipt_id;
			next($document_array);
		}
		
		$price_array	= $this->input->post('price');
		$this->load->model('Debt_model');
		$invoice_id		= $this->Debt_model->insertItem();
		
		if($invoice_id != null){
			$this->load->model('Good_receipt_model');
			$this->Good_receipt_model->update_set_invoice_id($invoice_id, $good_receipt_array);
			
			$this->load->model('Stock_in_model');
			$this->Stock_in_model->update_price($price_array);
			
			$this->load->model('Good_receipt_detail_model');
			$this->Good_receipt_detail_model->update_price($price_array);
		}
		
		redirect(site_url('Debt'));
	}
	
	public function getById()
	{
		$purchase_invoice_id		= $this->input->get('id');
		$this->load->model('Debt_model');
		$data['debt'] = $this->Debt_model->getById($purchase_invoice_id);
		
		$this->load->model('Good_receipt_model');
		$data['documents'] = $this->Good_receipt_model->select_by_invoice_id($purchase_invoice_id);
		
		$this->load->model('Good_receipt_detail_model');
		$data['details'] = $this->Good_receipt_detail_model->select_by_code_good_receipt_id_invoice_id($purchase_invoice_id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function confirm()
	{
		$purchase_invoice_id		= $this->input->post('id');
		$confirmed_by				= $this->session->userdata('user_id');
		$this->load->model('Debt_model');
		$data['debt'] = $this->Debt_model->updateById(1, $purchase_invoice_id, $confirmed_by);
		
		redirect(site_url('Debt'));
	}
	
	public function delete()
	{
		$purchase_invoice_id		= $this->input->post('id');
		if($this->session->has_userdata('user_id') == TRUE){
			$this->load->model('Debt_model');
			$result = $this->Debt_model->updateById(0, $purchase_invoice_id);
			
			if($result){
				$this->load->model('Good_receipt_model');
				$this->Good_receipt_model->update_unset_invoice_id($purchase_invoice_id);
			}
		};
	}
}
