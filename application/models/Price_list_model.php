<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Price_list_model extends CI_Model {
	private $table_price_list = 'price_list';
		
		public $id;
		public $item_id;
		public $price_list;

		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->item_id				= $db_item->item_id;
			$this->price_list			= $db_item->price_list;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id				= $this->id;
			$db_item->item_id			= $this->item_id;
			$db_item->price_list		= $this->price_list;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Item_model();
			
			$stub->id					= $db_item->id;
			$stub->item_id				= $db_item->item_id;
			$stub->price_list			= $db_item->price_list;
			
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
			$query 		= $this->db->get($this->table_price_list);
			$items	 	= $query->result();
			
			$result 	= $this->map_list($items);
			
			return $result;
		}
		
		public function insertItem($itemId, $priceList)
		{
			$this->id					= '';
			$this->price_list			= $priceList;
			$this->item_id				= $itemId;
			
			$db_item 					= $this->get_db_from_stub();
			$db_result 					= $this->db->insert($this->table_price_list, $db_item);
		}

		public function getById($id)
		{
			$this->db->where('id', $id);
			$query = $this->db->get($this->table_price_list);
			$result = $query->row();

			return $result;
		}

		public function deleteByItemId($itemId)
		{
			$this->db->db_debug = false;
			$this->db->where('item_id', $itemId);
			$result = $this->db->delete($this->table_price_list);
			return $result;
		}

		public function getByItemReference($reference)
		{
			$this->db->select('price_list.*');
			$this->db->from('price_list');
			$this->db->join('item', 'price_list.item_id = item.id');
			$this->db->where('item.reference', $reference);
			$this->db->order_by('id', "ASC");

			$query		= $this->db->get();
			$result		= $query->result();
			return $result;
		}
}