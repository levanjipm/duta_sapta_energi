<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->has_userdata('user_id') == FALSE){
			redirect(site_url('login'));

		}
	}
	public function accountDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);
		$this->load->view('finance/Bank/accountDashboard');
	}
	
	public function transactionDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);

		$this->load->view('finance/Bank/transactionDashboard');
	}
	
	public function assignDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		
		$this->load->view('accounting/Bank/assignBankDashboard', $data);
	}

	public function assignExpense()
	{
		$bankId		= $this->input->post('id');
		$this->load->model("Bank_model");
		$result		= $this->Bank_model->show_by_id($bankId);
		if($result->is_done == 0 && $result->transaction == 2){
			$user_id		= $this->session->userdata('user_id');
			$this->load->model('User_model');
			$data['user_login'] = $this->User_model->getById($user_id);
		
			$this->load->model('Authorization_model');
			$data['departments']	= $this->Authorization_model->getByUserId($user_id);
			$this->load->view('head');
			$this->load->view('accounting/header', $data);
			
			$data		= array();
			$data['bank'] = $result;
			
			if($result->other_id != NULL){
				$this->load->model("Opponent_model");
				$data['opponent'] = $this->Opponent_model->getById($result->other_id);
				$data['type'] = "Other";
			} else if($result->supplier_id != NULL){
				$this->load->model("Supplier_model");
				$data['opponent'] = $this->Supplier_mode->getById($result->supplier_id);
				$data['type'] = "Supplier";
			} else if($result->customer_id != NULL){
				$this->load->model("Customer_model");
				$data['opponent'] = $this->Customer_model->getById($result->customer_id);
				$data['type'] = "Customer";
			}
			
			$this->load->view('accounting/bank/assignExpense', $data);
		} else {
			redirect(site_url('Bank/assignDashboard'));
		}
	}

	public function assignIncome()
	{
		$bankId		= $this->input->post('id');
		$this->load->model("Bank_model");
		$result		= $this->Bank_model->show_by_id($bankId);
		if($result->is_done == 0 && $result->transaction == 1){
			$user_id		= $this->session->userdata('user_id');
			$this->load->model('User_model');
			$data['user_login'] = $this->User_model->getById($user_id);
		
			$this->load->model('Authorization_model');
			$data['departments']	= $this->Authorization_model->getByUserId($user_id);
			$this->load->view('head');
			$this->load->view('accounting/header', $data);
			
			$data		= array();
			$data['bank'] = $result;
			
			if($result->other_id != NULL){
				$this->load->model("Opponent_model");
				$data['opponent'] = $this->Opponent_model->getById($result->other_id);
				$data['type'] = "Other";
			} else if($result->supplier_id != NULL){
				$this->load->model("Supplier_model");
				$data['opponent'] = $this->Supplier_mode->getById($result->supplier_id);
				$data['type'] = "Supplier";
			} else if($result->customer_id != NULL){
				$this->load->model("Customer_model");
				$data['opponent'] = $this->Customer_model->getById($result->customer_id);
				$data['type'] = "Customer";
			}
			
			$this->load->view('accounting/bank/assignIncome', $data);
		} else {
			redirect(site_url('Bank/assignDashboard'));
		}
	}

	public function insertAssignExpense()
	{
		$bankId		= $this->input->post('id');
		$note		= $this->input->post('note');
		$expenseClass	= $this->input->post('expenseClass');

		$this->load->model("Bank_model");
		$result		= $this->Bank_model->show_by_id($bankId);
		if($result->is_done == 0 && $result->transaction == 2){
			$this->Bank_model->assignExpense($bankId, $expenseClass, $note);
		}
	}

	public function insertAssignIncome()
	{
		$bankId		= $this->input->post('id');
		$note		= $this->input->post('note');
		$incomeClass	= $this->input->post('incomeClass');

		$this->load->model("Bank_model");
		$result		= $this->Bank_model->show_by_id($bankId);
		if($result->is_done == 0 && $result->transaction == 1){
			$this->Bank_model->assignIncome($bankId, $incomeClass, $note);
		}
	}
	
	public function getUnassignedTransactions($department)
	{
		if($department == 'accounting'){
			$type		= $this->input->get('type');
			$account	= $this->input->get('account');
			$page		= $this->input->get('page');
			$offset		= ($page - 1) * 10;
			$this->load->model('Bank_model');
			$data['banks'] = $this->Bank_model->getUnassignedTransactions($account, $type, $offset);
			$data['pages'] = max(1, ceil($this->Bank_model->countUnassignedTransactions($account, $type)/10));
		}
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getAssignedTransactions()
	{
		$type		= $this->input->get('type');
		$account	= $this->input->get('account');
		$page		= $this->input->get('page');
		$dateStart	= $this->input->get('dateStart');
		$dateEnd	= $this->input->get('dateEnd');

		$offset		= ($page - 1) * 10;

		$this->load->model('Bank_model');
		$data['banks'] = $this->Bank_model->getAssignedTransactions($account, $type, $dateStart, $dateEnd, $offset);
		$data['pages'] = max(1, ceil($this->Bank_model->countAssignedTransactions($account, $type, $dateStart, $dateEnd)/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function assign_do()
	{
		$bank_transaction_id	= $this->input->post('id');
		$this->load->model('Bank_model');
		$result			= $this->Bank_model->show_by_id($bank_transaction_id);
		$data['bank']	= $result;
		$type			= $result->transaction;
		$customerId		= $result->customer_id;
		$supplier_id	= $result->supplier_id;
		$other_id		= $result->other_id;

		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		
		if($type == 1 && $customerId != null){
			$this->load->model('Invoice_model');
			$data['invoices'] = $this->Invoice_model->getIncompletedTransaction($customerId);
			$data['opponent'] = 'Customer';
			$this->load->view('accounting/Bank/assignBankIn', $data);
		} else  if($type == 2 && $supplier_id != null){
			$this->load->model('Debt_model');
			$data['invoices'] = $this->Debt_model->getIncompletedTransaction($supplier_id);
			$data['opponent'] = 'Supplier';
			$this->load->view('accounting/Bank/assignBankOut', $data);
		} else if($type == 1 && $other_id != null){
			$this->load->model('Invoice_model');
			$data['invoices'] = $this->Invoice_model->getIncompletedTransactionByOpponentId($other_id);
			$data['opponent'] = 'Other';
			$this->load->view('accounting/Bank/assignBankIn', $data);
		} else if($type == 2 && $other_id != null){
			$this->load->model('Debt_other_model');
			$data['invoices'] = $this->Debt_other_model->getIncompletedTransaction($other_id);
			$data['opponent'] = 'Other';
			$this->load->view('accounting/Bank/assignBankOut', $data);
		}
	}
	
	public function insertAssign()
	{
		$bank_id		= $this->input->post('bank_id');
		
		$this->load->model('Bank_model');
		$result 		= $this->Bank_model->show_by_id($bank_id);

		$type			= $result->transaction;
		$customer_id	= $result->customer_id;
		$supplier_id	= $result->supplier_id;
		$other_id		= $result->other_id;
		
		if($type == 1 && $customer_id != null){
			$this->Bank_model->assign_receivable($result);
		} else if($type == 2 && $supplier_id != null){
			$this->Bank_model->assign_payable($result);
		} else if($type == 1 && $other_id != null){
			$this->Bank_model->assign_receivable($result);
		} else if($type == 2 && $other_id != null){
			$this->Bank_model->assign_payable($result);
		}
		
		redirect(site_url('Bank/assignDashboard'));
	}
	
	public function mutationDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);
		$this->load->view('finance/Bank/mutationDashboard', $data);
	}
	
	public function getMutation()
	{
		$account_id		= $this->input->get('account');
		$date_start		= $this->input->get('start');
		$date_end		= $this->input->get('end');
		$page			= $this->input->get('page');
		$offset			= ($page - 1) * 25;
		
		$this->load->model('Bank_model');
		$data['balance']	= $this->Bank_model->getBalance($account_id, $date_start);
		$data['mutations'] 	= $this->Bank_model->getMutation($account_id, $date_start, $date_end, $offset);
		$data['pages'] 		= max(1, ceil($this->Bank_model->countMutation($account_id, $date_start, $date_end, $offset)/25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function opponent()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);
		$this->load->view('finance/Opponent/dashboard');
	}
	
	public function showOpponent()
	{
		$page		= $this->input->get('page');
		$term		= $this->input->get('term');
		$offset		= ($page - 1) * 10;
		
		$type		= $this->input->get('type');
		if($type == 'customer'){
			$this->load->model('Customer_model');
			$data['opponents']			= $this->Customer_model->showItems($offset, $term);
			$data['pages']				= max(1, ceil($this->Customer_model->countItems($term)/10));
		} else if($type == 'supplier'){
			$this->load->model('Supplier_model');
			$data['opponents']			= $this->Supplier_model->showItems($offset, $term);
			$data['pages']				= max(1, ceil($this->Supplier_model->countItems($term)/10));
		} else if($type == 'other'){
			$this->load->model('Opponent_model');
			$data['opponents']			= $this->Opponent_model->getItems($offset, $term);
			$data['pages']				= max(1, ceil($this->Opponent_model->countItems($term)/10));
		}
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function add_other_opponent()
	{
		$this->load->model('Other_bank_account_model');
		$this->Other_bank_account_model->insert_from_post();
		
		redirect(site_url('Bank/opponent'));
	}
	
	public function view_transaction($type, $id)
	{
		$data['type'] 	= $type;
		$data['id']		= $id;
		$this->load->view('head');
		$this->load->view('finance/header');
		$this->load->view('finance/opponent_mutation', $data);
	}
	
	public function show_opponent()
	{
		$opponent_type	= $this->input->get('type');
		$term			= $this->input->get('term');
		$page			= $this->input->get('page');
		$offset			= ($page - 1) * 25;

		if($opponent_type	== 'customer'){
			$this->load->model('Customer_model');
			$data['opponents'] = $this->Customer_model->show_items($offset, $term);
			$data['pages'] = max(1, ceil($this->Customer_model->count_items($term)/25));
			
		} else if($opponent_type == 'supplier'){
			$this->load->model('Supplier_model');
			$data['opponents'] = $this->Supplier_model->show_items($offset, $term);
			$data['pages'] = $this->Supplier_model->count_items($term);
			
		} else if($opponent_type == 'other'){
			$this->load->model('Other_bank_account_model');
			$data['opponents'] = $this->Other_bank_account_model->show_items($offset, $term);
			$data['opponents'] = $this->Other_bank_account_model->count_items($term);
		}
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function insertItem()
	{
		$account		= $this->input->post('account');
		$date			= $this->input->post('date');
		$value			= $this->input->post('value');
		$transaction	= $this->input->post('transaction');
		$type			= $this->input->post('type');
		$opponent_id	= $this->input->post('id');
		$petty_cash		= $this->input->post('petty_cash_transfer');
		$internal		= $this->input->post('internalAccountTransfer');
		$otherAccount	= $this->input->post('otherAccount');

		if($petty_cash	== 'on'){
			$opponent_id	= NULL;
			$type			= 'other';
		} else if($internal == 'on'){
			$opponent_id	= $otherAccount;
			$type			= 'internal';
		}
		
		$this->load->model('Bank_model');
		
		
		if($petty_cash	== 'on'){
			$insert_id		= $this->Bank_model->insertItem($date, $value, $transaction, $type, $opponent_id, $account, NULL, 1);
			$this->load->model('Petty_cash_model');
			$this->Petty_cash_model->insert_income($insert_id, $value, $date);
		} else if($internal == 'on'){
			$this->Bank_model->insertItem($date, $value, 2, $type, $opponent_id, $account, NULL, 1);
			$this->Bank_model->insertItem($date, $value, 1, $type, $account, $opponent_id, NULL, 1);
		} else {
			$this->Bank_model->insertItem($date, $value, $transaction, $type, $opponent_id, $account);
		}
	}

	public function getCurrentBalance()
	{
		$id			= $this->input->get('id');
		$this->load->model("Bank_model");
		$data = $this->Bank_model->getCurrentBalance($id);
		echo $data;
	}

	public function viewTransactions($type, $id)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);

		$data = array();
		if($type == 1){
			$data['type'] = "Customer";
			$this->load->model("Customer_model");
			$data['opponent'] = $this->Customer_model->getById($id);
		} else if($type == 2){
			$data['type'] = "Supplier";
			$this->load->model("Supplier_model");
			$data['opponent'] = $this->Supplier_model->getById($id);
		} else if($type == 3){
			$data['type'] = "Other";
			$this->load->model("Opponent_model");
			$data['opponent'] = $this->Opponent_model->getById($id);
		};

		$this->load->view('finance/Opponent/mutationDashboard', $data);
	}

	public function resetDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);
		
		$this->load->view('accounting/Bank/resetBankDashboard', $data);
	}

	public function viewAssignmentById()
	{
		$id			= $this->input->get('id');
		$this->load->model("Bank_model");
		$data		= $this->Bank_model->getAssignmentsBankId($id);
		header('Content-Type: application/json');
		echo json_encode($data);		
	}

	public function resetBankForm()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);

		$data			= array();
		$id			= $this->input->post('id');
		$this->load->model("Bank_model");
		$bank	= $this->Bank_model->getById($id);
		$data['bank']		= $bank;
		if($bank == null){
			redirect(site_url('Bank/resetDashboard'));
		}
		$accountId			= $bank->account_id;
		$this->load->model("Internal_bank_account_model");
		$data['account']	= $this->Internal_bank_account_model->getById($accountId);
		$data['type']		= $bank->type;

		$customerId			= $bank->customer_id;
		$opponentId			= $bank->other_id;
		$supplierId			= $bank->supplier_id;

		if($customerId != NULL){
			$this->load->model("Customer_model");
			$opponent		= $this->Customer_model->getByid($customerId);
			$complete_address		= '';
			$customer_name			= $opponent->name;
			$complete_address		.= $opponent->address;
			$customer_city			= $opponent->city;
			$customer_number		= $opponent->number;
			$customer_rt			= $opponent->rt;
			$customer_rw			= $opponent->rw;
			$customer_postal		= $opponent->postal_code;
			$customer_block			= $opponent->block;
		
			if($customer_number != null){
				$complete_address	.= ' No. ' . $customer_number;
			}
					
			if($customer_block != null && $customer_block != "000"){
				$complete_address	.= ' Blok ' . $customer_block;
			}
				
			if($customer_rt != '000'){
				$complete_address	.= ' RT ' . $customer_rt;
			}
					
			if($customer_rw != '000' && $customer_rt != '000'){
				$complete_address	.= ' /RW ' . $customer_rw;
			}
					
			if($customer_postal != null){
				$complete_address	.= ', ' . $customer_postal;
			}

			$data['opponent']	= array(
				"name" => $customer_name,
				"address" => $complete_address,
				"city" => $customer_city
			);
		} else if($supplierId != NULL){
			$this->load->model("Supplier_model");
			$opponent				= $this->Supplier_model->getById($supplierId);

			$supplier_name			= $opponent->name;
			$complete_address		= '';
			$complete_address		.= $opponent->address;
			$supplier_number		= $opponent->number;
			$supplier_block			= $opponent->block;
			$supplier_rt			= $opponent->rt;
			$supplier_rw			= $opponent->rw;
			$supplier_postal_code	= $opponent->postal_code;
			$supplier_city			= $opponent->city;
		
			$complete_address		.= 'No. ' . $supplier_number;
		
			if($supplier_block		== '' && $supplier_block == '000'){
				$complete_address	.= 'Block ' . $supplier_block;
			};
		
			if($supplier_rt != '' && $supplier_rt != '000'){
				$complete_address	.= 'RT ' . $supplier_rt . '/ RW ' . $supplier_rw;
			}
		
			if($supplier_postal_code != ''){
				$complete_address	.= ', ' . $supplier_postal_code;
			}

			$data['opponent']	= array(
				"name" => $supplier_name,
				"address" => $complete_address,
				"city" => $supplier_city
			);
		} else if($opponentId != NULL){
			$this->load->model("Opponent_model");
			$opponent			= $this->Opponent_model->getById($opponentId);
			$data['opponent']	= array(
				"name" => $opponent->name,
				"address" => $opponent->description,
				"city" => $opponent->type
			);
		}

		if($bank->type == "receivable"){
			$this->load->model("Receivable_model");
			$data['receivable']		= $this->Receivable_model->getByBankId($id);
		} else if($bank->type == "payable"){
			$this->load->model("Payable_model");
			$data['payable']		= $this->Payable_model->getByBankId($id);
		} else if($bank->type == "pettyCash") {
			$data['opponent']	= array(
				"name" => "Petty Cash",
				"address" => "--",
				"city" => ""
			);
		} else if($bank->type == "salesReturn") {
			$transactionReference		= $bank->transaction_reference;
			$data['balancer']			= $this->Bank_model->getById($transactionReference);
		} else if($bank->type == "salesReturn") {
			$transactionReference		= $bank->transaction_reference;
			$data['balancer']			= $this->Bank_model->getById($transactionReference);
		}

		$this->load->view('accounting/Bank/resetBankForm', $data);
	}

	public function deleteDashboard()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);

		if($data['user_login']->access_level < 4){
			redirect(site_url('Welcome'));
		} else {
			$this->load->view('head');
			$this->load->view('administrator/header', $data);
			$this->load->view('administrator/Bank/deleteDashboard');
		}
	}

	public function deleteById()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$user		= $this->User_model->getById($user_id);
		if($user->access_level > 3){
			$id			= $this->input->post('id');
			$this->load->model("Bank_model");
			$result	= $this->Bank_model->deleteById($id);
			echo $result;
		} else {
			echo 0;
		}
	}

	public function resetByBankId()
	{
		$bankId			= $this->input->post('id');

		$this->load->model("Bank_model");
		$bank		= $this->Bank_model->getById($bankId);
		$parentId	= $bank->bank_transaction_major;
		if($parentId == NULL){
			$status = false;
		} else {
			$bankChildData		= $this->Bank_model->getChildByParentId($parentId, $bankId);
			$status				= true;
			foreach($bankChildData as $bankChild){
				if($bankChild->is_done != 0 || $bankChild->is_delete != 0){
					$status		= false;
					break;
				}
				next($bankChildData);
			}
		}

		$this->Bank_model->deleteByBankId($bankId);
		$this->Bank_model->updateUndoneById($bankId);

		if($status){
			$this->Bank_model->mergeByParentId($parentId);
		}
	}

	public function getMutationByOpponent()
	{
		$type			= $this->input->get('type');
		$id				= $this->input->get('id');
		$page			= $this->input->get('page');
		$offset			= ($page - 1) * 10;

		$this->load->model("Bank_model");
		$data['items']		= $this->Bank_model->getMutationByOpponent($id, $type, $offset);
		$data['pages']		= max(1, ceil($this->Bank_model->countMutationByOpponent($id, $type)/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
