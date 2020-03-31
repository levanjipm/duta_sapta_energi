<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Other_bank_account_model extends CI_Model {
	private $table_account = 'other_bank_account';
		
		public $id;
		public $name;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->name					= $db_item->name;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id				= $this->id;
			$db_item->name				= $this->name;
			
			return $db_item;
		}
		
		public function update_db_from_stub()
		{
			$db_item = new class{};

			$db_item->id				= $this->id;
			$db_item->name				= $this->name;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Item_model();
			
			$stub->id					= $db_item->id;
			$stub->name					= $db_item->name;
			
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
		
		public function show_items($offset = 0, $term = '', $limit = 25)
		{
			if($term != ''){
				$this->db->like('name', $term, 'both');
				$this->db->limit($limit, $offset);
			}
			$this->db->order_by('name', 'asc');
			$query 		= $this->db->get($this->table_account, $limit, $offset);
			$accounts 	= $query->result();
			
			return $accounts;	
		}
		
		public function count_items($term = '')
		{
			if($term != ''){
				$this->db->like('name', $term, 'both');
				$this->db->limit($limit, $offset);
			}
			$query 		= $this->db->get($this->table_account);
			$accounts 	= $query->num_rows();
			
			return $accounts;	
		}
		
		public function insert_from_post()
		{
			$name			= $this->input->post('name');
			$db_item		= array(
				'id' => '',
				'name' => $name
			);
			
			$this->db->insert($this->table_account, $db_item);
		}
}