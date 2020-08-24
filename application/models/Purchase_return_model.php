<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_return_model extends CI_Model {
	private $table_return = 'code_purchase_return';
		
		public $id;
		public $name;
		public $supplier_id;
		public $created_by;
		public $created_date;
		public $is_confirm;
		public $is_delete;
		public $confirmed_by;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->name					= $db_item->name;
			$this->supplier_id			= $db_item->supplier_id;
			$this->created_by			= $db_item->created_by;
			$this->created_date			= $db_item->created_date;
			$this->$is_confirm			= $db_item->$is_confirm;
			$this->$is_delete			= $db_item->$is_delete;
			$this->$confirmed_by		= $db_item->$confirmed_by;

			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->name					= $this->name;
			$db_item->supplier_id			= $this->supplier_id;
			$db_item->created_by			= $this->created_by;
			$db_item->created_date			= $this->created_date;
			$db_item->$is_confirm			= $this->$is_confirm;
			$db_item->$is_delete			= $this->$is_delete;
			$db_item->$confirmed_by			= $this->$confirmed_by;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Area_model();
			
			$stub->id					= $db_item->id;
			$stub->name					= $db_item->name;
			$stub->supplier_id			= $db_item->supplier_id;
			$stub->created_by			= $db_item->created_by;
			$stub->created_date			= $db_item->created_date;
			$stub->$is_confirm			= $db_item->$is_confirm;
			$stub->$is_delete			= $db_item->$is_delete;
			$stub->$confirmed_by		= $db_item->$confirmed_by;
			
			return $stub;
		}
		
		public function map_list($items)
		{
			$result = array();
			foreach ($items as $item)
			{
				$result[] = $this->get_new_stub_from_db($area);
			}
			return $result;
		}

		private function generateName($date)
		{
			$name		= "PRS-" . date('Ym', strtotime($date)) . "-" . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9);

			$this->db->where('name', $name);
			$query = $this->db->get($this->table_return);
			$result = $query->num_rows();
			if($result == 0){
				return $name;
			} else {
				$this->Purchase_return_model->generateName($date);
			}
		}
		
		public function insertItem()
		{
			$db_item = array(
				"id" => "",
				"supplier_id" => $this->input->post('supplier'),
				"created_by" => $this->session->userdata('user_id'),
				"created_date" => date("Y-m-d"),
				"name" => $this->Purchase_return_model->generateName(date('Y-m-d')),
				"is_confirm" => 0,
				"is_delete" => 0,
				"confirmed_by" => null
			);

			$this->db->insert($this->table_return, $db_item);
			$insertId		= $this->db->insert_id();

			return $insertId;
		}
}
