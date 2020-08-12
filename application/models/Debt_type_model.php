<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debt_type_model extends CI_Model {
	private $table_type = 'debt_type';
		
		public $id;
		public $name;
		public $description;
		public $is_operational;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->name					= $db_item->name;
			$this->description			= $db_item->description;
			$this->is_operational		= $db_item->is_operational;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->name					= $this->name;
			$db_item->description			= $this->description;
			$db_item->is_operational		= $this->is_operational;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Area_model();
			
			$stub->id					= $db_item->id;
			$stub->name					= $db_item->name;
			$stub->description			= $db_item->description;
			$stub->is_operational		= $db_item->is_operational;
			
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

		public function getItems($offset, $term = "", $limit = 10)
		{
			if($term != ""){
				$this->db->like('name', $term, 'both');
				$this->db->or_like('description', $term, 'both');
			};

			$this->db->order_by('name', 'ASC');
			$this->db->limit($limit, $offset);

			$query = $this->db->get($this->table_type);
			$result = $query->result();
			return $result;
		}

		public function countItems($term = "")
		{
			if($term != ""){
				$this->db->like('name', $term, 'both');
				$this->db->or_like('description', $term, 'both');
			};
			$query = $this->db->get($this->table_type);
			$result = $query->num_rows();
			return $result;
		}

		public function insertItem($name, $description, $operational)
		{
			$this->db->db_debug = false;

			$this->id 				= "";
			$this->name 			= $name;
			$this->is_operational	= $operational;
			$this->description 		= $description;

			$db_item 					= $this->get_db_from_stub($this);
			$db_result 					= $this->db->insert($this->table_type, $db_item);
			
			return $this->db->affected_rows();
		}

		public function deleteById($itemId)
		{
			$this->db->db_debug = false;
			$this->db->where('id', $itemId);
			$this->db->delete($this->table_type);

			$result = $this->db->affected_rows();
			return $result;
		}

		public function getById($itemId)
		{
			$this->db->where('id', $itemId);
			$query = $this->db->get($this->table_type);

			$result = $query->row();
			return $result;
		}

		public function updateById($itemId, $itemName, $itemDescription, $operational)
		{
			$this->db->db_debug = false;

			$this->db->set('name', $itemName);
			$this->db->set('description', $itemDescription);
			$this->db->set('is_operational', $operational);
			print_r($this->db->last_query());
			$this->db->where('id', $itemId);

			$query = $this->db->update($this->table_type);
			$result = $this->db->affected_rows();
			return $result;
		}

		public function getAllItems()
		{
			$this->db->order_by('name', 'asc');
			$query = $this->db->get($this->table_type);
			$result = $query->result();

			return $result;
		}
}