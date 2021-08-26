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

		$this->load->Model("Customer_route_model");
		$data['customers']		= $this->Customer_model->
		
		$this->load->view('head');
		$this->load->view('inventory/header', $data);
		$this->load->view('inventory/Route/assignCustomer');
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
}
