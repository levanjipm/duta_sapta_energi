<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opponent_model extends CI_Model {
	private $table_opponent = 'other_opponent';
		
		public $id;
		public $name;
		public $description;
		public $type;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->name					= $db_item->name;
			$this->description			= $db_item->description;
			$this->type					= $db_item->type;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id				= $this->id;
			$db_item->name				= $this->name;
			$db_item->description		= $this->description;
			$db_item->type				= $this->type;
			
			return $db_item;
		}
		
		public function update_db_from_stub()
		{
			$db_item = new class{};

			$db_item->id				= $this->id;
			$db_item->name				= $this->name;
			$db_item->description		= $this->description;
			$db_item->type				= $this->type;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Item_model();
			
			$stub->id					= $db_item->id;
			$stub->name					= $db_item->name;
			$stub->description			= $db_item->description;
			$stub->type					= $db_item->type;
			
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
		
		public function getItems($offset = 0, $term = "", $limit = 10)
		{
			$this->db->select('other_opponent.*, other_opponent_type.name as type');
			$this->db->from('other_opponent');
			$this->db->join('other_opponent_type', 'other_opponent.type = other_opponent_type.id', 'left');
			if($term != ""){
				$this->db->like('other_opponent.name', $term, 'both');
				$this->db->or_like('other_opponent.description', $term, 'both');
			};
			$this->db->order_by('other_opponent.name');
			$this->db->limit($limit, $offset);
			
			$query		= $this->db->get();
			$result		= $query->result();

			return $result;
		}

		public function countItems($term)
		{
			if($term != ""){
				$this->db->like('name', $term, 'both');
				$this->db->or_like('description', $term, 'both');
			}

			$query		= $this->db->get($this->table_opponent);
			$result		= $query->num_rows();

			return $result;
		}

		public function deleteById($id)
		{
			$this->db->where('id', $id);
			$result = $this->db->delete($this->table_opponent);
			return $result;
		}

		public function insertItem()
		{
			$db_item = array(
				"id" => '',
				"name" => $this->input->post('name'),
				"description" => $this->input->post('description'),
				"type" => $this->input->post('type')
			);

			$this->db->insert($this->table_opponent, $db_item);
			$result = $this->db->insert_id();

			return $result;
		}

		public function getById($id)
		{
			$this->db->select('other_opponent.*, other_opponent_type.name as type');
			$this->db->from('other_opponent');
			$this->db->join('other_opponent_type', 'other_opponent.type = other_opponent_type.id');
			$this->db->where('other_opponent.id', $id);
			$query		= $this->db->get();
			$result		= $query->row();
			return $result;
		}

		public function updateById()
		{
			$this->db->set('name', $this->input->post('name'));
			$this->db->set('description', $this->input->post('description'));
			$this->db->set('type', $this->input->post('type'));
			$this->db->where('id', $this->input->post('id'));

			$this->db->update($this->table_opponent);
			return $this->db->affected_rows();
		}
}