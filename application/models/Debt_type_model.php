<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debt_type_model extends CI_Model {
	private $table_type = 'debt_type';
		
		public $id;
		public $name;
		public $description;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->name					= $db_item->name;
			$this->description			= $db_item->description;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->name					= $this->name;
			$db_item->description			= $this->description;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Area_model();
			
			$stub->id					= $db_item->id;
			$stub->name					= $db_item->name;
			$stub->description			= $db_item->description;
			
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
}