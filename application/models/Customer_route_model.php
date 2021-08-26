<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_route_model extends CI_Model {
	private $table_route = 'customer_route';
		
		public $id;
		public $customer_id;
		public $route_id;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->customer_id			= $db_item->customer_id;
			$this->route_id				= $db_item->route_id;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->customer_id			= $this->customer_id;
			$db_item->route_id				= $this->route_id;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Customer_model();
			
			$stub->id					= $db_item->id;
			$stub->customer_id			= $db_item->customer_id;
			$stub->route_id				= $db_item->route_id;
			
			return $stub;
		}
		
		public function map_list($routes)
		{
			$result = array();
			foreach ($routes as $route)
			{
				$result[] = $this->get_new_stub_from_db($route);
				continue;
			}
			return $result;
		}
		
		public function deleteByRouteId($routeId){
			$this->db->where('route_id', $routeId);
			$query		= $this->db->delete($this->table_route);
		}
}
