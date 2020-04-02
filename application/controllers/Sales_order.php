<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_order extends CI_Controller {
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
		
		$this->load->view('sales/sales_order');
	}
	
	public function view_unconfirmed_sales_order()
	{
		$page		= $this->input->get('page');
		$term		= $this->input->get('term');
		$offset		= ($page - 1) * 25;
		$this->load->model('Sales_order_model');
		$data['sales_orders'] 	= $this->Sales_order_model->show_unconfirmed_sales_order($offset, $term);
		$data['pages'] 			= max(1, ceil($this->Sales_order_model->count_unconfirmed_sales_order($term)/25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function create()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->show_by_id($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		
		$this->load->model('Sales_order_model');
		$guid = $this->Sales_order_model->create_guid();
		$data['guid'] = $guid;
		
		$this->load->model('Customer_model');
		$data['customers'] = $this->Customer_model->show_items();
		
		$this->load->model('User_model');
		$data['users'] = $this->User_model->show_all();
		
		$this->load->view('sales/sales_order_create_dashboard',$data);
	}
	
	public function input_sales_order()
	{	
		$this->load->model('Sales_order_model');
		$id = $this->Sales_order_model->insert_from_post();
		if($id != NULL){
			$this->load->model('Sales_order_detail_model');
			$this->Sales_order_detail_model->insert_from_post($id);
		};
		
		if($id == null){
			redirect(site_url('Sales_order'));
		} else {
			redirect(site_url('Sales_order/sales_order_check_out/') . $id);
		}
	}
	
	public function sales_order_check_out($id)
	{
		$this->load->model('Sales_order_model');
		$sales_order 	= $this->Sales_order_model->show_by_id($id);
		$result 		= $sales_order;
		
		$data['general'] = $result;
		
		$customer_id = $data['general']->customer_id;
		$this->load->model('Customer_model');
		$data['customer'] = $this->Customer_model->show_by_id($customer_id);
		
		$this->load->model('Sales_order_detail_model');
		$data['details'] = $this->Sales_order_detail_model->show_by_code_sales_order_id($id);
		
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->show_by_id($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		$this->load->view('sales/sales_order_check_out', $data);
	}
	
	public function view_sales_order()
	{
		$user_id			= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user']		= $this->User_model->show_by_id($user_id);
		
		$sales_order_id		= $this->input->get('id');
		$this->load->model('Sales_order_model');
		$result = $this->Sales_order_model->show_by_id($sales_order_id);
		$data['general']	= $result;
		
		$customer_id		= $result->customer_id;
		$this->load->model('Invoice_model');
		$data['receivable'] = $this->Invoice_model->view_maximum_by_customer($customer_id);
		
		$this->load->model('Sales_order_detail_model');
		$data['detail'] = $this->Sales_order_detail_model->show_by_code_sales_order_id($sales_order_id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function confirm()
	{
		$sales_order_id		= $this->input->post('id');
		$this->load->model('Sales_order_model');
		$this->Sales_order_model->confirm($sales_order_id);
		
		redirect(site_url('Sales_order'));
	}
	
	public function delete()
	{
		$sales_order_id		= $this->input->post('id');
		$this->load->model('Sales_order_model');
		$this->Sales_order_model->delete($sales_order_id);
	}
	
	public function track()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->show_by_id($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		
		$this->load->view('sales/sales_order_track_dashboard');
	}
	
	public function view_track()
	{
		$term		= $this->input->get('term');
		$page		= $this->input->get('page');
		$offset		= ($page -1 ) * 25;
		
		$this->load->model('Sales_order_model');
		$data['sales_orders'] = $this->Sales_order_model->show_uncompleted_sales_order($offset, $term);

		$data['pages'] = max(1, ceil($this->Sales_order_model->count_uncompleted_sales_order($term)/25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function view_track_detail()
	{
		$code_sales_order_id		= $this->input->get('id');
		$this->load->model('Sales_order_detail_model');
		$data	= $this->Sales_order_detail_model->show_by_code_sales_order_id($code_sales_order_id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function close()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->show_by_id($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		
		$sales_order_id		= $this->input->post('id');
		$this->load->model('Sales_order_model');
		$data['sales_order'] = $this->Sales_order_model->show_by_id($sales_order_id);
		
		$this->load->model('Sales_order_detail_model');
		$data['details']	= $this->Sales_order_detail_model->show_by_code_sales_order_id($sales_order_id);
		
		$this->load->view('sales/sales_order_close_dashboard', $data);
	}
	
	public function close_do()
	{
		$this->load->model('Sales_order_close_request_model');
		$result = $this->Sales_order_close_request_model->close();
		
		if($result != NULL){
			redirect(site_url('Sales_order/close_check_out/') . $result);
		} else {
			redirect(site_url('Sales_order/close_check_out'));
		}
	}
	
	public function close_check_out($status)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->show_by_id($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		
		if($status == 'failed'){
			$this->load->view('sales/close_check_out_failed');
		} else {
			$this->load->model('Sales_order_model');
			$data['general'] = $this->Sales_order_model->show_by_id($status);
			
			$customer_id = $data['general']->customer_id;
			$this->load->model('Customer_model');
			$data['customer'] = $this->Customer_model->show_by_id($customer_id);
			
			$this->load->model('Sales_order_detail_model');
			$data['details'] = $this->Sales_order_detail_model->show_by_code_sales_order_id($status);
			
			$this->load->model('Sales_order_close_request_model');
			$data['close_sales_order'] = $this->Sales_order_close_request_model->show_by_id($status);
			
			$this->load->view('sales/close_check_out', $data);
		}
	}
	
	public function archive()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->show_by_id($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		
		$this->load->model('Sales_order_model');
		$data['years']	= $this->Sales_order_model->show_years();
		
		$this->load->view('sales/sales_order_archive', $data);
	}
	
	public function view_archive()
	{
		$page			= $this->input->get('page');
		$term			= $this->input->get('term');
		$offset			= ($page - 1) * 25;
		$year			= $this->input->get('year');
		$month			= $this->input->get('month');
		
		$this->load->model('Sales_order_model');
		$data['sales_orders']		= $this->Sales_order_model->show_items($year, $month, $offset, $term);
		$data['pages']				= max(1, ceil($this->Sales_order_model->count_items($year, $month, $term)/25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function view_by_id()
	{
		$id			= $this->input->get('id');
		$this->load->model('Sales_order_model');
		$data['general']	= $this->Sales_order_model->show_by_id($id);
		
		$this->load->model('Sales_order_detail_model');
		$data['items']		= $this->Sales_order_detail_model->show_by_code_sales_order_id($id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
