<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule_model extends CI_Model {
	private $table_schedule = 'customer_schedule';
		
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
				SELECT customer.*, 
				CASE WHEN monday.id IS NOT NULL THEN 1 ELSE 0 END AS mon,
				CASE WHEN tuesday.id IS NOT NULL THEN 1 ELSE 0 END AS tue,
				CASE WHEN wednesday.id IS NOT NULL THEN 1 ELSE 0 END AS wed,
				CASE WHEN thursday.id IS NOT NULL THEN 1 ELSE 0 END AS thu,
				CASE WHEN friday.id IS NOT NULL THEN 1 ELSE 0 END AS fri,
				CASE WHEN saturday.id IS NOT NULL THEN 1 ELSE 0 END AS sat,
				CASE WHEN sunday.id IS NOT NULL THEN 1 ELSE 0 END AS sun

				FROM customer
				LEFT JOIN (
					SELECT customer_schedule.id, customer_schedule.customer_id
					FROM customer_schedule
					WHERE customer_schedule.day = 0
				) AS monday
				ON monday.customer_id = customer.id

				LEFT JOIN (
					SELECT customer_schedule.id, customer_schedule.customer_id
					FROM customer_schedule
					WHERE customer_schedule.day = 1
				) AS tuesday
				ON tuesday.customer_id = customer.id

				LEFT JOIN (
					SELECT customer_schedule.id, customer_schedule.customer_id
					FROM customer_schedule
					WHERE customer_schedule.day = 2
				) AS wednesday
				ON wednesday.customer_id = customer.id

				LEFT JOIN (
					SELECT customer_schedule.id, customer_schedule.customer_id
					FROM customer_schedule
					WHERE customer_schedule.day = 3
				) AS thursday
				ON thursday.customer_id = customer.id

				LEFT JOIN (
					SELECT customer_schedule.id, customer_schedule.customer_id
					FROM customer_schedule
					WHERE customer_schedule.day = 4
				) AS friday
				ON friday.customer_id = customer.id

				LEFT JOIN (
					SELECT customer_schedule.id, customer_schedule.customer_id
					FROM customer_schedule
					WHERE customer_schedule.day = 5
				) AS saturday
				ON saturday.customer_id = customer.id

				LEFT JOIN (
					SELECT customer_schedule.id, customer_schedule.customer_id
					FROM customer_schedule
					WHERE customer_schedule.day = 6
				) AS sunday
				ON sunday.customer_id = customer.id

				WHERE customer.name LIKE '%$term%'
				OR customer.address LIKE '%$term%'
				OR customer.pic_name LIKE '%$term%'
				OR customer.city LIKE '%$term%'
				ORDER BY customer.name ASC
				LIMIT $limit OFFSET $offset
			");

			$response = array();

			$result		= $query->result();
			foreach($result as $data){
				array_push($response, array(
					"id" => $data->id,
					"address" => $data->address,
					"block" => $data->block,
					"city" => $data->city,
					"date_created" => $data->date_created,
					"is_black_list" => $data->is_black_list,
					"is_remind" => $data->is_remind,
					"latitude" => $data->latitude,
					"longitude" => $data->longitude,
					"name" => $data->name,
					"npwp" => $data->npwp,
					"number" => $data->number,
					"phone_number" => $data->phone_number,
					"pic_name" => $data->pic_name,
					"plafond" => $data->plafond,
					"postal_code" => $data->postal_code,
					"rt" => $data->rt,
					"rw" => $data->rw,
					"term_of_payment" => $data->term_of_payment,
					"uid" => $data->uid,
					"schedule" => array($data->mon, $data->tue, $data->wed, $data->thu, $data->fri, $data->sat, $data->sun)
				));
			}
			return $response;
		}

		public function countItems($term = ""){
			$query		= $this->db->query("
				SELECT customer.id
				FROM customer
			");

			$result		= $query->num_rows();
			return $result;
		}

		public function getByCustomerId($id){
			$query			= $this->db->query("
				SELECT customer.*, 
				CASE WHEN monday.id IS NOT NULL THEN 1 ELSE 0 END AS mon,
				CASE WHEN tuesday.id IS NOT NULL THEN 1 ELSE 0 END AS tue,
				CASE WHEN wednesday.id IS NOT NULL THEN 1 ELSE 0 END AS wed,
				CASE WHEN thursday.id IS NOT NULL THEN 1 ELSE 0 END AS thu,
				CASE WHEN friday.id IS NOT NULL THEN 1 ELSE 0 END AS fri,
				CASE WHEN saturday.id IS NOT NULL THEN 1 ELSE 0 END AS sat,
				CASE WHEN sunday.id IS NOT NULL THEN 1 ELSE 0 END AS sun

				FROM customer
				LEFT JOIN (
					SELECT customer_schedule.id, customer_schedule.customer_id
					FROM customer_schedule
					WHERE customer_schedule.day = 0
				) AS monday
				ON monday.customer_id = customer.id

				LEFT JOIN (
					SELECT customer_schedule.id, customer_schedule.customer_id
					FROM customer_schedule
					WHERE customer_schedule.day = 1
				) AS tuesday
				ON tuesday.customer_id = customer.id

				LEFT JOIN (
					SELECT customer_schedule.id, customer_schedule.customer_id
					FROM customer_schedule
					WHERE customer_schedule.day = 2
				) AS wednesday
				ON wednesday.customer_id = customer.id

				LEFT JOIN (
					SELECT customer_schedule.id, customer_schedule.customer_id
					FROM customer_schedule
					WHERE customer_schedule.day = 3
				) AS thursday
				ON thursday.customer_id = customer.id

				LEFT JOIN (
					SELECT customer_schedule.id, customer_schedule.customer_id
					FROM customer_schedule
					WHERE customer_schedule.day = 4
				) AS friday
				ON friday.customer_id = customer.id

				LEFT JOIN (
					SELECT customer_schedule.id, customer_schedule.customer_id
					FROM customer_schedule
					WHERE customer_schedule.day = 5
				) AS saturday
				ON saturday.customer_id = customer.id

				LEFT JOIN (
					SELECT customer_schedule.id, customer_schedule.customer_id
					FROM customer_schedule
					WHERE customer_schedule.day = 6
				) AS sunday
				ON sunday.customer_id = customer.id
				WHERE customer.id = '$id'
			");

			$data		= $query->row();
			$response	= array(
				"id" => $data->id,
				"address" => $data->address,
				"block" => $data->block,
				"city" => $data->city,
				"date_created" => $data->date_created,
				"is_black_list" => $data->is_black_list,
				"is_remind" => $data->is_remind,
				"latitude" => $data->latitude,
				"longitude" => $data->longitude,
				"name" => $data->name,
				"npwp" => $data->npwp,
				"number" => $data->number,
				"phone_number" => $data->phone_number,
				"pic_name" => $data->pic_name,
				"plafond" => $data->plafond,
				"postal_code" => $data->postal_code,
				"rt" => $data->rt,
				"rw" => $data->rw,
				"term_of_payment" => $data->term_of_payment,
				"uid" => $data->uid,
				"schedule" => array($data->mon, $data->tue, $data->wed, $data->thu, $data->fri, $data->sat, $data->sun)
			);

			return $response;
		}

		public function editByCustomerId($customerId, $scheduleArray){
			$this->db->where('customer_id', $customerId);
			$this->db->delete($this->table_schedule);

			$finalArray = array();
			foreach($scheduleArray as $day => $value){
				if($value == 1){
					array_push($finalArray, array(
						"id" => "",
						"customer_id" => $customerId,
						"day" => $day
					));
				}
			}

			$this->db->insert_batch($this->table_schedule, $finalArray);
			return $this->db->affected_rows();
		}

		public function getByDay($day){
			$query		= $this->db->query("
				SELECT customer.*
				FROM customer
				JOIN customer_schedule ON customer.id = customer_schedule.customer_id
				WHERE customer_schedule.day = $day
			");

			$result		= $query->result();
			return $result;
		}

		public function countPendingAssignment(){
			$query			= $this->db->query("
				SELECT customer.id
				FROM customer
				WHERE customer.id NOT IN (
					SELECT DISTINCT(customer_id)
					FROM customer_schedule
				)
			");

			$result		= $query->num_rows();
			return $result;
		}

		public function getUnassignedCustomer(){
			$query			= $this->db->query("
				SELECT customer.*
				FROM customer
				WHERE customer.id NOT IN (
					SELECT DISTINCT(customer_id)
					FROM customer_schedule
				)
				ORDER BY customer.name ASC
			");

			$result			= $query->result();
			return $result;
		}
}
