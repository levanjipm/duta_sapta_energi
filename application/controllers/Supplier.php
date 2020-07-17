<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	
class Supplier extends CI_Controller {
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
		
		$this->load->view('purchasing/supplier_manage_dashboard');
	}
	
	public function getItems()
	{
		$page		= $this->input->get('page');
		$term		= $this->input->get('term');
		$offset		= ($page - 1) * 25;
		
		$this->load->model('Supplier_model');
		$data['suppliers']	= $this->Supplier_model->showItems($offset, $term);
		$data['pages']		= $this->Supplier_model->countItems($term);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function insertItem()
	{
		$this->load->model('supplier_model');
		$this->supplier_model->insert_from_post();
		
		redirect(site_url('supplier'));
	}
	
	public function deleteById()
	{
		$this->load->model('supplier_model');
		$result = $this->supplier_model->deleteById();
		
		echo $result;
	}
	
	public function updateById()
	{
		$this->load->model('supplier_model');
		$this->supplier_model->updateById();
		
		redirect(site_url('supplier'));
	}
	
	public function getById()
	{
		$supplier_id	= $this->input->get('id');
		$this->load->model('Supplier_model');
		$item = $this->Supplier_model->getById($supplier_id);
		
		header('Content-Type: application/json');
		echo json_encode($item);
	}
}