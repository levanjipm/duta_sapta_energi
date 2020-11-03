<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_accountant_model extends CI_Model {
	private $table_customer_accountant = 'customer_accountant';
		
		public $id;
		public $customer_id;
		public $accountant_id;

		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->$customer_id			= $db_item->$customer_id;
			$this->$accountant_id		= $db_item->$accountant_id;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->$customer_id			= $this->$customer_id;
			$db_item->$accountant_id		= $this->$accountant_id;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Item_class_model();
			
			$stub->id					= $db_item->id;
			$stub->$customer_id			= $db_item->$customer_id;
			$stub->$accountant_id		= $db_item->$accountant_id;
			
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

		public function updateByAccountant($type, $customerIdArray, $accountantId)
		{
			if($type == 1){
				$batch = array();
				$this->db->where_in('customer_id', $customerIdArray);
				$this->db->where('accountant_id', $accountantId);
				$query			= $this->db->get($this->table_customer_accountant);
				$result			= $query->result();

				$customerIdArrayExist = array();
				foreach($result as $item){
					array_push($customerIdArrayExist, $item->customer_id);
				}

				$finalCustomerArray		= array_diff($customerIdArray, $customerIdExist);

				foreach($finalCustomerArray as $finalCustomer){
					$batch[]	= $finalCustomer;
				}

				if(count($batch) > 0){
					$this->db->insert_batch($this->table_customer_accountant, $batch);
				}

			} else if($type == 0){
				$this->db->where_in('customer_id', $customerIdArray);
				$this->db->where('accountant_id', $accountantId);
				$this->db->delete($this->table_customer_accountant);
			} else if($type == 2){
				$batch			= array();
				$customerIdArray = array();
				$this->load->model("Customer_model");
				$customerObjects = $this->Customer_model->showCustomerAccountantItems(0, "", $accountantId, 0);
				foreach($customerObjects as $customerObject)
				{
					array_push($customerIdArray, $customerObject->id);
				}
				
				$this->db->where_in('customer_id', $customerIdArray);
				$this->db->where('accountant_id', $accountantId);
				$query			= $this->db->get($this->table_customer_accountant);
				$result			= $query->result();

				$customerIdArrayExist = array();
				foreach($result as $item){
					array_push($customerIdArrayExist, $item->customer_id);
				}

				$finalCustomerArray		= array_diff($customerIdArray, $customerIdArrayExist);

				foreach($finalCustomerArray as $finalCustomer){
					$batch[]		= array(
						"id" => "",
						"customer_id" => $finalCustomer,
						"accountant_id" => $accountantId
					);
				}

				$this->db->insert_batch($this->table_customer_accountant, $batch);
			}
		}

		public function countUnassignedCustomers()
		{
			$query			= $this->db->query("
				SELECT customer.id FROM customer
				JOIN (
					SELECT DISTINCT(customer_accountant.customer_id) as id
					FROM customer_accountant
					JOIN users ON customer_accountant.accountant_id = users.id
					JOIN user_authorization ON user_authorization.user_id = users.id
					WHERE user_authorization.department_id = '1'
					AND users.is_active = '1'
				) AS a
				ON customer.id = a.id
				WHERE customer.is_black_list = '0'
			");

			$assignedCustomers = $query->num_rows();

			$query			= $this->db->query("
				SELECT id FROM customer
				WHERE customer.is_black_list = '0'
			");

			$totalCustomers = $query->num_rows();
			$result = array(
				'assignedCustomers' => $assignedCustomers,
				'totalCustomers' => $totalCustomers
			);

			return $result;
		}

}
