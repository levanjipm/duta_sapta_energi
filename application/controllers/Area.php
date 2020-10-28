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
		
		$this->load->view('sales/Area/detailDashboard', $data);
	}

	public function getChartItems()
	{
		$areaId			= $this->input->get('area');
		
		$this->load->model("Invoice_model");
		$invoiceObject		= $this->Invoice_model->getAchivementByAreaId($areaId, 0, 6);

		$this->load->model("Customer_target_model");
		$targetObject		= (array)$this->Customer_target_model->getByAreaId($areaId);

		$customerTargetArray		= array();
		foreach($targetObject as $target){
			$dateCreated		= $target->dateCreated;
			$value				= $target->value;

			if(!array_key_exists($target->customer_id, $customerTargetArray)){
				$customerTargetArray[$target->customer_id] = array();
			}

			for($i = 0; $i <= 6; $i++){
				$month		= date("m", strtotime("-" . $i . "month"));
				$year		= date("Y", strtotime("-" . $i . "month"));
				$date		= date("Y-m-d", mktime(0,0,0, $month, 1, $year));

				if($dateCreated <= $date){
					$customerTargetArray[$target->customer_id][$i] = $value;
				}
			}

			next($targetObject);
		}

		foreach($customerTargetArray as $customerId => $targetArray){
			for($i = 0; $i <= 6; $i++){
				if(empty($customerTargetArray[$customerId][$i])){
					$customerTargetArray[$customerId][$i] = 0;
				}
			}
		}

		echo json_encode($customerTargetArray);
	}
}
