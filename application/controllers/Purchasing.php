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

	public function countIncompletePurchaseOrders()
	{
		$this->load->model('Purchase_order_model');
		$data = $this->Purchase_order_model->countIncompletePurchaseOrder();

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function countRestockItems()
	{
		$this->load->model("Stock_out_model");
		$data = $this->Stock_out_model->countRestockItems();
		$zScoreArray = array();
		$itemArray = array();
		$currentDate = date("Y-m-d");
		foreach($data as $datum){
			$itemId = $datum->item_id;
			$month		= $datum->month;
			$year		= $datum->year;

			$quantity		= $datum->sumQuantity;
			$stock[$itemId]			= $datum->residue;
			
			$formatedDate = date("Y-m-d", mktime(0,0,0, $month, 1, $year));
			$dateDifference = abs(strtotime($currentDate) - strtotime($formatedDate));
			$years = floor($dateDifference / (365*60*60*24));
			$months = floor(($dateDifference - $years * 365*60*60*24) / (30*60*60*24));  

			$confidence_level = $datum->confidence_level;
			if(!array_key_exists($itemId, $zScoreArray)){
				$zScoreArray[$itemId] = (100 - $confidence_level) / 100;
				$itemArray[$itemId] = array();
			}

			$itemArray[$itemId][$months] = $quantity;
		}

		$result = array();

		foreach($itemArray as $itemId => $quantityArray)
		{
			for($i = 0; $i < 6; $i ++){
				if(!array_key_exists($i, $itemArray[$itemId])){
					$itemArray[$itemId][$i] = 0;
				}
			}

			$itemArray3 = array($itemArray[$itemId][0], $itemArray[$itemId][1], $itemArray[$itemId][2]);
			$average3 = ceil( array_sum($itemArray3) / count($itemArray3) );

			$variance3 = 0;
			foreach($itemArray3 as $item3){
				$variance3 += pow(($item3 - $average3), 2); 
			} 
			$standardDeviation3 = (float)sqrt($variance3/count($itemArray3));

			$itemArray6 = array($itemArray[$itemId][0], $itemArray[$itemId][1], $itemArray[$itemId][2],$itemArray[$itemId][3], $itemArray[$itemId][4], $itemArray[$itemId][5]);
			$average6 = ceil(array_sum($itemArray6) / count($itemArray6) );
			$variance6 = 0;
			foreach($itemArray6 as $item6){
				$variance6 += pow(($item6 - $average6), 2); 
			}
			$standardDeviation6 = (float)sqrt($variance6/count($itemArray6));

			$zScore = $zScoreArray[$itemId];
	
			$safetyStock3 = 	$zScore * $standardDeviation3 + $average3;
			$safetyStock6 = 	$zScore * $standardDeviation6 + $average6;
			if((int)$stock[$itemId] < $safetyStock3 || (int)$stock[$itemId] < $safetyStock6){
				$result[$itemId][0] = $safetyStock3;
				$result[$itemId][1] = $safetyStock6;
				$result[$itemId][2] = (int)$stock[$itemId];
			}
		}

		header('Content-Type: application/json');
		echo json_encode($result);
	}
}
