<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Delivery_order_detail_model extends CI_Model {
	private $table_delivery_order = 'delivery_order';
		
		public $id;
		public $sales_order_id;
		public $code_delivery_order_id;
		public $quantity;
		
		public $item_id;
		public $customer_id;
		
		public $reference;
		public $name;
		public $do_name;
		public $so_name;
		public $customer_name;
		public $address;
		public $city;
		public $date;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_new_stub_from_db_items($db_item)
		{
			$stub = new Delivery_order_detail_model();
			
			$stub->id				= $db_item->id;
			$stub->reference		= $db_item->reference;
			$stub->name				= $db_item->name;
			$stub->quantity			= $db_item->quantity;
			$stub->do_name			= $db_item->do_name;
			$stub->so_name			= $db_item->so_name;
			$stub->customer_name	= $db_item->customer_name;
			$stub->address			= $db_item->address;
			$stub->city				= $db_item->city;
			$stub->date				= $db_item->date;
			
			return $stub;
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id						= $db_item->id;
			$this->sales_order_id			= $db_item->sales_order_id;
			$this->code_delivery_order_id	= $db_item->code_delivery_order_id;
			$this->quantity					= $db_item->quantity;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id						= $this->id;
			$db_item->sales_order_id			= $this->sales_order_id;
			$db_item->code_delivery_order_id	= $this->code_delivery_order_id;
			$db_item->quantity					= $this->quantity;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Delivery_order_detail_model();
			
			$stub->id						= $db_item->id;
			$stub->sales_order_id			= $db_item->sales_order_id;
			$stub->code_delivery_order_id	= $db_item->code_delivery_order_id;
			$stub->quantity					= $db_item->quantity;
			
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
		
		public function map_list_with_items($items)
		{
			$result = array();
			foreach ($items as $item)
			{
				$result[] = $this->get_new_stub_from_db_items($item);
			}
			return $result;
		}
		
		public function create_batch($id)
		{
			$sales_order_array	= array_keys($this->input->post('quantity'));
			$quantity_array		= array_values($this->input->post('quantity'));
			foreach($sales_order_array as $sales_order){
				$key			= key($sales_order_array);
				$quantity		= $quantity_array[$key];
				if($quantity > 0){
					$batch[] = array(
						'id' => '',
						'sales_order_id' => $sales_order,
						'code_delivery_order_id' => $id,
						'quantity' => $quantity,
					);
				}
				
				next($sales_order_array);
			}
			
			return $batch;
		}
		
		public function insert_from_post($id)
		{
			$batch 					= $this->Delivery_order_detail_model->create_batch($id);
			$this->db->insert_batch($this->table_delivery_order, $batch);
			
			return ($this->db->affected_rows() > 0);
		}
		
		public function show_by_id($id)
		{
			$this->db->where('id', $id);
			$query = $this->db->get($this->table_delivery_order);
			
			$item = $query->row();
			
			return ($item !== null) ? $this->get_stub_from_db($item) : null;
		}
		
		public function getDeliveryOrderBatch($id)
		{
			$this->db->select('price_list.item_id, code_sales_order.customer_id, delivery_order.quantity, delivery_order.code_delivery_order_id');
			$this->db->from('delivery_order');
			$this->db->join('sales_order', 'delivery_order.sales_order_id = sales_order.id');
			$this->db->join('price_list', 'sales_order.price_list_id = price_list.id');
			$this->db->join('code_sales_order', 'code_sales_order.id = sales_order.code_sales_order_id', 'inner');
			$this->db->where('delivery_order.code_delivery_order_id', $id);
			
			$query 	= $this->db->get();
			$items	= $query->result();
			
			$batch = $this->Delivery_order_detail_model->create_stock_batch($items);
			return ($batch !== null) ? $batch : null;
		}
		
		public function create_stock_batch($results)
		{
			foreach($results as $result){
				$quantity					= $result->quantity;
				$item_id					= $result->item_id;
				$customer_id				= $result->customer_id;
				$code_delivery_order_id		= $result->code_delivery_order_id;
				$batch[] = array(
					'id' => '',
					'quantity' => $quantity,
					'code_delivery_order_id' => $code_delivery_order_id,
					'customer_id' => $customer_id,
					'item_id' => $item_id,
				);
			}
			
			return $batch;
		}
		
		public function getByCodeSalesOrderId($sales_order_id)
		{
			$this->db->select('DISTINCT(code_delivery_order.id) as id, code_delivery_order.date, code_delivery_order.name, code_delivery_order.is_confirm, code_delivery_order.is_sent, code_delivery_order.invoice_id');
			$this->db->from('delivery_order');
			$this->db->join('sales_order', 'delivery_order.sales_order_id = sales_order.id');
			$this->db->join('code_delivery_order', 'delivery_order.code_delivery_order_id = code_delivery_order.id');
			$this->db->where('sales_order.code_sales_order_id', $sales_order_id);
			$this->db->where('code_delivery_order.is_delete', 0);
			$query 	= $this->db->get();
			$items	= $query->result();
			
			return $items;
		}

		public function getByCodeDeliveryOrderId($id)
		{
			$this->db->select('delivery_order.*, item.name, item.reference, item.id as item_id');
			$this->db->from('delivery_order');
			$this->db->join('sales_order', 'delivery_order.sales_order_id = sales_order.id');
			$this->db->join('price_list', 'price_list.id = sales_order.price_list_id');
			$this->db->join('item', 'price_list.item_id = item.id');
			$this->db->where('delivery_order.code_delivery_order_id', $id);
			$query		= $this->db->get();
			$result		= $query->result();

			return $result;
		}
		
		public function getStatusByCodeDeliveryOrderId($id)
		{
			$this->db->select('delivery_order.*, sales_order.quantity as ordered, sales_order.sent');
			$this->db->from('delivery_order');
			$this->db->join('sales_order', 'delivery_order.sales_order_id = sales_order.id');
			$this->db->where('delivery_order.code_delivery_order_id', $id);
			$query		= $this->db->get();
			$result		= $query->result();
			
			$sales_order_array		= $this->Delivery_order_detail_model->createSalesOrderBatch($result);
			return $sales_order_array;
		}
		
		public function createSalesOrderBatch($delivery_order_array)
		{
			$batch		= array();
			foreach($delivery_order_array as $delivery_order)
			{
				$sales_order_id		= $delivery_order->sales_order_id;
				$quantity			= $delivery_order->quantity;
				$ordered			= $delivery_order->ordered;
				$sent				= $delivery_order->sent;
				
				$final_quantity		= $sent - $quantity;
				if($final_quantity < $ordered){
					$batch[] = array(
						'id' => $sales_order_id,
						'status' => 0,
						'sent' => $final_quantity
					);
				} else if($final_quantity == $ordered){
					$batch[] = array(
						'id' => $sales_order_id,
						'status' => 1,
						'sent' => $final_quantity
					);
				}
				
				next($delivery_order_array);
			}
			
			return $batch;
		}
}