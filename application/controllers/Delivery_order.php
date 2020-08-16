<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Delivery_order extends CI_Controller {
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
		$this->load->view('inventory/header', $data);
		
		$this->load->model('Sales_order_model');
		$data['sales_orders'] = $this->Sales_order_model->show_uncompleted_sales_order();
		
		if(!empty($data['sales_order'])){
			$customer_ids = array();
			foreach($data['sales_orders'] as $sales_order){
				array_push($customer_ids, $sales_order->customer_id);
			}
			
			$this->load->model('Customer_model');
			$data['customers'] = $this->Customer_model->show_all_by_id($customer_ids);
		}
		
		$this->load->view('inventory/DeliveryOrder/deliveryOrderCreate', $data);
	}
	
	public function confirmDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('inventory/header', $data);
		
		$this->load->view('inventory/DeliveryOrder/deliveryOrderConfirmDashboard');
	}
	
	public function viewSalesOrderbyId()
	{
		$id		= $this->input->get('sales_order_id');
		
		$this->load->model('Sales_order_detail_model');
		$result	= $this->Sales_order_detail_model->show_by_code_sales_order_id($id);
		$data['details'] = $result;
		
		$this->load->model('Delivery_order_model');
		$data['guid'] = $this->Delivery_order_model->create_guid();
		
		$this->load->model('User_model');
		$userId				= $this->session->userdata('user_id');
		$data['user']		= $this->User_model->getById($userId);

		$this->load->model('Sales_order_model');
		$result 			= $this->Sales_order_model->getById($id);
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
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function insertItem()
	{
		$codeSalesOrderId 	= $this->input->post('salesOrderId');
		$guid			= $this->input->post('guid');

		$user_id		= $this->session->userdata('user_id');

		$this->load->model('User_model');
		$userObject = $this->User_model->getById($user_id);
		$accessLevel = $userObject->access_level;

		//Back-end validation//
		$this->load->model('Sales_order_model');
		$this->load->model('Sales_order_detail_model');
		$this->load->model('Price_list_model');
		$this->load->model('Customer_model');

		$result 			= $this->Sales_order_model->getById($codeSalesOrderId);
		
		$customerId		= $result->customer_id;
		$invoicingMethod = $result->invoicing_method;
		
		$customerObject = $this->Customer_model->getById($customerId);
		$plafond		= $customerObject->plafond;

		$this->load->model('Bank_model');
		$pending_bank_data	= $this->Bank_model->getPendingValueByOpponentId('customer', $customerId);
		
		$this->load->model('Invoice_model');
		$receivable = $this->Invoice_model->getReceivableByCustomerId($customerId)->value;

		$deliveryOrderValue = 0;
		$totalQuantity = 0;
		$quantityValidation = true;

		$quantityArray = $this->input->post('quantity');
		foreach($quantityArray as $quantity){
			$salesOrderId = key($quantityArray);
			$salesOrder = $this->Sales_order_detail_model->getById($salesOrderId);

			$priceListId = $salesOrder->price_list_id;
			$discount =  $salesOrder->discount;
			$priceListObject = $this->Price_list_model->getById($priceListId);
			$priceList = $priceListObject->price_list;

			$price = (float) $priceList * (100 - $discount) / 100;
			$totalPrice = $price * $quantity;

			$totalQuantity += $quantity;
			$deliveryOrderValue += $totalPrice;
			if($quantity < 0){
				$quantityValidation = false;
				break;
			}
			next($quantityArray);
		}

		if($totalQuantity == 0){
			$quantityValidation = false;
		};

		if($quantityValidation && ($receivable - $pending_bank_data + $deliveryOrderValue <= $plafond || $accessLevel > 2)){
			$this->load->model('Delivery_order_model');
			$result 		= $this->Delivery_order_model->check_guid($guid);
			if($result){
				$sales_order_array	= array_keys($this->input->post('quantity'));
				$quantity_array		= array_values($this->input->post('quantity'));

				$result = $this->Sales_order_detail_model->check_sales_order($sales_order_array, $quantity_array);
				
				if($result){
					$deliveryOrderId = $this->Delivery_order_model->insertItem();
					
					$this->load->model('Delivery_order_detail_model');
					$result = $this->Delivery_order_detail_model->insert_from_post($deliveryOrderId);
					
					if($result){
						$this->Sales_order_detail_model->updateSalesOrderSent($sales_order_array, $quantity_array);
					}
				};

				if($invoicingMethod == 1){
					redirect(site_url('Delivery_order/createDashboard'));
				} else if($invoicingMethod == 2){
					redirect(site_url('Delivery_order/printDeliveryOrder/' . $deliveryOrderId));
				}

			} else {
				redirect(site_url('Delivery_order/createDashboard'));
			}
		} else {
			redirect(site_url('Delivery_order/createDashboard'));
		}
		
	}
	
	public function getByCodeDeliveryOrderId($id)
	{
		$this->load->model('Delivery_order_detail_model');
		$this->load->model('Delivery_order_model');
		
		$data['invoice'] = $this->Delivery_order_model->show_by_id($id);
		$data['general'] = $this->Delivery_order_detail_model->getByCodeDeliveryOrderId($id);
		
		$delivery_order_array 	= $this->Delivery_order_detail_model->get_batch_by_code_delivery_order_id($id);
		
		$this->load->model('Stock_in_model');
		$result					= $this->Stock_in_model->check_stock($delivery_order_array);
		if($result){
			$data['info'] = '';
		} else {
			$data['info'] = 'Stock';
		}
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function archive()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('inventory/header', $data);
		
		$this->load->model('Delivery_order_model');
		$data['years']	= $this->Delivery_order_model->show_years();
		
		$this->load->view('inventory/DeliveryOrder/deliveryOrderArchive', $data);
	}

	public function printDeliveryOrder($delivery_order_id)
	{
		$this->load->model('Delivery_order_model');
		$result = $this->Delivery_order_model->getById($delivery_order_id);
		$data['general']	= $result;

		$customerId = $result->customer_id;
		$this->load->model('Customer_model');
		$data['customer'] = $this->Customer_model->getById($customerId);
		
		$this->load->model('Delivery_order_detail_model');
		$data['items'] = $this->Delivery_order_detail_model->getByCodeDeliveryOrderId($delivery_order_id);
		
		$this->load->view('head');
		$this->load->view('inventory/DeliveryOrder/deliveryOrderPrint', $data);
	}
	
	public function confirmById()
	{
		$delivery_order_id			= $this->input->post('id');
		$this->load->model('Delivery_order_model');
		$result = $this->Delivery_order_model->updateById(1, $delivery_order_id);
		if($result){
			$this->load->model('Sales_order_model');
			$sales_order = $this->Sales_order_model->show_invoicing_method_by_id($delivery_order_id);
			
			if($sales_order[0]->invoicing_method == 2){
				$resultArray = array(
					'result' => 'success',
					'invoicingMethod' => 2,
				);
			} else {
				$resultArray = array(
					'result' => 'success',
					'invoicingMethod' => 1,
				);
			}
		} else {
			$resultArray = array(
				'result' => 'failed',
				'invoicingMethod' => null,
			);
		}

		$data =  json_encode($resultArray);

		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function sendById()
	{
		$this->load->model('Delivery_order_model');
		$deliveryOrderId		= $this->input->post('id');
		$deliveryOrderObject	= $this->Delivery_order_model->getById($deliveryOrderId);

		$invoicingMethod		= $deliveryOrderObject->invoicing_method;
		$invoiceId				= $deliveryOrderObject->invoice_id;

		if(($invoicingMethod == 1 && $invoiceId != null) || ($invoicingMethod == 2 && $invoiceId == null)){
			$this->load->model('Delivery_order_detail_model');
			$deliveryOrderArray 	= $this->Delivery_order_detail_model->getDeliveryOrderBatch($deliveryOrderId);
			
			$this->load->model('Stock_in_model');
			$result					= $this->Stock_in_model->checkStock($deliveryOrderArray);
			
			if($result){
				$check 				= $this->Delivery_order_model->sendById($deliveryOrderId);
				if($check){			
					$this->load->model('Stock_out_model');
					$this->Stock_out_model->sendDeliveryOrder($deliveryOrderArray);
					echo 1;
				} else {
					echo 0;
				}
			} else {
				echo 0;
			}
		} else {
			echo 0;
		}
	}

	public function cancelSendById()
	{
		$deliveryOrderId		= $this->input->post('id');
		$this->load->model('Delivery_order_model');
		$result = $this->Delivery_order_model->updateById(-1, $deliveryOrderId);
		if($result){
			echo 1;
		} else {
			echo 0;
		}
	}
	
	public function getBySalesOrderId()
	{
		$sales_order_id		= $this->input->get('sales_order_id');
		
		$this->load->model('Delivery_order_detail_model');
		$data 				= $this->Delivery_order_detail_model->getByCodeSalesOrderId($sales_order_id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function viewArchive()
	{
		$page			= $this->input->get('page');
		$term			= $this->input->get('term');
		$offset			= ($page - 1) * 10;
		$year			= $this->input->get('year');
		$month			= $this->input->get('month');
		
		$this->load->model('Delivery_order_model');
		$data['delivery_orders'] 	= $this->Delivery_order_model->getArchive($year, $month, $offset, $term);
		$data['pages']				= max(1, ceil($this->Delivery_order_model->countArchive($year, $month, $term)/10));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function getById()
	{
		$id				= $this->input->get('id');
		$this->load->model('Delivery_order_model');
		$deliveryOrder	= $this->Delivery_order_model->getById($id);
		$data['general'] = $deliveryOrder;

		$customerId = $deliveryOrder->customer_id;
		$this->load->model('Customer_model');
		$data['customer'] = $this->Customer_model->getById($customerId);

		$this->load->model('Delivery_order_detail_model');
		$items		= $this->Delivery_order_detail_model->getByCodeDeliveryOrderId($id);

		$data['items'] = $items;

		$stockArray = array();
		foreach($items as $item){
			$stock = array(
				'item_id'=> $item->item_id,
				'quantity' => $item->quantity
			);

			array_push($stockArray, $stock);
		}

		$this->load->model('Stock_in_model');
		$stockStatus = $this->Stock_in_model->checkStock($stockArray);
		
		$data['status'] = $stockStatus;

		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function deleteById()
	{
		$deliveryOrderId				= $this->input->post('id');
		$this->load->model("Delivery_order_model");
		$deliveryOrder			= $this->Delivery_order_model->getById($deliveryOrderId);

		$invoiceId				= $deliveryOrder->invoice_id;

		$this->load->model('Delivery_order_detail_model');
		$sales_order_array		= (array) $this->Delivery_order_detail_model->getByCodeDeliveryOrderId($deliveryOrderId);

		if(count($sales_order_array) > 0){
			$result = array();
			foreach($sales_order_array as $item){
				$ordered 	= $item->ordered;
				$quantity	= $item->quantity;
				$id 		= $item->sales_order_id;
				$sent 		= $item->sent;
				if($sent - $quantity == $ordered && $sent - $quantity > 0){
					$status = 1;
				} else {
					$status = 0;
				}

				$salesOrderArray = array(
					'id' => $id,
					'status' => $status,
					'sent' => max(0, ($sent - $quantity)),
				);

				array_push($result, $salesOrderArray);

				next($sales_order_array);
			}

			$this->load->model('Sales_order_detail_model');
			$this->Sales_order_detail_model->updateCancelDeliveryOrder($result);
			
			$this->load->model('Delivery_order_model');
			$this->Delivery_order_model->updateById(0, $deliveryOrderId);

			$this->load->model('Invoice_model');
			$this->Invoice_model->deleteById($invoiceId);

			echo 1;
		} else {
			echo 0;
		}
	}
	
	public function getByName()
	{
		$name			= $this->input->post('name');
		$this->load->model('Delivery_order_model');
		$data	= $this->Delivery_order_model->getByName("DO-DSE-" . $name);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getUnconfirmedDeliveryOrder()
	{
		$page = $this->input->get('page');
		$offset = ($page - 1)* 10;

		$this->load->model('Delivery_order_model');
		$items = $this->Delivery_order_model->showUnconfirmedDeliveryOrder($offset);

		$this->load->model('Customer_model');
		$itemsArray = [];
		foreach($items as $item){
			$itemArray = (array) $item;
			$customerId = $item->customer_id;
			$customerArray = $this->Customer_model->getById($customerId);
			$customer = array(
				'id' => $customerArray->id,
				'name' => $customerArray->name,
				'address' => $customerArray->address,
				'rt' => $customerArray->rt,
				'rw' => $customerArray->rw,
				'block' => $customerArray->block,
				'city' => $customerArray->city,
				'pic' => $customerArray->pic_name,
				'postal_code' => $customerArray->postal_code,
				'number' => $customerArray->number
			);
			
			$itemArray['customer'] = $customer;
			array_push($itemsArray, $itemArray);
		}

		$data['items'] = (object) $itemsArray;
		$data['pages'] = max(1, ceil($this->Delivery_order_model->countUnconfirmedDeliveryOrder()/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getUnsentDeliveryOrder()
	{
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 10;

		$this->load->model('Delivery_order_model');
		$items = $this->Delivery_order_model->showUnsentDeliveryOrder($offset);

		$this->load->model('Customer_model');
		$itemsArray = [];
		foreach($items as $item){
			$itemArray = (array) $item;
			$customerId = $item->customer_id;
			$customerArray = $this->Customer_model->getById($customerId);
			$customer = array(
				'id' => $customerArray->id,
				'name' => $customerArray->name,
				'address' => $customerArray->address,
				'rt' => $customerArray->rt,
				'rw' => $customerArray->rw,
				'block' => $customerArray->block,
				'city' => $customerArray->city,
				'pic' => $customerArray->pic_name,
				'postal_code' => $customerArray->postal_code,
				'number' => $customerArray->number
			);
			
			$itemArray['customer'] = $customer;
			array_push($itemsArray, $itemArray);
		}

		$data['items'] = (object) $itemsArray;
		$data['pages'] = max(1, ceil($this->Delivery_order_model->countUnsentDeliveryOrder()/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}
}