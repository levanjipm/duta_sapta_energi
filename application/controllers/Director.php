<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Director extends CI_Controller {
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

		if($data['user_login']->access_level < 5){
			redirect(site_url(""));
		} else {	
			$this->load->model('Authorization_model');
			$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
			$this->load->view('head');
			$this->load->view('director/header', $data);

			$data			= array();
			$this->load->model("Bank_model");
			$data['bank']	= $this->Bank_model->getTotalCurrentBalance();

			$this->load->model("Petty_cash_model");
			$data['petty']	= $this->Petty_cash_model->getCurrentBalance();

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
			$this->load->view('director/dashboard', $data);
		}
		
	}

	public function IncomeStatement()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);

		if($data['user_login']->access_level < 5){
			redirect(site_url(""));
		} else {
			$this->load->view('head');
			$this->load->view('director/header');
			$this->load->view('director/incomeStatement/dashboard');
		}
	}

	public function getIncomeStatement()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);

		if($data['user_login']->access_level == 5){
			$data		= array();
			$month		= $this->input->get('month');
			$year		= $this->input->get('year');
			
			//Sales//
			$this->load->model("Invoice_model");
			$sales		= (float)$this->Invoice_model->getValueByMonthYear($month, $year);

			$this->load->model("Sales_return_received_model");
			$salesReturn	= (float)$this->Sales_return_received_model->getValueByMonthYear($month, $year);

			$data['sales']		= $sales;
			$data['salesReturn']	= $salesReturn;

			//Purchasing//
			$this->load->model("Debt_model");
			$purchase	= (float)$this->Debt_model->getValueByMonthYear($month, $year);
			
			$this->load->model("Purchase_return_sent_model");
			$purchaseReturn		= (float)$this->Purchase_return_sent_model->getValueByMonthYear($month, $year);

			$data['purchase']	= $purchase;
			$data['purchaseReturn']	= $purchaseReturn;

			//Stock Value//
			$this->load->model("Stock_in_model");
			if($month == 0){
				$initialDate		= $year . "-01-01";
				$endDate			= $year . "-12-31";
			} else {
				$initialDate		= date("Y-m-t", mktime(0,0,0,$month - 1, 1, $year));
				$endDate			= date("Y-m-t", mktime(0,0,0,$month, 1, $year));
			}

			$stockInitial			= $this->Stock_in_model->calculateValue($initialDate);
			$stockEnd				= $this->Stock_in_model->calculateValue($endDate);
			$data['stockChange']	= $stockEnd - $stockInitial;

			//Cost and Gain of setting invoice as done//
			$this->load->model("Receivable_model");
			$data['receivable']		= $this->Receivable_model->getSetDoneCost($month, $year);

			$this->load->model("Payable_model");
			$data['payable']		= $this->Payable_model->getSetDoneGain($month, $year);

			//Expense//
			$this->load->model("Expense_model");
			$data['expense']	= $this->Expense_model->getByMonthYear($month, $year);

			//Other income//
			$this->load->model("Income_model");
			$data['income']		= $this->Income_model->getByMonthYear($month, $year);

			//Other invoice -- Operational//
			$this->load->model("Invoice_model");
			$data['otherSales']		= $this->Invoice_model->getOtherByMonthYear($month, $year);

			$this->load->model("Debt_other_model");
			$data['otherPurchase']	= $this->Debt_other_model->getByMonthYear($month, $year);

			header('Content-Type: application/json');
			echo json_encode($data);
		}
	}

	public function getBalanceStatement()
	{
		$month			= $this->input->get('month');
		$year			= $this->input->get('year');
		if($month == 0){
			$date			= date("Y-m-t", mktime(0,0,0, 12, 1, $year));
		} else {
			$date			= date("Y-m-t", mktime(0,0,0, $month, 1, $year));
		}
		
		$this->load->model("Petty_cash_model");
		$data['cashOnHand']		= $this->Petty_cash_model->getBalanceByDate($date);
		
		$this->load->model("Bank_model");
		$data['cashOnAccount']	= $this->Bank_model->getBalanceByDate($date);

		$this->load->model("Receivable_model");
		$data['receivable']		= $this->Receivable_model->getByDate($date);

		$this->load->model("Stock_in_model");
		$data['stock']			= (float)$this->Stock_in_model->calculateValue($date);

		$this->load->model("Asset_model");
		$assets					= $this->Asset_model->calculateValue($date);
		$assetValue 			= 0;
		$depreciationValue		= 0;
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
				$assetValue 		+= $estimatedValue;
				$depreciationValue	+= $depreciation;
			}
		}

		$data['assetValue'] 	= $assetValue;
		$data['depreciation']	= $depreciationValue;

		$this->load->model("Debt_model");
		$data['debt']			= $this->Debt_model->getIncompletedTransactionByDate($date);

		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
