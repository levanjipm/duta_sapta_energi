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

		public function getUnconfirmedItems($offset = 0, $term = "", $limit = 10)
		{
			$this->db->select("code_purchase_return.*, supplier.name as supplierName");
			$this->db->from('code_purchase_return');
			$this->db->join('supplier', 'code_purchase_return.supplier_id = supplier.id');
			
			$this->db->where('code_purchase_return.is_confirm', 0);
			$this->db->where('code_purchase_return.is_delete', 0);
			if($term != ""){
				$this->db->like('code_purchase_return.name', $term, 'both');
				$this->db->or_like('supplier.name', $term, 'both');
			}
			$this->db->limit($limit, $offset);
			$query			= $this->db->get();
			$result			= $query->result();
			return $result;
		}

		public function countUnconfirmedItems($term = "")
		{
			$this->db->where('is_confirm', 0);
			$this->db->where('is_delete', 0);
			if($term != ""){
				$this->db->like('name', $term, 'both');
			}
			$query			= $this->db->get($this->table_return);
			$result			= $query->num_rows();
			return $result;
		}

		public function getById($id)
		{
			$query		= $this->db->query("
				SELECT code_purchase_return.*, users.name as created_by
				FROM code_purchase_return
				JOIN users ON code_purchase_return.created_by = users.id
				WHERE code_purchase_return.id = '$id'
			");

			$result		= $query->row();
			return $result;
		}

		public function getIncompletedReturn($offset = 0, $term = "", $limit = 10)
		{
			$this->db->select('code_purchase_return.*');
			$this->db->from('code_purchase_return');
			$this->db->join('supplier', 'code_purchase_return.supplier_id = supplier.id');
			if($term != ""){
				$this->db->like('code_purchase_return.name', $term);
				$this->db->like('supplier.name', $term);
			}
			$this->db->where('code_purchase_return.is_confirm', 1);
			$this->db->limit($limit, $offset);
			$query		 = $this->db->get();
			$result		= $query->result();
			return $result;
		}

		public function countIncompletedReturn($term = "")
		{
			if($term != ""){
				$this->db->like('purchase_return.name', $term);
				$this->db->like('supplier.name', $term);
			}
			$query		 = $this->db->get($this->table_return);
			$result		= $query->num_rows();
			return $result;
		}
}
