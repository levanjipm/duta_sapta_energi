<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Route_model extends CI_Model {
	private $table_route = 'routes';
		
		public $id;
		public $name;
		public $created_by;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id							= $db_item->id;
			$this->name							= $db_item->name;
			$this->addcreated_byess				= $db_item->created_by;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id						= $this->id;
			$db_item->name						= $this->name;
			$db_item->created_by				= $this->created_by;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Route_model();
			
			$stub->id						= $db_item->id;
			$stub->name						= $db_item->name;
			$stub->created_by				= $db_item->created_by;
			
			return $stub;
		}
		
		public function map_list($routes)
		{
			$result = array();
			foreach ($routes as $route)
			{
				$result[] = $this->get_new_stub_from_db($route);
			}
			return $result;
		}
		
		public function getItems($offset = 0, $term = ""){
			if($term != ""){
				$query		= $this->db->query("
					SELECT routes.name, routes.id, COALESCE(customer_route.count, 0) AS count
					FROM routes
					LEFT JOIN (
						SELECT COUNT(customer_route.id) AS count, customer_route.route_id
						FROM customer_route
						GROUP BY customer_route.route_id
					) AS customer_route
					ON customer_route.route_id = routes.id
					WHERE routes.name LIKE '%$term%'
					ORDER BY routes.name ASC
					LIMIT 10 OFFSET $offset
				");
			} else {
				$query		= $this->db->query("
					SELECT routes.name, routes.id, COALESCE(customer_route.count, 0) AS count
					FROM routes
					LEFT JOIN (
						SELECT COUNT(customer_route.id) AS count, customer_route.route_id
						FROM customer_route
						GROUP BY customer_route.route_id
					) AS customer_route
					ON customer_route.route_id = routes.id
					ORDER BY routes.name ASC
					LIMIT 10 OFFSET $offset
				");
			}

			$result		= $query->result();
			return $result;
		}

		public function countItems($term = ""){
			if($term != ""){
				$this->db->like('name', $term, 'both');
			}
			$query		= $this->db->get($this->table_route);
			$result		= $query->num_rows();
			return $result;
		}

		public function insertItem($route){

			$this->db->select('*');
			$this->db->from($this->table_route);
			$this->db->where('name =', $route);
			$items = $this->db->get();
			$count = $items->num_rows();
			
			if($count == 0){
				$this->id					= '';
				$this->name					= $route;
				$this->created_by			= $this->session->userdata('user_id');
				
				$db_item 					= $this->get_db_from_stub($this);
				$db_result 					= $this->db->insert($this->table_route, $db_item);
				return 1;
			} else {
				return 0;
			}
		}

		public function EditById($routeId, $routeName){
			$this->db->select("*");
			$this->db->from($this->table_route);
			$this->db->where('name =', $routeName);
			$items = $this->db->get();
			$count = $items->num_rows();

			if($count == 0){
				$this->db->where('id', $routeId);
				$this->db->set('name', $routeName);
				$this->db->update($this->table_route);
				if($this->db->affected_rows() == 1){
					return 1;
				} else {
					return 0;
				}
			} else {
				return 0;
			}
		}

		public function deleteById($id){
			$this->db->where('id', $id);
			$this->db->delete($this->table_route);
			if($this->db->affected_rows() == 1){
				return 1;
			} else {
				return 0;
			}
		}

		public function getAllItems(){
			$query		= $this->db->query("
				SELECT routes.name, routeTable.count, routes.id, routes.name
				FROM routes
				JOIN (
					SELECT COUNT(code_sales_order.id) AS count, customer_route.route_id
					FROM code_sales_order
					JOIN customer ON code_sales_order.customer_id = customer.id
					JOIN customer_route ON customer_route.customer_id = customer.id
					WHERE code_sales_order.id NOT IN
					(
						SELECT code_sales_order_close_request.code_sales_order_id
						FROM code_sales_order_close_request
						WHERE is_confirm = 1
					)
					AND code_sales_order.id IN (
						SELECT DISTINCT(sales_order.code_sales_order_id)
						FROM sales_order
						WHERE sales_order.status = 0
					)
					GROUP BY customer_route.route_id
				) AS routeTable
				ON routes.id = routeTable.route_id
				ORDER BY routes.name ASC
			");

			$result = $query->result();
			return $result;
		}
}
