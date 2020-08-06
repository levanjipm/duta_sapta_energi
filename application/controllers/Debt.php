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
		
		$this->load->view('accounting/Debt/dashboard');
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

	public function getUninvoicedSupplierIds()
	{
		$this->load->model('Good_receipt_model');
		$supplier = $this->Good_receipt_model->getUninvoicedSupplierIds();
		
		header('Content-Type: application/json');
		echo json_encode($supplier);
	}
	
	public function getUninvoicedDocumentsBySupplierId()
	{
		$term				= $this->input->get('term');
		$page				= $this->input->get('page');
		$offset				= ($page - 1) * 25;
		$supplier_id		= $this->input->get('supplier_id');
		
		$this->load->model('Good_receipt_model');
		$items = $this->Good_receipt_model->getUninvoicedDocumentsBySupplierId($supplier_id, $offset, $term);
		$data['bills'] = $items;
		
		$items = $this->Good_receipt_model->countUninvoicedDocumentsBySupplierId($supplier_id, $term);
		$data['pages'] = max(ceil($items / 25), 1);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function insertItem()
	{
		$priceArray	= $this->input->post('price');

		$this->load->model('Debt_model');
		$purchaseInvoiceId		= $this->Debt_model->insertItem();
		
		if($purchaseInvoiceId != null){
			$goodReceiptIdArray = $this->input->post('document');
			$this->load->model('Good_receipt_model');
			$resultGoodReceipt = $this->Good_receipt_model->updateInvoiceStatusById(1, $purchaseInvoiceId, $goodReceiptIdArray);

			if($resultGoodReceipt > 0){
				$this->load->model('Stock_in_model');
				$this->Stock_in_model->updatePrice($priceArray);
				
				$this->load->model('Good_receipt_detail_model');
				$this->Good_receipt_detail_model->updatePrice($priceArray);

				echo 1;
			} else {
				$this->Debt_model->deleteItem($purchaseInvoiceId);
				echo 0;
			}
		} else {
			echo 0;
		}
	}

	public function insertBlankItem()
	{
		$this->load->model('Debt_other_model');
		$result = $this->Debt_other_model->insertItem();
		echo $result;
	}
	
	public function getById()
	{
		$purchase_invoice_id		= $this->input->get('id');
		$this->load->model('Debt_model');
		$data['debt'] = $this->Debt_model->getById($purchase_invoice_id);
		
		$this->load->model('Good_receipt_model');
		$data['documents'] = $this->Good_receipt_model->getByInvoiceId($purchase_invoice_id);
		
		$this->load->model('Good_receipt_detail_model');
		$data['details'] = $this->Good_receipt_detail_model->select_by_code_good_receipt_id_invoice_id($purchase_invoice_id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function deleteById()
	{
		$invoiceId			= $this->input->post('id');
		$this->load->model('Debt_model');
		$result = $this->Debt_model->updateById(0, $invoiceId);
		
		if($result){
			$this->load->model('Good_receipt_model');
			$result = $this->Good_receipt_model->updateInvoiceStatusById(0, $invoiceId);
			echo $result;
		} else {
			echo 0;
		}
	}

	public function confirmById()
	{
		$invoiceId		= $this->input->post('id');
		$this->load->model('Debt_model');
		$result 		= $this->Debt_model->updateById(1, $invoiceId);

		echo $result;
	}

	public function confirmDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);

		$this->load->view('accounting/debt/confirmDashboard');
	}

	public function archiveDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
	}

	public function loadForm()
	{
		$event	= $this->input->get('type');
		if($event == 2){
			$this->load->view('accounting/Debt/blankDebtDocumentForm');
		} else if($event == 1){
			$this->load->view('accounting/Debt/regularDebtDocumentForm');
		}
	}
}
