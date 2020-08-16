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
			$this->load->view('sales/stockDashboard');
		} else  if($department == 'Inventory'){
			$this->load->view('inventory/header', $data);
			$this->load->view('inventory/stockDashboard');
		}
	}
	
	public function showItems()
	{
		$page		= $this->input->get('page');
		$term		= $this->input->get('term');
		$offset		= ($page - 1 ) * 25;
		
		$this->load->model('Stock_in_model');
		$data['stocks'] = $this->Stock_in_model->showItems($offset, $term);
		$data['pages']	= max(1, ceil($this->Stock_in_model->countItems($offset, $term)/25));
		
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
		$data['item'] = $this->Item_model->showById($item_id);
		
		$this->load->view('inventory/stockCard', $data);
	}
	
	public function viewCard()
	{
		$itemId			= $this->input->get('id');
		$this->load->model('Stock_in_model');
		$stockIn = $this->Stock_in_model->viewCard($itemId);

		print_r($stockIn);
		// $stockInArray = (array) $stockIn;

		// $stockInArray = (array) $stockIn;
		// $stockInIdArray = array();
		// foreach($stockInArray as $stockIn){
		// 	$id = $stockIn->id;
		// 	if(!in_array($id, $stockInIdArray)){
		// 		array_push($stockInIdArray, $id);	
		// 	}
		// }

		// $this->load->model('Stock_out_model');
		// $stockOut = $this->Stock_out_model->getByInIdArray($stockInIdArray);

		// $stockOutArray = (array) $stockOut;

		// $items = array_merge($stockOutArray, $stockInArray);

		// $date = array_column($items, 'date');
		// array_multisort($date, SORT_ASC, $items);

		// $quantity = array_column($items, 'quantity');
		// array_multisort($quantity, SORT_DESC, $items);

		// $data = $items;
		
		// header('Content-Type: application/json');
		// echo json_encode($data);
	}
}
