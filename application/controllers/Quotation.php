<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation extends CI_Controller {
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
		$this->load->view('sales/Quotation/createDashboard');
	}

	public function insertItem()
	{
		$this->load->model("Quotation_model");
		$quotationId = $this->Quotation_model->insertItem();
		if($quotationId != NULL){
			$this->load->model("Quotation_detail_model");
			$this->Quotation_detail_model->insertItem($quotationId);
		}

		redirect(site_url("Quotation/createDashboard"));
	}

	public function confirmDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);

		if($data['user_login']->access_level > 2){
			$this->load->model('Authorization_model');
			$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
			$this->load->view('head');
			$this->load->view('sales/header', $data);
			$this->load->view('sales/Quotation/confirmDashboard');
		} else {
			redirect("Sales");
		}		
	}

	public function getItems()
	{
		$page		= $this->input->get('page');
		$term		= $this->input->get('term');
		$offset		= ($page -1 ) * 10;
		$this->load->model("Quotation_model");
		$data['items'] = $this->Quotation_model->getUnconfirmedItems($offset, $term);
		$data['pages'] = max(1, ceil($this->Quotation_model->countUnconfirmedItems($term)/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getById()
	{
		$id			= $this->input->get('id');
		$this->load->model("Quotation_model");
		$quotation = $this->Quotation_model->getById($id);
		$data['general'] = $quotation;

		$this->load->model("Quotation_detail_model");
		$data['items'] = $this->Quotation_detail_model->getByCodeId($id);

		$customer_id = $quotation->customer_id;
		$this->load->model("Customer_model");
		$data['customer'] = $this->Customer_model->getById($customer_id);
		
		header('Content-Type: application/json');
		echo json_encode($data);

	}

	public function confirmById()
	{
		$id			= $this->input->post('id');
		$this->load->model("Quotation_model");
		$result			= $this->Quotation_model->updateById(1, $id);

		echo $result;
	}

	public function deleteById()
	{
		$id				= $this->input->post('id');
		$this->load->model("Quotation_model");
		$result			= $this->Quotation_model->updateById(0, $id);

		echo $result;
	}

	public function print($id)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);

		if($data['user_login']->access_level > 2){
			$this->load->model('Authorization_model');
			$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
			$this->load->view('head');
			$this->load->view('sales/header', $data);

			$data = array();
			$this->load->model("Quotation_model");
			$quotation = $this->Quotation_model->getById($id);
			$data['general'] = $quotation;

			$this->load->model("Quotation_detail_model");
			$data['items'] = $this->Quotation_detail_model->getByCodeId($id);

			$customer_id = $quotation->customer_id;
			$this->load->model("Customer_model");
			$data['customer'] = $this->Customer_model->getById($customer_id);

			$this->load->view('sales/Quotation/print', $data);
		} else {
			redirect("Sales");
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
		$this->load->view('sales/quotation/archiveDashboard');
	}

	public function archiveView()
	{
		$year		= $this->input->get('year');
		$month		= $this->input->get('month');
		$page		= $this->input->get('page');
		$offset		= ($page - 1)* 10;

		$this->load->model("Quotation_model");
		$data['items'] = $this->Quotation_model->getArchiveItems($year, $month, $offset);
		$data['pages'] = max(1, ceil($this->Quotation_model->countArchiveItems($year, $month)/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
?>
