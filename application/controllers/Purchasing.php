<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchasing extends CI_Controller {
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
		$this->load->view('purchasing/header', $data);
		$this->load->view('purchasing/dashboard');
	}
	
	public function calculateNeeds()
	{
		$this->load->model('Sales_order_detail_model');
		$items		= $this->Sales_order_detail_model->getItemNeeded();

		$this->load->model('Purchase_order_detail_model');
		$this->load->model('Stock_in_model');

		$orderArray = array();

		foreach($items as $item)
		{
			$itemId = $item->item_id;

			$orderQuantity = $item->quantity;

			$pendingQuantity = $this->Purchase_order_detail_model->getPendingItemsById($itemId);
			$stockQuantity	= $this->Stock_in_model->getStockByItemId($itemId);

			$pending = $pendingQuantity->quantity;
			$stock	= $stockQuantity->quantity;

			if($pending + $stock < $orderQuantity)
			{
				$reference = $item->reference;
				$name = $item->name;

				$order = array(
					'id' => $itemId,
					'reference' => $reference,
					'name' => $name,
					'quantity' => (int) ($orderQuantity - $stock - $pending)
				);

				array_push($orderArray, $order);
			}
		}

		header('Content-Type: application/json');
		echo json_encode($orderArray);
	}
}
