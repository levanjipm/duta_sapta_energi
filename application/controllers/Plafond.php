<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plafond extends CI_Controller {
	public function index()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		
		$this->load->view('sales/plafond_dashboard');
	}

	public function confirm()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		$this->load->view('sales/plafond_status_dashboard');
	}
	
	public function submitPlafond()
	{
		$this->load->model('Plafond_model');
		$result		= $this->Plafond_model->insertItem();
		if($result != NULL){
			$id			= $result;
			redirect('Plafond/plafond_check_out/' . $id);
		} else {
			redirect('Plafond/failedSubmission');
		}
	}
	
	public function showById()
	{
		$id = $this->input->get('id');
		$this->load->model('Plafond_model');
		$data['plafond'] = $this->Plafond_model->getById($id);
		
		$customer_id = $data['plafond']->customer_id;
		$this->load->model('Customer_model');
		$data['customer'] = $this->Customer_model->getById($customer_id);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function deleteById()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		if($data['user_login']->access_level > 2){
			$id		= $this->input->post('id');
			$this->load->model('Plafond_model');
			$result		= $this->Plafond_model->updatePlafond($id, 0, 1);
		}
	}
	
	public function confirmSubmission()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		if($data['user_login']->access_level > 2){
			$id		= $this->input->post('id');
			$this->load->model('Plafond_model');
			$result		= $this->Plafond_model->updatePlafond($id, 1, 0);
			
			if($result != 0){			
				$this->load->model('Plafond_model');
				$data				= $this->Plafond_model->getById($id);
				$customer_id		= $data->customer_id;
				$submitted_plafond	= $data->submitted_plafond;
				
				$this->load->model('Customer_model');
				$this->Customer_model->update_plafond($customer_id, $submitted_plafond);

				redirect(site_url("/Plafond/successConfirm/" . $id));
			} else {
				redirect(site_url("/Plafond/failedConfirm"));
			}
		}
	}
	
	public function successConfirm($submission_id)
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		
		$this->load->model('Plafond_model');
		$result		= $this->Plafond_model->getById($submission_id);
		$data['submission']		= $result;
		
		$customer_id		= $result->customer_id;
		$this->load->model('Customer_model');
		$data['customer']		= $this->Customer_model->getById($customer_id);
		
		$data['status']			= 'success';
		
		$this->load->view('sales/plafond_confirm_result', $data);
	}

	public function failedConfirm()
	{
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		
		$this->load->model('Plafond_model');
		$result		= $this->Plafond_model->getById($submission_id);
		$data['submission']		= $result;
		
		$customer_id		= $result->customer_id;
		$this->load->model('Customer_model');
		$data['customer']		= $this->Customer_model->getById($customer_id);
		
		$data['status']			= 'failed';
		
		$this->load->view('sales/plafond_confirm_result', $data);
	}

	public function successSubmission($submission_id)
	{		
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		
		$this->load->model('Plafond_model');
		$result		= $this->Plafond_model->getById($submission_id);
		$data['submission']		= $result;
		
		$customer_id		= $result->customer_id;
		$this->load->model('Customer_model');
		$data['customer']		= $this->Customer_model->getById($customer_id);
		
		$data['status']			= 'success';
		
		$this->load->view('sales/plafond_check_out', $data);
	}
	
	public function failedSubmission()
	{		
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		
		$this->load->view('head');
		$this->load->view('sales/header', $data);
		
		$data['status']			= 'failed';
		
		$this->load->view('sales/plafond_check_out', $data);
	}
	
	public function showUnconfirmed()
	{
		$term		= $this->input->get('term');
		$page		= $this->input->get('page');
		$offset		= ($page - 1) * 25;
		$this->load->model('Customer_model');
		$data['customers'] = $this->Customer_model->show_unconfirmed_plafond_customers($offset, $term);
		$item = $this->Customer_model->count_unconfirmed_plafond_customers($term);
		$data['pages'] = max(1, ceil($item / 25));
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
