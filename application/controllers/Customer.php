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
		$data['user_login'] = $this->User_model->show_by_id($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		
		$this->load->model('Customer_model');
		$items = $this->Customer_model->show_items();
		$data['customers'] = $items;
		
		$this->load->model('Area_model');
		$data['areas'] = $this->Area_model->show_all();
		
		$data['authorize'] = $this->session->userdata('user_role');
		
		$this->load->model('Customer_model');
		$data['pages'] = ceil($this->Customer_model->count_items() / 25);
		
		$this->load->view('sales/customer_manage_dashboard',$data);
	}
	
	public function insert_customer()
	{
		$this->load->model('Customer_model');
		$result = $this->Customer_model->insert_from_post();
		
		echo $result;
	}
	
	public function delete_customer()
	{
		$this->load->model('Customer_model');
		$result = $this->Customer_model->delete_by_id();
		
		echo $result;
	}
	
	public function show_by_id()
	{
		$customer_id		= $this->input->get('id');
		$this->load->model('Customer_model');
		$data				= $this->Customer_model->show_by_id($customer_id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function update_customer()
	{
		$this->load->model('Customer_model');
		$result = $this->Customer_model->update_from_post();
		
		echo $result;
	}
	
	public function show_items()
	{
		$term		= $this->input->get('term');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 25;
		$this->load->model('Customer_model');
		$data['customers'] = $this->Customer_model->show_items($offset, $term);
		$item = $this->Customer_model->count_items($term);
		$data['pages'] = max(1, ceil($item / 25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function get_customer_by_id()
	{
		$id = $this->input->get('id');
		$this->load->model('Customer_model');
		$data = $this->Customer_model->show_by_id($id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function show_plafonded_customers()
	{
		$term		= $this->input->get('term');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 25;
		$this->load->model('Customer_model');
		$data['customers'] = $this->Customer_model->show_plafonded_customers($offset, $term);
		$item = $this->Customer_model->count_items($term);
		$data['pages'] = max(1, ceil($item / 25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function plafond()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->show_by_id($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		
		$this->load->view('sales/plafond_dashboard');
	}
	
	public function submit_plafond()
	{
		$this->load->model('Plafond_model');
		$result		= $this->Plafond_model->insert_from_post();
		if($result != NULL){
			$id			= $result;
			redirect('Customer/plafond_submission_success/' . $id);
		} else {
			redirect('Customer/plafond_submission_failed');
		}
	}
	
	public function plafond_submission_success($submission_id)
	{		
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->show_by_id($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		
		$this->load->model('Plafond_model');
		$result		= $this->Plafond_model->show_by_id($submission_id);
		$data['submission']		= $result;
		
		$customer_id		= $result->customer_id;
		$this->load->model('Customer_model');
		$data['customer']		= $this->Customer_model->show_by_id($customer_id);
		
		$data['status']			= 'success';
		
		$this->load->view('sales/plafond_check_out', $data);
	}
	
	public function plafond_submission_failed()
	{		
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->show_by_id($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		
		$data['status']			= 'failed';
		
		$this->load->view('sales/plafond_check_out', $data);
	}
	
	public function check_plafond_status()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->show_by_id($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		
		$this->load->view('sales/plafond_status_dashboard');
	}
	
	public function show_unconfirmed_plafond()
	{
		$term		= $this->input->get('term');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 25;
		$this->load->model('Customer_model');
		$data['customers'] = $this->Customer_model->show_unconfirmed_plafond_customers($offset, $term);
		$item = $this->Customer_model->count_unconfirmed_plafond_customers($term);
		$data['pages'] = max(1, ceil($item / 25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function show_by_plafond_submission_id()
	{
		$id			= $this->input->get('id');
		$this->load->model('Plafond_model');
		$result		=  $this->Plafond_model->show_by_id($id);
		$data['plafond']	= $result;
		$customer_id		= $result->customer_id;
		
		$this->load->model('Customer_model');
		$result		= $this->Customer_model->show_by_id($customer_id);
		
		$data['customer']	= $result;
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function delete_plafond_submission()
	{
		$id		= $this->input->get('id');
		$this->load->model('Plafond_model');
		$result		= $this->Plafond_model->update_plafond($id, 0, 1);
	}
	
	public function confirm_plafond()
	{
		$id		= $this->input->post('id');
		$this->load->model('Plafond_model');
		$result		= $this->Plafond_model->update_plafond($id, 1, 0);
		
		if($result != 0){			
			$this->load->model('Plafond_model');
			$data				= $this->Plafond_model->show_by_id($id);
			$customer_id		= $data->customer_id;
			$submitted_plafond	= $data->submitted_plafond;
			
			$this->load->model('Customer_model');
			$this->Customer_model->update_plafond($customer_id, $submitted_plafond);
		}
		
		redirect(site_url('Customer/check_plafond_status'));
	}
	
	public function select_customer_sales_order()
	{
		$customer_id		= $this->input->get('id');
		
		$this->load->model('Customer_model');
		$data['customer']	= $this->Customer_model->show_by_id($customer_id);
		
		$this->load->model('Sales_order_detail_model');
		$data['pending_value']	= $this->Sales_order_detail_model->show_pending_value($customer_id);
		
		$this->load->model('Invoice_model');
		$data['pending_invoice']	= $this->Invoice_model->view_maximum_by_customer($customer_id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}