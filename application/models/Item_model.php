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
		public $brand;
		
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
			$db_item->brand				= $this->brand;
			
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
					SELECT price_list.id, price_list.price_list, item.id as item_id, item.reference, item.name, COALESCE(stockTable.residue, 0) AS stock, brand.id as brand_id, brand.name AS brand
					FROM price_list
					JOIN item ON item.id = price_list.item_id
					LEFT JOIN (
						SELECT SUM(residue) AS residue, stock_in.item_id
						FROM stock_in
						GROUP BY stock_in.item_id
					) stockTable
					ON stockTable.item_id = item.id
					JOIN brand ON item.brand = brand.id
					WHERE price_list.id IN (
						SELECT MAX(price_list.id)
						FROM price_list
						GROUP BY price_list.item_id
					) AND (item.name LIKE '%$filter%' OR reference LIKE '%$filter%') 
					ORDER BY item.reference
					LIMIT $limit OFFSET $offset");
			} else {
				$query = $this->db->query("
					SELECT price_list.id, price_list.price_list, item.id as item_id, item.reference, item.name, COALESCE(stockTable.residue, 0) AS stock, brand.id as brand_id, brand.name AS brand
					FROM price_list
					JOIN item ON item.id = price_list.item_id
					LEFT JOIN (
						SELECT SUM(residue) AS residue, stock_in.item_id
						FROM stock_in
						GROUP BY stock_in.item_id
					) stockTable
					ON stockTable.item_id = item.id
					JOIN brand ON item.brand = brand.id
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
				$this->brand				= $this->input->post('brand');

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
				SELECT price_list.price_list, item.*, brand.id as brand_id, brand.name as brand
					FROM price_list
					JOIN item ON item.id = price_list.item_id
					JOIN brand ON item.brand = brand.id
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
			$brand				= $this->input->post('brand');
			
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
				$this->db->set('brand', $brand);			
				$this->db->where('id', $id);
				$this->db->update($this->table_item);
			}
			
			if($priceList != '' && $priceList > 0 && $priceList != $prevPriceList){
				$this->load->model('Price_list_model');
				$this->Price_list_model->insertItem($id, $priceList);
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
			$this->db->select('item.*, item_class.name as className, brand.name as brand, brand.id AS brand_id');
			$this->db->from('item');
			$this->db->join('item_class', 'item.type = item_class.id');
			$this->db->join('brand', 'item.brand = brand.id');
			$this->db->where('item.reference', $reference);

			$query		= $this->db->get();
			$result		= $query->row();
			return $result;
		}

		public function getValueByItemArray($itemArray)
		{
			$itemIdArray		= array();
			$quantityArray		= array();
			$stockValueArray	= array();
			$responseArray		= array();

			foreach($itemArray as $itemId => $quantity){
				array_push($itemIdArray, $itemId);
				$stockValueArray[$itemId] = array();
				$responseArray[$itemId]	= array();
				next($itemArray);
			}

			$query			= $this->db->query("
				SELECT stock_in.price, stock_in.residue, stock_in.item_id
				FROM stock_in
				LEFT JOIN (
					SELECT SUM(stock_out.quantity) AS quantity, stock_out.in_id, COALESCE(deliveryOrderTable.date, eventTable.date) as date 
					FROM stock_out
					LEFT JOIN 
					(
						SELECT code_delivery_order.date, delivery_order.id 
						FROM delivery_order
						JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					) deliveryOrderTable
					ON stock_out.delivery_order_id = deliveryOrderTable.id
					LEFT JOIN (
						SELECT code_event.date, event.id
						FROM event
						JOIN code_event ON event.code_event_id = code_event.id
					) eventTable
					ON eventTable.id = stock_out.event_id
					LEFT JOIN 
					(
						SELECT code_purchase_return_sent.date, purchase_return_sent.id
						FROM purchase_return_sent
						JOIN code_purchase_return_sent ON purchase_return_sent.code_purchase_return_sent_id = code_purchase_return_sent.id
					) purchaseReturnTable
					ON purchaseReturnTable.id = stock_out.purchase_return_id
					GROUP BY stock_out.in_id
				) AS stockOutTable
				ON stockOutTable.in_id = stock_in.id
				LEFT JOIN (
					SELECT code_good_receipt.date, good_receipt.id
					FROM good_receipt
					JOIN code_good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
				) AS goodReceiptTable
				ON goodReceiptTable.id = stock_in.good_receipt_id
				LEFT JOIN (
					SELECT code_sales_return_received.date, sales_return_received.id
					FROM sales_return_received
					JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
				) AS salesReturnTable
				ON salesReturnTable.id = stock_in.sales_return_received_id
				LEFT JOIN (
					SELECT code_event.date, event.id
					FROM event
					JOIN code_event ON event.code_event_id = code_event.id
				) eventTable
				ON stock_in.event_id = eventTable.id
				WHERE stock_in.item_id IN (" . implode($itemIdArray, ",") . ")
				AND stock_in.residue > 0
				ORDER BY COALESCE(eventTable.date, goodReceiptTable.date, salesReturnTable.date) ASC
			");

			$result			= $query->result();
			foreach($result as $item)
			{
				$itemId		= $item->item_id;
				array_push($stockValueArray[$itemId], (array) $item);
				next($result);
			}

			$value				= array();
			foreach($itemArray as $itemId => $quantity)
			{
				$value[$itemId]		= 0;
				$paramQuantity		= $quantity;
				$stockValue			= $stockValueArray[$itemId];
				$i				= 0;
				while($paramQuantity > 0){
					if($paramQuantity >= $stockValue[$i]['residue']){
						$value[$itemId]		+= $stockValue[$i]['residue'] * $stockValue[$i]['price'];
						$paramQuantity		-= $stockValue[$i]['residue'];
						$i++;
					} else {
						$value[$itemId]		+= $paramQuantity * $stockValue[$i]['price'];
						$paramQuantity		= 0;
					}
				}

				$value[$itemId]		= $value[$itemId] / $quantity;
				next($itemArray);
			}

			$query			= $this->db->query("
				SELECT item.reference, item.name, item.id
				FROM item
				WHERE item.id IN (" . implode($itemIdArray, ",") . ")
			");

			$result			= $query->result();
			foreach($result as $item){
				$responseArray[$item->id]['reference']	= $item->reference;
				$responseArray[$item->id]['name']		= $item->name;
				$responseArray[$item->id]['value']		= $value[$item->id];
				$responseArray[$item->id]['quantity']	= $itemArray[$item->id];
				next($result);
			}

			return $responseArray;
		}
}