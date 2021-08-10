<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_in_model extends CI_Model {
	private $table_stock_in = 'stock_in';
		
		public $id;
		public $item_id;
		public $quantity;
		public $residue;
		public $supplier_id;
		public $customer_id;
		public $good_receipt_id;
		public $sales_return_received_id;
		public $event_id;
		public $price;
		
		public function __construct()
		{
			parent::__construct();
			
			$this->id						='';
			$this->item_id					='';
			$this->quantity					='';
			$this->residue					='';
			$this->supplier_id				='';
			$this->customer_id				='';
			$this->good_receipt_id			='';
			$this->sales_return_received_id			='';
			$this->event_id					='';
			$this->price					='';
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id						= $db_item->id;
			$this->item_id					= $db_item->item_id;
			$this->quantity					= $db_item->quantity;
			$this->residue					= $db_item->residue;
			$this->supplier_id				= $db_item->supplier_id;
			$this->customer_id				= $db_item->customer_id;
			$this->good_receipt_id			= $db_item->good_receipt_id;
			$this->sales_return_received_id	= $db_item->sales_return_id;
			$this->event_id					= $db_item->event_id;
			$this->price					= $db_item->price;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id						= $this->id;
			$db_item->item_id					= $this->item_id;
			$db_item->quantity					= $this->quantity;
			$db_item->residue					= $this->residue;
			$db_item->supplier_id				= $this->supplier_id;
			$db_item->customer_id				= $this->customer_id;
			$db_item->good_receipt_id			= $this->good_receipt_id;
			$db_item->sales_return_received_id	= $this->sales_return_id;
			$db_item->event_id					= $this->event_id;
			$db_item->price						= $this->price;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Delivery_order_model();
			
			$stub->id						= $db_item->id;
			$stub->item_id					= $db_item->item_id;
			$stub->quantity					= $db_item->quantity;
			$stub->residue					= $db_item->residue;
			$stub->supplier_id				= $db_item->supplier_id;
			$stub->customer_id				= $db_item->customer_id;
			$stub->good_receipt_id			= $db_item->good_receipt_id;
			$stub->sales_return_received_id	= $db_item->sales_return_id;
			$stub->event_id					= $db_item->event_id;
			$stub->price					= $db_item->price;
			
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
		
		public function insertItem($stockInArray)
		{
			$result = $this->db->insert_batch($this->table_stock_in, $stockInArray);
			return $result;
		}
		
		public function getResidueByItemId($item_id)
		{
			$this->db->where('residue >', 0);
			$this->db->where('item_id', $item_id);
			$this->db->order_by('id', 'ASC');
			$query 	= $this->db->get($this->table_stock_in);
			$result	= $query->row();
			
			return $result;
			
		}
		
		public function updatePrice($priceArray)
		{
			foreach($priceArray as $price){
				$id		= key($priceArray);
				$batch[] = array(
					'good_receipt_id' => $id,
					'price' => $price
				);
				
				next($priceArray);
			}
			
			$this->db->update_batch($this->table_stock_in,$batch, 'good_receipt_id'); 
		}
		
		public function updateById($id, $final_quantity)
		{
			$this->db->set('residue', $final_quantity);
			$this->db->where('id', $id);
			$this->db->update($this->table_stock_in);
		}
		
		public function checkStock($stock_array)
		{
			$final_stock_array		= array();
			if(!empty($stock_array)){
				foreach($stock_array as $stock){
					$item_id				= $stock['item_id'];
					$quantity				= $stock['quantity'];
					if(array_key_exists($item_id, $final_stock_array)){
						$final_quantity	= $final_stock_array[$item_id] + $quantity;
						$final_stock_array[$item_id] = $final_quantity;
					} else {
						$final_stock_array[$item_id] = $quantity;
					}
				}
			}
			
			$sufficient_stock	= TRUE;			
			if(!empty($final_stock_array)){
				foreach($final_stock_array as $item_id => $final_stock){
					$this->db->select_sum('residue');
					$this->db->where('item_id', $item_id);
					$query		= $this->db->get($this->table_stock_in);
					$result		= $query->row();
					
					$stock		= max(0, $result->residue);
					if($stock < $final_stock){
						$sufficient_stock = FALSE;
					}

					continue;
				}
			}
			
			return $sufficient_stock;
		}
		
		public function showItems($offset = 0, $term = "", $limit = 10)
		{
			$query		= $this->db->query("
				SELECT item.reference, item.name, item.id, (COALESCE(current.quantity, 0) - COALESCE(processOut.quantity, 0) + COALESCE(processIn.quantity, 0)) AS stock, processOut.quantity AS processOut, processIn.quantity AS processIn
				FROM item
				LEFT JOIN (
					SELECT SUM(residue) AS quantity, item_id
					FROM stock_in
					GROUP BY item_id
				) current
				ON current.item_id = item.id
				LEFT JOIN (
					SELECT SUM(delivery_order.quantity) AS quantity, price_list.item_id
					FROM delivery_order
					JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN price_list ON sales_order.price_list_id = price_list.id
					WHERE code_delivery_order.is_confirm = 1
					AND code_delivery_order.is_sent = 0
					AND code_delivery_order.is_delete = 0
					GROUP BY price_list.item_id
				) processOut
				ON processOut.item_id = item.id
				LEFT JOIN (
					SELECT SUM(good_receipt.quantity) AS quantity, purchase_order.item_id
					FROM good_receipt
					JOIN code_good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
					JOIN purchase_order ON good_receipt.purchase_order_id = purchase_order.id
					WHERE code_good_receipt.is_confirm = 1
					AND code_good_receipt.is_confirm = 0
					AND code_good_receipt.is_delete = 0
					GROUP BY purchase_order.item_id
				) processIn
				ON processIn.item_id = item.id
				WHERE item.reference LIKE '%$term%' OR item.name LIKE '%$term%'
				ORDER BY item.reference ASC
				LIMIT $limit OFFSET $offset
			");

			$result		= $query->result();
			return $result;
		}
		
		public function countItems($offset = 0, $term = '', $limit = 25)
		{
			$query		= $this->db->query("
				SELECT item.reference, item.name, item.id, SUM(stock_in.residue) as stock
				FROM stock_in
				JOIN item ON stock_in.item_id = item.id
				WHERE item.reference LIKE '%$term%' OR item.name LIKE '%$term%'
				GROUP BY stock_in.item_id
			");

			$result		= $query->num_rows();
			return $result;
		}
		
		public function ViewCard($item_id)
		{
			$query = $this->db->query("
				SELECT * FROM (
					SELECT COALESCE(customer.name, supplier.name, 'Internal Transaction') as name, COALESCE(deliveryOrderTable.date, eventTable.date,purchaseReturnTable.date) as date, COALESCE(deliveryOrderTable.quantity, eventTable.quantity, purchaseReturnTable.quantity) * (-1) as quantity, COALESCE(deliveryOrderTable.name, eventTable.name, purchaseReturnTable.name) as documentName, COALESCE(deliveryOrderTable.deliveryOrderId, eventTable.eventId, purchaseReturnTable.returnId) AS documentId, COALESCE(deliveryOrderTable.type, eventTable.type, purchaseReturnTable.type) AS documentType
					FROM stock_out
					LEFT JOIN (
						SELECT stock_out.id, code_delivery_order.name, code_delivery_order.date, SUM(delivery_order.quantity) AS quantity, code_delivery_order.id AS deliveryOrderId, 'deliveryOrder' AS type
						FROM delivery_order
						JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
						JOIN price_list ON sales_order.price_list_id = price_list.id
						JOIN stock_out ON stock_out.delivery_order_id = delivery_order.id
						JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
						LEFT JOIN stock_in ON stock_out.in_id = stock_in.id
						WHERE stock_in.item_id = '$item_id'
						GROUP BY stock_in.item_id, code_delivery_order.id
					) AS deliveryOrderTable
					ON deliveryOrderTable.id = stock_out.id
					LEFT JOIN (
						SELECT stock_out.id, code_event.name, code_event.date, SUM(event.quantity) as quantity, code_event.id AS eventId, 'event' AS type
						FROM event
						JOIN stock_out ON stock_out.event_id = event.id
						JOIN code_event ON event.code_event_id = code_event.id
						GROUP BY event.item_id, event.code_event_id
					) AS eventTable
					ON eventTable.id = stock_out.id
					LEFT JOIN (
						SELECT purchase_return_sent.id, code_purchase_return_sent.name, code_purchase_return_sent.date, SUM(purchase_return_sent.quantity) AS quantity, purchase_return_sent.code_purchase_return_sent_id AS returnId, 'purchaseReturn' AS type
						FROM purchase_return_sent
						JOIN stock_out ON stock_out.purchase_return_id = purchase_return_sent.id
						JOIN code_purchase_return_sent ON code_purchase_return_sent.id = purchase_return_sent.code_purchase_return_sent_id
						JOIN purchase_return ON purchase_return_sent.purchase_return_id = purchase_return.id
						GROUP BY purchase_return_sent.code_purchase_return_sent_id, purchase_return.item_id
					) purchaseReturnTable
					ON purchaseReturnTable.id = stock_out.purchase_return_id
					LEFT JOIN customer ON stock_out.customer_id = customer.id
					LEFT JOIN supplier ON stock_out.supplier_id = supplier.id
					WHERE stock_out.in_id IN(
						SELECT stock_in.id 
						FROM stock_in
						LEFT JOIN (
							SELECT good_receipt.id, code_good_receipt.name, code_good_receipt.date 
							FROM code_good_receipt
							JOIN good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
						) goodReceiptTable
						ON stock_in.good_receipt_id = goodReceiptTable.id
						LEFT JOIN customer ON stock_in.customer_id = customer.id
						LEFT JOIN supplier ON stock_in.supplier_id = supplier.id
						WHERE stock_in.item_id = '$item_id'
					)
					UNION
					(
						SELECT COALESCE(customer.name, supplier.name, 'Internal Transaction') as name, COALESCE(goodReceiptTable.date, salesReturnTable.date,eventTable.date) as date, stock_in.quantity, COALESCE(goodReceiptTable.name, salesReturnTable.name, eventTable.name) as documentName, COALESCE(goodReceiptTable.goodReceiptId, salesReturnTable.saesReturnId, eventTable.eventId) AS documentId, COALESCE(goodReceiptTable.type, salesReturnTable.type, eventTable.type) AS documentType
						FROM stock_in
						LEFT JOIN (
							SELECT good_receipt.id, code_good_receipt.name, code_good_receipt.date, code_good_receipt.id AS goodReceiptId, 'goodReceipt' AS type
							FROM code_good_receipt
							JOIN good_receipt ON good_receipt.code_good_receipt_id = code_good_receipt.id
						) goodReceiptTable
						ON stock_in.good_receipt_id = goodReceiptTable.id
						LEFT JOIN(
							SELECT code_sales_return_received.name, code_sales_return_received.date, sales_return_received.quantity, sales_return_received.id, code_sales_return_received.id AS saesReturnId, 'salesReturn' AS type
							FROM sales_return_received
							JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
						) salesReturnTable
						ON stock_in.sales_return_received_id = salesReturnTable.id
						LEFT JOIN (
							SELECT stock_in.id, code_event.name, code_event.date, SUM(event.quantity) as quantity, code_event.id AS eventId, 'event' AS type
							FROM event
							JOIN stock_in ON stock_in.event_id = event.id
							JOIN code_event ON event.code_event_id = code_event.id
							GROUP BY event.item_id, event.code_event_id
						) AS eventTable
						ON stock_in.id = eventTable.id
						LEFT JOIN customer ON stock_in.customer_id = customer.id
						LEFT JOIN supplier ON stock_in.supplier_id = supplier.id
						WHERE stock_in.item_id = '$item_id'
					)
				) AS cardTable
				WHERE cardTable.documentId IS NOT NULL
				ORDER BY cardTable.date ASC, cardTable.quantity DESC
			");
			
			$result		= $query->result();
			return $result;
		}

		public function getStockByItemId($itemId)
		{
			$this->db->select("COALESCE(SUM(residue), 0) as quantity, id");
			$this->db->where('item_id', $itemId);
			
			$query = $this->db->get($this->table_stock_in);

			$result = $query->row();
			return $result;
		}

		public function deleteItemFromGoodReceipt($codeGoodReceiptId)
		{
			$query = $this->db->query("
				DELETE stock_in, good_receipt, FROM stock_in FROM stock_in
					JOIN good_receipt ON stock_in.good_receipt_id = good_receipt.id
					WHERE good_receipt.code_good_receipt_id = '$codeGoodReceiptId'
			");
			$result = $query->affected_rows();
			return $result;
		}

		public function calculateValue($date)
		{
			$query		= $this->db->query("
				SELECT SUM(a.value) AS value FROM (
					SELECT (stock_in.price * (stock_in.quantity - COALESCE(stockOutTable.quantity, 0))) AS value
					FROM stock_in
					LEFT JOIN (
						SELECT COALESCE(SUM(stock_out.quantity), 0) AS quantity, stock_out.in_id, COALESCE(deliveryOrderTable.date, eventTable.date, purchaseReturnTable.date) as date 
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
						WHERE COALESCE(deliveryOrderTable.date, eventTable.date, purchaseReturnTable.date) <= '$date'
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
						SELECT code_event.date, event.id
						FROM event
						JOIN code_event ON event.code_event_id = code_event.id
					) eventTable
					ON stock_in.event_id = eventTable.id
					WHERE COALESCE(eventTable.date, goodReceiptTable.date) <= '$date'
					AND stock_in.sales_return_received_id IS NULL
					UNION (
						SELECT SUM(stock_in.price * sales_return_received.quantity) AS value
						FROM sales_return_received
						JOIN sales_return ON sales_return_received.sales_return_id = sales_return.id
						JOIN delivery_order ON delivery_order.id = sales_return.delivery_order_id
						JOIN stock_out ON delivery_order.id = stock_out.delivery_order_id
						JOIN stock_in ON stock_out.in_id = stock_in.id
						JOIN code_sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
						WHERE code_sales_return_received.created_date <= '$date'
						GROUP BY sales_return_received.id
					)
				) AS a
			");

			$value			= 0;
			$result			= $query->result();
			foreach($result as $item){
				$value += $item->value;
			}
			return $value;
		}

		public function updateStockByStockOutBatch($stockBatch)
		{
			foreach($stockBatch as $stockItem)
			{
				$id			= $stockItem['id'];
				$quantity	= $stockItem['quantity'];

				$this->db->where('id', $id);
				$query		= $this->db->get($this->table_stock_in);
				$result		= $query->row();

				$currentResidue = $result->residue;
				$finalResidue = $currentResidue + $quantity;
				echo("Final residue is:" . $finalResidue);
				
				$this->db->set('residue', $finalResidue);
				$this->db->where('id', $id);
				$this->db->update($this->table_stock_in);

				next($stockBatch);
				continue;
			}
		}

		public function checkItemsByCodeGoodReceiptId($id)
		{
			$query			= $this->db->query("
				SELECT * FROM stock_in
				WHERE good_receipt_id IN (
					SELECT id FROM good_receipt
					WHERE good_receipt.code_good_receipt_id = '$id'
				)
			");

			$total			= $query->num_rows();
			
			$query			= $this->db->query("
				SELECT * FROM stock_in
				WHERE good_receipt_id IN (
					SELECT id FROM good_receipt
					WHERE good_receipt.code_good_receipt_id = '$id'
				)
				AND stock_in.quantity = stock_in.residue
			");

			$counted			= $query->num_rows();
			
			if($total == $counted){
				return true;
			} else {
				return false;
			}
		}

		public function deleteByCodeGoodReceiptId($id)
		{
			$query		= $this->db->query("
				DELETE FROM stock_in
				WHERE good_receipt_id IN
				(
					SELECT id FROM good_receipt
					WHERE good_receipt.code_good_receipt_id = '$id'
				)
			");
		}

		public function deleteBySalesReturnId($idArray)
		{
			$this->db->where_in('sales_return_received_id', $idArray);
			return $this->db->delete($this->table_stock_in);
		}
}
