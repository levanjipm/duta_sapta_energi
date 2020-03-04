<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item_model extends CI_Model {
	private $table_item = 'item';
	private $table_price_list = 'price_list';
		
		public $id;
		public $reference;
		public $name;
		public $type;
		
		public $price_list;

		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->reference			= $db_item->reference;
			$this->name					= $db_item->name;
			$this->type					= $db_item->type;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id				= $this->id;
			$db_item->reference			= $this->reference;
			$db_item->name				= $this->name;
			$db_item->type				= $this->type;
			
			return $db_item;
		}
		
		public function update_db_from_stub()
		{
			$db_item = new class{};

			$db_item->reference			= $this->reference;
			$db_item->name				= $this->name;
			$db_item->type				= $this->type;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Item_model();
			
			$stub->id					= $db_item->id;
			$stub->reference			= $db_item->reference;
			$stub->name					= $db_item->name;
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
		
		public function show_items($offset = 0, $filter = '', $limit = 25)
		{
			if($filter != ''){
				$this->db->like('name', $filter, 'both');
				$this->db->or_like('reference', $filter, 'both');
			}
			
			$query 		= $this->db->get($this->table_item, $limit, $offset);
			$items	 	= $query->result();
			
			$result 	= $this->map_list($items);
			
			return $result;
		}
		
		public function count_items($filter = '')
		{
			if($filter != ''){
				$this->db->like('name', $filter, 'both');
				$this->db->or_like('reference', $filter, 'both');
			}
			
			$query		= $this->db->get($this->table_item);
			$result		= $query->num_rows();
			
			return $result;
		}
		
		public function insert_from_post()
		{
			$this->db->select('*');
			$this->db->from($this->table_item);
			$this->db->where('reference =', $this->input->post('item_reference'));;
			$item = $this->db->count_all_results();
			
			if($item == 0){
				$this->id					= '';
				$this->reference			= $this->input->post('item_reference');
				$this->name					= $this->input->post('item_name');
				$this->type					= $this->input->post('class_id');
				
				$db_item 					= $this->get_db_from_stub();
				$db_result 					= $this->db->insert($this->table_item, $db_item);
				
				$insert_id = $this->db->insert_id();
				
				return $insert_id;
			}
		}
		
		public function show_cart($ids)
		{
			if(!empty($ids)){
				$query = $this->db->query("
					SELECT price_list.id, price_list.item_id, price_list.price_list, item.name, item.reference, item.type
						FROM price_list
						JOIN item ON item.id = price_list.item_id
						WHERE price_list.id IN (
							SELECT MAX(price_list.id)
							FROM price_list
							GROUP BY price_list.item_id
						) AND price_list.item_id IN (" . implode (',',$ids) . ")");
				$item = $query->result();
				
				return $item;
			}
		}
		
		public function show_purchase_cart($ids)
		{
			if(!empty($ids)){
				$this->db->where_in('id', $ids);
				$query = $this->db->get($this->table_item);
				$item = $query->result();
				return $item;
			}
		}
		
		public function select_by_id($item_id)
		{
			$this->db->where('id', $item_id);
			$query = $this->db->get($this->table_item,1);
			$item	= $query->row();
			
			return $item;
		}
		
		public function show_by_id()
		{
			$item_id = $this->input->post('item_id');
			$query = $this->db->query("
				SELECT item.id, price_list.item_id, price_list.price_list, item.name, item.reference, item.type
					FROM price_list
					JOIN item ON item.id = price_list.item_id
					WHERE price_list.id IN (
						SELECT MAX(price_list.id)
						FROM price_list
						GROUP BY item_id
					) AND item_id = '$item_id'");
			$item = $query->row();
			
			return $item;
		}
		
		public function update_from_post()
		{
			$item_id 			= $this->input->post('item_id');
			$updated_price_list = $this->input->post('item_price_list');
			
			$query 				= $this->db->query("
				SELECT price_list.price_list
					FROM price_list
					JOIN item ON item.id = price_list.item_id
					WHERE price_list.id IN (
						SELECT MAX(price_list.id)
						FROM price_list
						GROUP BY item_id
					) AND item_id = '$item_id'");
					
			$item 				= $query->row();
			$price_list 		= $item->price_list;
			
			$this->db->select('id');
			$this->db->from($this->table_item);
			$this->db->where('reference =', $this->input->post('item_reference'));;
			$this->db->where('id <>',$this->input->post('item_id'));
			$count = $this->db->count_all_results();
			
			if($count == 0){
				$this->reference	= $this->input->post('item_reference');
				$this->name			= $this->input->post('item_name');
				$this->type			= $this->input->post('item_type');
				$db_item			= $this->update_db_from_stub();
					
				$this->db->where('id', $item_id);
				$this->db->update($this->table_item, $db_item);
			}
			
			if($updated_price_list != '' && $updated_price_list > 0 && $updated_price_list != $price_list){
				$this->load->model('Price_list_model');
				$this->Price_list_model->insert_from_post($item_id, $updated_price_list);
			}
		}
}