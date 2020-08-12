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
		
		public function showItems($offset = 0, $term = '', $limit = 25)
		{
			$query = $this->db->query("SELECT item_class.*, COALESCE(a.quantity,0) as quantity FROM item_class
				LEFT JOIN (
					SELECT COUNT(id) as quantity, type FROM item GROUP BY type
				) as a
				ON a.type = item_class.id
				WHERE item_class.name LIKE '%$term%' OR item_class.description LIKE '%$term%'
				LIMIT $limit OFFSET $offset");
			$result		= $query->result();
			
			return $result;
		}
		
		public function countItems($term = '')
		{
			if($term != ''){
				$this->db->like('name', $term, 'both');
				$this->db->or_like('description', $term, 'both');
			}
			
			$query		= $this->db->get($this->table_item_class);
			$result		= $query->num_rows();
			
			return $result;
		}
		
		public function showAllItems()
		{
			$query 		= $this->db->get($this->table_item_class);
			$items	 	= $query->result();
			
			$result 	= $this->map_list($items);
			
			return $result;
			
		}
		
		public function insertItem()
		{
			$this->db->select('*');
			$this->db->from($this->table_item_class);
			$this->db->where('name =', $this->input->post('item_class_name'));;
			$item = $this->db->count_all_results();
			
			if($item == 0){
				$this->id					= '';
				$this->name					= $this->input->post('name');
				$this->description			= $this->input->post('description');
				$this->created_by			= $this->session->userdata('user_id');
				$db_item 					= $this->get_db_from_stub($this);
				
				$db_result 					= $this->db->insert($this->table_item_class, $db_item);
				
				return $this->db->affected_rows();
			} else {
				return 0;
			}
		}
		
		public function deleteById($classId)
		{
			$this->db->where('id', $classId);
			$this->db->delete($this->table_item_class);
			
			return $this->db->affected_rows();
		}
		
		public function showById($id)
		{
			$this->db->where('id', $id);
			$query = $this->db->get($this->table_item_class);
			$result = $query->row();
			
			return $result;
		}
		
		public function updateById()
		{
			$id				= $this->input->post('id');
			$name			= $this->input->post('name');
			$description	= $this->input->post('description');
			
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
				
				return $this->db->affected_rows();
			} else {
				return 0;
			}
		}
}