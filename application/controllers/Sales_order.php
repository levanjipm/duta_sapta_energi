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
		$data['user_login'] = $this->User_model->getById($user_id);

		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);

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

		$this->load->view('sales/sales_order_create_dashboard',$data);
	}

	public function inputItem()
	{
		$this->load->model('Sales_order_model');
		$id = $this->Sales_order_model->insert_from_post();
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
		$this->load->view('sales/sales_order_check_out', $data);
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

	public function showById()
	{
		$user_id			= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user']		= $this->User_model->getById($user_id);

		$sales_order_id		= $this->input->get('id');
		$this->load->model('Sales_order_model');
		$result 			= $this->Sales_order_model->getById($sales_order_id);
		$data['general']	= $result;

		$customer_id		= $result->customer_id;

		$this->load->model('Customer_model');
		$data['customer']	= $this->Customer_model->getById($customer_id);

		$this->load->model('Sales_order_detail_model');
		$data['pending_value']	= $this->Sales_order_detail_model->getPendingValueByCustomerId($customer_id);

		$this->load->model('Bank_model');
		$data['pending_bank_data']	= $this->Bank_model->getPendingValueByOpponentId('customer', $customer_id);

		$this->load->model('Invoice_model');
		$data['receivable'] = $this->Invoice_model->view_maximum_by_customer($customer_id);

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

		$sales_order_id		= $this->input->post('id');

		$this->load->model('Sales_order_model');
		$result 			= $this->Sales_order_model->getById($sales_order_id);
		$data['general']	= $result;

		$customer_id		= $result->customer_id;

		$this->load->model('Customer_model');
		$data['customer']	= $this->Customer_model->getById($customer_id);

		$this->load->model('Sales_order_detail_model');
		$data['pending_value']	= $this->Sales_order_detail_model->getPendingValueByCustomerId($customer_id);

		$this->load->model('Bank_model');
		$data['pending_bank_data']	= $this->Bank_model->getPendingValueByOpponentId('customer', $customer_id);

		$this->load->model('Invoice_model');
		$data['receivable'] = $this->Invoice_model->getReceivableByCustomerId($customer_id);

		$plafond = (float)$data['customer']->plafond;
		$pendingOrderValue = (float)$data['pending_value']->value;
		$pendingBankValue = (float)$data['pending_bank_data']->value;
		$receivable = (float) ($data['receivable']->value - $data['receivable']->paid);

		$value = $receivable + $pendingOrderValue - $pendingBankValue;

		if($value <= $plafond || ($value > $plafond && $access_level >= 3))
		{
			$this->load->model('Sales_order_model');
			$this->Sales_order_model->updateById(1, $sales_order_id);
		}

		redirect(site_url('Sales_order'));
	}

	public function deleteSalesOrder()
	{
		$sales_order_id		= $this->input->post('id');
		$this->load->model('Sales_order_model');
		$this->Sales_order_model->delete($sales_order_id);
	}

	public function track()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);

		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);

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
		$data['user_login'] = $this->User_model->getById($user_id);

		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);

		$this->load->view('head');
		$this->load->view('sales/header', $data);

		$sales_order_id		= $this->input->post('id');
		$this->load->model('Sales_order_model');
		$data['sales_order'] = $this->Sales_order_model->getById($sales_order_id);

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
		$data['user_login'] = $this->User_model->getById($user_id);

		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);

		$this->load->view('head');
		$this->load->view('sales/header', $data);

		if($status == 'failed'){
			$this->load->view('sales/close_check_out_failed');
		} else {
			$this->load->model('Sales_order_model');
			$data['general'] = $this->Sales_order_model->getById($status);

			$customer_id = $data['general']->customer_id;
			$this->load->model('Customer_model');
			$data['customer'] = $this->Customer_model->getById($customer_id);

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
		$data['user_login'] = $this->User_model->getById($user_id);

		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);

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
		$data['general']	= $this->Sales_order_model->getById($id);

		$this->load->model('Sales_order_detail_model');
		$data['items']		= $this->Sales_order_detail_model->show_by_code_sales_order_id($id);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function close_confirmation()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);

		if($data['user_login']->access_level <= 2){
			redirect(site_url('Sales_order'));
		} else {
			$this->load->model('Authorization_model');
			$data['departments']	= $this->Authorization_model->getByUserId($user_id);

			$this->load->view('head');
			$this->load->view('sales/header', $data);

			$this->load->view('sales/close_confirmation_dashboard');
		}
	}

	public function get_unconfirmed_closed_sales_order()
	{
		$this->load->model('Sales_order_close_request_model');
		$data = $this->Sales_order_close_request_model->get_unconfirmed();

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function get_close_request_by_id()
	{
		$this->load->model('Sales_order_close_request_model');
		$this->load->model('Sales_order_model');
		$this->load->model('Sales_order_detail_model');

		$id = $this->input->get('id');
		$data['general'] = $this->Sales_order_close_request_model->show_by_id($id);

		$code_sales_order_id = $data['general']->code_sales_order_id;
		$data['sales_order'] = $this->Sales_order_model->getById($code_sales_order_id);

		$data['items'] = $this->Sales_order_detail_model->show_by_code_sales_order_id($code_sales_order_id);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function update_close_status()
	{
		$status = $this->input->post('status');
		$id = $this->input->post('id');
		$user_id	= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		if($data['user_login']->access_level > 2){
			$this->load->model('Sales_order_close_request_model');
			$data['general'] = $this->Sales_order_close_request_model->show_by_id($id);

			$code_sales_order_id = $data['general']->code_sales_order_id;

			$result = $this->Sales_order_close_request_model->update_status($status, $id, $user_id);

			if($result == 1 && $status == 1){
				$this->load->model('Sales_order_detail_model');
				$this->Sales_order_detail_model->update_status_by_code_sales_order_id($code_sales_order_id);
			}
		}
	}
}
