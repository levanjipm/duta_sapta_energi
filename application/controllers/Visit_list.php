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

		$this->load->model("Visit_list_model");
		$data['items']		= $this->Visit_list_model->getCustomerList($mode, $term, $offset);
		$data['pages']		= max(1, ceil($this->Visit_list_model->countCustomerList($mode, $term)/10));

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
}
