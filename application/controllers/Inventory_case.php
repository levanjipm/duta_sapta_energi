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
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('inventory/header', $data);
		
		$this->load->view('inventory/case/case_dashboard');
	}

	public function create($event)
	{
		switch($event){
			case 'lost':
				$this->load->view('inventory/case/case_lost_goods_dashboard');
				break;
			case 'found':
				$this->load->view('inventory/case/case_found_goods_dashboard');
				break;
			case 'dematerialized':
				$this->load->view('inventory/case/case_dematerialized_goods_dashboard');
				break;
			case 'materialized':
				$this->load->view('inventory/case/case_materialized_goods_dashboard');
				break;
			default:
				redirect(site_url("Inventory_case"));
		}
	}

	public function input($event)
	{
		$user_id		= $this->session->userdata('user_id');

		switch($event){
			case 'lost':
				$date			= $this->input->post('date');
				$quantity_array	= $this->input->post('quantity');
				$type			= 1;
				
				$this->load->model('Inventory_case_model');
				$result = $this->Inventory_case_model->insertItem($user_id, $date, $type);
				
				if($result != NULL){
					$this->load->model('Inventory_case_detail_model');
					$this->Inventory_case_detail_model->insertItem($result, $quantity_array, $type);
				}

				break;
			case 'found':
				$date			= $this->input->post('date');
				$quantity_array	= $this->input->post('quantity');
				$price_array	= $this->input->post('price');
				$type			= 2;
				
				$this->load->model('Inventory_case_model');
				$result = $this->Inventory_case_model->insertItem($user_id, $date, $type);
				
				if($result != NULL){
					$this->load->model('Inventory_case_detail_model');
					$this->Inventory_case_detail_model->insertItem($result, $quantity_array, $type, $price_array);
				}
				break;
			case 'dematerialized':
				$this->load->view('inventory/case/case_dematerialized_goods_dashboard');
				break;
			case 'materialized':
				$this->load->view('inventory/case/case_materialized_goods_dashboard');
				break;
			default:
				
		}

		redirect(site_url("Inventory_case"));
	}
	
	public function confirmDashboard()
	{
		$this->load->view('inventory/case/case_confirm_dashboard');
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
	
	public function showById()
	{
		$id = $this->input->get('id');
		$this->load->model('Inventory_case_model');
		$result		= $this->Inventory_case_model->showById($id);
		$data['general']	= $result;
		
		$this->load->model('Inventory_case_detail_model');
		$result		= $this->Inventory_case_detail_model->showByCodeId($id);
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
		
		redirect(site_url('Inventory_case'));
	}
}
