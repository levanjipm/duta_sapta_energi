<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Route extends CI_Controller {
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
		$this->load->view('inventory/header', $data);
		$this->load->view('inventory/Route/dashboard');
	}

	public function assignCustomer($routeId){
		$user_id		= $this->session->userdata('user_id');
		$this->load->model('User_model');
		$data['user_login'] = $this->User_model->getById($user_id);
		
		$this->load->model('Authorization_model');
		$data['departments']	= $this->Authorization_model->getByUserId($user_id);
		$data['routeId']		= $routeId;
		
		$this->load->view('head');
		$this->load->view('inventory/header', $data);
		$this->load->view('inventory/Route/assignCustomer', $data);
	}

	public function getCustomers($routeId){
		$term			= $this->input->get('term');
		$page			= $this->input->get('page');
		$offset			= ($page - 1) * 10;

		$this->load->model("Customer_route_model");
		$data				= array();
		$data['customers'] = $this->Customer_route_model->getCustomersByRouteId($routeId, $term, $offset);

		$this->load->model("Customer_model");
		$data['pages']		= max(1, ceil($this->Customer_model->countItems($term)/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getItems(){
		$page			= $this->input->get('page');
		$term			= $this->input->get('term');

		$this->load->model("Route_model");
		$data['items']		= $this->Route_model->getItems(($page - 1) * 10, $term);
		$data['pages']		= max(1, ceil($this->Route_model->countItems($term)/10));

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function insertItem(){
		$route			= $this->input->post('route');
		$this->load->model("Route_model");
		$data			= $this->Route_model->insertItem($route);
		echo $data;
	}

	public function EditById(){
		$routeId = $this->input->post('id');
		$routeName = $this->input->post('name');
		$this->load->model("Route_model");
		$result = $this->Route_model->EditById($routeId, $routeName);
		echo $result;
	}

	public function deleteById(){
		$routeId		= $this->input->post('id');
		$this->load->model("Customer_route_model");
		$this->Customer_route_model->deleteByRouteId(($routeId));

		$this->load->model("Route_model");
		$result = $this->Route_model->deleteById($routeId);
		if($result == 1){
			echo 1;
		} else {
			echo 0;
		}
	}

	public function assignById(){
		$customer_id		= $this->input->post('customer_id');
		$route_id			= $this->input->post('route_id');
		$this->load->model("Customer_route_model");
		$result = $this->Customer_route_model->assignById($customer_id, $route_id, 1);
		echo $result;
	}

	public function unassignById(){
		$customer_id		= $this->input->post('customer_id');
		$route_id			= $this->input->post('route_id');
		$this->load->model("Customer_route_model");
		$result = $this->Customer_route_model->assignById($customer_id, $route_id, 2);
		echo $result;
	}

	public function getAllItems(){
		$this->load->model("Route_model");
		$data = $this->Route_model->getAllItems();
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
