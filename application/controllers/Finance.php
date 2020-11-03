<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finance extends CI_Controller {
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
		$this->load->view('finance/header', $data);

		$data			= array();
		$this->load->model("Bank_model");
		$data['bank']	= $this->Bank_model->getTotalCurrentBalance();

		$this->load->model("Petty_cash_model");
		$data['petty']	= $this->Petty_cash_model->getCurrentBalance();

		//Calculating current ratio//
		$this->load->model("Invoice_model");
		$receivableArray		= $this->Invoice_model->getIncompletedTransactionByDate(date("Y-m-d"));
		$receivable				= $receivableArray->value - $receivableArray->paid;
		$bankAccount	= $data['bank'];
		$pettyCashAccount	= $data['petty'];
		
		$this->load->model("Asset_model");
		$asset			= $this->Asset_model->getCurrentValue(date("Y-m-d"));
		$assetValue		= 0;
		foreach($asset as $item){
			$value		= $item->value;
			$depreciation	= $item->depreciation_time;
			$residueValue	= $item->residue_value;
			$date			= strtotime($item->date);
			$difference		= (strtotime("now") - $date) / (60 * 60 * 24 * 30);
			$currentValue	= ($value - $residueValue) * ($depreciation - $difference) / $depreciation;
			$assetValue		+= $currentValue;
		}

		$this->load->model("Debt_model");
		$liability		= $this->Debt_model->getIncompletedTransactionByDate(date("Y-m-d"));
		if($liability == 0){
			$data['ratio']	= 0;
		} else {
			$data['ratio']		= ($assetValue + $receivable + $bankAccount + $pettyCashAccount) / $liability;
		}

		$this->load->view('finance/dashboard', $data);
	}
}
