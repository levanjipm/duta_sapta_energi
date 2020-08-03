<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Area_model extends CI_Model {
	private $table_area = 'customer_area';
		
		public $id;
		public $name;
		public $major_id;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->name					= $db_item->name;
			$this->major_id				= $db_item->major_id;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->name					= $this->name;
			$db_item->major_id				= $this->major_id;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Area_model();
			
			$stub->id					= $db_item->id;
			$stub->name					= $db_item->name;
			$stub->major_id				= $db_item->major_id;
			
			return $stub;
		}
		
		public function map_list($areas)
		{
			$result = array();
			foreach ($areas as $area)
			{
				$result[] = $this->get_new_stub_from_db($area);
			}
			return $result;
		}
		
		public function showAllItems()
		{
			$query 		= $this->db->get($this->table_area);
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
			$query = $this->db->get($this->table_area);
			$result = $query->result();
			
			return $result;
			
		}
		
		public function countItems($term = "")
		{
			if($term != ""){
				$this->db->like('name', $term, 'both');
			}
			
			$query = $this->db->get($this->table_area);
			$result = $query->num_rows();
			
			return $result;
		}
		
		public function insertItem()
		{
			$this->db->db_debug = false;
			$this->db->select('*');
			$this->db->from($this->table_area);
			$this->db->where('name =', $this->input->post('name'));;
			$item = $this->db->count_all_results();
			
			if($item == 0){
				$this->id					= '';
				$this->name					= $this->input->post('name');
				$db_item 					= $this->get_db_from_stub($this);
				$db_result 					= $this->db->insert($this->table_area, $db_item);
				
				return $this->db->affected_rows();
			} else {
				return 0;
			}
		}
		
		public function updateById($area_id, $area_name)
		{
			$this->db->where('id !=', $area_id);
			$this->db->where('name', $area_name);
			$query		= $this->db->get($this->table_area);
			$num_rows	= $query->num_rows();
			
			if($num_rows	== 0){
				$this->db->set('name', $area_name);
				$this->db->where('id', $area_id);
				$this->db->update($this->table_area);
				
				return $this->db->affected_rows();
			} else {
				return 0;
			}
		}
		
		public function deleteById($area_id)
		{
			$this->db->db_debug = FALSE;
			$this->db->where('id', $area_id);
			$this->db->delete($this->table_area);
			
			if($this->db->affected_rows() == 1){
				return 1;
			} else {
				return 0;
			}
		}
		
		public function getItemById($area_id)
		{
			$this->db->where('id', $area_id);
			$query = $this->db->get($this->table_area);
			
			$result = $query->row();
			
			return $result;
		}

		public function getAllItems()
		{
			$this->db->order_by('name');
			$query = $this->db->get($this->table_area);
			$result = $query->result();

			return $result;
		}
}