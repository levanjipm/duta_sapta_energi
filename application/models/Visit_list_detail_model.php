<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visit_list_detail_model extends CI_Model {
	private $table_visit = 'visit_list';
		
		public $id;
		public $customer_id;
		public $code_visit_list_id;
		public $result;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->customer_id			= $db_item->customer_id;
			$this->code_visit_list_id		= $db_item->code_visit_list_id;
			$this->result				= $db_item->result;

			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->customer_id			= $this->customer_id;
			$db_item->code_visit_list_id		= $this->code_visit_list_id;
			$db_item->result				= $this->result;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Salary_slip_model();
			
			$stub->id					= $db_item->id;
			$stub->customer_id			= $db_item->customer_id;
			$stub->code_visit_list_id		= $db_item->code_visit_list_id;
			$stub->result				= $db_item->result;

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

		public function insertItem($customerIdArray, $codeVisitList)
		{
			$batch		= array();
			foreach($customerIdArray as $customerId)
			{
				$batch[]	= array(
					"id" => "",
					"customer_id" => $customerId,
					"code_visit_list_id" => $codeVisitList,
					"result" => 0
				);

				next($customerIdArray);
			}

			$this->db->insert_batch($this->table_visit, $batch);
		}

		public function getByCodeId($id)
		{
			$query			= $this->db->query("
				SELECT visit_list.id, customer.name, customer.address, customer.number, customer.block, customer.rt, customer.rw, customer.postal_code, customer.city, visit_list.result, visit_list.note
				FROM
				visit_list
				JOIN customer
				ON visit_list.customer_id = customer.id
				WHERE visit_list.code_visit_list_id = '$id'
			");
			$result			= $query->result();
			return $result;
		}

		public function updateReport()
		{
			$resultArray		= $this->input->post('result');
			$noteArray			= $this->input->post('note');

			$batch				= array();

			foreach($resultArray as $visitListId => $result)
			{
				$batch[]	= array(
					"id" => $visitListId,
					"result" => $result,
					"note" => $this->db->escape_str($noteArray[$visitListId])
				);

				next($resultArray);
			}

			$this->db->update_batch($this->table_visit, $batch, "id");
		}

		public function getRecap()
		{

		}
}
