<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brand_model extends CI_Model {
	private $table_brand = 'brand';
		
		public $id;
		public $name;
		public $created_by;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->name					= $db_item->name;
			$this->created_by			= $db_item->created_by;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->name					= $this->name;
			$db_item->created_by			= $this->created_by;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Brand_model();
			
			$stub->id					= $db_item->id;
			$stub->name					= $db_item->name;
			$stub->created_by			= $db_item->created_by;
			
			return $stub;
		}
		
		public function map_list($brands)
		{
			$result = array();
			foreach ($brands as $brand)
			{
				$result[] = $this->get_new_stub_from_db($brand);
			}
			return $result;
		}

		public function getItems()
		{
			$this->db->order_by('name');
			$query 		= $this->db->get($this->table_brand);
			$areas	 	= $query->result();
			
			$items = $this->map_list($areas);
			
			return $items;
			
		}
		
		public function showItems($offset = 0, $term = '', $limit = 10)
		{
			if($term != ""){
				$this->db->like('name', $term, 'both');
			}
			$this->db->limit($limit, $offset);
			$this->db->order_by('name');
			$query = $this->db->get($this->table_brand);
			$result = $query->result();
			
			return $result;
			
		}
		
		public function countItems($term = "")
		{
			if($term != ""){
				$this->db->like('name', $term, 'both');
			}
			
			$query = $this->db->get($this->table_brand);
			$result = $query->num_rows();
			
			return $result;
		}

		public function insertItem($name)
		{
			if($name != ""){
				$this->db->where('name', $name);
				$query = $this->db->get($this->table_brand);
				$count = $query->num_rows();

				if($count == 0){
					$this->id		= "";
					$this->name		= $name;
					$this->created_by	= $this->session->userdata('user_id');
	
					$db_item 					= $this->get_db_from_stub($this);
					$db_result 					= $this->db->insert($this->table_brand, $db_item);
	
					return ($this->db->affected_rows() == 0) ? NULL : $this->db->insert_id();
				} else {
					return NULL;
				}
				
			} else {
				return NULL;
			}
		}

		public function deleteItem($id)
		{
			$this->db->db_debug = FALSE;
			$this->db->where('id', $id);
			$query = $this->db->delete($this->table_brand);
			$result = $query->num_rows;

			return $result;
		}

		public function updateItem($id, $name)
		{
			$this->db->db_debug = FALSE;
			$this->db->where('id', $id);
			$this->db->set('name', $name);
			$query = $this->db->update($this->table_brand);
			$result = $this->db->affected_rows();

			return $result;
		}
}