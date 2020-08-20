<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense_class_model extends CI_Model {
	private $table_expense = 'expense_class';
		
		public $id;
		public $name;
		public $parent_id;
		public $description;
		public $created_by;
		public $created_date;
		public $type;

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
			$this->created_by				= $db_item->created_by;
			$this->created_date				= $db_item->created_date;
			$this->type						= $db_item->type;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->name					= $this->name;
			$db_item->parent_id				= $this->parent_id;
			$db_item->description			= $this->description;
			$db_item->created_by			= $this->created_by;
			$db_item->created_date			= $this->created_date;
			$db_item->type					= $this->type;
			
			return $db_item;
		}
		
		public function update_db_from_stub()
		{
			$db_item = new class{};

			$db_item->id					= $this->id;
			$db_item->name					= $this->name;
			$db_item->parent_id				= $this->parent_id;
			$db_item->description			= $this->description;
			$db_item->created_by			= $this->created_by;
			$db_item->created_date			= $this->created_date;
			$db_item->type					= $this->type;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Item_model();
			
			$stub->id						= $db_item->id;
			$stub->name						= $db_item->name;
			$stub->parent_id				= $db_item->parent_id;
			$stub->description				= $db_item->description;
			$stub->created_by				= $db_item->created_by;
			$stub->created_date				= $db_item->created_date;
			$stub->type						= $db_item->type;
			
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
		
		public function getItems()
		{			
			$query	= $this->db->get($this->table_expense);
			$result	= $query->result();
			
			return $result;
		}
		
		public function insertItem()
		{
			$name			= $this->input->post('name');
			$information	= $this->input->post('information');
			
			if($this->input->post('null_check') == 'on'){				
				$db_item	= array(
					'id' => '',
					'name' => $name,
					'parent_id' => null,
					'description' => $information,
					'created_by' => $this->session->userdata('user_id'),
					'created_date' => date('Y-m-d'),
					'type' => null
				);
			} else {
				$parent_id	= $this->input->post('parent_id');
				$db_item	= array(
					'id' => '',
					'name' => $name,
					'parent_id' => $parent_id,
					'description' => $information,
					'created_by' => $this->session->userdata('user_id'),
					'created_date' => date('Y-m-d'),
					'type' => $this->input->post('type')
				);
			}
			$this->db->insert($this->table_expense, $db_item);
			return $this->db->affected_rows();
		}
		
		public function getById($id)
		{
			$this->db->where('id', $id);
			$query	= $this->db->get($this->table_expense);
			$result	= $query->row();
			
			return $result;
		}
		
		public function updateById($name, $information, $type, $id, $parent_id = null)
		{
			$this->db->set('name', $name);
			$this->db->set('description', $information);
			$this->db->set('parent_id', $parent_id);
			$this->db->set('type', $type);
			
			$this->db->where('id', $id);
			$this->db->update($this->table_expense);

			return $this->db->affected_rows();
		}
}