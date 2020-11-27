<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visit_list extends CI_Controller {
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
		$this->load->view('sales/VisitList/createDashboard');
	}

	public function createForm()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);

		$data['date'] = $this->input->get('date');
		$sales = $this->input->get('sales');
		$data['sales'] = $this->User_model->getById($sales);
		$this->load->view('sales/VisitList/createForm', $data);
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
		$this->load->view('sales/VisitList/confirmDashboard');
	}

	public function reportDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		$this->load->view('sales/VisitList/reportDashboard');
	}

	public function getRecommendedList()
	{
		$page			= $this->input->get('page');
		$offset			= ($page - 1) * 10;
		$this->load->model("Customer_model");
		$this->Customer_model->getVisitRecommendedList($offset);
	}

	public function getCustomerVisitList()
	{
		$mode				= $this->input->get('mode');
		$page				= $this->input->get('page');
		$offset				= ($page - 1) * 25;
		$term				= $this->input->get("term");
		$includedAreas		= $this->input->get('areas');
		$sales				= $this->input->get('sales');
		$date				= $this->input->get('date');

		if(empty($this->input->get('areas'))){
			$includedAreas	= array();
		}

		$this->load->model("Visit_list_model");
		$data['items']		= $this->Visit_list_model->getCustomerList($sales, $mode, $includedAreas, $term, $offset, $date);
		$data['pages']		= max(1, ceil($this->Visit_list_model->countCustomerList($sales, $mode, $includedAreas, $term, $date)/25));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function insertItem()
	{
		$customerArray		= $this->input->post('customer');
		$date				= $this->input->post('date');
		$sales				= $this->input->post('sales');

		$this->load->model("Visit_list_model");
		$visitListId		= $this->Visit_list_model->insertItem($date, $sales);
		if($visitListId != NULL){
			$this->load->model("Visit_list_detail_model");
			$this->Visit_list_detail_model->insertItem($customerArray, $visitListId);
			echo 1;
		} else {
			echo 0;
		}
	}

	public function getUnconfirmedItems()
	{
		$page			= $this->input->get('page');
		$offset			= ($page - 1) * 10;

		$this->load->model("Visit_list_model");
		$data['items']		= $this->Visit_list_model->getUnconfirmedItems($offset);
		$data['pages']		= max(1, ceil($this->Visit_list_model->countUnconfirmedItems()/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getUnreportedItems()
	{
		$page			= $this->input->get('page');
		$offset			= ($page - 1) * 10;

		$this->load->model("Visit_list_model");
		$data['items']		= $this->Visit_list_model->getUnreportedItems($offset);
		$data['pages']		= max(1, ceil($this->Visit_list_model->countUnreportedItems()/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getById()
	{
		$id			= $this->input->get('id');
		$this->load->model("Visit_list_model");
		$data['general']		= $this->Visit_list_model->getByid($id);

		$this->load->model("Visit_list_detail_model");
		$data['items']			= $this->Visit_list_detail_model->getByCodeId($id);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function confirmById()
	{
		$id			= $this->input->get('id');
		$this->load->model("Visit_list_model");
		$result		= $this->Visit_list_model->updateById($id, 1);
		echo $result;
	}

	public function deleteById()
	{
		$id			= $this->input->get('id');
		$this->load->model("Visit_list_model");
		$result		= $this->Visit_list_model->updateById($id, 0);
		echo $result;
	}

	public function print($id)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);

		$this->load->model("Visit_list_model");
		$data['general']	= $this->Visit_list_model->getById($id);

		$this->load->model("Visit_list_detail_model");
		$data['items']		= $this->Visit_list_detail_model->getByCodeId($id);
		$this->load->view('sales/VisitList/print', $data);
	}

	public function submitReport()
	{
		$this->load->model("Visit_list_model");
		$id			= $this->input->post('id');
		$result		= $this->Visit_list_model->updateReport($id);
		if($result == 1){
			$this->load->model("Visit_list_detail_model");		
			$this->Visit_list_detail_model->updateReport();

			echo 1;
		} else {
			echo 0;
		}		
	}

	public function archiveDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		$this->load->view('sales/VisitList/archiveDashboard');
	}

	public function getItems()
	{
		$page			= $this->input->get('page');
		$month			= $this->input->get('month');
		$year			= $this->input->get('year');
		$offset			= ($page - 1) * 10;

		$this->load->model("Visit_list_model");
		$data['items']		= $this->Visit_list_model->getItems($offset, $month, $year);
		$data['pages']		= max(1, ceil($this->Visit_list_model->countItems($month, $year)/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function recapDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);

		$this->load->model("Visit_list_model");
		$data['sales']			= $this->Visit_list_model->getUsers();
		
		$this->load->model("Customer_model");
		$data['customers']		= $this->Customer_model->getAllItemsByArea();

		$this->load->view('sales/VisitList/recapDashboard', $data);
	}

	public function getRecap()
	{
		$year			= $this->input->get('year');
		$month			= $this->input->get('month');
		$sales			= $this->input->get('sales');
		
		$this->load->model("Visit_list_detail_model");
		$data			= $this->Visit_list_detail_model->getRecap($year, $month, $sales);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function cancelDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('administrator/header', $data);
		if($data['user_login']->access_level > 3)
		{
			$this->load->view('administrator/VisitList/cancelDashboard');
		} else {
			redirect(site_url());
		}
	}

	public function cancelById()
	{
		$id			= $this->input->get('id');
		$this->load->model("Visit_list_model");
		$result		= $this->Visit_list_model->cancelById($id);
		echo $result;
	}
}
