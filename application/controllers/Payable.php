<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payable extends CI_Controller {
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
		$this->load->view('accounting/header', $data);
		$this->load->view('accounting/Payable/dashboard');
	}

	public function finance()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);
		$this->load->view('finance/Payable/dashboard');
	}
	
	public function viewPayable()
	{
		$category			= $this->input->get('category');
		$this->load->model('Debt_model');
		$data	= $this->Debt_model->viewPayableChart($category);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function viewPayableBySupplierId()
	{
		$supplierId = $this->input->get('id');
		$this->load->model('Debt_model');
		$data['items'] = $this->Debt_model->getPayableBySupplierId($supplierId);

		$this->load->model('Supplier_model');
		$data['supplier'] = $this->Supplier_model->getById($supplierId);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function viewCompleteBySupplierId($supplierId)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);

		$data		= array();
		$this->load->model("Supplier_model");
		$data['supplier']	= $this->Supplier_model->getByid($supplierId);
		$this->load->view('accounting/Payable/supplierCompletePayable', $data);
	}

	public function viewPayableByOtherId()
	{
		$supplierId = $this->input->get('id');
		$this->load->model('Debt_model');
		$data['items'] = $this->Debt_model->getPayableByOtherId($supplierId);

		$this->load->model('Opponent_model');
		$data['supplier'] = $this->Opponent_model->getById($supplierId);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getSupplierOpponentItems()
	{
		$page			= $this->input->get('page');
		$term			= $this->input->get('term');
		$offset			= ($page - 1) * 10;
		$this->load->model("Payable_model");
		$data['items'] = $this->Payable_model->getSupplierOpponentItems($offset, $term);
		$data['pages'] = max(1, ceil($this->Payable_model->countSupplierOpponentItems($offset)/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function viewBySupplierId($supplierId)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);

		$this->load->model('Supplier_model');
		$data['supplier'] = $this->Supplier_model->getById($supplierId);
		$this->load->view('accounting/payable/supplierPayable', $data);
	}

	public function viewFinanceBySupplierId($supplierId)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);

		$this->load->model('Supplier_model');
		$data['supplier'] = $this->Supplier_model->getById($supplierId);
		$this->load->view('finance/payable/supplierPayable', $data);
	}


	public function getCompletePayableBySupplierId()
	{
		$supplierId			= $this->input->get('id');
		$page				= $this->input->get('page');
		$offset				= ($page - 1) * 10;
		$this->load->model("Debt_model");
		$data['items'] = $this->Debt_model->getPayableBySupplierId($supplierId, $offset);
		$data['pages']	= max(1, ceil($this->Debt_model->countPayableBySupplierId($supplierId)/10));

		$this->load->model("Bank_model");
		$data['pendingBankData'] = $this->Bank_model->getPendingValueByOpponentId("supplier", $supplierId);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getCompletePayableBySupplierIdAll()
	{
		$supplierId			= $this->input->get('id');
		$page				= $this->input->get('page');
		$offset				= ($page - 1) * 10;
		$this->load->model("Debt_model");
		$data['items'] = $this->Debt_model->getCompletePayableBySupplierId($supplierId, $offset);
		$data['pages']	= max(1, ceil($this->Debt_model->countCompletePayableBySupplierId($supplierId)/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getCompletePayableByOpponentId()
	{
		$opponentId		= $this->input->get('id');
		$this->load->model("Debt_model");
		$data['items'] = $this->Debt_model->getPayableByOtherId($opponentId);

		$this->load->model("Payable_model");
		$data['payable'] = $this->Payable_model->getPayableByOtherId($opponentId);

		$this->load->model("Bank_model");
		$data['pendingBankData'] = $this->Bank_model->getPendingValueByOpponentId("otherOut", $opponentId);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function setInvoiceAsDone()
	{
		$invoiceId			= $this->input->post('id');
		$date				= $this->input->post('date');
		$type				= $this->input->post('type');
		if($type == 1){
			$this->load->model("Debt_model");
			$result = $this->Debt_model->setInvoiceAsDone($invoiceId, $date);
			echo $result;
		} else {
			$this->load->model("Debt_other_model");
			$result	= $this->Debt_other_model->setInvoiceAsDone($invoiceId, $date);
			echo $result;
		}
	}

	public function viewByOpponentId($opponentId)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('accounting/header', $data);

		$this->load->model("Opponent_model");
		$data['opponent']		= $this->Opponent_model->getById($opponentId);
		$this->load->view('accounting/payable/opponentPayable', $data);
	}

	public function viewFinanceByOpponentId($opponentId)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('finance/header', $data);

		$this->load->model("Opponent_model");
		$data['opponent']		= $this->Opponent_model->getById($opponentId);
		$this->load->view('finance/payable/opponentPayable', $data);
	}

	public function resetByBankId()
	{
		$bankId			= $this->input->post('id');
		$this->load->model("Bank_model");
		$this->load->model("Payable_model");
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

		$this->Payable_model->deleteByBankId($bankId);
		$this->Bank_model->updateUndoneById($bankId);

		if($status){
			$this->Bank_model->mergeByParentId($parentId);
		}
	}

	public function deleteBlankById()
	{
		$id			= $this->input->post('id');
		$this->load->model("Payable_model");
		$result	= $this->Payable_model->deleteBlankById($id);
		echo $result;
	}
}
