<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_status_model extends CI_Model {
	private $table_status = 'attendance_status';
		
		public $id;
		public $name;
		public $description;
		public $point;

		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->name					= $db_item->name;
			$this->description			= $db_item->description;
			$this->point				= $db_item->point;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id				= $this->id;
			$db_item->name				= $this->name;
			$db_item->description		= $this->description;
			$db_item->point				= $this->point;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Area_model();
			
			$stub->id					= $db_item->id;
			$stub->name					= $db_item->name;
			$stub->description			= $db_item->description;
			$stub->point				= $db_item->point;
			
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
			if($term != ""){
				$this->db->like('name', $term, 'both');
				$this->db->or_like("description", $term, 'both');
			};

			$this->db->order_by('name');
			$this->db->limit($limit, $offset);
			$query = $this->db->get($this->table_status);
			$result = $query->result();

			return $result;
		}

		public function countItems($term)
		{
			if($term != ""){
				$this->db->like('name', $term, 'both');
				$this->db->or_like("description", $term, 'both');
			};

			$query = $this->db->get($this->table_status);
			$result = $query->num_rows();

			return $result;
		}

		public function insertItem()
		{
			$this->db->db_debug = false;

			$name = $this->input->post('name');
			$description = $this->input->post('description');
			$point = $this->input->post('point');

			$this->id			= "";
			$this->name			= $name;
			$this->description	= $description;
			$this->point		= $point;

			$db_item 			= $this->get_db_from_stub($this);
			$db_result 			= $this->db->insert($this->table_status, $db_item);

			return $this->db->affected_rows();
		}

		public function updateById()
		{
			$this->db->db_debug = false;

			$this->db->set('name', $this->input->post('name'));
			$this->db->set('description', $this->input->post('description'));
			$this->db->set('point', $this->input->post('point'));
			$this->db->where('id', $this->input->post('id'));

			$this->db->update($this->table_status);

			return $this->db->affected_rows();
		}

		public function getById($statusId)
		{
			$this->db->where('id', $statusId);
			$query		= $this->db->get($this->table_status);
			$result		= $query->row();
			return $result;
		}

		public function deleteById($statusId)
		{
			$this->db->db_debug = FALSE;
			$this->db->where('id', $statusId);
			$this->db->delete($this->table_status);

			if($this->db->affected_rows() == 1){
				return 1;
			} else {
				return 0;
			}
		}
}