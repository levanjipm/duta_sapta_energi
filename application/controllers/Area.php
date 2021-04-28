<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Area extends CI_Controller {
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
		$this->load->view('sales/header', $data);
		$this->load->view('sales/Area/dashboard');
	}

	public function insertItem()
	{
		$this->load->model('Area_model');
		$result = $this->Area_model->insertItem();
		echo $result;
	}
	
	public function updateById()
	{
		$area_id	= $this->input->post('id');
		$area_name	= $this->input->post('name');
		
		$this->load->model('Area_model');
		$result = $this->Area_model->updateById($area_id, $area_name);
		
		echo $result;
	}
	
	public function deleteById()
	{
		$area_id	= $this->input->post('id');
		
		$this->load->model('Area_model');
		$result = $this->Area_model->deleteById($area_id);
		
		echo $result;
	}
	
	public function getItems()
	{
		$page = $this->input->get('page');
		$term = $this->input->get('term');
		$offset = ($page - 1) * 25;
		
		$this->load->model('Area_model');
		$data['areas'] = $this->Area_model->showItems($offset, $term);
		
		$data['pages'] = max(1, ceil($this->Area_model->countItems($term)/10));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getAllItems()
	{
		$this->load->model("Area_model");
		$data		= $this->Area_model->getAllItems();

		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function getItemById()
	{
		$id = $this->input->get('id');
		$this->load->model('Area_model');
		$data = $this->Area_model->getItemById($id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function viewDetail()
	{
		$id			= $this->input->post('id');
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);

		$data			= array();
		$this->load->model("Area_model");
		$data['area']		= $this->Area_model->getItemById($id);

		$this->load->model("Customer_model");
		$data['customers']	= $this->Customer_model->getByAreaId($id);

		$this->load->model("Invoice_model");
		$data['payments'] = array(
			$this->Invoice_model->calculatePaymentsByAreaId(1, $id),
			$this->Invoice_model->calculatePaymentsByAreaId(2, $id),
			$this->Invoice_model->calculatePaymentsByAreaId(3, $id),
			$this->Invoice_model->calculatePaymentsByAreaId(4, $id)
		);
		
		$this->load->model("Brand_model");
		$data['brands']		= $this->Brand_model->getItems();
		$this->load->view('sales/Area/detailDashboard', $data);
	}

	public function getChartItems()
	{
		$areaId			= $this->input->get('area');
		$brand			= $this->input->get('brand');

		$currentYear	= date("Y");
		$currentMonth	= date('m');
		$currentDate	= mktime(0,0,0, $currentMonth, 1, $currentYear);

		$this->load->model("Invoice_model");
		$invoiceObject		= $this->Invoice_model->getAchivementByAreaId($areaId, $brand, 0, 6);
		$invoiceValue		= array();
		foreach($invoiceObject as $invoiceItem){
			$year		= $invoiceItem->year;
			$month		= $invoiceItem->month;
			$label		= date("F Y", mktime(0, 0, 0, $month, 1, $year));
			$date		= mktime(0,0,0,$month, 1, $year);

			$difference	= round(($currentDate - $date) / (60 * 60 * 24 * 30));
			if($difference >= 0 && $difference <= 6){
				$invoiceValue[$difference]	= array(
					"value" => (float)$invoiceItem->value,
					"label" => $label
				);
			}
		}

		for($i = 0; $i <= 6; $i++){
			if(!array_key_exists($i, $invoiceValue)){
				$label = date("F Y", strtotime("-" . $i . "month", mktime(0,0,0,date('m'), 1, date("Y"))));
				$invoiceValue[$i]	= array(
					"value" => 0,
					"label" => $label
				);
			}
		}

		$invoiceValue		= array_reverse($invoiceValue);

		$this->load->model("Customer_target_model");
		$targetObject		= (array)$this->Customer_target_model->getByAreaId($areaId, $brand);
		$customerArray		= array();
		foreach($targetObject as $target){
			$dateCreated		= $target->dateCreated;
			$monthCreated		= date('m', strtotime($dateCreated));
			$yearCreated		= date('Y', strtotime($dateCreated));
			$date				= mktime(0,0,0,$monthCreated, 1, $yearCreated);
			$value				= $target->value;

			$customerId			= $target->customer_id;
			if(!array_key_exists($customerId, $customerArray)){
				$customerArray[$customerId] = array();
			}

			for($i = 0; $i <= 6; $i++){
				$paramMonth			= date('m', strtotime("-" . $i . "month"));
				$paramYear			= date('Y', strtotime("-" . $i . "month"));
				$paramDate			= mktime(0,0,0, $paramMonth, 1, $paramYear);

				$difference			= (int) round(($paramDate - $date) / (60 * 60 * 24 * 30));
				if($difference >= 0){
					$customerArray[$customerId][$i] = $value;
				} else if($difference < 0) {
					$customerArray[$customerId][$i] = 0;
				}
			}			
		}

		$targetArray		= array_fill(0, 7, 0);
		foreach($customerArray as $customerItem){
			for($i = 0; $i < 6; $i++){
				$label 				= date("F Y", strtotime("-" . $i . "month", mktime(0,0,0,date('m'), 1, date("Y"))));
				$currentValue		= $targetArray[$i]['value'];
				$value				= $customerItem[$i];
				$nextValue			= $currentValue + $value;
				
				$targetArray[$i]	= array(
					"value" => $nextValue,
					"label" => $label
				);
			}			
		}

		$targetValue		= array_reverse($targetArray);

		$data['target']		= $targetValue;
		$data['value']		= $invoiceValue;

		echo json_encode($data);
	}
}
