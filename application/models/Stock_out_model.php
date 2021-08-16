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
				while($quantity > 0){
					$stock_in				= $this->Stock_in_model->getResidueByItemId($item_id);
					$residue				= $stock_in->residue;
					$in_id					= $stock_in->id;
					if($residue > $quantity){
						$current_residue	= $residue - $quantity;
						$this->Stock_in_model->updateById($in_id, $current_residue);
						$this->Stock_out_model->insertStockDeliveryOrder($in_id, $quantity, $delivery_order_id, $customer_id); 
						break;
					} else {
						$this->Stock_in_model->updateById($in_id, 0);
						$this->Stock_out_model->insertStockDeliveryOrder($in_id, $residue, $delivery_order_id, $customer_id);
						
						$quantity -= $residue;
					}
				}

				next($delivery_order_array);
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

				next($eventItemArray);
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

		public function insertFromPurchaseReturn($stockOutArray, $supplierId)
		{
			$this->load->model('Stock_in_model');
			foreach($stockOutArray as $stockOut){
				$item_id				= $stockOut['item_id'];
				$quantity				= $stockOut['quantity'];
				$purchase_return_id		= $stockOut['id'];
				while($quantity > 0){
					$stock_in				= $this->Stock_in_model->getResidueByItemId($item_id);
					$residue				= $stock_in->residue;
					$in_id					= $stock_in->id;
					
					if($residue	> $quantity){
						$current_residue	= $residue - $quantity;
						$this->Stock_in_model->updateById($in_id, $current_residue);
						$result = $this->Stock_out_model->insertStockOutFromPurchaseReturn($in_id, $quantity, $purchase_return_id, $supplierId); 
						break;
					} else {
						$current_residue		= $quantity - $residue;
						$this->Stock_in_model->updateById($in_id, 0);
						$this->Stock_out_model->insertStockOutFromPurchaseReturn($in_id, $current_residue, $purchase_return_id, $supplierId);
						$quantity = $quantity - $residue;
					}
				}
			}
		}

		public function insertStockOutFromPurchaseReturn($in_id, $quantity, $purchase_return_id, $supplierId)
		{
			$db_item		= array(
				'in_id' => $in_id,
				'quantity' => $quantity,
				'event_id' => null,
				'customer_id' => null,
				'supplier_id' => $supplierId,
				'purchase_return_id' => $purchase_return_id,
				'delivery_order_id' => null
			);
				
			$this->db->insert($this->table_stock_out, $db_item);
			return $this->db->affected_rows();
		}

		public function countRestockItems()
		{
			$query			= $this->db->query("
				SELECT b.id as item_id, b.name, b.reference, b.sumQuantity, b.confidence_level, b.month, b.year, c.residue, COALESCE(d.bought, 0) AS bought
				FROM (
					SELECT item.id, item.reference, item.name, item.confidence_level, SUM(sales_order.quantity) AS sumQuantity, MONTH(code_sales_order.date) as month, YEAR(code_sales_order.date) as year
					FROM sales_order
					JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
					JOIN price_list ON sales_order.price_list_id = price_list.id
					JOIN item ON price_list.item_id = item.id
					WHERE item.is_notified_stock = '1' AND code_sales_order.is_confirm = '1' AND code_sales_order.id NOT IN (
						SELECT code_sales_order_id FROM code_sales_order_close_request
						WHERE is_approved = '1'
					)
					AND code_sales_order.date BETWEEN DATE_ADD(CURDATE(),INTERVAL -6 MONTH) AND CURDATE()
					GROUP BY YEAR(code_sales_order.date), MONTH(code_sales_order.date), price_list.item_id
					ORDER BY code_sales_order.date ASC
				) AS b
				JOIN (
					SELECT SUM(residue) as residue, item_id as id 
					FROM stock_in
					GROUP BY item_id
				) AS c
				ON b.id = c.id
				LEFT JOIN (
					SELECT SUM(purchase_order.quantity - purchase_order.received) AS bought, purchase_order.item_id
					FROM purchase_order
					WHERE purchase_order.status = '0'
					GROUP BY purchase_order.item_id
				) AS d
				ON b.id = d.item_id
			");

			$result		= $query->result();
			return $result;
		}

		public function deleteByCodeDeliveryOrderId($deliveryOrderId)
		{
			$query			= $this->db->query("
				SELECT * FROM stock_out WHERE id IN (
					SELECT stock_out.id 
					FROM stock_out
					JOIN delivery_order ON stock_out.delivery_order_id = delivery_order.id
					JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					WHERE code_delivery_order.id = '$deliveryOrderId'
				)
			");

			$result			= $query->result();

			$query			= $this->db->query("
				DELETE stock_out
				FROM stock_out
				JOIN delivery_order ON stock_out.delivery_order_id = delivery_order.id
				JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
				WHERE code_delivery_order.id = '$deliveryOrderId'
			");

			$deleteResult			= $this->db->affected_rows();
			if($deleteResult > 0){
				return $result;
			} else {
				return NULL;
			}
			
		}

		public function calculateMonthlyOutput($reference)
		{
			$paramDate		= date("Y-m-d", mktime(0,0,0,date('m'), 1, date('Y')));
			$query		= $this->db->query("
				SELECT SUM(stock_out.quantity) AS quantity, MONTH(code_delivery_order.date) AS month, YEAR(code_delivery_order.date) AS year
				FROM stock_out
				JOIN delivery_order ON stock_out.delivery_order_id = delivery_order.id
				JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
				JOIN price_list ON sales_order.price_list_id = price_list.id
				JOIN item ON price_list.item_id = item.id
				JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
				WHERE code_delivery_order.date <= '$paramDate'
				AND item.reference = '$reference'
				GROUP BY MONTH(code_delivery_order.date), YEAR(code_delivery_order.date)
				ORDER BY code_delivery_order.date ASC
			");

			$result		= $query->result();
			return $result;
		}

		public function deleteByPurchaseReturnId($idArray)
		{
			$this->db->where_in('purchase_return_id', $idArray);
			return $this->db->delete($this->table_stock_out);
		}
	}
?>
