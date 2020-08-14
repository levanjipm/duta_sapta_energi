<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_account extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->has_userdata('user_id') == FALSE){
			redirect(site_url('login'));

		}
	}

	public function getItems()
	{
		$page		= $this->input->get('page');
		$term		= $this->input->get('term');
		$offset		= ($page - 1) * 10;
		$this->load->model('Internal_bank_account_model');

		$data['items'] = $this->Internal_bank_account_model->getItems($offset, $term);
		$data['pages'] = max(1, ceil($this->Internal_bank_account_model->countItems($term)/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getById()
	{
		$id		= $this->input->get('id');
		$this->load->model("Internal_bank_account_model");
		$data = $this->Internal_bank_account_model->getById($id);
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function insertItem()
	{
		$this->load->model('Internal_bank_account_model');
		$result = $this->Internal_bank_account_model->insertItem();
		echo $result;
	}

	public function deleteById()
	{
		$id		= $this->input->post('id');
		$this->load->model('Internal_bank_account_model');
		
		$result = $this->Internal_bank_account_model->delete_by_id($id);
		echo $result;
	}

	public function updateById()
	{
		$this->load->model("Internal_bank_account_model");
		$result = $this->Internal_bank_account_model->updateById();
		echo $result;
	}
}
