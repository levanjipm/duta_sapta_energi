<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brand extends CI_Controller {
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
		$this->load->view('sales/Brand/dashboard');
	}

	public function getItems()
	{
		$term		= $this->input->get('term');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 25;

		$this->load->model('Brand_model');
		$data['brands'] = $this->Brand_model->showItems($offset, $term);
		$item = $this->Brand_model->countItems($term);
		$data['pages'] = max(1, ceil($item / 25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function insertItem()
	{
		$name		= $this->input->post('name');
		$this->load->model("Brand_model");
		$result = $this->Brand_model->insertItem($name);
		echo $result;
	}

	public function deleteItem()
	{
		$id			= $this->input->post('id');
		$this->load->model("Brand_model");
		$result = $this->Brand_model->deleteItem($id);
		echo $result;
	}

	public function editItem()
	{
		$id			= $this->input->post('id');
		$name		= $this->input->post('name');

		$this->load->model("Brand_model");
		$result		= $this->Brand_model->updateItem($id, $name);
		echo $result;
	}

	public function viewDetail($brandId)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);

		$data	= array();
		$this->load->model("Brand_model");
		$data['brand']		= $this->Brand_model->getById($brandId);

		$this->load->view('sales/Brand/detail', $data);
	}

	public function customerBought()
	{
		$brandId			= $this->input->get('id');
		$month				= $this->input->get('month');
		$year				= $this->input->get('year');
		
		$this->load->model("Brand_model");
		$result		= $this->Brand_model->getCustomerBought($brandId, $month, $year);
		echo json_encode($result);
	}

	public function getChartItems()
	{
		$range				= $this->input->get('range');
		$from				= $this->input->get('from');
		$brand				= $this->input->get('brand');

		$this->load->model("Customer_target_model");
		$result		= $this->Customer_target_model->getByBrandId($brand);
		
		$areaTargetResponse		= array();
		$customerTargetResponse = array();

		foreach($result as $item){
			$dateCreated	= $item->dateCreated;
			$value			= $item->value;
			$customerId		= $item->customer_id;

			$area_id		= $item->area_id;
			$area_name		= $item->name;

			if(!array_key_exists($customerId, $customerTargetResponse)){
				$customerTargetResponse[$customerId] = array();
			}

			if(!array_key_exists($area_id, $areaTargetResponse)){
				$areaTargetResponse[$area_id] = array(
					"value" => 0,
					"name" => $area_name
				);
			}

			$areaTargetResponse[$area_id]['value'] += (float)$value;

			array_push($customerTargetResponse[$customerId] , array(
				"value" => $value,
				"dateCreated" => $dateCreated
			));
		}

		$startDate		= date("Y-m-t h:i:s", strtotime("-" . $from . "months", mktime(0, 0, 0, date('m'), 1, date("Y"))));
		$endDate		= date("Y-m-d h:i:s", strtotime("-" . ($from + $range) . "months", mktime(0, 0, 0, date('m'), 1, date("Y"))));

		$startFormatedDate = date_create(date("Y-m-t", strtotime("-" . $from . "months", mktime(0, 0, 0, date('m'), 1, date("Y")))));

		$areaAchivementArray = array();
		$this->load->model("Invoice_model");
		$result		= $this->Invoice_model->getByBrandId($brand, $startDate, $endDate);
		foreach($result as $item){
			$area_id	= $item->id;
			$area_name	= $item->name;
			$month		= $item->month;
			$year		= $item->year;
			$value		= $item->value;

			$date		= date_create(date("Y-m-t", mktime(0, 0, 0, $month, 1, $year)));
			$difference	= date_diff($date, $startFormatedDate);
			if($difference->m < 6){
				if(!array_key_exists($area_id, $areaAchivementArray)){
					$areaAchivementArray[$area_id] = array(
						"name" => $area_name,
						"value" => array_fill(0, 6, array())
					);
				}
				
				$areaAchivementArray[$area_id]["value"][$difference->m]['label'] = date("M Y", strtotime("-" . $difference->m . "month"));
				$areaAchivementArray[$area_id]["value"][$difference->m]['value'] = (float)$value;
			}
		}

		foreach($areaAchivementArray as $area_id => $areaAchivement){
			foreach($areaAchivement["value"] as $difference => $areaItem){
				if($areaItem == array()){
					$areaAchivementArray[$area_id]["value"][$difference]["label"] = date("M Y", strtotime("-" . $difference . "month"));
					$areaAchivementArray[$area_id]["value"][$difference]["value"] = 0;
				}
			}
		}

		$finalResult = $areaAchivementArray;

		echo json_encode($finalResult);
	}
}
