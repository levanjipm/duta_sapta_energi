<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_order extends CI_Controller {
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

		if($data['user_login']->access_level > 1){
			$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);

		$this->load->view('head');
		$this->load->view('sales/header', $data);

		$this->load->view('sales/SalesOrder/dashboard');
		} else {
			redirect(site_url("Sales"));
		}		
	}

	public function showUnconfirmedSalesOrders()
	{
		$page		= $this->input->get('page');
		$term		= $this->input->get('term');
		$offset		= ($page - 1) * 10;
		$this->load->model('Sales_order_model');
		$data['sales_orders'] 	= $this->Sales_order_model->getUnconfirmedSalesOrder($offset, $term);
		$data['pages'] 			= max(1, ceil($this->Sales_order_model->countUnconfirmedSalesOrder($term)/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getIncompleteSalesOrder()
	{
		$page = $this->input->get('page');
		$offset = ($page - 1) * 10;
		$term	= $this->input->get('term');

		$resultArray = array();

		$this->load->model('Sales_order_model');
		$this->load->model('Customer_model');
		$salesOrderArray	= $this->Sales_order_model->getIncompleteSalesOrder($offset, $term);
		foreach($salesOrderArray as $salesOrder){
			$childResultArray = (array) $salesOrder;
			$customerId = $salesOrder->customer_id;
			$customer = $this->Customer_model->getById($customerId);

			$childResultArray['customer'] = $customer;
			array_push($resultArray, $childResultArray);
		}

		$data['items']			= (object) $resultArray;
		$data['pages'] 			= max(1, ceil($this->Sales_order_model->countIncompleteSalesOrder($term)/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getIncompleteSalesOrderDelivery()
	{
		$page = $this->input->get('page');
		$offset = ($page - 1) * 10;
		$term	= $this->input->get('term');
		$areas	= $this->input->get('areas');
		$resultArray = array();

		$this->load->model('Sales_order_model');
		$this->load->model('Customer_model');
		$salesOrderArray	= $this->Sales_order_model->getIncompleteSalesOrderDelivery($offset, $term, $areas);
		foreach($salesOrderArray as $salesOrder){
			$childResultArray = (array) $salesOrder;
			$customerId = $salesOrder->customer_id;
			$customer = $this->Customer_model->getById($customerId);
		
			$childResultArray['customer'] = $customer;
			array_push($resultArray, $childResultArray);
		}
		
		$data['items']			= (object) $resultArray;
		$data['pages'] 			= max(1, ceil($this->Sales_order_model->countIncompleteSalesOrderDelivery($term, $areas)/10));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function createDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);

		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);

		$this->load->view('head');
		$this->load->view('sales/header', $data);

		$data = array();

		$this->load->model('Sales_order_model');
		$guid = $this->Sales_order_model->create_guid();
		$data['guid'] = $guid;

		$this->load->model('User_model');
		$data['users'] = $this->User_model->show_all();

		$this->load->view('sales/SalesOrder/createDashboard',$data);
	}

	public function inputItem()
	{
		$this->load->model('Sales_order_model');
		$id = $this->Sales_order_model->insertItem();
		if($id != NULL){
			$this->load->model('Sales_order_detail_model');
			$this->Sales_order_detail_model->insert_from_post($id);
		};

		if($id == null){
			redirect(site_url('Sales_order/failedSubmission'));
		} else {
			redirect(site_url('Sales_order/successSubmission/') . $id);
		}
	}

	public function successSubmission($id)
	{
		$this->load->model('Sales_order_model');
		$sales_order 	= $this->Sales_order_model->getById($id);
		$result 		= $sales_order;

		$data['general'] = $result;

		$customer_id = $data['general']->customer_id;
		$this->load->model('Customer_model');
		$data['customer'] = $this->Customer_model->getById($customer_id);

		$this->load->model('Sales_order_detail_model');
		$data['details'] = $this->Sales_order_detail_model->show_by_code_sales_order_id($id);

		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);

		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);

		$this->load->view('head');
		$this->load->view('sales/header', $data);
		$this->load->view('sales/SalesOrder/checkOut', $data);
	}

	public function failedSubmission()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);

		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);

		$this->load->view('head');
		$this->load->view('sales/header', $data);
	}

	public function trackById()
	{
		$salesOrderId = $this->input->get('id');

		$this->load->model('Sales_order_model');
		$result 			= $this->Sales_order_model->getById($salesOrderId);
		$customerId			= $result->customer_id;
		$data['general']	= $result;


		$this->load->model('Customer_model');
		$data['customer']	= $this->Customer_model->getById($customerId);

		$this->load->model('Sales_order_detail_model');
		$data['detail'] = $this->Sales_order_detail_model->show_by_code_sales_order_id($salesOrderId);

		$this->load->model('Delivery_order_model');
		$data['deliveryOrders'] = $this->Delivery_order_model->getItemBySalesOrderId($salesOrderId);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function showById()
	{
		$user_id			= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user']		= $this->User_model->getById($user_id);

		$sales_order_id		= $this->input->get('id');

		$this->load->model('Sales_order_model');
		$result 			= $this->Sales_order_model->getById($sales_order_id);
		$data['general']	= $result;

		$customerId			= $result->customer_id;

		$this->load->model('Customer_model');
		$data['customer']	= $this->Customer_model->getById($customerId);

		$this->load->model('Sales_order_detail_model');
		$data['pendingValue']	= $this->Sales_order_detail_model->getPendingValueByCustomerId($customerId);

		$this->load->model('Bank_model');
		$data['pendingBankData']	= $this->Bank_model->getPendingValueByOpponentId('customer', $customerId);
		
		$this->load->model('Delivery_order_model');
		$data['deliveryOrders'] = $this->Delivery_order_model->getItemBySalesOrderId($sales_order_id);

		$this->load->model('Invoice_model');

		$minimumDate = date('Y-m-d');
		$receivableValue = 0;
		$invoices = $this->Invoice_model->getCustomerStatusById($customerId);
		foreach($invoices as $invoice){
			$value = $invoice->value;
			$paid = $invoice->paid;
			$date = $invoice->date;

			if($date < date('Y-m-d', strtotime($minimumDate))){
				$minimumDate = $date;
			};

			$receivableValue += ($value - $paid);
		};

		$pendingInvoice = array(
			'debt' => $receivableValue,
			'date' => date('Y-m-d', strtotime($minimumDate))
		);

		$data['receivable'] = (object) $pendingInvoice;

		$this->load->model('Sales_order_detail_model');
		$data['detail'] = $this->Sales_order_detail_model->show_by_code_sales_order_id($sales_order_id);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function confirmSalesOrder()
	{
		$user_id = $this->session->userdata['user_id'];
		$this->load->model('User_model');
		$data['user']		= $this->User_model->getById($user_id);

		$access_level = $data['user']->access_level;

		$salesOrderId		= $this->input->post('id');

		$this->load->model('Sales_order_model');
		$result 			= $this->Sales_order_model->getById($salesOrderId);
		$salesOrderObject	= $result;

		$customerId			= $result->customer_id;

		$this->load->model('Customer_model');
		$customerObject	= $this->Customer_model->getById($customerId);

		$this->load->model('Sales_order_detail_model');
		$pendingValue	= $this->Sales_order_detail_model->getPendingValueByCustomerId($customerId);

		$this->load->model('Bank_model');
		$pendingBankValue	= $this->Bank_model->getPendingValueByOpponentId('customer', $customerId);

		$this->load->model('Invoice_model');

		$minimumDate = date('Y-m-d');
		$receivableValue = 0;
		$invoices = $this->Invoice_model->getCustomerStatusById($customerId);
		foreach($invoices as $invoice){
			$value = $invoice->value;
			$paid = $invoice->paid;
			$date = $invoice->date;

			if($date < date('Y-m-d', strtotime($minimumDate))){
				$minimumDate = $date;
			};

			$receivableValue += ($value - $paid);
		};

		$plafond = $customerObject->plafond;
		$termOfPayment	= $customerObject->term_of_payment;

		$debt 			= $receivableValue;
		$dateParameter 	= $minimumDate;
		$dateDifference = date_diff(new DateTime(date('Y-m-d')), new DateTime(date('Y-m-d', strtotime($dateParameter))));

		$dateDifferenceDay = $dateDifference->d;

		if((($dateParameter == null || $dateDifferenceDay < $termOfPayment) && ($debt < $plafond)) || $access_level > 2){
			$result = $this->Sales_order_model->updateById(1, $salesOrderId);
			echo $result;
		} else {
			echo 0;
		}
	}

	public function deleteById()
	{
		$sales_order_id		= $this->input->post('id');
		$this->load->model('Sales_order_model');
		$result = $this->Sales_order_model->updateById(0, $sales_order_id);

		echo $result;
	}

	public function trackDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);

		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);

		$this->load->view('head');
		$this->load->view('sales/header', $data);

		$this->load->view('sales/SalesOrder/trackDashboard');
	}

	public function archiveDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);

		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);

		$this->load->view('head');
		$this->load->view('sales/header', $data);

		$this->load->model('Sales_order_model');
		$data['years']	= $this->Sales_order_model->show_years();

		$this->load->view('sales/SalesOrder/archiveDashboard', $data);
	}

	public function archiveView()
	{
		$page			= $this->input->get('page');
		$term			= $this->input->get('term');
		$offset			= ($page - 1) * 25;
		$year			= $this->input->get('year');
		$month			= $this->input->get('month');

		$this->load->model('Sales_order_model');
		$data['sales_orders']		= $this->Sales_order_model->showItems($year, $month, $offset, $term);
		$data['pages']				= max(1, ceil($this->Sales_order_model->countItems($year, $month, $term)/25));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function closeSalesOrderDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);

		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);

		$this->load->view('head');
		$this->load->view('sales/header', $data);

		$this->load->view('sales/salesOrder/closeDashboard');
	}

	public function closeSalesOrderInput()
	{
		$this->load->model('Sales_order_close_request_model');
		$result = $this->Sales_order_close_request_model->insertItem();

		echo $result;
	}

	public function confirmCloseSalesOrderDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);

		if($data['user_login']->access_level > 2){
			$this->load->model('Authorization_model');
			$data['departments']	= $this->Authorization_model->getByUserId($user_id);

			$this->load->view('head');
			$this->load->view('sales/header', $data);

			$this->load->view('sales/SalesOrder/closeConfirmDashboard');
		} else {
			redirect(site_url('Sales_order'));
		}		
	}

	public function getUnconfirmedCloseSubmission()
	{
		$this->load->model('Sales_order_close_request_model');
		$data = $this->Sales_order_close_request_model->getUnocnfirmedItems();

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getCloseSubmissionById()
	{
		$this->load->model('Sales_order_close_request_model');
		$this->load->model('Sales_order_model');
		$this->load->model('Sales_order_detail_model');

		$id = $this->input->get('id');
		$data['general'] = $this->Sales_order_close_request_model->getById($id);

		$code_sales_order_id = $data['general']->code_sales_order_id;
		$data['sales_order'] = $this->Sales_order_model->getById($code_sales_order_id);

		$data['items'] = $this->Sales_order_detail_model->show_by_code_sales_order_id($code_sales_order_id);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function confirmCloseSalesOrder()
	{
		$id = $this->input->post('id');
		$user_id	= $this->session->userdata('user_id');

		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		if($data['user_login']->access_level > 1){
			$this->load->model('Sales_order_close_request_model');

			$data = $this->Sales_order_close_request_model->getById($id);
			$salesOrderId = $data->code_sales_order_id;

			$result = $this->Sales_order_close_request_model->updateById(1, $id);
			if($result == 1){
				$this->load->model('Sales_order_detail_model');
				$this->Sales_order_detail_model->updateStatusByCodeSalesOrderId($salesOrderId);
				echo 1;
			} else {
				echo 0;
			}
		} else {
			echo 0;
		}
	}

	public function deleteCloseSalesOrder()
	{
		$id = $this->input->post('id');
		$user_id	= $this->session->userdata('user_id');

		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		if($data['user_login']->access_level > 1){
			$this->load->model('Sales_order_close_request_model');
			$result = $this->Sales_order_close_request_model->updateById(0, $id);

			echo $result;
		} else {
			echo 0;
		}
	}

	public function getUnclosedSalesOrders()
	{
		$page = $this->input->get('page');
		$offset = ($page - 1) * 10;
		$term	= $this->input->get('term');

		$resultArray = array();

		$this->load->model('Sales_order_model');
		$this->load->model('Customer_model');
		$salesOrderArray	= $this->Sales_order_model->getUnclosedSalesOrders($offset, $term);
		foreach($salesOrderArray as $salesOrder){
			$childResultArray = (array) $salesOrder;
			$customerId = $salesOrder->customer_id;
			$customer = $this->Customer_model->getById($customerId);

			$childResultArray['customer'] = $customer;
			array_push($resultArray, $childResultArray);
		}

		$data['items']			= (object) $resultArray;
		$data['pages'] 			= max(1, ceil($this->Sales_order_model->countUnclosedSalesOrders($term)/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getIncompletedSalesOrdersByCustomerId()
	{
		$customerId			= $this->input->get('customerId');

		$this->load->model("Sales_order_model");
		$data = $this->Sales_order_model->getPendingSalesOrdersByCustomerId($customerId);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getIncompletedSalesOrdersByCustomer()
	{
		$this->load->model("Sales_order_model");
		$page				= $this->input->get('page');
		$offset				= ($page - 1) * 10;
		$data['items']		= $this->Sales_order_model->getIncompletedSalesOrdersByCustomer($offset);
		$data['pages']		= max(1, ceil($this->Sales_order_model->countIncompletedSalesOrdersByCustomer()/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getConfirmedByCustomerId($customerId)
	{
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 10;

		$this->load->model("Sales_order_model");
		$data['items'] = $this->Sales_order_model->getConfirmedByCustomerId($customerId, $offset);
		$data['pages'] = max(1, ceil($this->Sales_order_model->countConfirmedByCustomerId($customerId)/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getCompletePendingSalesOrdersByCustomerId()
	{
		$customerId			= $this->input->get('customerId');

		$this->load->model("Sales_order_model");
		$data = $this->Sales_order_model->getPendingSalesOrdersByCustomerId($customerId);

		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
