<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Petty_cash_class_model extends CI_Model {
	private $table_petty_cash = 'petty_cash_class';
		
		public $id;
		public $name;
		public $parent_id;
		public $description;

		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id						= $db_item->id;
			$this->name						= $db_item->name;
			$this->parent_id				= $db_item->parent_id;
			$this->description				= $db_item->description;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->name					= $this->name;
			$db_item->parent_id				= $this->parent_id;
			$db_item->description			= $this->description;
			
			return $db_item;
		}
		
		public function update_db_from_stub()
		{
			$db_item = new class{};

			$db_item->id					= $this->id;
			$db_item->name					= $this->name;
			$db_item->parent_id				= $this->parent_id;
			$db_item->description			= $this->description;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Item_model();
			
			$stub->id						= $db_item->id;
			$stub->name						= $db_item->name;
			$stub->parent_id				= $db_item->parent_id;
			$stub->description				= $db_item->description;
			
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
		
		public function show_all($offset = 0, $term = '', $limit = 25)
		{
			$query		= $this->db->query("SELECT e.name AS parent_name, 
				e.id AS parent_id, 
				r.id AS child_id, 
				r.name AS child_name
				FROM petty_cash_class r
				LEFT JOIN petty_cash_class e 
				ON e.id = r.parent_id 
				ORDER BY COALESCE(parent_name, child_name),
				child_name;");
			$result		= $query->row();
			
			return $result;
		}
		
		public function count_all($term = '')
		{
			$query		= $this->db->query("SELECT e.name AS parent_name, 
				e.id AS parent_id, 
				r.id AS child_id, 
				r.name AS child_name
				FROM petty_cash_class r
				LEFT JOIN petty_cash_class e 
				ON e.id = r.parent_id 
				ORDER BY COALESCE(parent_name, child_name),
				child_name;");
			$result		= $query->result();
			
			return $result;
		}
}