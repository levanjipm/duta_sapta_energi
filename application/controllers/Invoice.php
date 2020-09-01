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
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		
		$this->load->view('accounting/Invoice/createDashboard');
	}
	
	public function getUninvoicedDeliveryOrders()
	{
		$type		= $this->input->get('type');
		$term		= $this->input->get('term');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 10;
		$accountant	= $this->session->userdata('user_id');

		$this->load->model('Delivery_order_model');
		$result = $this->Delivery_order_model->getUninvoicedDeliveryOrders($type, $offset, $term, $accountant);
		$data['delivery_orders'] = $result;
		
		$result = $this->Delivery_order_model->countUninvoicedDeliveryOrders($type, $term, $accountant);
		$data['pages'] = max(1, ceil($result / 10));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function createRetailInvoice()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);

		$deliveryOrderId		= $this->input->post('id');

		$this->load->model('Delivery_order_model');
		$deliveryOrderObject =  $this->Delivery_order_model->getById($deliveryOrderId);
		$data['deliveryOrder'] = $deliveryOrderObject;

		$customerId = $deliveryOrderObject->customer_id;
		$this->load->model('Customer_model');
		$data['customer'] = $this->Customer_model->getById($customerId);

		$this->load->model('Delivery_order_detail_model');
		$result = $this->Delivery_order_detail_model->getByCodeDeliveryOrderId($deliveryOrderId);
		$data['details'] = $result;
		
		$this->load->view('accounting/invoice/createRetailInvoice', $data);
	}
	
	public function printRetailInvoice()
	{		
		$deliveryOrderId		= $this->input->post('id');	

		$this->load->model('Delivery_order_model');
		$result = $this->Delivery_order_model->getById($deliveryOrderId);
		if($result->invoice_id != null && $result->invoicing_method != 1){
			redirect(site_url('Invoice'));
		} else {
			$data['deliveryOrder'] = $result;

			$customer_id	= $result->customer_id;
			$this->load->model('Customer_model');
			$data['customer'] = $this->Customer_model->getById($customer_id);
	
			$this->load->model('Delivery_order_detail_model');
			$data['details'] = $this->Delivery_order_detail_model->getByCodeDeliveryOrderId($deliveryOrderId);
			
			$this->load->model('Delivery_order_model');
	
			$this->load->model('Invoice_model');
			$resultInsertInvoice = $this->Invoice_model->insertFromDeliveryOrder($result);
			if($resultInsertInvoice){
				$this->Delivery_order_model->updateInvoiceId($deliveryOrderId, $resultInsertInvoice);
				$this->load->view('head');
				$this->load->view('accounting/invoice/printRetailInvoice', $data);			
			} else {
				redirect(site_url('Invoice'));
			}
		}
	}

	public function createCoorporateInvoice()
	{
		$deliveryOrderId = $this->input->post('id');

		$this->load->model('Delivery_order_model');
		$result = $this->Delivery_order_model->getById($deliveryOrderId);
		if($result->invoicing_method != 2 && $result->invoice_id == null){
			redirect(site_url('Invoice'));
		} else {
			$data['deliveryOrder'] = $result;

			$customer_id	= $result->customer_id;
			$this->load->model('Customer_model');
			$data['customer'] = $this->Customer_model->getById($customer_id);
	
			$this->load->model('Delivery_order_detail_model');
			$data['details'] = $this->Delivery_order_detail_model->getByCodeDeliveryOrderId($deliveryOrderId);
			
			$this->load->model('Delivery_order_model');
	
			$this->load->model('Invoice_model');
			$resultInsertInvoice = $this->Invoice_model->insertFromDeliveryOrder($result);
			if($resultInsertInvoice){
				$this->Delivery_order_model->updateInvoiceId($deliveryOrderId, $resultInsertInvoice);
				$this->load->view('head');
				$this->load->view('accounting/invoice/printCoorporateInvoice', $data);			
			} else {
				redirect(site_url('Invoice'));
			}
		}
	}
	
	public function convertNumberToWords()
	{
		$value		= $this->input->get('value');
		$this->load->helper('Number_to_words');
		echo ucwords(Number_to_words($value));
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

		$data = array();

		$this->load->view('accounting/Invoice/confirmDashboard');
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

		$data = array();
		$this->load->model('Invoice_model');
		$data['years'] = $this->Invoice_model->getYears();
		
		$this->load->view('accounting//invoice/archiveDashboard', $data);
	}
	
	public function getUnconfirmedinvoice()
	{
		$this->load->model('Invoice_model');
		$data = $this->Invoice_model->getUnconfirmedInvoice();

		$this->load->model('Customer_model');
		$this->load->model('Delivery_order_model');

		$finalArray = array();

		$resultArray = (array) $data;
		foreach($resultArray as $result){
			$arrayResult = (array) $result;
			$customer_id = $result->customer_id;
			$deliveryOrderId = $result->code_delivery_order_id;
			$customer = (array) $this->Customer_model->getById($customer_id);
			$deliveryOrder = (array) $this->Delivery_order_model->getById($deliveryOrderId);

			$arrayResult['customer'] = $customer;
			$arrayResult['deliveryOrder'] = $deliveryOrder;

			array_push($finalArray, $arrayResult);
		}

		header('Content-Type: application/json');
		echo json_encode($finalArray);
	}

	public function getById()
	{
		$invoiceId = $this->input->get('id');
		
		$this->load->model('Invoice_model');
		$this->load->model('Customer_model');

		$this->load->model('Delivery_order_model');
		$this->load->model('Delivery_order_detail_model');

		$this->load->model('Sales_order_model');

		$data['invoice'] = $this->Invoice_model->getById($invoiceId);

		$deliveryOrder = $this->Delivery_order_model->getByInvoiceId($invoiceId);

		$customerId = $deliveryOrder->customer_id;
		$deliveryOrderId = $deliveryOrder->id;
		$salesOrderId = $deliveryOrder->code_sales_order_id;

		$data['customer'] = $this->Customer_model->getById($customerId);
		$data['items'] = $this->Delivery_order_detail_model->getByCodeDeliveryOrderId($deliveryOrderId);
		$data['delivery_order'] = $deliveryOrder;
		$data['sales_order'] = $this->Sales_order_model->getById($salesOrderId);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function confirmById()
	{
		$invoiceId = $this->input->post('id');
		$taxInvoice = $this->input->post('taxInvoice');

		$this->load->model('Delivery_order_model');
		$deliveryOrder = $this->Delivery_order_model->getByInvoiceId($invoiceId);
		$salesOrderId = $deliveryOrder->code_sales_order_id;

		$this->load->model('Sales_order_model');
		$salesOrder = $this->Sales_order_model->getById($salesOrderId);

		$taxing		= $salesOrder->taxing;
		$invoicingMethod = $salesOrder->invoicing_method;

		$sentStatus = $deliveryOrder->is_sent;
		$confirmStatus = $deliveryOrder->is_confirm;

		if($sentStatus == 1 && $confirmStatus == 1){
			$this->load->model('Invoice_model');
			if($taxing == 1){
				$result = $this->Invoice_model->updateById($invoiceId, $taxInvoice);
			} else {
				$result = $this->Invoice_model->updateById($invoiceId);
			}
		} else {
			$result = 0;
		}

		echo $result;
	}

	public function deleteById()
	{
		$invoiceId = $this->input->post('id');
		$this->load->model('Delivery_order_model');
		$result = $this->Delivery_order_model->unassignInvoiceById($invoiceId);
		if($result == 1){
			$this->load->model('Invoice_model');
			$invoiceResult = $this->Invoice_model->deleteById($invoiceId);
			
			echo $invoiceResult;
		} else {
			echo 0;
		}
	}

	public function getItems(){
		$page = $this->input->get('page');
		$month = $this->input->get('month');
		$year = $this->input->get('year');
		$offset = ($page - 1) * 10;

		$this->load->model('Invoice_model');
		$this->load->model('Customer_model');

		$invoices = $this->Invoice_model->getItems($offset, $month, $year);
		$result = array();
		foreach($invoices as $invoice){
			$itemArray = array();
			$customerId = $invoice->customer_id;

			$customerObject = (array) $this->Customer_model->getById($customerId);
			$itemArray = (array) $invoice;
			$itemArray['customer'] = $customerObject;

			array_push($result, $itemArray);
		};

		$data['items'] = (object) $result;
		$data['pages'] = max(1, ceil($this->Invoice_model->countItems($month, $year) / 10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
