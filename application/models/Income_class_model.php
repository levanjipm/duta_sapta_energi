<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Income_class_model extends CI_Model {
	private $table_income = 'income_class';
		
		public $id;
		public $name;
		public $description;
		public $created_by;
		public $created_date;

		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id						= $db_item->id;
			$this->name						= $db_item->name;
			$this->description				= $db_item->description;
			$this->created_by				= $db_item->created_by;
			$this->created_date				= $db_item->created_date;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->name					= $this->name;
			$db_item->description			= $this->description;
			$db_item->created_by			= $this->created_by;
			$db_item->created_date			= $this->created_date;
			
			return $db_item;
		}
		
		public function update_db_from_stub()
		{
			$db_item = new class{};

			$db_item->id					= $this->id;
			$db_item->name					= $this->name;
			$db_item->description			= $this->description;
			$db_item->created_by			= $this->created_by;
			$db_item->created_date			= $this->created_date;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Item_model();
			
			$stub->id						= $db_item->id;
			$stub->name						= $db_item->name;
			$stub->description				= $db_item->description;
			$stub->created_by				= $db_item->created_by;
			$stub->created_date				= $db_item->created_date;
			
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
			if($term != ''){
				$this->db->like('name', $term, 'both');
				$this->db->or_like('description', $term, 'both');
			}
			
			$query	= $this->db->get($this->table_income, $limit, $offset);
			$result	= $query->result();
			
			return $result;
		}
		
		public function count_all($term)
		{
			if($term != ''){
				$this->db->like('name', $term, 'both');
				$this->db->or_like('description', $term, 'both');
			}
			
			$query	= $this->db->get($this->table_income);
			$result	= $query->num_rows();
			
			return $result;
		}
		
		public function insert_from_post()
		{
			$db_item		= array(
				'name' => $this->input->post('name'),
				'description' => $this->input->post('information'),
				'created_by' => $this->session->userdata('user_id'),
				'created_date' => date('Y-m-d')
			);
			
			$this->db->insert($this->table_income, $db_item);
		}
}