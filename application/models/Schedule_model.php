<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule_model extends CI_Model {
	private $table_route = 'customer_schedule';
		
		public $customer_id;
		public $day;
		public $id;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id							= $db_item->id;
			$this->customer_id					= $db_item->customer_id;
			$this->day							= $db_item->day;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id						= $this->id;
			$db_item->customer_id				= $this->customer_id;
			$db_item->day						= $this->day;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Route_model();
			
			$stub->id						= $db_item->id;
			$stub->customer_id				= $db_item->customer_id;
			$stub->day						= $db_item->day;
			
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
		
		public function getItems($term = "", $offset = 0, $limit = 10){
			$query		= $this->db->query("
				SELECT customer.*, scheduleTable.day
				FROM customer
				LEFT JOIN (
					SELECT customer_schedule.day, customer_schedule.customer_id
					FROM customer_schedule
				) AS scheduleTable
				ON scheduleTable.customer_id = customer.id
				WHERE customer.name LIKE '%$term%'
				OR customer.address LIKE '%$term%'
				OR customer.pic_name LIKE '%$term%'
				OR customer.city LIKE '%$term%'
				ORDER BY customer.name ASC
				LIMIT $limit OFFSET $offset
			");

			$result		= $query->result();
			return $result;
		}

		public function countItems($term = ""){
			$query		= $this->db->query("
				SELECT customer.id
				FROM customer
			");

			$result		= $query->num_rows();
			return $result;
		}
}
