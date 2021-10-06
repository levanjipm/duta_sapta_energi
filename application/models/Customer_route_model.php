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

		public function getCustomersByRouteId($routeId, $term = "", $offset = 0, $limit = 10){
			if($term == ""){
				$query		= $this->db->query("
					SELECT customer.name, customer.address, customer.rw, customer.rt, customer.id, customer.city, customer.number, customer.postal_code, customer.block, IF(customer_route.id IS NULL, 0, 1) AS assigned
					FROM customer
					LEFT JOIN customer_route ON customer_route.customer_id = customer.id
					ORDER BY customer.name ASC
					LIMIT $limit OFFSET $offset
				");
			} else {
				$query		= $this->db->query("
					SELECT customer.name, customer.address, customer.rw, customer.rw, customer.id,  customer.city, customer.number, customer.postal_code, customer.block, IF(customer_route.id IS NULL, 0, 1) AS assigned
					FROM customer
					LEFT JOIN customer_route ON customer_route.customer_id = customer.id
					WHERE customer.name LIKE '%$term%' OR customer.address LIKE '%$term%'
					OR customer.city LIKE '%$term%'
					ORDER BY customer.name ASC
					LIMIT $limit OFFSET $offset
				");
			}
			
			$result		= $query->result();
			return $result;
		}

		public function assignById($customerId, $routeId, $status){
			if($status == 1){
				$this->db_item = array(
					"id" => '',
					"customer_id" => $customerId,
					"route_id" => $routeId
				);

				$this->db->insert('customer_route', $this->db_item);
				return $this->db->insert_id();
			} else {
				$this->db->where('route_id', $routeId);
				$this->db->where('customer_id', $customerId);
				$this->db->delete($this->table_route);
				return $this->db->affected_rows();
			}
		}

		public function countUnassignedCustomer(){
			$query		= $this->db->query("
				SELECT customer.id
				FROM customer
				WHERE id NOT IN (
					SELECT customer_route.customer_id
					FROM customer_route
				)
			");

			$result		= $query->num_rows();
			return $result;
		}

		public function getCustomerByRouteId($routeId){
			$query		= $this->db->query("
				SELECT customer.*  
				FROM customer
				JOIN customer_route ON customer.id = customer_route.customer_id
				WHERE customer_route.route_id = '$routeId'
				ORDER BY customer.name ASC
			");

			$result		= $query->result();
			return $result;
		}

		public function getUnassignedCustomers(){
			$query		= $this->db->query("
				SELECT customer.*
				FROM customer
				WHERE customer.id NOT IN (
					SELECT customer_route.customer_id
					FROM customer_route
				)
				ORDER BY customer.name ASC
			");

			$result		= $query->result();
			return $result;
		}
}
