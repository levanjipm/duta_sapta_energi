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

		header("Content-Type:application/json");
		echo json_encode($data);
	}
}
