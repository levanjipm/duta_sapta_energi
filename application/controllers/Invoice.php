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
		$result = $this->Delivery_order_model->getUninvoicedDeliveryOrders($type, $offset, $term);
		$data['delivery_orders'] = $result;
		$result = $this->Delivery_order_model->countUninvoicedDeliveryOrders($type, $term);
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
		
		$this->load->view('accounting/Invoice/createRetailInvoice', $data);
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
			
			$deliveryOrder		= $this->Delivery_order_model->getById($deliveryOrderId);
			$this->load->model("Internal_bank_account_model");
			$data['banks']		= $this->Internal_bank_account_model->getShownItems();

			if($deliveryOrder->invoice_id == NULL){
				$this->load->model('Invoice_model');
				$resultInsertInvoice = $this->Invoice_model->insertFromDeliveryOrder($result);
				if($resultInsertInvoice){
					$this->Delivery_order_model->updateInvoiceId($deliveryOrderId, $resultInsertInvoice);
					
					$data['invoice'] = $this->Invoice_model->getById($resultInsertInvoice);
					$this->load->view('head');
					$this->load->view('accounting/Invoice/printRetailInvoice', $data);

				} else {
					redirect(site_url('Invoice'));
				}	
			} else {
				$this->load->model('Invoice_model');
				$data['invoice'] = $this->Invoice_model->getById($deliveryOrder->invoice_id);
				$this->load->view('head');
				$this->load->view('accounting/Invoice/printRetailInvoice', $data);
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
			$user_id		= $this->session->userdata('user_id');
			$this->load->model('User_model');
			$data['user_login'] = $this->User_model->getById($user_id);
		
			$this->load->model('Authorization_model');
			$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
			$this->load->view('head');
			$this->load->view('accounting/header', $data);

			$data = array();

			$data['deliveryOrder'] = $result;

			$customer_id	= $result->customer_id;
			$this->load->model('Customer_model');
			$data['customer'] = $this->Customer_model->getById($customer_id);
	
			$this->load->model('Delivery_order_detail_model');
			$data['details'] = $this->Delivery_order_detail_model->getByCodeDeliveryOrderId($deliveryOrderId);
			$this->load->view('accounting/Invoice/createCoorporateInvoice', $data);			
		}
	}
	
	public function insertCoorporateInvoice()
	{
		$deliveryOrderId = $this->input->post('id');

		$this->load->model('Delivery_order_model');
		$result = $this->Delivery_order_model->getById($deliveryOrderId);
		if($result->invoicing_method != 2 && $result->invoice_id != null){
			redirect(site_url('Invoice/createCoorporateInvoice'));
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
				redirect(site_url('Invoice/printCoorporateInvoice/') . $resultInsertInvoice);			
			} else {
				redirect(site_url('Invoice/createCoorporateInvoice'));
			}
		}
	}

	public function printCoorporateInvoice($invoiceId)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		$data = array();

		$this->load->model("Delivery_order_model");
		$deliveryOrder = $this->Delivery_order_model->getByInvoiceId($invoiceId);

		$customerId			= $deliveryOrder->customer_id;
		$deliveryOrderId	= $deliveryOrder->id;
		$salesOrderId		= $deliveryOrder->code_sales_order_id;

		$data['deliveryOrder'] = $deliveryOrder;

		$this->load->model("Sales_order_model");
		$data['salesOrder'] = $this->Sales_order_model->getById($salesOrderId);

		$this->load->model("Customer_model");
		$data['customer'] = $this->Customer_model->getById($customerId);

		$this->load->model('Invoice_model');
		$data['invoice'] = $this->Invoice_model->getById($invoiceId);

		$this->load->model("Delivery_order_detail_model");
		$data['items'] = $this->Delivery_order_detail_model->getByCodeDeliveryOrderId($deliveryOrderId);

		$this->load->view('head');
		$this->load->view('accounting/Invoice/printCoorporateInvoice', $data);
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
		
		$this->load->view('accounting/Invoice/archiveDashboard', $data);
	}
	
	public function getUnconfirmedinvoice()
	{
		$this->load->model('Invoice_model');
		$data = $this->Invoice_model->getUnconfirmedInvoice();

		$this->load->model('Customer_model');
		$this->load->model('Opponent_model');
		$this->load->model('Delivery_order_model');

		$finalArray = array();

		$resultArray = (array) $data;
		foreach($resultArray as $result){
			$arrayResult = (array) $result;
			$customer_id = $result->customer_id;
			$opponent_id	= $result->opponent_id;
			$deliveryOrderId = $result->code_delivery_order_id;

			$customer = (array) $this->Customer_model->getById($customer_id);
			$opponent = (array) $this->Opponent_model->getById($opponent_id);
			$deliveryOrder = (array) $this->Delivery_order_model->getById($deliveryOrderId);

			$arrayResult['customer'] = $customer;
			$arrayResult['opponent'] = $opponent;
			$arrayResult['deliveryOrder'] = $deliveryOrder;

			array_push($finalArray, $arrayResult);
			continue;
		}

		header('Content-Type: application/json');
		echo json_encode($finalArray);
	}

	public function getById()
	{
		$invoiceId = $this->input->get('id');
		
		$this->load->model('Invoice_model');
		$this->load->model('Customer_model');
		$this->load->model("Opponent_model");

		$this->load->model('Delivery_order_model');
		$this->load->model('Delivery_order_detail_model');

		$this->load->model('Sales_order_model');

		$invoice = $this->Invoice_model->getById($invoiceId);
		$data['invoice'] = $invoice;
		if($invoice->customer_id != null || $invoice->opponent_id != null){
			//Blank invoice//
			$data['customer'] = $this->Customer_model->getById($invoice->customer_id);
			$data['opponent'] = $this->Opponent_model->getById($invoice->opponent_id);
			$data['items']		= null;
			$data['delivery_order'] = null;
			$data['sales_order'] = null;
		} else {
			$deliveryOrder = $this->Delivery_order_model->getByInvoiceId($invoiceId);
			$deliveryOrderId = $deliveryOrder->id;
			$salesOrderId = $deliveryOrder->code_sales_order_id;
			$customerId = $deliveryOrder->customer_id;

			$data['customer'] = $this->Customer_model->getById($customerId);
			$data['opponent'] = null;
			$data['items'] = $this->Delivery_order_detail_model->getByCodeDeliveryOrderId($deliveryOrderId);
			$data['delivery_order'] = $deliveryOrder;
			$data['sales_order'] = $this->Sales_order_model->getById($salesOrderId);
		}

		$this->load->model("Receivable_model");
		$data['receivable'] = $this->Receivable_model->getByInvoiceId($invoiceId);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function confirmById()
	{
		$invoiceId = $this->input->post('id');
		$taxInvoice = $this->input->post('taxInvoice');

		$this->load->model("Invoice_model");
		$invoice = $this->Invoice_model->getById($invoiceId);
		if($invoice->opponent_id == null && $invoice->customer_id == null){
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
				$result = $this->Invoice_model->updateById($invoiceId, $taxInvoice);
			} else {
				$result = 0;
			}
		} else {
			if($taxInvoice != null && $taxInvoice != ""){
				$result = $this->Invoice_model->updateById($invoiceId, $taxInvoice);
			} else {
				$result = $this->Invoice_model->updateById($invoiceId);
			}
			
		}

		echo $result;
	}

	public function deleteById()
	{
		$invoiceId = $this->input->post('id');
		$this->load->model("Invoice_model");
		$invoice = $this->Invoice_model->getById($invoiceId);
		if($invoice->opponent_id == null && $invoice->customer_id == null){
			$this->load->model('Delivery_order_model');
			$result = $this->Delivery_order_model->unassignInvoiceById($invoiceId);
			if($result == 1){
				$invoiceResult = $this->Invoice_model->deleteById($invoiceId);
				echo $invoiceResult;
			} else {
				echo 0;
			}
		} else {
			$invoiceResult = $this->Invoice_model->deleteById($invoiceId);
			echo $invoiceResult;
		}
	}

	public function getItems(){
		$page = $this->input->get('page');
		$month = $this->input->get('month');
		$year = $this->input->get('year');
		$offset = ($page - 1) * 10;

		$this->load->model('Invoice_model');
		$this->load->model('Customer_model');
		$this->load->model("Opponent_model");

		$invoices = $this->Invoice_model->getItems($offset, $month, $year);
		$result = array();
		foreach($invoices as $invoice){
			$itemArray = array();
			$customerId = $invoice->customer_id;
			$opponentId	= $invoice->opponent_id;
			$itemArray = (array) $invoice;
			if($customerId != NULL){
				$customerObject = (array) $this->Customer_model->getById($customerId);
				$itemArray['customer'] = $customerObject;
				$itemArray['opponent'] = NULL;
			} else {
				$opponentObject	= (array) $this->Opponent_model->getById($opponentId);
				$itemArray['opponent']	= $opponentObject;
				$itemArray['customer']	= NULL;
			}

			array_push($result, $itemArray);
			continue;
		};

		$data['items'] = (object) $result;
		$data['pages'] = max(1, ceil($this->Invoice_model->countItems($month, $year) / 10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function createBlankDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		$this->load->view('accounting/Invoice/createBlankDashboard');
	}

	public function insertBlankItem()
	{
		$this->load->model("Invoice_model");
		$this->Invoice_model->insertBlankItem();
		redirect(site_url("invoice/createBlankDashboard"));
	}

	public function deleteDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		if($data['user_login']->access_level > 3){
			$this->load->model('Authorization_model');
			$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
			$this->load->view('head');
			$this->load->view('administrator/header', $data);
			$this->load->view('administrator/Invoice/deleteDashboard');
		 } else {
			  redirect(site_url("Welcome"));
		 }
	}

	public function getConfirmedItems()
	{
		$page			= $this->input->get('page');
		$month			= $this->input->get('month');
		$year			= $this->input->get('year');
		$offset			= ($page - 1) * 10;
		$this->load->model("Invoice_model");
		$data['items'] = $this->Invoice_model->getConfirmedItems($month, $year, $offset);
		$data['pages'] = max(1, ceil($this->Invoice_model->countConfirmedItems($month, $year)/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function journalDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		$this->load->view('accounting/Journal/salesDashboard');
	}

	public function getValueByMonthYearDaily()
	{
		$month			= $this->input->get('month');
		$year			= $this->input->get('year');
		$this->load->model("Invoice_model");
		$data		= $this->Invoice_model->getByMonthYearDaily($month, $year);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function downloadMonthYearReport()
	{
		$month			= $this->input->get('month');
		$year			= $this->input->get('year');
		$this->load->model("Invoice_model");
		$data		= $this->Invoice_model->getAllItemsByMonthYear($month, $year);

		$file_name = 'SalesJournal' . $month . $year . '.csv'; 
		header("Content-Description: File Transfer"); 
		header("Content-Disposition: attachment; filename=$file_name"); 
		header("Content-Type: application/csv;");
   
		$file = fopen('php://output', 'w');
		$header = array("Date","Name", "Tax Document", "Opponent", "Value"); 
		fputcsv($file, $header, ';');
		foreach ($data as $item)
		{
			$valueArray		= array(
				"date" => $item['date'],
				"name" => $item['name'],
				"tax_document" => $item['taxInvoice'],
				"opponent" => $item['opponent'],
				"value" => (float)$item['value']
			);

			fputcsv($file, $valueArray, ';');
			continue;
		}

		fclose($file); 
		exit;
	}

	public function getRecap()
	{
		$month			= $this->input->get('month');
		$year			= $this->input->get('year');
		$brand			= $this->input->get('brand');

		$this->load->model("Invoice_model");
		$data		= $this->Invoice_model->getRecap($month, $year, $brand);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getByCustomerIdDate()
	{
		$customerId		= $this->input->get('id');
		$date			= $this->input->get('date');
		$month			= $this->input->get('month');
		$year			= $this->input->get('year');

		$this->load->model("Invoice_model");
		$data['items']			= $this->Invoice_model->getByCustomerIdDate($customerId, date("Y-m-d", mktime(0,0,0,$month, $date, $year)));

		$this->load->model("Customer_model");
		$data['customer']			= $this->Customer_model->getById($customerId);

		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
