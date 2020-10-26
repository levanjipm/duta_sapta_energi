<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	
class Customer extends CI_Controller {
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
		$this->load->view('sales/header', $data);
		
		$this->load->model('Area_model');
		$data['areas'] = $this->Area_model->showItems();
		
		$data['authorize'] = $this->session->userdata('user_role');
		
		$this->load->view('sales/Customer/dashboard',$data);
	}
	
	public function insertItem()
	{
		$this->load->model('Customer_model');
		$result = $this->Customer_model->insertItem();
		if($result != NULL){
			$this->load->model("Customer_target_model");
			$date		= date("Y-m-d", mktime(0, 0, 0, date('m'), 1, date('Y')));
			$this->Customer_target_model->insertItem($result, 3000000, $date);
			echo 1;
		} else {
			echo 0;
		}
	}
	
	public function deleteById()
	{
		$this->load->model('Customer_model');
		$result = $this->Customer_model->deleteById();
		
		echo $result;
	}
	
	public function getItemById()
	{
		$customer_id		= $this->input->get('id');
		$this->load->model('Customer_model');
		$data				= $this->Customer_model->getById($customer_id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function updateItemById()
	{
		$this->load->model('Customer_model');
		$result = $this->Customer_model->updateItemById();
		
		echo $result;
	}
	
	public function showItems()
	{
		$term		= $this->input->get('term');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 25;
		$this->load->model('Customer_model');
		$data['customers'] = $this->Customer_model->showItems($offset, $term);
		$item = $this->Customer_model->countItems($term);
		$data['pages'] = max(1, ceil($item / 25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function showActiveItems()
	{
		$term		= $this->input->get('term');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 25;
		$this->load->model('Customer_model');
		$data['customers'] = $this->Customer_model->showActiveItems($offset, $term);
		$item = $this->Customer_model->countActiveItems($term);
		$data['pages'] = max(1, ceil($item / 25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function showById()
	{
		$id = $this->input->get('id');
		$this->load->model('Customer_model');
		$data = $this->Customer_model->getById($id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function getCustomerAccount()
	{
		$customerId		= $this->input->get('id');
		
		$this->load->model('Customer_model');
		$data['customer']	= $this->Customer_model->getById($customerId);
		
		$this->load->model('Sales_order_detail_model');
		$data['pending_value']	= $this->Sales_order_detail_model->getPendingValueByCustomerId($customerId);
		
		$this->load->model('Invoice_model');
		// $data['pending_invoice']	= $this->Invoice_model->getCustomerStatusById($customerId);
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

		$data['pending_invoice'] = (object) $pendingInvoice;

		$this->load->model('Bank_model');
		$data['pendingBank'] = $this->Bank_model->getPendingvalueByOpponentId('customer', $customerId);

		
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function assignAccountantDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		$this->load->view('accounting/assignAccountantDashboard');
	}

	public function viewCustomerDetail($customerId)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);

		$data = array();
		$this->load->model("Customer_model");
		$customer = $this->Customer_model->getById($customerId);
		$data['customer'] = $customer;

		$this->load->model("Area_model");
		$data['area'] = $this->Area_model->getItemById($customer->area_id);

		$this->load->view('sales/Customer/detailDashboard', $data);
	}

	public function showCustomerAccountantItems()
	{
		$term		= $this->input->get('term');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 10;
		$user		= $this->input->get('accountant_id');

		if(!isset($term)){
			$this->load->model('Customer_model');
			$data['customers'] = $this->Customer_model->showCustomerAccountantItems(0, "", $user, 0);
		} else {
			$this->load->model('Customer_model');
			$data['customers'] = $this->Customer_model->showCustomerAccountantItems($offset, $term, $user);
			$item = $this->Customer_model->countItems($term);
			$data['pages'] = max(1, ceil($item / 10));
		}
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function assignAccountant()
	{
		$includedCustomerArray = $this->input->post('included');
		$excludedCustomerArray = $this->input->post('excluded');
		$accountantId			= $this->input->post('accountant');

		$this->load->model("Customer_accountant_model");
		$includedResult = $this->Customer_accountant_model->updateByAccountant(1, $includedCustomerArray, $accountantId);
		$excludedResult = $this->Customer_accountant_model->updateByAccountant(0, $excludedCustomerArray, $accountantId);
	}

	public function assignAllCustomersToAccountant()
	{
		$accountantId = $this->input->post('accountant');
		$this->load->model("Customer_accountant_model");
		$includedResult = $this->Customer_accountant_model->updateByAccountant(2, array(), $accountantId);
	}

	public function getCurrentTarget()
	{
		$customerId = $this->input->get('id');
		$this->load->model("Customer_model");
		$data['customer'] = $this->Customer_model->getById($customerId);

		$this->load->model("Customer_target_model");
		$data['target'] = $this->Customer_target_model->getCurrentTarget($customerId);
		$data['items'] = $this->Customer_target_model->getByCustomerId($customerId);

		$this->load->model("Sales_order_model");
		$salesOrderyArray = array();
		$countArray = array();
		$salesOrders = $this->Sales_order_model->getByCustomerIdWeekly($customerId);
		foreach($salesOrders as $salesOrder){
			$week		= $salesOrder->week;
			$year		= $salesOrder->year;
			$count		= $salesOrder->count;
			$date		= date("Y-m-d", strtotime($year . "W" . str_pad($week, 2, "0", STR_PAD_LEFT)));
			$todayWeek	= date('W');
			$todayYear	= date('Y');
			$todayDate	= date("Y-m-d", strtotime($todayYear . "W" . str_pad($todayWeek, 2, "0", STR_PAD_LEFT)));

			$difference	= date_diff(new DateTime($todayDate), new DateTime($date));
			$dayDifference = $difference->d;
			$weekDifference	= $dayDifference / 7;

			$countArray[$weekDifference] = $count;

			next($salesOrders);
		}

		for($i = 0; $i < 12; $i++){
			if(!array_key_exists($i, $countArray)){
				$salesOrderArray[$i] = array(
					"count" => 0,
					"week" => date('W', strtotime("-" . $i . " weeks")),
					"year" => date('Y', strtotime("-" . $i . " weeks")),
				);
			} else {
				$salesOrderArray[$i] = array(
					"count" => $countArray[$i],
					"week" => date('W', strtotime("-" . $i . " weeks")),
					"year" => date('Y', strtotime("-" . $i . " weeks")),
				);
			}
		};

		$data['salesOrders'] = array_reverse($salesOrderArray);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function updateCustomerTarget()
	{
		$customerId		= $this->input->post('customerId');
		$value			= $this->input->post('value');
		$date			= $this->input->post('date');

		if($customerId != null && $value > 0){
			$this->load->model("Customer_target_model");
			$result = $this->Customer_target_model->insertItem($customerId, $value, date('Y-m-01', strtotime($date)));
			echo $result;
		} else {
			echo 0;
		}
	}

	public function salesOrderArchive($customerId)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		$data			= array();

		$this->load->model("Customer_model");
		$data['customer'] = $this->Customer_model->getById($customerId);
		
		$this->load->view('sales/customer/salesOrderArchive', $data);
	}

	public function updateVisitFrequency()
	{
		$id			= $this->input->get('id');
		$visit		= $this->input->get('visit');
		$this->load->model("Customer_model");
		$result	= $this->Customer_model->updateVisitById($id, $visit);
		echo $result;
	}

	public function deleteTargetByid()
	{
		$id			= $this->input->get('id');
		$this->load->model("Customer_target_model");
		$result		= $this->Customer_target_model->deleteById($id);
		echo $result;
	}

	public function getValueByDateRange()
	{
		$start			= $this->input->post('start');
		$end			= $this->input->post('end');
		$id				= $this->input->post('id');

		$this->load->model("Invoice_model");
		$result['value']		= $this->Invoice_model->getCustomerValueByDateRange($id, $start, $end);
		$result['distribution']	= $this->Invoice_model->getCustomerValueByDateRangerItemType($id, $start, $end);

		header('Content-Type: application/json');
		echo(json_encode($result));
	}
}
