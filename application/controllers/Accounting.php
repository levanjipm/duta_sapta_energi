<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounting extends CI_Controller {
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
		$this->load->view('accounting/dashboard');
	}

	public function salesReturn()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		$this->load->view('accounting/return/salesReturnDashboard');
	}

	public function purchaseReturn()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		$this->load->view('accounting/return/purchaseReturnDashboard');
	}

	public function getGeneralInformation(){
		$this->load->model("Delivery_order_model");
		$data['invoice']			= $this->Delivery_order_model->countUninvoicedDeliveryOrders(0);
		$this->load->model("Good_receipt_model");
		$data['debt']			= $this->Good_receipt_model->count_uninvoiced_documents(0);

		$this->load->model("Bank_model");
		$data['bank']			= $this->Bank_model->countUnassignedTransactions(0, 0);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getPendingCustomers()
	{
		$this->load->model("Customer_accountant_model");
		$result			= $this->Customer_accountant_model->countUnassignedCustomers();

		$data['unassigned']	= $result['totalCustomers'] - $result['assignedCustomers'];
		$data['total']		= $result['totalCustomers'];

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getInvoices()
	{
		$this->load->model("Invoice_model");
		$data = $this->Invoice_model->viewReceivableChart(1);
		$notDue		= 0;
		if(count($data) > 0){
			foreach($data as $item){
				$notDue	+= (float)$item['value'];
			}
		}

		$data = $this->Invoice_model->viewReceivableChart(2);
		$due		= 0;
		if(count($data) > 0){
			foreach($data as $item){
				$due	+= (float)$item['value'];
			}
		}

		$data['notDue'] = $notDue - $due;
		$data['due']	= $due;

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getPayable()
	{
		$this->load->model("Debt_model");

		$data = $this->Debt_model->viewPayableChart(1);
		$notDue		= 0;
		if(count($data) > 0){
			foreach($data as $item){
				$notDue	+= ((float)$item->value - (float)$item->paid);
			}
		}

		$data = $this->Debt_model->viewPayableChart(2);
		$due		= 0;
		if(count($data) > 0){
			foreach($data as $item){
				$due	+= ((float)$item->value - (float)$item->paid);
			}
		}

		$data['notDue'] = $notDue - $due;
		$data['due']	= $due;

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function checkValueDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		if($data['user_login']->access_level > 3){
			$this->load->model('Authorization_model');
			$data['departments']	= $this->Authorization_model->getByUserId($user_id);
			
			$this->load->view('head');
			$this->load->view('accounting/header', $data);
			$this->load->view('accounting/checkValue/dashboard');
		} else {
			redirect(site_url('Accounting'));
		}
	}

	public function checkValue()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		if($data['user_login']->access_level > 3){
			$this->load->model('Authorization_model');
			$data['departments']	= $this->Authorization_model->getByUserId($user_id);
			
			$this->load->view('head');
			$this->load->view('accounting/header', $data);

			$this->load->model("Item_model");
			$data['values']		= $this->Item_model->getValueByItemArray($this->input->post('quantity'));
			$this->load->view('accounting/checkValue/result', $data);
		} else {
			redirect(site_url('Accounting'));
		}
	}
}
