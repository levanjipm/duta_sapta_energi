<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_case_model extends CI_Model {
	private $table_event = 'code_event';
		
		public $id;
		public $type;
		public $created_by;
		public $date;
		public $is_confirm;
		public $confirmed_by;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->type					= $db_item->type;
			$this->created_by			= $db_item->created_by;
			$this->date					= $db_item->date;
			$this->is_confirm			= $db_item->is_confirm;
			$this->confirmed_by			= $db_item->confirmed_by;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->type					= $this->type;
			$db_item->created_by			= $this->created_by;
			$db_item->date					= $this->date;
			$db_item->is_confirm			= $this->is_confirm;
			$db_item->confirmed_by			= $this->confirmed_by;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Inventory_case_model();
			
			$stub->id					= $db_item->id;
			$stub->type					= $db_item->type;
			$stub->created_by			= $db_item->created_by;
			$stub->date					= $db_item->date;
			$stub->is_confirm			= $db_item->is_confirm;
			$stub->confirmed_by			= $db_item->confirmed_by;
			
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
				
		public function insert_from_post($created_by, $date, $type)
		{
			if($type == 1){
				//Lost goods//
				$db_item = array(
					'id' => '',
					'type' => 1,
					'created_by' => $created_by,
					'date' => $date,
					'is_confirm' => 0,
					'confirmed_by' => null
				);
			} else if($type == 2){
				$db_item = array(
					'id' => '',
					'type' => 2,
					'created_by' => $created_by,
					'date' => $date,
					'is_confirm' => 0,
					'confirmed_by' => null
				);
			}
			
			$this->db->insert($this->table_event, $db_item);
				
			return $this->db->insert_id();
		}
		
		public function show_unconfirmed_cases($offset = 0, $limit = 25)
		{
			$this->db->select('code_event.id, code_event.type, code_event.date, code_event.is_confirm, code_event.confirmed_by, users.name as created_by');
			$this->db->from('code_event');
			$this->db->join('users', 'code_event.created_by = users.id', 'left');
			$this->db->where('code_event.is_confirm', 0);
			$this->db->order_by('code_event.date');
			$this->db->limit($limit, $offset);
			
			$query 		= $this->db->get();
			$result		= $query->result();
			
			return $result;
		}
		
		public function count_unconfirmed_cases()
		{
			$this->db->where('is_confirm', 0);
			$query	= $this->db->get($this->table_event);
			$items	= $query->num_rows();
			
			return $items;
		}
		
		public function view_by_id($id)
		{
			$this->db->select('code_event.id, code_event.type, code_event.date, code_event.is_confirm, code_event.confirmed_by, users.name as created_by');
			$this->db->from('code_event');
			$this->db->join('users', 'code_event.created_by = users.id', 'left');
			$this->db->where('code_event.id', $id);
			
			$query 		= $this->db->get();
			$result		= $query->row();
			
			return $result;
		}
		
		public function confirm($id, $user_id)
		{
			$this->db->set('is_confirm', 1);
			$this->db->set('confirmed_by', $user_id);
			$this->db->where('id', $id);
			$this->db->update($this->table_event);
		}
}