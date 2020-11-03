<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item_model extends CI_Model {
	private $table_item = 'item';
	private $table_price_list = 'price_list';
		
		public $id;
		public $reference;
		public $name;
		public $type;
		public $is_notified_stock;
		public $confidence_level;
		
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
			$this->is_notified_stock	= $db_item->is_notified_stock;
			$this->confidence_level		= $db_item->confidence_level;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id				= $this->id;
			$db_item->reference			= $this->reference;
			$db_item->name				= $this->name;
			$db_item->type				= $this->type;
			$db_item->is_notified_stock	= $this->is_notified_stock;
			$db_item->confidence_level	= $this->confidence_level;
			
			return $db_item;
		}
		
		public function update_db_from_stub()
		{
			$db_item = new class{};

			$db_item->reference			= $this->reference;
			$db_item->name				= $this->name;
			$db_item->type				= $this->type;
			$db_item->is_notified_stock	= $this->is_notified_stock;
			$db_item->confidence_level	= $this->confidence_level;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Item_model();
			
			$stub->id					= $db_item->id;
			$stub->reference			= $db_item->reference;
			$stub->name					= $db_item->name;
			$stub->type					= $db_item->type;
			$stub->is_notified_stock	= $db_item->is_notified_stock;
			$stub->confidence_level		= $db_item->confidence_level;
			
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
		
		public function showItems($offset = 0, $filter = '', $limit = 25)
		{
			if($filter != ''){
				$query = $this->db->query("
					SELECT price_list.id, price_list.price_list, item.id as item_id, item.reference, item.name
					FROM price_list
					JOIN item ON item.id = price_list.item_id
					WHERE price_list.id IN (
						SELECT MAX(price_list.id)
						FROM price_list
						GROUP BY price_list.item_id
					) AND (item.name LIKE '%$filter%' OR reference LIKE '%$filter%') 
					ORDER BY item.reference
					LIMIT $limit OFFSET $offset");
			} else {
				$query = $this->db->query("
					SELECT price_list.id, price_list.price_list, item.id as item_id, item.reference, item.name
					FROM price_list
					JOIN item ON item.id = price_list.item_id
					WHERE price_list.id IN (
						SELECT MAX(price_list.id)
						FROM price_list
						GROUP BY price_list.item_id)
					ORDER BY item.reference
					LIMIT $limit OFFSET $offset");
			}
			
			$items	 	= $query->result();
			return $items;
		}
		
		public function countItems($filter = '')
		{
			if($filter != ''){
				$query = $this->db->query("
					SELECT price_list.id
						FROM price_list
						JOIN item ON item.id = price_list.item_id
						WHERE price_list.id IN (
							SELECT MAX(price_list.id)
							FROM price_list
							GROUP BY price_list.item_id) 
						AND (item.name LIKE '%$filter%' OR reference LIKE '%$filter%')");
			} else {
				$query = $this->db->query("
					SELECT price_list.id FROM price_list
					JOIN item ON item.id = price_list.item_id
					WHERE price_list.id IN (
						SELECT MAX(price_list.id)
						FROM price_list
						GROUP BY price_list.item_id)");
			}
			
			$items	 	= $query->num_rows();
			
			return $items;
		}
		
		public function insertItem()
		{
			$this->db->select('*');
			$this->db->from($this->table_item);
			$this->db->where('reference', $this->input->post('reference'));;
			$item = $this->db->count_all_results();
			
			if($item == 0){
				$this->id					= '';
				$this->reference			= $this->input->post('reference');
				$this->name					= $this->input->post('name');
				$this->type					= $this->input->post('class');
				$this->is_notified_stock	= $this->input->post('notify');
				$this->confidence_level		= $this->input->post('confidenceLevel');
				
				$db_item 					= $this->get_db_from_stub();
				$db_result 					= $this->db->insert($this->table_item, $db_item);
				
				$insert_id = $this->db->insert_id();
				
				return $insert_id;
			} else {
				return null;
			}
		}

		public function showById($item_id)
		{
			$query 				= $this->db->query("
				SELECT price_list.price_list, item.*
					FROM price_list
					JOIN item ON item.id = price_list.item_id
					WHERE price_list.id IN (
						SELECT MAX(price_list.id)
						FROM price_list
						GROUP BY item_id
					) AND item.id = '$item_id'");	
			$item 	= $query->row();
			
			return $item;
		}
		
		public function getByPriceListId($price_list_id)
		{
			$query 				= $this->db->query("
				SELECT price_list.price_list, item.reference, item.name, item.type, item.id
					FROM price_list
					JOIN item ON item.id = price_list.item_id
					WHERE price_list.id IN (
						SELECT MAX(price_list.id)
						FROM price_list
						GROUP BY item_id
					) AND price_list.id = '$price_list_id'");	
			$item 				= $query->row();
			
			return $item;
		}
		
		public function updateById()
		{
			$id		 			= $this->input->post('id');
			$reference			= $this->input->post('reference');
			$priceList			= $this->input->post('priceList');
			$name				= $this->input->post('name');
			$type				= $this->input->post('type');
			$confidenceLevel	= $this->input->post('confidenceLevel');
			$isNotified			= $this->input->post('isNotified');
			
			$query 				= $this->db->query("
				SELECT price_list.price_list
					FROM price_list
					JOIN item ON item.id = price_list.item_id
					WHERE price_list.id IN (
						SELECT MAX(price_list.id)
						FROM price_list
						GROUP BY item_id
					) AND item_id = '$id'");
					
			$item 				= $query->row();
			$prevPriceList 		= $item->price_list;
			
			$this->db->select('id');
			$this->db->from($this->table_item);
			$this->db->where('reference =', $reference);;
			$this->db->where('id <>',$id);
			$count = $this->db->count_all_results();
			
			if($count == 0){
				$this->db->set('reference', $reference);
				$this->db->set('name', $name);
				$this->db->set('type', $type);
				$this->db->set('is_notified_stock', $isNotified);
				$this->db->set('confidence_level', $confidenceLevel);					
				$this->db->where('id', $id);
				$this->db->update($this->table_item);
			}
			
			if($priceList != '' && $priceList > 0 && $priceList != $prevPriceList){
				$this->load->model('Price_list_model');
				$this->Price_list_model->insert_from_post($item_id, $updated_price_list);
			}
		}
		
		public function deleteById($id)
		{
			$this->db->db_debug = FALSE;
			$this->db->where('id', $id);
			$this->db->delete($this->table_item);
			
			if($this->db->affected_rows() == 1){
				return 1;
			} else {
				return 0;
			}
		}

		public function getByReference($reference)
		{
			$this->db->select('item.*, item_class.name as className');
			$this->db->from('item');
			$this->db->join('item_class', 'item.type = item_class.id');
			$this->db->where('item.reference', $reference);

			$query		= $this->db->get();
			$result		= $query->row();
			return $result;
		}
}