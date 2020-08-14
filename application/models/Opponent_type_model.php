<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opponent_type_model extends CI_Model {
	private $table_opponent = 'other_opponent_type';
		
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
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id				= $this->id;
			$db_item->name				= $this->name;
			$db_item->description		= $this->description;
			
			return $db_item;
		}
		
		public function update_db_from_stub()
		{
			$db_item = new class{};

			$db_item->id				= $this->id;
			$db_item->name				= $this->name;
			$db_item->description		= $this->description;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Item_model();
			
			$stub->id					= $db_item->id;
			$stub->name					= $db_item->name;
			$stub->description			= $db_item->description;
			
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
		
		public function insertItem()
		{
			$db_item = array(
				"id" => '',
				"name" => $this->input->post('name'),
				"description" => $this->input->post('description')
			);

			$this->db->insert($this->table_opponent, $db_item);
			$result = $this->db->insert_id();

			return $result;
		}

		public function getItems($offset = 0, $term = "", $limit = 10)
		{
			if($term != ""){
				$this->db->like('name', $term, 'both');
				$this->db->or_like('description', $term, 'both');
			}

			if($limit != 0){
				$this->db->limit($limit, $offset);
			}

			$this->db->order_by("name");
			$query		= $this->db->get($this->table_opponent);
			$result		= $query->result();
			
			return $result;
		}

		public function countItems($term)
		{
			if($term != ""){
				$this->db->like('name', $term, 'both');
				$this->db->or_like('description', $term, 'both');
			}
			$query	= $this->db->get($this->table_opponent);
			$result	= $query->num_rows();
			return $result;
		}

		public function deleteById($id)
		{
			$this->db->db_debug = false;
			$this->db->where('id', $id);
			$result = $this->db->delete($this->table_opponent);
			return $result;
		}

		public function getById($id)
		{
			$this->db->where("id", $id);
			$query = $this->db->get($this->table_opponent);
			$result = $query->row();
			return $result;
		}

		public function updateById()
		{
			$this->db->db_debug = false;
			$this->db->set('name', $this->input->post('name'));
			$this->db->set('description', $this->input->post('description'));
			$this->db->where('id', $this->input->post('id'));

			$this->db->update($this->table_opponent);
			return $this->db->affected_rows();
		}
}