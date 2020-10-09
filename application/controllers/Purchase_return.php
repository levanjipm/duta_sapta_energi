<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_return extends CI_Controller {
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
		$this->load->view('purchasing/Return/createDashboard');
	}

	public function insertItem()
	{
		$this->load->model('Purchase_return_model');
		$result = $this->Purchase_return_model->insertItem();
		if($result != null){
			$this->load->model('Purchase_return_detail_model');
			$this->Purchase_return_detail_model->insertItem($result);
			echo 1;
		} else {
			echo 0;
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
		$this->load->view('purchasing/Return/confirmDashboard');
	}

	public function getUnconfirmedItems()
	{
		$page			= $this->input->get("page");
		$term			= $this->input->get("term");
		$offset			= ($page - 1) * 10;
		$this->load->model("Purchase_return_model");
		$data['items'] = $this->Purchase_return_model->getUnconfirmedItems($offset, $term);
		$data['pages'] = max(1, ceil($this->Purchase_return_model->countUnconfirmedItems($term)/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function confirmById()
	{
		$id			= $this->input->post('id');
		$this->load->model("Purchase_return_model");
		$result		= $this->Purchase_return_model->updateById(1, $id);
		echo $result;
	}

	public function deleteById()
	{
		$id		= $this->input->post('id');
		$this->load->model("Purchase_return_model");
		$result		= $this->Purchase_return_model->updateById(0, $id);
		echo $result;
	}

	public function getById()
	{
		$id		= $this->input->get('id');
		$this->load->model("Purchase_return_model");
		$purchaseReturn = $this->Purchase_return_model->getById($id);

		$data['general'] = $purchaseReturn;
		$supplierId = $purchaseReturn->supplier_id;
		$this->load->model("Supplier_model");
		
		$data['supplier'] = $this->Supplier_model->getById($supplierId);
		
		$this->load->model("Purchase_return_detail_model");
		$data['items'] = $this->Purchase_return_detail_model->getByCodeId($id);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function sendDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('inventory/header', $data);
		$this->load->view('inventory/Return/purchaseReturnDashboard');
	}

	public function loadForm($event)
	{
		if($event == "create")
		{
			$this->load->view('inventory/Return/purchaseReturnCreate');
		} else {
			$this->load->view('inventory/Return/purchaseReturnConfirm');
		}
	}

	public function getIncompletedReturn()
	{
		$page	= $this->input->get('page');
		$term	= $this->input->get('term');
		$offset	= ($page - 1) * 10;

		$this->load->model("Supplier_model");

		$batchArray = array();

		$this->load->model("Purchase_return_model");
		$result		= $this->Purchase_return_model->getIncompletedReturn($offset, $term);
		foreach($result as $item){
			$supplierId = $item->supplier_id;
			$supplier = (array) $this->Supplier_model->getById($supplierId);
			$array = (array) $item;

			$array['supplier'] = $supplier;

			$batchArray[] = $array;
		}

		$data['items'] = (object)$batchArray;
		$data['pages'] = max(1, ceil($this->Purchase_return_model->countIncompletedReturn($term)/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function sendItem()
	{
		$date			= $this->input->post('date');
		$document		= $this->input->post('document');
		$itemArray		= $this->input->post('sentQuantity');

		$this->load->model('Purchase_return_detail_model');
		$result		= $this->Purchase_return_detail_model->quantityCheck($itemArray);
		if($result){
			$this->load->model("Purchase_return_sent_model");
			$codePurchaseReturnSentId = $this->Purchase_return_sent_model->insertItem($date, $document);
			if($codePurchaseReturnSentId != null){
				$this->load->model("Purchase_return_sent_detail_model");
				$this->Purchase_return_sent_detail_model->insertItem($itemArray, $codePurchaseReturnSentId);

				$this->Purchase_return_detail_model->updateItems($itemArray);
				echo 1;
			} else {
				echo 0;
			}
		} else {
			echo 0;
		}
	}

	public function getUnconfirmedSentPurchaseReturn()
	{
		$page			= $this->input->get('page');
		$term			= $this->input->get('term');
		$offset			= ($page - 1) * 10;

		$this->load->model("Supplier_model");

		$batchArray = array();

		$this->load->model("Purchase_return_sent_model");
		$result		= $this->Purchase_return_sent_model->getUnconfirmedItems($offset, $term);
		foreach($result as $item){
			$supplierId = $item->supplier_id;
			$supplier = (array) $this->Supplier_model->getById($supplierId);
			$array = (array) $item;

			$array['supplier'] = $supplier;

			$batchArray[] = $array;
		}

		$data['items'] = (object) $batchArray;
		$data['pages'] = max(1, ceil($this->Purchase_return_sent_model->countUnconfirmedItems()/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getSentById()
	{
		$id		= $this->input->get('id');
		$this->load->model("Purchase_return_sent_model");
		$data['general'] = $this->Purchase_return_sent_model->getById($id);
		$supplierId = $data['general']->supplier_id;

		$this->load->model("Supplier_model");
		$data['supplier'] = $this->Supplier_model->getById($supplierId);

		$this->load->model("Purchase_return_sent_detail_model");
		$data['items'] = $this->Purchase_return_sent_detail_model->getByCodeId($id);

		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function confirmSentById()
	{
		$id			= $this->input->post('id');
		$this->load->model("Purchase_return_sent_model");
		$result = $this->Purchase_return_sent_model->updateById(1, $id);
		if($result == 1){
			$this->load->model("Purchase_return_detail_model");
			$dataArray = $this->Purchase_return_detail_model->getByCodeId($id);
			$stockItemArray = array();
			foreach($dataArray as $data){
				$stockItemArray[] = array(
					"item_id" => $data->item_id,
					"quantity" => $data->quantity,
					"id" => $data->id
				);

				next($dataArray);
			}

			$this->load->model("Stock_out_model");
			$this->Stock_out_model->insertFromPurchaseReturn($stockItemArray);
		}
		echo $result;
	}

	public function cancelSentById()
	{
		$id			= $this->input->post('id');
		$this->load->model("Purchase_return_sent_model");
		$result = $this->Purchase_return_sent_model->updateById(0, $id);
		if($result == 1){
			$this->load->model("Purchase_return_sent_detail_model");
			$items = (array)$this->Purchase_return_sent_detail_model->getByCodeId($id);
			$batch = array();
			foreach($items as $item){
				$quantity			= $item->quantity;
				$purchaseReturnId	= $item->purchaseReturnId;
				$batch[$purchaseReturnId] = $quantity;
			}

			$this->load->model("Purchase_return_detail_model");
			$this->Purchase_return_detail_model->updateQuantityByDelete($batch);

			echo 1;
		} else {
			echo 0;
		}
	}

	public function getUnassignedPurchaseReturn()
	{
		$page			= $this->input->get('page');
		$term			= $this->input->get('term');
		$offset			= ($page - 1) * 10;

		$this->load->model("Purchase_return_sent_model");
		$this->load->model("Supplier_model");

		$batch = array();

		$purchaseReturnItems	= $this->Purchase_return_sent_model->getUnassignedPurchaseReturn($offset, $term);
		foreach($purchaseReturnItems as $purchaseReturnItem){
			$supplierId = $purchaseReturnItem->supplier_id;
			$supplier = $this->Supplier_model->getById($supplierId);
			$batchItem = (array)$purchaseReturnItem;
			$batchItem['supplier'] = (array)$supplier;

			$batch[] = $batchItem;
		}

		$data['items']		= (object)$batch;
		$data['pages']		= $this->Purchase_return_sent_model->countUnassignedPurchaseReturn($term);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function updateBankDateById()
	{
		$id			= $this->input->post('id');
		$date		= $this->input->post('date');
		$account	= $this->input->post('account');

		$this->load->model("Purchase_return_sent_model");
		$purchaseReturn = $this->Purchase_return_sent_model->getById($id);
		$supplierId		= $purchaseReturn->supplier_id;
		$value			= $purchaseReturn->value;
		$type			= "supplier";

		$this->load->model("Bank_model");
		$bankId = $this->Bank_model->insertItem($date, $value, 2, $type, $supplierId, $account, NULL, 0, 0);
		$this->Bank_model->insertItem($date, $value, 1, $type, $supplierId, $account, $bankId, 1, 0);

		$result = $this->Purchase_return_sent_model->updateBankById($bankId, $id);
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
		$this->load->view('purchasing/header', $data);
		$data = array();

		$this->load->model("Purchase_return_model");
		$data['years'] = $this->Purchase_return_model->getYears();

		$this->load->view('purchasing/Return/archiveDashboard', $data);
	}

	public function getItems()
	{
		$month		= $this->input->get('month');
		$year		= $this->input->get('year');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 10;

		$this->load->model("Purchase_return_model");
		$data['items'] = $this->Purchase_return_model->getItems($month, $year, $offset);
		$data['pages'] = max(1, ceil($this->Purchase_return_model->countItems($month, $year)/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
?>
