<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_return_detail_model extends CI_Model {
	private $table_sales_return = 'sales_return';
		
		public $id;
		public $delivery_order_id;
		public $quantity;
		public $received;
		public $is_done;
		public $code_sales_return_id;
		
		public $complete_address;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->delivery_order_id	= $db_item->delivery_order_id;
			$this->quantity				= $db_item->quantity;
			$this->received				= $db_item->received;
			$this->is_done				= $db_item->is_done;
			$this->code_sales_return_id	= $db_item->code_sales_return_id;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;		
			$db_item->delivery_order_id		= $this->delivery_order_id;
			$db_item->quantity				= $this->quantity;		
			$db_item->received				= $this->received;				
			$db_item->is_done				= $this->is_done;				
			$db_item->code_sales_return_id	= $this->code_sales_return_id;	
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Supplier_model();
			
			$stub->id					= $db_item->id;		
			$stub->delivery_order_id	= $db_item->delivery_order_id;
			$stub->quantity				= $db_item->quantity;		
			$stub->received				= $db_item->received;				
			$stub->is_done				= $db_item->is_done;				
			$stub->code_sales_return_id	= $db_item->code_sales_return_id;
			
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
		
		public function getQuantityByDeliveryOrderIdArray($deliveryOrderIdArray)
		{
			$formattedArray = "'" . implode(',', $deliveryOrderIdArray) . "'";

			$query = $this->db->query("SELECT delivery_order.id, delivery_order.quantity, COALESCE(SUM(IF(sales_return.is_done = 1, sales_return.received, sales_return.quantity)), 0) AS returned 
				FROM sales_return
				LEFT JOIN code_sales_return ON sales_return.code_sales_return_id = code_sales_return.id
				JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
				WHERE delivery_order.id IN ($formattedArray)  AND code_sales_return.is_delete = '0'");
			$result = $query->result();
			return $result;
		}

		public function insertItemArray($returnQuantityArray, $codeSalesReturnId)
		{
			$batch		= array();
			foreach($returnQuantityArray as $key => $returnQuantity)
			{
				if($returnQuantity > 0){
					$item		= array(
						'id' => "",
						'delivery_order_id' => $key,
						'quantity' => $returnQuantity,
						'received' => 0,
						'is_done' => 0,
						'code_sales_return_id' => $codeSalesReturnId
					);
					array_push($batch, $item);	
				}
				next($returnQuantityArray);
			}

			$this->db->insert_batch($this->table_sales_return, $batch);
			
		}

		public function getByCodeSalesReturnId($codeSalesReturnId)
		{
			$query	= $this->db->query("
				SELECT item.reference, item.name, sales_return.id, sales_return.quantity, sales_return.received, sales_return.is_done, price_list.price_list, sales_order.discount FROM
				sales_return
				JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
				JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
				JOIN price_list ON sales_order.price_list_id = price_list.id
				JOIN item ON price_list.item_id = item.id
				WHERE sales_return.code_sales_return_id = '$codeSalesReturnId'
			");
			$result = $query->result();
			return $result;
		}

		public function getIncompletedReturn($offset = 0, $term = "", $limit = 10)
		{
			$query		= $this->db->query("
				SELECT code_sales_return.name, code_sales_return.created_date, customer.*, code_sales_return.id, delivery_order.code_delivery_order_id as deliveryOrderId, users.name as created_by
				 FROM
				(
					SELECT DISTINCT(sales_return.code_sales_return_id) as id
					FROM sales_return
					LEFT JOIN code_sales_return ON sales_return.code_sales_return_id = code_sales_return.id
					WHERE sales_return.is_done = '0' AND code_sales_return.is_confirm = '1'
				) AS a
				JOIN code_sales_return ON a.id = code_sales_return.id
				JOIN users ON code_sales_return.created_by = users.id
				JOIN sales_return ON sales_return.code_sales_return_id = code_sales_return.id
				JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
				JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
				JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
				JOIN customer ON code_sales_order.customer_id = customer.id
				WHERE customer.name LIKE '%$term%' OR customer.address LIKE '%$term%'
				ORDER BY code_sales_return.created_date
			");
			$result = $query->result();
			return $result;
		}

		public function countIncompletedReturn($term = "")
		{
			$query		= $this->db->query("
				SELECT code_sales_return.id
				FROM
				(
					SELECT DISTINCT(sales_return.code_sales_return_id) as id
					FROM sales_return
					WHERE sales_return.is_done = '0'
				) AS a
				JOIN code_sales_return ON a.id = code_sales_return.id
				JOIN sales_return ON sales_return.code_sales_return_id = code_sales_return.id
				JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
				JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
				JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
				JOIN customer ON code_sales_order.customer_id = customer.id
				WHERE customer.name LIKE '%$term%' OR customer.address LIKE '%$term%'
			");
			$result = $query->num_rows();
			return $result;
		}

		public function quantityCheck($itemArray)
		{
			$itemArrayKeys		= array_keys($itemArray);

			$this->db->select('sales_return.*');
			$this->db->where_in('id', $itemArrayKeys);
			$query		= $this->db->get($this->table_sales_return);
			$result		= $query->result();

			$data		= true;

			foreach($result as $item){
				$id			= $item->id;
				$quantity	= $item->quantity;
				$received	= $item->received;
				$return		= $itemArray[$id];

				if($received + $return > $quantity){
					$data	= false;
				}
			}

			return $result;
		}

		public function updateItems($itemArray)
		{
			$idArray		 = array_keys($itemArray);

			$this->db->where_in('id', $idArray);
			$query			= $this->db->get($this->table_sales_return);
			$result			= $query->result();
			$resultArray	= (array) $result;

			$batch			= array();

			foreach($resultArray as $result)
			{
				$received		= (int) $result->received;
				$quantity		= (int) $result->quantity;
				$id				= $result->id;
				$return			= (int) $itemArray[$id];
				
				if($received + $return == $quantity){
					$batchItem		= array(
						'id' => $id,
						'received' => ($received + $return),
						'is_done' => 1
					);
				} else if($received + $return < $quantity) {
					$batchItem		= array(
						'id' => $id,
						'received' => ($received + $return),
						'is_done' => 0
					);
				}

				array_push($batch, $batchItem);
			}

			$this->db->update_batch($this->table_sales_return, $batch, 'id');
		}

		public function getByCodeDeliveryOrderId($deliveryOrderId)
		{
			$query			= $this->db->query("
				SELECT delivery_order.id, COALESCE(IF(sales_return.is_done = 1, sales_return.received, sales_return.quantity),0) as quantity 
				FROM sales_return
				JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
				JOIN code_sales_return ON sales_return.code_sales_return_id = code_sales_return.id
				JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
				WHERE code_delivery_order.id = '$deliveryOrderId' AND code_sales_return.is_delete <> 0
			");

			$result = $query->result();
			return $result;
		}

		public function updateByDeleteReceivedArray($quantityArray)
		{
			$keyArray = array();
			foreach($quantityArray as $id => $quantity)
			{
				array_push($keyArray, $id);
			};

			$this->db->where_in('id', $keyArray);
			$query		= $this->db->get($this->table_sales_return);
			$result		= $query->result();

			$updateBatch = array();
			foreach($result as $item)
			{
				$quantity		= $item->quantity;
				$received		= $item->received;
				$id				= $item->id;

				$finalReceived	= $received - $quantityArray[$id];
				$updateBatch[] = array(
					'id' => $id,
					'received' => $finalReceived,
					'is_done' => 0
				);
			};
			$this->db->update_batch($this->table_sales_return, $updateBatch, 'id');
		}
	}
?>
