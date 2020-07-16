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
		
		$this->load->view('sales/customer_manage_dashboard',$data);
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
		$customer_id		= $this->input->get('id');
		
		$this->load->model('Customer_model');
		$data['customer']	= $this->Customer_model->getById($customer_id);
		
		$this->load->model('Sales_order_detail_model');
		$data['pending_value']	= $this->Sales_order_detail_model->getPendingValueByCustomerId($customer_id);
		
		$this->load->model('Invoice_model');
		$data['pending_invoice']	= $this->Invoice_model->view_maximum_by_customer($customer_id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}