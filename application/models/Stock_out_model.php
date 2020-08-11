<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_out_model extends CI_Model {
	private $table_stock_out = 'stock_out';
		
		public $id;
		public $in_id;
		public $quantity;
		public $customer_id;
		public $supplier_id;
		public $delivery_order_id;
		public $code_event_id;
		public $purchase_return_id;
		
		public function __construct()
		{
			parent::__construct();
			
			$this->id						= '';
			$this->in_id					= NULL;
			$this->supplier_id				= NULL;
			$this->customer_id				= NULL;
			$this->delivery_order_id	= NULL;
			$this->purchase_return_id		= NULL;
			$this->code_event_id			= NULL;
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id						= $db_item->id;
			$this->in_id					= $db_item->in_id;
			$this->quantity					= $db_item->quantity;
			$this->supplier_id				= $db_item->supplier_id;
			$this->customer_id				= $db_item->customer_id;
			$this->delivery_order_id		= $db_item->delivery_order_id;
			$this->purchase_return_id		= $db_item->purchase_return_id;
			$this->code_event_id			= $db_item->code_event_id;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id						= $this->id;
			$db_item->in_id						= $this->in_id;
			$db_item->quantity					= $this->quantity;
			$db_item->supplier_id				= $this->supplier_id;
			$db_item->customer_id				= $this->customer_id;
			$db_item->delivery_order_id			= $this->delivery_order_id;
			$db_item->purchase_return_id		= $this->purchase_return_id;
			$db_item->code_event_id				= $this->code_event_id;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Delivery_order_model();
			
			$stub->id						= $db_item->id;
			$stub->in_id					= $db_item->in_id;
			$stub->quantity					= $db_item->quantity;
			$stub->supplier_id				= $db_item->supplier_id;
			$stub->customer_id				= $db_item->customer_id;
			$stub->delivery_order_id		= $db_item->delivery_order_id;
			$stub->purchase_return_id		= $db_item->purchase_return_id;
			$stub->code_event_id			= $db_item->code_event_id;
			
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
		
		public function sendDeliveryOrder($delivery_order_array)
		{
			$this->load->model('Stock_in_model');
			foreach($delivery_order_array as $delivery_order){
				$item_id				= $delivery_order['item_id'];
				$quantity				= $delivery_order['quantity'];
				$delivery_order_id		= $delivery_order['delivery_order_id'];
				$customer_id			= $delivery_order['customer_id'];
				$stock_in				= $this->Stock_in_model->getResidueByItemId($item_id);
				$residue				= $stock_in->residue;
				$in_id					= $stock_in->id;
				while($quantity > 0){
					if($residue		> $quantity){
						$current_residue	= $residue - $quantity;
						$this->Stock_in_model->updateById($in_id, $current_residue);
						$this->Stock_out_model->insertStockDeliveryOrder($in_id, $quantity, $delivery_order_id, $customer_id); 
						break;
					} else {
						$current_residue		= $quantity - $residue;
						$this->Stock_in_model->updateById($in_id, 0);
						$this->Stock_out_model->insertStockDeliveryOrder($in_id, $current_residue, $delivery_order_id, $customer_id);
						
						$quantity = $quantity - $residue;
					}
				}
			}
		}
		
		public function insertStockDeliveryOrder($in_id, $quantity, $delivery_order_id, $customer_id)
		{
			$db_item		= array(
				'in_id' => $in_id,
				'quantity' => $quantity,
				'delivery_order_id' => $delivery_order_id,
				'customer_id' => $customer_id,
				'supplier_id' => null,
				'event_id' => null,
				'purchase_return_id' => null
			);
				
			$this->db->insert($this->table_stock_out, $db_item);
		}
		
		public function insertByEvent($eventItemArray)
		{
			$this->load->model('Stock_in_model');
			foreach($eventItemArray as $event){
				$item_id				= $event['item_id'];
				$quantity				= $event['quantity'];
				$event_id				= $event['id'];

				while($quantity > 0){
					$stock_in				= $this->Stock_in_model->getResidueByItemId($item_id);
					$residue				= $stock_in->residue;
					$in_id					= $stock_in->id;
					
					if($residue	> $quantity){
						$current_residue	= $residue - $quantity;
						$this->Stock_in_model->updateById($in_id, $current_residue);
						$result = $this->Stock_out_model->insertStockOutFromEvent($in_id, $quantity, $event_id); 
						break;
					} else {
						$current_residue		= $quantity - $residue;
						$this->Stock_in_model->updateById($in_id, 0);
						$result = $this->Stock_out_model->insertStockOutFromEvent($in_id, $current_residue, $event_id);
						$quantity = $quantity - $residue;
					}
				}
			}
		}
		
		public function insertStockOutFromEvent($in_id, $quantity, $event_id)
		{
			$db_item		= array(
				'in_id' => $in_id,
				'quantity' => $quantity,
				'event_id' => $event_id,
				'customer_id' => null,
				'supplier_id' => null,
				'purchase_return_id' => null,
				'delivery_order_id' => null
			);
				
			$this->db->insert($this->table_stock_out, $db_item);
			return $this->db->affected_rows();
		}

		public function getByInIdArray($inIdArray){
			$query	= $this->db->query("
				SELECT COALESCE(deliveryOrderTable.name, eventTable.name) as documentName, COALESCE(customer.name, supplier.name, 'Internal Transaction') as name, COALESCE(deliveryOrderTable.date, eventTable.date) as date, COALESCE(deliveryOrderTable.quantity, eventTable.quantity) as quantity
				FROM stock_out
				LEFT JOIN (
					SELECT stock_out.id, code_delivery_order.name, code_delivery_order.date, SUM(delivery_order.quantity) as quantity
					FROM delivery_order
					JOIN stock_out ON stock_out.delivery_order_id = delivery_order.id
					JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					GROUP BY delivery_order.code_delivery_order_id
				) AS deliveryOrderTable
				ON stock_out.id = deliveryOrderTable.id
				LEFT JOIN (
					SELECT stock_out.id, code_event.name, code_event.date, SUM(event.quantity) as quantity
					FROM event
					JOIN stock_out ON stock_out.event_id = event.id
					JOIN code_event ON event.code_event_id = code_event.id
					GROUP BY event.item_id, event.code_event_id
				) AS eventTable
				ON stock_out.id = eventTable.id
				LEFT JOIN customer ON stock_out.customer_id = customer.id
				LEFT JOIN supplier ON stock_out.supplier_id = supplier.id
				
			");
			$result = $query->result();
			return $result;
		}
}