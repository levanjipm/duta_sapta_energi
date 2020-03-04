<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debt extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->has_userdata('user_id') == FALSE){
			redirect(site_url('login'));

		}
	}
	
	public function index()
	{
		$this->load->view('head');
		$this->load->view('accounting/header');
		$this->load->view('accounting/debt_document');
	}
	
	public function view_uninvoiced_documents()
	{
		$this->load->model('Good_receipt_model');
		$items = $this->Good_receipt_model->view_uninvoiced_documents();
		$data['bills'] = $items;
		
		$items = $this->Good_receipt_model->count_uninvoiced_documents();
		$data['pages'] = max(ceil($items / 25), 1);
	}
}
