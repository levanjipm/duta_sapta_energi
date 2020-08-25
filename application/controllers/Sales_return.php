<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_return extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->has_userdata('user_id') == FALSE){
			redirect(site_url('login'));

		}
	}
	
	public function createDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		$this->load->view('sales/return/createDashboard');
	}
	
	public function authenticate()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		
		$data = array();
		
		$deliveryOrderId			= $this->input->post('id');
		$this->load->model('Delivery_order_model');
		$delivery_order = $this->Delivery_order_model->getById($deliveryOrderId);
		$data['general'] = $delivery_order;

		$customerId = $delivery_order->customer_id;
		$this->load->model('Customer_model');
		$data['customer'] = $this->Customer_model->getById($customerId);
		
		$this->load->model('Delivery_order_detail_model');
		$deliveryOrder = (array) $this->Delivery_order_detail_model->getByCodeDeliveryOrderId($deliveryOrderId);

		$this->load->model('Sales_return_detail_model');
		$returnDeliveryOrder = (array)$this->Sales_return_detail_model->getByCodeDeliveryOrderId($deliveryOrderId);

		$returnDeliveryOrderArray = array();
		foreach($returnDeliveryOrder as $returnItem)
		{
			$returnDeliveryOrderArray[$returnItem->id] = $returnItem->quantity;
			next($returnDeliveryOrder);
		}

		$resultArray = array();

		foreach($deliveryOrder as $item){
			$batchArray = (array) $item;
			$batchArray['returned'] = array_key_exists($item->id,$returnDeliveryOrder)? $returnDeliveryOrder[$item->id]: 0;

			array_push($resultArray, $batchArray);
			next($deliveryOrder);
		}
		
		$data['items'] = $resultArray;
		
		$this->load->view('sales/Return/validation', $data);
	}
	
	public function insertItem()
	{
		$total_quantity = array_sum($this->input->post('return_quantity'));
		if($total_quantity > 0)
		{
			$check = true;
			$returnQuantityArray = $this->input->post('return_quantity');
			$this->load->model('Sales_return_detail_model');

			$deliveryOrderIdArray	= array();
			foreach($returnQuantityArray as $returnQuantity)
			{
				$deliveryOrderId		= key($returnQuantityArray);
				array_push($deliveryOrderIdArray, $deliveryOrderId);
				next($returnQuantityArray);
			}

			//Check for previous return//
			$returnArray		= $this->Sales_return_detail_model->getQuantityByDeliveryOrderIdArray($deliveryOrderIdArray);
			foreach($returnArray as $return){
				$deliveryOrderId	= $return->id;
				$returned 			= $return->returned;
				$quantity			= $return->quantity;
				$returnQuantity		= $returnQuantityArray[$deliveryOrderId];

				if($returnQuantity + $returned > $quantity)
				{
					$check = false;
					break;
				}
			}

			if($check){
				$this->load->model("Sales_return_model");
				$codeSalesReturnId = $this->Sales_return_model->insertItem();
				if($codeSalesReturnId != null)
				{
					$this->load->model('Sales_return_detail_model');
					$this->Sales_return_detail_model->insertItemArray($returnQuantityArray, $codeSalesReturnId);
				}
			}
		}
		
		redirect(site_url('Sales_return/createDashboard'));
	}
	
	public function confirmDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		$this->load->view('sales/return/confirmDashboard');
	}

	public function getUnconfirmedDocuments()
	{
		$page = $this->input->get('page');
		$offset = ($page - 1) * 10;

		$this->load->model('Sales_return_model');
		$data['items'] = $this->Sales_return_model->getUnconfirmedDocuments($offset);
		$data['pages'] = max(1, ceil($this->Sales_return_model->countUnconfirmedDocuments()/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getById()
	{
		$id		= $this->input->get('id');

		$this->load->model('Sales_return_model');
		$salesReturn 		= $this->Sales_return_model->getById($id);

		$this->load->model('Delivery_order_model');
		$deliveryOrderId 	= $salesReturn->codeDeliveryOrderId;
		$data['deliveryOrder'] = $this->Delivery_order_model->getById($deliveryOrderId);

		$this->load->model("Customer_model");
		$customerId			= $salesReturn->customer_id;
		$data['customer']	= $this->Customer_model->getById($customerId);

		$data['salesReturn']	= $salesReturn;

		$this->load->model('Sales_return_detail_model');
		$data['items']			= $this->Sales_return_detail_model->getByCodeSalesReturnId($id);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function confirmById()
	{
		$salesReturnId		= $this->input->post('id');

		$this->load->model('Sales_return_model');
		$result = $this->Sales_return_model->updateById(1, $salesReturnId);

		echo $result;
	}
	
	public function deleteById()
	{
		$salesReturnId		= $this->input->post('id');

		$this->load->model('Sales_return_model');
		$result = $this->Sales_return_model->updateById(0, $salesReturnId);

		echo $result;
	}

	public function receiveDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('inventory/header', $data);
		$this->load->view('inventory/Return/salesReturnDashboard');
	}

	public function loadForm($event)
	{
		if($event == "create")
		{
			$this->load->view('inventory/Return/salesReturnCreate');
		} else {
			$this->load->view('inventory/Return/salesReturnConfirm');
		}
	}

	public function getIncompletedReturn()
	{
		$page		= $this->input->get('page');
		$term		= $this->input->get('term');
		$offset		= ($page - 1) * 10;

		$this->load->model('Sales_return_detail_model');
		$data['items'] = $this->Sales_return_detail_model->getIncompletedReturn($offset, $term);
		$data['pages'] = max(1, ceil($this->Sales_return_detail_model->countIncompletedReturn($term)/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function receiveItem()
	{
		$date			= $this->input->post('date');
		$documentName	= $this->input->post('document');
		$itemArray		= $this->input->post('quantity');

		$this->load->model('Sales_return_detail_model');
		$result		= $this->Sales_return_detail_model->quantityCheck($itemArray);
		if($result){
			$this->load->model('Sales_return_received_model');
			$codeSalesReturnId = $this->Sales_return_received_model->insertItem($date, $documentName);
			if($codeSalesReturnId != null){
				$this->load->model('Sales_return_received_detail_model');
				$this->Sales_return_received_detail_model->insertItem($itemArray, $codeSalesReturnId);
				$this->Sales_return_detail_model->updateItems($itemArray);
				echo 1;
			} else {
				echo 0;
			}
		} else {
			echo 0;
		}
	}
	public function getUnconfirmedSalesReturn()
	{
		$page			= $this->input->get('page');
		$this->load->model("Sales_return_received_model");
		$offset			= ($page - 1) * 10;
		$data['items']			= $this->Sales_return_received_model->getUnconfirmedSalesreturn($offset);
		$data['pages']			= max(1, ceil($this->Sales_return_received_model->countUnconfirmedSalesReturn()));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getReceivedById()
	{
		$id			= $this->input->get('id');
		$this->load->model('Sales_return_received_model');
		$salesReturnReceived = $this->Sales_return_received_model->getById($id);

		$customerId = $salesReturnReceived->customer_id;

		$data['general'] = $salesReturnReceived;

		$this->load->model('Customer_model');
		$data['customer'] = $this->Customer_model->getById($customerId);
		$this->load->model('Sales_return_received_detail_model');
		$data['items'] = $this->Sales_return_received_detail_model->getByCodeId($id);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function confirmReceivedById()
	{
		$id			= $this->input->post('id');
		$this->load->model('Sales_return_received_model');
		$result = $this->Sales_return_received_model->updateById(1, $id);
		if($result == 1){
			$salesReturn			= $this->Sales_return_received_model->getById($id);
			$codeDeliveryOrderId	= $salesReturn->deliveryOrderId;
			$this->load->model("Sales_return_received_detail_model");
			$salesReturnItems		= $this->Sales_return_received_detail_model->getByCodeId($id);
			$salesReturnArray = array();
			foreach($salesReturnItems as $salesReturnItem)
			{
				$array = array(
					'id' => '',
					'supplier_id' => null,
					'customer_id' => $salesReturn->customer_id,
					'sales_return_received_id' => $salesReturnItem->id,
					'quantity' => $salesReturnItem->quantity,
					'residue' => $salesReturnItem->quantity,
					'good_receipt_id' => null,
					'event_id' => null,
					'price' => $salesReturnItem->value,
					'item_id' => $salesReturnItem->item_id
				);

				array_push($salesReturnArray, $array);
			};

			$this->load->model('Stock_in_model');
			$this->Stock_in_model->insertItem($salesReturnArray);
		}

		echo $result;
	}

	public function archiveDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('inventory/header', $data);
		$this->load->view('sales/Return/archiveDashboard');
	}

	public function deleteReceivedById()
	{
		$id			= $this->input->post('id');
		$this->load->model('Sales_return_received_model');
		$result = $this->Sales_return_received_model->updateById(0, $id);
		if($result == 1){
			$salesReturnBatch = array();
			$this->load->model('Sales_return_received_detail_model');
			$receivedItems = $this->Sales_return_received_detail_model->getByCodeId($id);
			foreach($receivedItems as $receivedItem){
				if($receivedItem->quantity > 0){
					$id		= $receivedItem->id;
					$salesReturnBatch[$id] = $receivedItem->quantity;
				}
			}

			$this->load->model("Sales_return_detail_model");
			$this->Sales_return_detail_model->updateByDeleteReceivedArray($salesReturnBatch);
			echo 1;
		} else {
			echo 0;
		}
	}

	public function getUnassignedSalesReturn()
	{
		$page			= $this->input->get('page');
		$offset			= ($page - 1) * 10;
		$this->load->model('Sales_return_received_model');
		$data['items']	= $this->Sales_return_received_model->getUnassignedItems($offset);
		$data['pages']	= max(1, ceil($this->Sales_return_received_model->countUnassignedItems()/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function updateBankDateById()
	{
		$salesReturnReceivedId			= $this->input->post('id');
		$date							= $this->input->post('date');
		$account						= $this->input->post('account');

		if($date > "2020-01-01"){
			$this->load->model("Sales_return_received_model");
			$salesReturn		= $this->Sales_return_received_model->getById($salesReturnReceivedId);
			$customer_id		= $salesReturn->customer_id;

			$this->load->model("Sales_return_received_detail_model");
			$salesReturnDetail	= $this->Sales_return_received_detail_model->getByCodeId($salesReturnReceivedId);

			$salesReturnValue	= 0;
			$items				= (array) $salesReturnDetail;
			foreach($items as $item){
				$quantity		= $item->quantity;
				$price_list		= $item->price_list;
				$discount		= $item->discount;
				$netPrice		= $price_list * (100 - $discount) / 100;
				$totalPrice		= $netPrice * $quantity;

				$salesReturnValue	+= $totalPrice;
			}

			$this->load->model("Bank_model");
			$result = $this->Bank_model->insertItem($date, $salesReturnValue, 1, 'customer', $customer_id, $account);
			if($result != NULL){
				$this->Sales_return_received_model->updateFinanceStatusById($salesReturnReceivedId, $result);
				$this->Bank_model->insertItem($date, $salesReturnValue, 2, 'customer', $customer_id, $account, $result, 1);

				echo 1;
			} else {
				echo 0;
			}
		} else {
			echo 0;
		}
	}
}
