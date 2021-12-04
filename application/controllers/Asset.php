<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asset extends CI_Controller {
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
		$this->load->view('accounting/header', $data);
		
		$this->load->view('accounting/Asset/dashboard');
	}

	public function getExistingItems()
	{
		$term		= $this->input->get('term');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 10;

		$this->load->model('Asset_model');
		$data['items']		= $this->Asset_model->getExistingItems($offset, $term);
		$data['pages']		= max(1, ceil($this->Asset_model->countExistingItems($term)/10));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function getItems()
	{
		$term		= $this->input->get('term');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 10;

		$this->load->model('Asset_model');
		$data['items']		= $this->Asset_model->getItems($offset, $term);
		$data['pages']		= max(1, ceil($this->Asset_model->countItems($term)/10));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function classDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		
		$this->load->view('accounting/Asset/classDashboard');
	}
	
	public function insertItem()
	{
		$this->load->model('Asset_model');
		$result = $this->Asset_model->insertItem();

		echo $result;
	}
	
	public function getClassItems()
	{
		$term		= $this->input->get('term');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 10;
		
		$this->load->model('Asset_type_model');
		$data['types']		= $this->Asset_type_model->getItems($offset, $term);
		$data['pages']		= max(1, ceil($this->Asset_type_model->countItems($term)/10));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function getClassById()
	{
		$id		= $this->input->get('id');
		$this->load->model('Asset_type_model');
		$data	= $this->Asset_type_model->getById($id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function updateClassById()
	{
		$id				= $this->input->post('id');
		$name			= $this->input->post('name');
		$description	= $this->input->post('description');
		
		$this->load->model('Asset_type_model');
		$result	= $this->Asset_type_model->updateById($id, $name, $description);
		echo $result;
	}
	
	public function insertClassItem()
	{
		$name			= $this->input->post('name');
		$description	= $this->input->post('description');
		$this->load->model('Asset_type_model');
		$result = $this->Asset_type_model->insertItem($name, $description);

		echo $result;
	}
	
	public function getById()
	{
		$id			= $this->input->get('id');
		
		$this->load->model('Asset_model');
		$data		= $this->Asset_model->getById($id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function deleteClassById()
	{
		$id			= $this->input->post('id');
		$this->load->model('Asset_type_model');
		$result = $this->Asset_type_model->deleteById($id);
		echo $result;
	}

	public function updateById()
	{
		$this->load->model('Asset_model');
		$result = $this->Asset_model->updateById();

		echo $result;
	}

	function setSoldById()
	{
		$id			= $this->input->post('id');
		$date		= $this->input->post('date');
		$this->load->model("Asset_model");
		$result = $this->Asset_model->setSoldById($id, $date);
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
		$this->load->view('accounting/header', $data);
		
		$this->load->view('accounting/Asset/archiveDashboard');
	}

	public function valueDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		$this->load->view('accounting/Asset/valueDashboard');
	}

	public function calculateAsset()
	{
		$date		= $this->input->get('date');
		$this->load->model("Stock_in_model");
		$data['stock'] = (float)$this->Stock_in_model->calculateValue($date);

		$this->load->model("Asset_model");
		$assets = $this->Asset_model->calculateValue($date);
		$assetValue = 0;
		foreach($assets as $asset){
			$soldDate			= $asset->sold_date;
			if($soldDate == null || $soldDate > $date){
				$purchaseDate		= $asset->date;
				$depreciation		= $asset->depreciation_time;
				$value				= $asset->value;
				$residueValue		= $asset->residue_value;
				$yearParameter		= date('Y',strtotime($date));
				$year				= date('Y', strtotime($purchaseDate));

				$monthParameter		= date('m', strtotime($date));
				$month				= date('m', strtotime($purchaseDate));

				$diff				= (($yearParameter - $year) * 12) + ($monthParameter - $month);
				$estimatedValue		= max(0, $value - $diff * ($value - $residueValue) / $depreciation);
				$assetValue += $estimatedValue;
			}

			continue;
		}

		$data['asset'] = $assetValue;

		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
