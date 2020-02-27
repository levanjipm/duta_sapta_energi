<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Area_model extends CI_Model {
	private $table_area = 'customer_area';
		
		public $id;
		public $name;
		public $major_id;
		
		public $complete_address;
		
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
		
		public function show_limited($limit, $offset, $term)
		{
			$query 		= $this->db->get($this->table_area, $limit, $offset);
			$areas	 	= $query->result();
			
			$items = $this->map_list($areas);
			
			return $items;
			
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
		
		public function show_by_id($id)
		{
			// $where['id'] = $id;
			// $this->db->where($where);
			// $query = $this->db->get($this->table_client, 1);
			// $item = $query->row();
			
			// return ($item !== null) ? $this->get_stub_from_db($item) : null;
		}
		
		public function update_from_post($id)
		{
			// $where['id']			= $id;
			// $this->name				= $this->input->post('client_name');
			// $this->address			= $this->input->post('client_address');
			// $this->city				= $this->input->post('client_city');
			// $this->pic				= $this->input->post('client_pic');
			// $this->phone			= $this->input->post('client_phone');
			// $this->npwp				= $this->input->post('client_npwp');
			
			// $db_item = $this->get_db_from_stub();
			
			// $this->db->where($where);
			// // $this->db->set('name', $this->name);
			// // $this->db->set('address', $this->address);
			// // $this->db->set('city', $this->city);
			// // $this->db->set('pic', $this->pic);
			// // $this->db->set('phone', $this->phone);
			// // $this->db->set('npwp', $this->npwp);\
			// $this->db->update($this->table_client, $db_item);
		}
}