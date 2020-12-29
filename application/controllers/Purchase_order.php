<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_order extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->has_userdata('user_id') == FALSE){
			redirect(site_url('login'));
		}
	}
	
	public function confirmDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('purchasing/header', $data);
		$this->load->view('Purchasing/PurchaseOrder/confirmDashboard');
	}
	
	public function createDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('purchasing/header', $data);
		
		$this->load->model('Purchase_order_model');
		$guid	= $this->Purchase_order_model->create_guid();
		
		$data['guid'] = $guid;
		$this->load->view('Purchasing/PurchaseOrder/createDashboard', $data);
	}
	
	public function insertItem()
	{
		$this->load->model('Purchase_order_model');
		$purchase_order_id = $this->Purchase_order_model->insertItem();
		
		if($purchase_order_id != null){
			$this->load->model('Purchase_order_detail_model');
			$this->Purchase_order_detail_model->insert_from_post($purchase_order_id);
		}
		
		redirect(site_url('Purchase_order/createDashboard'));
	}
	
	public function getById($id)
	{
		$this->load->model('Purchase_order_model');
		$purchaseOrder	= $this->Purchase_order_model->showById($id);
		$data['general'] = $purchaseOrder;
		$supplierId = $purchaseOrder->supplier_id;

		$this->load->model("Supplier_model");
		$data['supplier'] = $this->Supplier_model->getById($supplierId);
		
		$this->load->model("Good_receipt_model");
		$data['goodReceipt']	= $this->Good_receipt_model->getReceivedByPurchaseOrderId($id);
		
		$this->load->model('Purchase_order_detail_model');
		$data['detail']		= $this->Purchase_order_detail_model->getByCodeId($id);
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function confirmById()
	{
		$id		= $this->input->post('id');
		$this->load->model('Purchase_order_model');
		$result = $this->Purchase_order_model->confirmById($id);
		
		echo $result;
	}
	
	public function deleteById()
	{
		$id		= $this->input->post('id');
		$this->load->model('Purchase_order_model');
		$result = $this->Purchase_order_model->deleteById($id);
		
		echo $result;
	}
	
	public function print($purchase_order_id)
	{
		$this->load->model('Purchase_order_model');
		$purchaseOrder	= $this->Purchase_order_model->showById($purchase_order_id);
		$data['general']	= $purchaseOrder;

		$this->load->model("Supplier_model");
		$supplierId			= $purchaseOrder->supplier_id;
		$data['supplier']	= $this->Supplier_model->getById($supplierId);
		
		$this->load->model('Purchase_order_detail_model');
		$data['detail']		= $this->Purchase_order_detail_model->getByCodeId($purchase_order_id);
		
		$this->load->view('head');
		$this->load->view('purchasing/PurchaseOrder/print', $data);
	}
	
	public function archiveDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('purchasing/header', $data);
		
		$this->load->view('purchasing/PurchaseOrder/archiveDashboard');
	}
	
	public function showArchive()
	{
		$page			= $this->input->get('page');
		$term			= $this->input->get('term');
		$offset			= ($page - 1) * 10;
		$year			= $this->input->get('year');
		$month			= $this->input->get('month');
		
		$this->load->model('Purchase_order_model');
		$data['purchase_orders'] 	= $this->Purchase_order_model->getItems($year, $month, $offset, $term);
		$data['pages']				= max(1, ceil($this->Purchase_order_model->count_items($year, $month, $term)/10));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function view_by_id()
	{
		$id				= $this->input->get('id');
		$this->load->model('Purchase_order_model');
		$data['general']	= $this->Purchase_order_model->show_by_id($id);
		
		$this->load->model('Purchase_order_detail_model');
		$data['items']		= $this->Purchase_order_detail_model->show_by_purchase_order_id($id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function pending()
	{		
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('purchasing/header', $data);
		
		$this->load->model('Purchase_order_detail_model');
		$data['suppliers']	= $this->Purchase_order_detail_model->show_supplier_for_incomplete_purchase_orders();
		
		$this->load->view('purchasing/PurchaseOrder/pendingDashboard', $data);
	}

	public function getPendingPurchaseOrder()
	{
		$supplier_id = $this->input->get('supplier_id');
		$page = $this->input->get('page');

		$offset = ($page - 1) * 10;
		$this->load->model('Purchase_order_model');
		$data['items'] = $this->Purchase_order_model->getIncompletePurchaseOrder($offset, $supplier_id);

		$data['pages'] = max(1, ceil($this->Purchase_order_model->countIncompletePurchaseOrder($supplier_id)/10));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getAllPendingPurchaseOrder()
	{
		$supplier_id = $this->input->get('supplier_id');
		$this->load->model('Purchase_order_model');
		$data = $this->Purchase_order_model->getAllIncompletePurchaseOrder($supplier_id);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getAllPendingSupplier()
	{
		$this->load->model('Purchase_order_model');
		$data = $this->Purchase_order_model->getAllIncompletePurchaseOrderSupplier();

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getUnconfirmedPurchaseOrders()
	{
		$this->load->model('Purchase_order_model');
		$this->load->model('Supplier_model');

		$data = $this->Purchase_order_model->getUnconfirmedPurchaseOrders();
		$result = array();
		$items = (array) $data;
		foreach($items as $item){
			$array = (array) $item;
			$supplierId = $item->supplier_id;
			$supplier = $this->Supplier_model->getById($supplierId);

			$array['supplier'] = $supplier;

			array_push($result, $array);
		}

		header('Content-Type: application/json');
		echo json_encode($result);
	}

	public function createFromDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('purchasing/header', $data);
		$data = array();

		$data = $_POST;
		$this->load->model("Purchase_order_model");
		$data['guid']			= $this->Purchase_order_model->create_guid();

		$this->load->view('purchasing/PurchaseOrder/createFromDashboard', $data);
	}
	
	public function closeDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		if($data['user_login']->access_level > 3){
			$this->load->model('Authorization_model');
			$data['departments']	= $this->Authorization_model->getByUserId($user_id);
			
			$this->load->view('head');
			$this->load->view('administrator/header', $data);
			$this->load->view('administrator/PurchaseOrder/closeDashboard');
		} else {
			redirect(site_url("Welcome"));
		}
	}

	public function editDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		if($data['user_login']->access_level > 3){
			$this->load->model('Authorization_model');
			$data['departments']	= $this->Authorization_model->getByUserId($user_id);
			
			$this->load->view('head');
			$this->load->view('administrator/header', $data);
			$this->load->view('administrator/PurchaseOrder/editDashboard');
		} else {
			redirect(site_url("Welcome"));
		}
	}

	public function editForm()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		if($data['user_login']->access_level > 3){
			$this->load->model('Authorization_model');
			$data['departments']	= $this->Authorization_model->getByUserId($user_id);
			
			$this->load->view('head');
			$this->load->view('administrator/header', $data);

			$data			= array();
			$id				= $this->input->post('id');
			$this->load->model("Purchase_order_model");
			$data['general']	= $this->Purchase_order_model->showById($id);

			if($data['general'] == null){
				redirect("Welcome");
			} else {
				$supplierId			= $data['general']->supplier_id;
				$this->load->model("Supplier_model");
				$data['supplier']		= $this->Supplier_model->getById($supplierId);
				
				$this->load->model("Purchase_order_detail_model");
				$data['items']			= $this->Purchase_order_detail_model->getByCodeId($id);
				$this->load->view('administrator/PurchaseOrder/editForm', $data);
			}
			
		} else {
			redirect(site_url("Welcome"));
		}	
	}

	public function getPendingItems()
	{
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 10;

		$this->load->model("Purchase_order_model");

		$this->load->model("Supplier_model");

		$resultArray = array();
		$purchaseOrders = $this->Purchase_order_model->getIncompletePurchaseOrder($offset);
		foreach($purchaseOrders as $purchaseOrder){
			$supplierId = $purchaseOrder->supplier_id;
			$supplier = $this->Supplier_model->getById($supplierId);
			$batch = (array)$purchaseOrder;
			$batch['supplier'] = (array)$supplier;
			array_push($resultArray, $batch);
		}

		$data['items'] = $resultArray;
		$data['pages'] = max(1, ceil($this->Purchase_order_model->countIncompletePurchaseOrder()/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function closePurchaseOrder()
	{
		$id			= $this->input->post('id');
		$this->load->model("Purchase_order_model");

		$result = $this->Purchase_order_model->closeById($id);
		if($result == 1){
			$this->load->model("Purchase_order_detail_model");
			$this->Purchase_order_detail_model->closeByCodeId($id);
			echo 1;
		} else {
			echo 0;
		}
	}

	public function viewPurchaseOrderDetail($purchaseOrderId)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('purchasing/header', $data);

		$data		= array();

		$this->load->model("Purchase_order_model");
		$purchaseOrder		= $this->Purchase_order_model->showById($purchaseOrderId);
		$data['general'] = $purchaseOrder;
		$supplierId = $purchaseOrder->supplier_id;

		$this->load->model("Supplier_model");
		$data['supplier'] = $this->Supplier_model->getById($supplierId);
		
		$this->load->model("Good_receipt_model");
		$data['goodReceipt']	= $this->Good_receipt_model->getReceivedByPurchaseOrderId($purchaseOrderId);
		
		$this->load->model('Purchase_order_detail_model');
		$data['detail']		= $this->Purchase_order_detail_model->getByCodeId($purchaseOrderId);

		$this->load->view('purchasing/PurchaseOrder/purchaseOrderDetail', $data);
	}

	public function updatePurchaseOrder()
	{
		//Update general information//
		$status			= $this->input->post('status');
		if($status == 2 || $status == 3){
			$sendDate		= NULL;
			if($status == 2){
				$inputStatus	= "URGENT";
			} else {
				$inputStatus	= NULL;
			}
		} else {
			$inputStatus		= NULL;
			$sendDate	= $this->input->post('request_date');
		}

		$promoCode		= $this->input->post('promo_code');
		if(empty($this->input->post('dropship'))){
			$dropshipAddress		= NULL;
			$dropshipCity			= NULL;
			$dropshipContactPerson	= NULL;
			$dropshipContact		= NULL;
		} else {
			$dropshipAddress		= $this->input->post('dropship_address');
			$dropshipCity			= $this->input->post('dropship_city');
			$dropshipContactPerson	= $this->input->post('dropship_contact_person');
			$dropshipContact		= $this->input->post('dropship_contact_person');
		}

		$note						= $this->input->post('note');
		$id							= $this->input->post('id');

		$this->load->model("Purchase_order_model");
		$this->Purchase_order_model->updateById($id, $inputStatus, $sendDate, $promoCode, $dropshipAddress, $dropshipCity, $dropshipContactPerson, $dropshipContact, $note);

		$this->load->model("Purchase_order_detail_model");
		if(!empty($this->input->post('deletedItems'))){
			$this->Purchase_order_detail_model->deletePurchaseOrderItem($this->input->post('deletedItems'));
		}
		
		$discountArray		= $this->input->post('discount');
		$pricelistArray		= $this->input->post('pricelist');
		$quantityArray		= $this->input->post('quantity');
		if(!empty($this->input->post('deletedItems'))){
			$batch		= array();
			foreach($discountArray as $key => $discount){
				if(!in_array($key, $this->input->post('deletedItems'))){
					$batch[]		= array(
						"id" => $key,
						"discount" => $discountArray[$key],
						"pricelist" => $pricelistArray[$key],
						"quantity" => $quantityArray[$key]
					);
				}
			}
		} else {
			$batch		= array();
			foreach($discountArray as $key => $discount){
				$batch[]		= array(
					"id" => $key,
					"discount" => $discountArray[$key],
					"pricelist" => $pricelistArray[$key],
					"quantity" => $quantityArray[$key]
				);
			}
		}

		$this->Purchase_order_detail_model->updateItem($batch);

		if(!empty($this->input->post('extraPriceList'))){
			$extraBatch				= array();
			$pricelistArray			= $this->input->post('extraPriceList');
			$discountArray			= $this->input->post('extraDiscount');
			$quantityArray			= $this->input->post('extraQuantity');

			foreach($pricelistArray as $itemId => $pricelist)
			{
				$discount			= $discountArray[$itemId];
				$netprice			= (100 - $discount) * $pricelist / 100;
				$extraArray			= array(
					"id" => "",
					"price_list" => $pricelist,
					"net_price" => $netprice,
					"quantity" => $quantityArray[$itemId],
					"item_id" => $itemId,
					"received" => 0,
					"status" => 0,
					"code_purchase_order_id" => $id
				);

				array_push($extraBatch, $extraArray);
				next($pricelistArray);
			}

			$this->Purchase_order_detail_model->insertItemBatch($extraBatch);
		}

		if(!empty($this->input->post('extraBonusPriceList'))){
			$extraBatch				= array();
			$quantityArray			= $this->input->post('extraBonusQuantity');

			foreach($quantityArray as $itemId => $quantity)
			{
				$discount			= $discountArray[$itemId];
				$netprice			= (100 - $discount) * $pricelist / 100;
				$extraArray			= array(
					"id" => "",
					"price_list" => 1,
					"net_price" => 0,
					"quantity" => $quantity,
					"item_id" => $itemId,
					"received" => 0,
					"status" => 0,
					"code_purchase_order_id" => $id
				);

				array_push($extraBatch, $extraArray);
				next($quantityArray);
			}

			$this->Purchase_order_detail_model->insertItemBatch($extraBatch);
		}
	}

	public function getPendingPurchaseOrderBySupplierId()
	{
		$supplierId			= $this->input->get('supplierId');
		$this->load->model("Purchase_order_model");
		$data		= $this->Purchase_order_model->getPendingPurchaseOrderBySupplierId($supplierId);

		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
