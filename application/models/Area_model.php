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
		
		public function show_all()
		{
			$query 		= $this->db->get($this->table_area);
			$areas	 	= $query->result();
			
			$items = $this->map_list($areas);
			
			return $items;
			
		}
		
		public function show_limited($offset = 0, $term = '', $limit = 25)
		{
			$query	= $this->db->query("SELECT asdf.customer, `customer_area`.* FROM `customer_area` LEFT JOIN 
						(SELECT COUNT(customer.id) as customer, area_id FROM customer GROUP BY customer.area_id) AS asdf 
						ON asdf.area_id = customer_area.id LIMIT $limit OFFSET $offset");
			$areas	 	= $query->result();
			
			return $areas;
			
		}
		
		public function insert_from_post()
		{
			$this->db->select('*');
			$this->db->from($this->table_area);
			$this->db->where('name =', $this->input->post('area'));;
			$item = $this->db->count_all_results();
			
			if($item == 0){
				$this->id					= '';
				$this->name					= $this->input->post('area');
				$db_item 					= $this->get_db_from_stub($this);
				$db_result 					= $this->db->insert($this->table_area, $db_item);
			}
		}
		
		public function update_from_post($area_id, $area_name)
		{
			$this->db->where('id !=', $area_id);
			$this->db->where('name', $area_name);
			$query		= $this->db->get($this->table_area);
			$num_rows	= $query->num_rows();
			
			if($num_rows	== 0){
				$this->db->set('name', $area_name);
				$this->db->where('id', $area_id);
				$this->db->update($this->table_area);
			}
		}
		
		public function delete_area($area_id)
		{
			$this->db->where('id', $area_id);
			$this->db->delete($this->table_area);
		}
}