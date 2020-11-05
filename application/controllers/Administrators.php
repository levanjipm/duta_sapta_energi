<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administrators extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->has_userdata('user_id') == FALSE){
			redirect(site_url('login'));
		}

		$this->load->model("User_model");
		$user		= $this->User_model->getById($this->session->userdata('user_id'));
		if($user->access_level < 5){
			redirect(site_url("Welcome"));
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
		$this->load->view('administrator/header', $data);
		$this->load->view('administrator/dashboard', $data);
	}

	public function deleteInvoiceById()
	{
		$id				= $this->input->post('id');
		$this->load->model("Receivable_model");
		$receivable = $this->Receivable_model->getByInvoiceId($id);
		if(count($receivable) > 0){
			echo 0;
		} else {
			$this->load->model("Invoice_model");
			$invoice			= $this->Invoice_model->getById($id);
			if($invoice->customer_id == NULL && $invoice->opponent_id == NULL){
				$this->load->model("Delivery_order_model");
				$deliveryOrders		= $this->Delivery_order_model->getByInvoiceId($id);
				$deleteDeliveryOrderResult = $this->Delivery_order_model->unassignInvoiceById($id);
				if($deleteDeliveryOrderResult > 0){
					$result = $this->Invoice_model->permanentDeleteInvoiceById($id);
					if($result > 0){
						echo 1;
					} else {
						foreach($deliveryOrders as $deliveryOrder){
							$deliveryOrderId = $deliveryOrder->id;
							$this->Delivery_order_model->updateInvoiceId($deliveryOrderId, $id);

							next($deliveryOrder);
						}

						echo 0;
					}
				} else {
					echo 0;
				}
			} else {
				$result = $this->Invoice_model->permanentDeleteInvoiceById($id);
				if($result > 0){
					echo 1;
				} else {
					echo 0;
				}
			}
		}
	}

	public function deleteDeliveryOrderById()
	{
		$deliveryOrderId			= $this->input->post('id');
		$this->load->model("Stock_out_model");
		$result = $this->Stock_out_model->deleteByCodeDeliveryOrderId($deliveryOrderId);
		if($result != NULL){
			$stockInBatch = array();
			$this->load->model("Stock_in_model");
			foreach($result as $item){
				$stockInArray = array(
					"id"		=> $item->in_id,
					"quantity"	=> $item->quantity
				);
				array_push($stockInBatch, $stockInArray);
				next($result);
			}

			$this->Stock_in_model->updateStockByStockOutBatch($stockInBatch);

			$this->load->model("Sales_order_detail_model");
			$this->Sales_order_detail_model->updateByCodeDeliveryOrderIdCancel($deliveryOrderId);

			$this->load->model("Delivery_order_model");
			$deleteResult = $this->Delivery_order_model->deleteById($deliveryOrderId);
			echo $deleteResult;
		} else {
			echo 0;
		}
	}

	public function deleteDebtById()
	{
		$id			= $this->input->post('id');
		$type		= $this->input->post('type');

		if($type == "regular"){
			$this->load->model("Debt_model");
			$result		= $this->Debt_model->deleteDebtById($id);
			if($result == 1){
				$this->load->model("Good_receipt_model");
				$this->Good_receipt_model->unsetInvoiceByInvoiceId($id);
				echo 1;
			} else {
				echo 0;
			}
		} else if($type == "blank"){
		}		
	}

	public function deleteGoodReceiptById()
	{
		$id			= $this->input->post('id');
		$this->load->model("Stock_in_model");
		$result = $this->Stock_in_model->checkItemsByCodeGoodReceiptId($id);
		if($result){
			$this->load->model("Good_receipt_model");
			$goodReceipt		= $this->Good_receipt_model->showById($id);
			$invoiceId			= $goodReceipt->invoice_id;
			if($invoiceId == NULL){
				$this->Stock_in_model->deleteByCodeGoodReceiptId($id);
				$this->Good_receipt_model->deleteById($id);

				$this->load->model("Purchase_order_detail_model");
				$this->Purchase_order_detail_model->updateByCodeGoodReceiptIdCancel($id);

				echo 1;
			} else {
				echo 0;
			}			
		} else {
			echo 0;
		}
	}
}
