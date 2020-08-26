<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asset_model extends CI_Model {
	private $table_asset = 'fixed_asset';
		
		public $id;
		public $name;
		public $description;
		public $sold_date;
		public $value;
		public $depreciation_time;
		public $date;
		public $type;
		public $residue_value;

		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->name					= $db_item->name;
			$this->description			= $db_item->description;
			$this->sold_date			= $db_item->sold_date;
			$this->value				= $db_item->value;
			$this->depreciation_time	= $db_item->depreciation_time;
			$this->date					= $db_item->date;
			$this->type					= $db_item->type;
			$this->residue_value		= $db_item->residue_value;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->name					= $this->name;
			$db_item->description			= $this->description;
			$db_item->sold_date				= $this->sold_date;
			$db_item->value					= $this->value;
			$db_item->depreciation_time		= $this->depreciation_time;
			$db_item->date					= $this->date;
			$db_item->type					= $this->type;
			$db_item->residue_value			= $this->residue_value;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Item_class_model();
			
			$stub->id					= $db_item->id;
			$stub->name					= $db_item->name;
			$stub->description			= $db_item->description;
			$stub->sold_date			= $db_item->sold_date;
			$stub->value				= $db_item->value;
			$stub->depreciation_time	= $db_item->depreciation_time;
			$stub->date					= $db_item->date;
			$stub->type					= $db_item->type;
			$stub->residue_value		= $db_item->residue_value;
			
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
		
		public function getItems($offset = 0, $term = '', $limit = 25)
		{
			if($term != ''){
				$this->db->like('name', $term, 'both');
				$this->db->or_like('description', $term, 'both');
			};
			
			$query		= $this->db->get($this->table_asset, $limit, $offset);
			$result		= $query->result();
			
			return $result;
		}
		
		public function countItems($term = '')
		{
			if($term != ''){
				$this->db->like('name', $term, 'both');
				$this->db->or_like('description', $term, 'both');
			};
			
			$query		= $this->db->get($this->table_asset);
			$result		= $query->num_rows();
			
			return $result;
		}
		
		public function insertItem()
		{
			$name				= $this->input->post('name');
			$description		= $this->input->post('description');
			$value				= $this->input->post('value');
			$depreciation		= $this->input->post('depreciation');
			$type				= $this->input->post('assetType');
			$date				= $this->input->post('date');

			$db_item	= array(
				'id' => '',
				'name' => $name,
				'description' => $description,
				'value' => $value,
				'depreciation_time' => $depreciation,
				'date' => $date,
				'type' => $type,
				'sold_date' => null,
				'residue_value' => $residualValue
			);
			
			$this->db->insert($this->table_asset, $db_item);
			return $this->db->affected_rows();
		}
		
		public function getById($id)
		{
			$this->db->where('id', $id);
			$query		= $this->db->get($this->table_asset);
			$result		= $query->row();
			
			return $result;
		}

		public function updateById()
		{
			$id					= $this->input->post('id');
			$name				= $this->input->post('name');
			$description		= $this->input->post('description');
			$value				= $this->input->post('value');
			$depreciation		= $this->input->post('depreciation');
			$type				= $this->input->post('assetType');
			$date				= $this->input->post('date');
			$residue_value		= $this->input->post('residualValue');

			$db_item	= array(
				'name' => $name,
				'description' => $description,
				'value' => $value,
				'depreciation_time' => $depreciation,
				'date' => $date,
				'type' => $type,
				'residue_value' => $residue_value
			);
			$this->db->set($db_item);
			$this->db->where("id", $id);
			$this->db->update($this->table_asset);

			return $this->db->affected_rows();
		}

}
