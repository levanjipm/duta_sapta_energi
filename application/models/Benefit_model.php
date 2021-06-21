<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Benefit_model extends CI_Model {
	private $table_benefit = 'benefit';
		
		public $id;
		public $name;
		public $information;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->name					= $db_item->name;
			$this->information			= $db_item->information;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->name					= $this->name;
			$db_item->information			= $this->information;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new User_model();
			
			$stub->id					= $db_item->id;
			$stub->name					= $db_item->name;
			$stub->information			= $db_item->information;
			
			return $stub;
		}
		
		public function map_list($users)
		{
			$result = array();
			foreach ($users as $user)
			{
				$result[] = $this->get_new_stub_from_db($user);
				continue;
			}
			return $result;
		}
		
		public function getItems($offset = 0, $term = "", $limit = 10)
		{
			if($term != ""){
				$this->db->like('name', $term, 'both');
				$this->db->or_like('information', $term, 'both');
			}
			
			$this->db->limit($limit, $offset);
			$query = $this->db->get($this->table_benefit);
			$result = $query->result();
			
			return $result;
		}
		
		public function countItems($term)
		{
			if($term != ""){
				$this->db->like('name', $term, 'both');
				$this->db->or_like('information', $term, 'both');
			}
			
			$query = $this->db->get($this->table_benefit);
			$result = $query->num_rows();
			
			return $result;
		}
		
		public function insertItem()
		{
			$this->db->db_debug = false;
			
			$db_item = array(
				'id' => '',
				'name' => $this->input->post('name'),
				'information' => $this->input->post('information')
			);
			
			$db_result 	= $this->db->insert($this->table_benefit, $db_item);

			return ($this->db->affected_rows());
		}
		
		public function deleteById($id)
		{
			$this->db->db_debug = false;
			
			$this->db->where('id', $id);
			$this->db->delete($this->table_benefit);

			if($this->db->affected_rows() == 1){
				return 1;
			} else {
				return 0;
			}
		}
		
		public function getById($id)
		{
			$this->db->where('id', $id);
			$query = $this->db->get($this->table_benefit);
			$result = $query->row();
			
			return $result;
		}
		
		public function updateById()
		{
			$id = $this->input->post('id');
			$name = $this->input->post('name');
			$information = $this->input->post('information');
			
			$this->db->set('name', $name);
			$this->db->set('information', $information);
			$this->db->where('id', $id);
			
			$this->db->update($this->table_benefit);
			
			if($this->db->affected_rows() == 1){
				return 1;
			} else {
				return 0;
			}
		}
}