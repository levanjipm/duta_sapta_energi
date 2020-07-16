<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->has_userdata('user_id') == FALSE){
			redirect(site_url('login'));

		}
	}
	
	public function view($department)
	{
		$this->load->view('head');
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
			
		if($department == 'Sales'){
			$this->load->view('sales/header', $data);
			$this->load->view('sales/stock_dashboard');
		} else  if($department == 'Inventory'){
			$this->load->view('inventory/header', $data);
			$this->load->view('inventory/stock_dashboard');
		}
	}
	
	public function search()
	{
		$page		= $this->input->get('page');
		$term		= $this->input->get('term');
		$offset		= ($page - 1 ) * 25;
		
		$this->load->model('Stock_in_model');
		$data['stocks'] = $this->Stock_in_model->search_stock_table($offset, $term);
		$data['pages']	= $this->Stock_in_model->count_stock_table($offset, $term);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function card($item_id)
	{
		$this->load->view('head');
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
			
		$this->load->view('head');
		$this->load->view('inventory/header', $data);
		
		$this->load->model('Item_model');
		$data['items'] = $this->Item_model->select_by_id($item_id);
		
		$this->load->view('inventory/stock_card', $data);
	}
	
	public function card_view()
	{
		$item_id		= $this->input->get('item_id');
		$page			= $this->input->get('page');
		$offset			= ($page - 1) * 25;
		$this->load->model('Stock_in_model');
		$data['stock'] = $this->Stock_in_model->card_view($item_id, $offset);
		
		$data['pages'] = $this->Stock_in_model->count_card($item_id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
