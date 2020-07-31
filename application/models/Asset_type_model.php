<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asset_type_model extends CI_Model {
	private $table_asset_type = 'fixed_asset_type';
		
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
			
			$db_item->id				= $this->id;
			$db_item->name				= $this->name;
			$db_item->description		= $this->description;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Item_class_model();
			
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
		
		public function show_all()
		{
			$query 		= $this->db->get($this->table_asset_type);
			$items	 	= $query->result();
						
			return $items;
		}
		
		public function input_from_post($name, $description)
		{
			$db_item	= array(
				'name' => $name,
				'description' => $description
			);
			
			$this->db->insert($this->table_asset_type, $db_item);
		}
		
		public function show_items($offset = 0, $term = '', $limit = 25)
		{
			if($term != ''){
				$this->db->like('name', $term, 'both');
				$this->db->or_like('description', $term, 'both');
			};
			
			$query	= $this->db->get($this->table_asset_type, $limit, $offset);
			$result	= $query->result();
			
			return $result;
		}
		
		public function count_items($term = '')
		{
			if($term != ''){
				$this->db->like('name', $term, 'both');
				$this->db->or_like('description', $term, 'both');
			};
			
			$query	= $this->db->get($this->table_asset_type);
			$result	= $query->num_rows();
			
			return $result;
		}
		
		public function show_by_id($id)
		{
			$this->db->where('id', $id);
			$query	= $this->db->get($this->table_asset_type);
			$result	= $query->row();
			
			return $result;
		}
		
		public function update($id, $name, $description)
		{
			$this->db->set('name', $name);
			$this->db->set('description', $description);
			$this->db->where('id', $id);
			
			$this->db->update($this->table_asset_type);
		}

		
		public function getAllItems()
		{
			$this->db->order_by('name');
			$query = $this->db->get($this->table_asset_type);
			$result = $query->result();

			return $result;
		}
}