<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promotion extends CI_Controller {
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
		$this->load->view('sales/promotion/createDashboard');
	}

	public function insertItem()
	{
		$this->load->model("Promotion_model");
		$result		= $this->Promotion_model->insertItem();
		redirect(site_url('Promotion/createDashboard'));
	}

	public function confirmDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		if($data['user_login']->access_level > 2){
			$this->load->view('head');
			$this->load->view('sales/header', $data);
			$this->load->view('sales/Promotion/confirmDashboard');
		} else {
			redirect(site_url('Sales'));
		}
	}

	public function getUnconfirmedItems()
	{
		$page			= $this->input->get('page');
		$offset			= ($page - 1) * 10;
		$this->load->model("Promotion_model");
		$data['items']		= $this->Promotion_model->getUnconfirmedItems($offset);
		$data['pages']		= max(1, ceil($this->Promotion_model->countUnconfirmedItems()));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function confirmById()
	{
		$id			= $this->input->post('id');
		$this->load->model("Promotion_model");
		$result		= $this->Promotion_model->updateById($id, 1);
		echo $result;
	}

	public function deleteById()
	{
		$id			= $this->input->post('id');
		$this->load->model("Promotion_model");
		$result		= $this->Promotion_model->updateById($id, 2);
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
		$this->load->view('sales/header', $data);
		$this->load->view('sales/Promotion/archiveDashboard');
	}

	public function getItems()
	{
		$page			= $this->input->get('page');
		$month			= $this->input->get('month');
		$year			= $this->input->get('year');
		$offset			= ($page - 1) * 10;
		$this->load->model("Promotion_model");
		$data['items']		= $this->Promotion_model->getItems($offset, $month, $year);
		$data['pages']		= max(1, ceil($this->Promotion_model->countItems($month, $year)));

		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
