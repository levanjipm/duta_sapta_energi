<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_sales_model extends CI_Model {
	private $table_customer_sales = 'customer_sales';
		
		public $id;
		public $customer_id;
		public $sales_id;

		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->customer_id			= $db_item->customer_id;
			$this->sales_id				= $db_item->sales_id;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->customer_id			= $this->customer_id;
			$db_item->sales_id				= $this->sales_id;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Customer_model();
			
			$stub->id					= $db_item->id;
			$stub->customer_id			= $db_item->customer_id;
			$stub->sales_id				= $db_item->sales_id;
			
			return $stub;
		}
		
		public function map_list($items)
		{
			$result = array();
			foreach ($items as $item)
			{
				$result[] = $this->get_new_stub_from_db($item);
			}
			return $result;
		}

		public function getBySales($salesId, $offset = 0, $term = "", $includedAreas = array(), $limit = 10)
		{
			$areaFilter = "";
			if(count($includedAreas) > 0){
				$areaFilter = "customer.area_id IN (";
				foreach($includedAreas as $area){
					$areaFilter .= "'" . $area . "',";
					next($includedAreas);
				}

				$areaFilter = substr($areaFilter, 0, -1);
				$areaFilter .= ") AND";
			}

			$query			= $this->db->query("
				SELECT customer.*, IF(a.id IS NULL, 0, 1) AS status
				FROM customer
				LEFT JOIN (
					SELECT DISTINCT(customer_sales.customer_id) AS customer_id, customer_sales.id
					FROM customer_sales
					WHERE customer_sales.sales_id = '$salesId'
				) AS a
				ON a.customer_id = customer.id
				WHERE $areaFilter (
					customer.name LIKE '%$term%'
					OR customer.address LIKE '%$term%'
					OR customer.city LIKE '%$term%'
				)
				ORDER BY customer.name
				LIMIT $limit OFFSET $offset
			");


			$result		= $query->result();
			return $result;
		}

		public function countBySales($salesId, $term = "", $includedAreas = array()){
			$areaFilter = "";
			if(count($includedAreas) > 0){
				$areaFilter = "customer.area_id IN (";
				foreach($includedAreas as $area){
					$areaFilter .= "'" . $area . "',";
					next($includedAreas);
				}

				$areaFilter = substr($areaFilter, 0, -1);
				$areaFilter .= ") AND";
			}

			$query			= $this->db->query("
				SELECT customer.id
				FROM customer
				WHERE $areaFilter (
					customer.name LIKE '%$term%'
					OR customer.address LIKE '%$term%'
					OR customer.city LIKE '%$term%'
				)
			");

			$result		= $query->num_rows();
			return $result;
		}

		public function updateCustomerList($customerArray, $status, $salesId =  null)
		{
			if($status == 0){
				$this->db->where_in('customer_id', $customerArray);
				$this->db->delete($this->table_customer_sales);

			} else if($status == 1){
				$this->db->where_in('customer_id', $customerArray);
				$this->db->where('sales_id', $salesId);
				$query		= $this->db->get($this->table_customer_sales);
				$result		= $query->result();

				$existCustomerArray = array();
				foreach($result as $data){
					array_push($existCustomerArray, $data->customer_id);
				}
				
				$finalCustomerArray		= array_diff($customerArray, $existCustomerArray);
				$itemArray		= array();
				foreach($finalCustomerArray as $finalCustomer){
					$itemArray[] = array(
						"id" => "",
						"customer_id" => $finalCustomer,
						"sales_id" => $salesId
					);
				}
				if(count($itemArray) > 0){
					$this->db->insert_batch($this->table_customer_sales, $itemArray);
				}
			}
		}

		public function getByCustomerUID($customerUID)
		{
			$query		= $this->db->query("
				SELECT users.name, users.email
				FROM users
				WHERE users.id IN (
					SELECT customer_sales.sales_id
					FROM customer_sales
					JOIN customer ON customer_sales.customer_id = customer.id
					WHERE customer.uid = '$customerUID'
				)
				AND users.is_active = '1'
				ORDER BY users.access_level ASC, users.name ASC
			");

			$result		= $query->row();
			return ($result == null) ? null : $result;
		}
	}
?>
