<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_case extends CI_Controller {
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
		$this->load->view('inventory/header', $data);
		
		$this->load->view('inventory/case_dashboard');
	}
	
	public function lost_goods()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->show_by_id($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('inventory/header', $data);
		
		$this->load->view('inventory/case_lost_goods_dashboard');
	}
	
	public function case_lost_goods_input()
	{
		$user_id		= $this->session->userdata('user_id');
		
		$date			= $this->input->post('date');
		$quantity_array	= $this->input->post('quantity');
		$type			= 1;
		
		$this->load->model('Inventory_case_model');
		$result = $this->Inventory_case_model->insert_from_post($user_id, $date, $type);
		
		if($result != NULL){
			$this->load->model('Inventory_case_detail_model');
			$this->Inventory_case_detail_model->insert_from_post($result, $quantity_array, $type);
		}
		
		redirect(site_url('Inventory_case'));
	}
	
	public function found_goods()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->show_by_id($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('inventory/header', $data);
		
		$this->load->view('inventory/case_found_goods_dashboard');
	}
	
	public function case_found_goods_input()
	{
		$user_id		= $this->session->userdata('user_id');
		
		$date			= $this->input->post('date');
		$quantity_array	= $this->input->post('quantity');
		$price_array	= $this->input->post('price');
		$type			= 2;
		
		$this->load->model('Inventory_case_model');
		$result = $this->Inventory_case_model->insert_from_post($user_id, $date, $type);
		
		if($result != NULL){
			$this->load->model('Inventory_case_detail_model');
			$this->Inventory_case_detail_model->insert_from_post($result, $quantity_array, $type, $price_array);
		}
		
		redirect(site_url('Inventory_case'));
	}
	
	public function confirm_dashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->show_by_id($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->show_by_user_id($user_id);
		
		$this->load->view('head');
		$this->load->view('inventory/header', $data);
		$this->load->view('inventory/case_confirm_dashboard');
	}
	
	public function view_unconfirmed_case()
	{
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 25;
		
		$this->load->model('Inventory_case_model');
		$result = $this->Inventory_case_model->show_unconfirmed_cases($offset);
		$data['cases'] = $result;
		
		$result = $this->Inventory_case_model->count_unconfirmed_cases($offset);
		$data['pages'] = max(0, ceil($result/25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function view_by_id()
	{
		$id = $this->input->get('id');
		$this->load->model('Inventory_case_model');
		$result		= $this->Inventory_case_model->view_by_id($id);
		$data['general']	= $result;
		
		$this->load->model('Inventory_case_detail_model');
		$result		= $this->Inventory_case_detail_model->view_by_code_id($id);
		$data['details']	= $result;
		
		$stock_array 	= $this->Inventory_case_detail_model->get_batch_by_code_event_id($id);
		
		$this->load->model('Stock_in_model');
		$stock = $this->Stock_in_model->check_stock($stock_array);
		$data['status'] = $stock;
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function confirm()
	{
		$user_id		= $this->session->userdata('user_id');
		$id				= $this->input->post('id');
		$status			= $this->input->post('status');
		if($status == 'true'){
			$this->load->model('Inventory_case_detail_model');
			$stock_array 	= $this->Inventory_case_detail_model->get_batch_by_code_event_id($id);
			
			$this->load->model('Stock_in_model');
			
			$stock = $this->Stock_in_model->check_stock($stock_array);
			if($stock){
				$stock_array 	= $this->Inventory_case_detail_model->get_stock_out_batch_by_code_event_id($id);
				if(!empty($stock_array)){
					$this->load->model('Stock_out_model');
					$this->Stock_out_model->send_event($stock_array);
				}
				
				$stock_array 	= $this->Inventory_case_detail_model->get_stock_in_batch_by_code_event_id($id);
				
				if(!empty($stock_array)){
					$this->load->model('Stock_in_model');
					$this->Stock_in_model->input_from_code_event($stock_array);
				}
				$this->load->model('Inventory_case_model');
				$result = $this->Inventory_case_model->confirm($id, $user_id);
			}
		}
		
		redirect(site_url('Inventory_case/confirm_dashboard'));
	}
}
