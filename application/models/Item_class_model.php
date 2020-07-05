<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item_class_model extends CI_Model {
	private $table_item_class = 'item_class';
		
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
			$this->created_by			= $db_item->created_by;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id				= $this->id;
			$db_item->name				= $this->name;
			$db_item->description		= $this->description;
			$db_item->created_by		= $this->created_by;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Item_class_model();
			
			$stub->id					= $db_item->id;
			$stub->name					= $db_item->name;
			$stub->description			= $db_item->description;
			$stub->created_by			= $db_item->created_by;
			
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
			$this->db->select('item_class.*, COUNT(item.id) as quantity');
			if($term != ''){
				$this->db->like('item_class.name', $term, 'both');
				$this->db->or_like('item_class.description', $term, 'both');
			}
			
			$this->db->from('item_class');
			$this->db->join('item', 'item.type = item_class.id', 'left');
			$this->db->group_by('item.type');
			$this->db->limit($limit, $offset);
			
			$query		= $this->db->get();
			$result		= $query->result();
			
			return $result;
		}
		
		public function count_items($term = '')
		{
			if($term != ''){
				$this->db->like('name', $term, 'both');
				$this->db->or_like('description', $term, 'both');
			}
			
			$query		= $this->db->get($this->table_item_class);
			$result		= $query->num_rows();
			
			return $result;
		}
		
		public function show_all()
		{
			$query 		= $this->db->get($this->table_item_class);
			$items	 	= $query->result();
			
			$result 	= $this->map_list($items);
			
			return $result;
			
		}
		
		public function insert_from_post()
		{
			$this->db->select('*');
			$this->db->from($this->table_item_class);
			$this->db->where('name =', $this->input->post('item_class_name'));;
			$item = $this->db->count_all_results();
			
			if($item == 0){
				$this->id					= '';
				$this->name					= $this->input->post('item_class_name');
				$this->description			= $this->input->post('item_class_description');
				$this->created_by			= $this->session->userdata('user_id');
				$db_item 					= $this->get_db_from_stub($this);
				
				$db_result 					= $this->db->insert($this->table_item_class, $db_item);
			}
		}
		
		public function delete_by_id()
		{
			$this->db->where('id', $this->input->post('item_class_id'));
			$this->db->delete($this->table_item_class);
		}
		
		public function update_from_post($id, $name, $description)
		{
			$this->db->select('id');
			$this->db->from($this->table_item_class);
			$this->db->where('name', $name);
			$this->db->where('id !=',$id);
			$count = $this->db->count_all_results();
			
			if($count == 0){
				$this->db->set('name', $name);
				$this->db->set('description', $description);
				$this->db->where('id', $id);
				$this->db->update($this->table_item_class);
			}
		}
}