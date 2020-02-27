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
		
		public function show_all()
		{
			$query 		= $this->db->get($this->table_item_class);
			$items	 	= $query->result();
			
			$result 	= $this->map_list($items);
			
			return $result;
			
		}
		
		public function show_limited($limit, $offset)
		{
			$query 		= $this->db->get($this->table_item_class, $limit, $offset);
			$items	 	= $query->result();
			
			$result = $this->map_list($items);
			
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