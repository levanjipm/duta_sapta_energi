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
		
		echo $result;
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

		$this->load->model("Sales_order_model");
		$data['salesOrders'] = $this->Sales_order_model->getPendingSalesOrdersByCustomerId($customerId);

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
}
