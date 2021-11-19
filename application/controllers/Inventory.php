<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory extends CI_Controller {
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
		$this->load->view('inventory/header', $data);

		$this->load->view('inventory/dashboard');
	}

	public function getDashboardItems()
	{
		$this->load->model("Sales_order_model");
		$data['salesOrders']	= $this->Sales_order_model->countIncompletedSalesOrder();

		$this->load->model("Purchase_order_model");
		$data['purchaseOrders']	= $this->Purchase_order_model->countIncompletePurchaseOrder();

		$this->load->model("Delivery_order_model");
		$data['deliveryOrders']	= $this->Delivery_order_model->countUnsentDeliveryOrder();

		$this->load->model("Customer_route_model");
		$data['customer']	= $this->Customer_route_model->countUnassignedCustomer();

		header("Content-Type:application/json");
		echo json_encode($data);
	}

	public function pendingSalesOrderDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('inventory/header', $data);

		$this->load->view('inventory/Pending/salesOrderDashboard');
	}

	public function viewPendingSalesOrderById()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('inventory/header', $data);

		$id			= $this->input->get('id');

		$this->load->model("Sales_order_model");
		$data['salesOrder']		= $this->Sales_order_model->getById($id);
		$customerId				= $data['salesOrder']->customer_id;
		$this->load->model("Customer_model");
		$data['customer']		= $this->Customer_model->getById($customerId);

		$this->load->model("Sales_order_detail_model");
		$data['items']			= $this->Sales_order_detail_model->show_by_code_sales_order_id($id);

		$this->load->model("Delivery_order_model");
		$data['deliveryOrder']	= $this->Delivery_order_model->getItemBySalesOrderId($id);

		$this->load->view('inventory/Pending/salesOrderDetail', $data);
	}

	public function pendingPurchaseOrderDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('inventory/header', $data);

		$this->load->view('inventory/Pending/purchaseOrderDashboard');
	}

	public function viewPendingPurchaseOrderById()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('inventory/header', $data);

		$data		= array();
		$id			= $this->input->post('id');
		
		$this->load->model("Purchase_order_model");
		$data['purchaseOrder']		= $this->Purchase_order_model->showById($id);
		$supplierId					= $data['purchaseOrder']->supplier_id;

		$this->load->model("Supplier_model");
		$data['supplier']			= $this->Supplier_model->getById($supplierId);

		$this->load->model("Purchase_order_detail_model");
		$data['items']				= $this->Purchase_order_detail_model->getByCodeId($id);

		$this->load->model("Good_receipt_model");
		$data['goodReceipt']		= $this->Good_receipt_model->getReceivedByPurchaseOrderId($id);

		$this->load->view("inventory/Pending/purchaseOrderDetail", $data);
	}

	public function pendingDeliveryOrderDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('inventory/header', $data);

		$this->load->view('inventory/Pending/deliveryOrderDashboard');
	}

	public function getDailyShipments($limit = 14, $offset = 0)
	{
		$this->load->model("Delivery_order_model");
		$data		= $this->Delivery_order_model->getDailyShipments($limit, $offset);
		header("Content-Type:application/json");
		echo json_encode($data);
	}
}
