<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing_model extends CI_Model {
	private $table_billing = 'code_billing';
		
		public $id;
		public $date;
		public $name;
		public $created_by;
		public $is_confirm;
		public $is_delete;
		public $confirmed_by;
		public $billed_by;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->date					= $db_item->date;
			$this->name					= $db_item->name;
			$this->created_by			= $db_item->created_by;
			$this->is_confirm			= $db_item->is_confirm;
			$this->is_delete			= $db_item->is_delete;
			$this->confirmed_by			= $db_item->confirmed_by;
			$this->billed_by			= $db_item->billed_by;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->date					= $this->date;
			$db_item->name					= $this->name;
			$db_item->created_by			= $this->created_by;
			$db_item->is_confirm			= $this->is_confirm;
			$db_item->is_delete				= $this->is_delete;
			$db_item->confirmed_by			= $this->confirmed_by;
			$db_item->billed_by				= $this->billed_by;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new User_model();
			
			$stub->id					= $db_item->id;
			$stub->date					= $db_item->date;
			$stub->name					= $db_item->name;
			$stub->created_by			= $db_item->created_by;
			$stub->is_confirm			= $db_item->is_confirm;
			$stub->is_delete			= $db_item->is_delete;
			$stub->confirmed_by			= $db_item->confirmed_by;
			$stub->billed_by			= $db_item->billed_by;
			
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

		private function generateName($date)
		{
			$name = "CB-" . date('Y', strtotime($date)) . "-" . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9);
			$validation = false;
			while($validation = false){
				$this->db->where('name', $name);
				$query		= $this->db->get($this->table_billing);
				$result		= $query->num_rows();
				if($result == 0){
					$validation = true;
					break;
				} else {
					generateName($date);
				}
			}
			return $name;
		}
		
		public function insertItem($date, $collector)
		{
			$name = $this->Billing_model->generateName($date);
			$db_item = array(
				'id' => '',
				'date' => $date,
				'name' => $name,
				'created_by' => $this->session->userdata('user_id'),
				'is_confirm' => 0,
				'is_delete' => 0,
				'confirmed_by' => null,
				'billed_by' => $collector
			);

			$this->db->insert($this->table_billing, $db_item);
			return $this->db->insert_id();
		}
}