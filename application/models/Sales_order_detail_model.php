<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_order_detail_model extends CI_Model {
	private $table_sales_order = 'sales_order';
		
		public $id;
		public $price_list_id;
		public $discount;
		public $quantity;
		public $sent;
		public $status;
		public $code_sales_order_id;
		
		public $name;
		public $reference;
		public $price_list;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id						= $db_item->id;
			$this->price_list_id			= $db_item->price_list_id;
			$this->discount					= $db_item->discount;
			$this->quantity					= $db_item->quantity;
			$this->sent						= $db_item->sent;
			$this->status					= $db_item->status;
			$this->code_sales_order_id		= $db_item->code_sales_order_id;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->price_list_id			= $this->price_list_id;
			$db_item->discount				= $this->discount;
			$db_item->quantity				= $this->quantity;
			$db_item->sent					= $this->sent;
			$db_item->status				= $this->status;
			$db_item->code_sales_order_id	= $this->code_sales_order_id;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Sales_order_detail_model();
			
			$stub->id						= $db_item->id;
			$stub->price_list_id			= $db_item->price_list_id;
			$stub->discount					= $db_item->discount;
			$stub->quantity					= $db_item->quantity;
			$stub->sent						= $db_item->sent;
			$stub->status					= $db_item->status;
			$stub->code_sales_order_id		= $db_item->code_sales_order_id;
			
			return $stub;
		}
		
		public function get_new_stub_from_item_price_list_db($db_item)
		{
			$stub = new Sales_order_detail_model();

			$stub->name						= $db_item->name;
			$stub->reference				= $db_item->reference;
			$stub->price_list				= $db_item->price_list;
			$stub->discount					= $db_item->discount;
			$stub->quantity					= $db_item->quantity;
			$stub->id						= $db_item->id;
			$stub->sent						= $db_item->sent;
			
			return $stub;
		}
		
		public function map_list_item_price_list($items)
		{
			$result = array();
			foreach ($items as $item)
			{
				$result[] = $this->get_new_stub_from_item_price_list_db($item);
			}
			return $result;
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
		
		public function create_batch($id)
		{
			$discount_array		= $this->input->post('discount');
			$quantity_array		= $this->input->post('quantity');
			foreach($discount_array as $discount){
				$batch[] = array(
					'id' => '',
					'price_list_id' => key($discount_array),
					'discount' => $discount,
					'quantity' => $quantity_array[key($discount_array)],
					'code_sales_order_id' => $id
				);
				
				next($discount_array);
			}
			
			if(!empty($this->input->post('bonus_quantity'))){
				$bonus_array		= $this->input->post('bonus_quantity');
				foreach($bonus_array as $bonus){
					$batch[] = array(
						'id' => '',
						'price_list_id' => key($bonus_array),
						'discount' => '100',
						'quantity' => $bonus,
						'code_sales_order_id' => $id
					);
					
					next($bonus_array);
				}
			}
			
			return $batch;
		}
		
		public function insert_from_post($id)
		{
			$code_sales_order_id	= $id;			
			$batch 					= $this->Sales_order_detail_model->create_batch($id);
			$this->db->insert_batch($this->table_sales_order, $batch);
		}
		
		public function show_by_code_sales_order_id($id)
		{
			$query = $this->db->query("
				SELECT sales_order.id, item.name, item.reference, price_list.price_list, sales_order.quantity,
				(COALESCE(stock_table.stock,0) - COALESCE(sendingTable.sending,0)) AS stock, sales_order.sent, sales_order.discount
				FROM sales_order 
				JOIN price_list ON price_list.id = sales_order.price_list_id
				JOIN item ON price_list.item_id = item.id
				LEFT JOIN (
					SELECT SUM(residue) as stock, item_id 
					FROM stock_in 
					GROUP BY item_id
				) stock_table
				ON item.id = stock_table.item_id
				LEFT JOIN (
					SELECT SUM(delivery_order.quantity) AS sending, price_list.item_id
					FROM delivery_order
					JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN price_list ON sales_order.price_list_id = price_list.id
					WHERE code_delivery_order.is_sent = 0
					AND code_delivery_order.is_delete = 0
					GROUP BY price_list.item_id
				) sendingTable
				ON item.id = sendingTable.item_id
				WHERE sales_order.code_sales_order_id = '$id'
			");	

			$items	= $query->result();
			return $items;
		}
		
		public function getById($id)
		{
			$this->db->where('id', $id);
			$query = $this->db->get($this->table_sales_order);
			
			$item = $query->row();
			
			return ($item !== null) ? $this->get_stub_from_db($item) : null;
		}
		
		public function check_sales_order($sales_order_array, $quantity)
		{
			$code_sales_order_array 		= array();
			$validation						= TRUE;
			$this->load->model('Sales_order_detail_model');
			foreach($sales_order_array as $sales_order){
				$key						= key($sales_order_array);
				$quantity_delivery_order 	= $quantity[$key];
				$items 						= $this->Sales_order_detail_model->getById($sales_order);
				$sales_order_id 			= $items->code_sales_order_id;
				$quantity_ordered			= $items->quantity;
				$quantity_sent				= $items->sent;
				
				if($quantity_delivery_order + $quantity_sent > $quantity_ordered){
					$validation				= FALSE;
				}
				
				if(!in_array($sales_order_id, $code_sales_order_array)){
					array_push($code_sales_order_array, $sales_order_id);
				}
				
				next($sales_order_array);
			}
			
			$count	= count($code_sales_order_array);
			if($count == 1 && $validation == TRUE){
				return TRUE;
			} else {
				return FALSE;
			}
		}
		
		public function updateSalesOrderSent($sales_order_array, $quantity)
		{
			$batch = array();
			foreach($sales_order_array as $sales_order){
				$items 						= $this->Sales_order_detail_model->getById($sales_order);
				$key						= key($sales_order_array);
				$quantity_delivery_order	= $quantity[$key];
				$quantity_ordered			= $items->quantity;
				$quantity_sent				= $items->sent;
				$final_quantity				= $quantity_delivery_order + $quantity_sent;
				if($final_quantity			== $quantity_ordered){
					$status					= 1;
				} else {
					$status 				= 0;
				}
				
				$batch[] = array(
					'id' => $sales_order,
					'sent' => $final_quantity,
					'status' => $status
				);
				
				next($sales_order_array);
					
			}
			
			$this->db->update_batch($this->table_sales_order,$batch, 'id'); 
		}
		
		public function getItemNeeded()
		{
			$this->db->select('SUM(sales_order.quantity - sales_order.sent) as quantity, price_list.item_id, item.reference, item.name');
			$this->db->from('sales_order');
			$this->db->join('price_list', 'sales_order.price_list_id = price_list.id');
			$this->db->join('item', 'price_list.item_id = item.id');
			$this->db->where('sales_order.status', 0);
			$this->db->group_by('price_list.item_id');
			$query		= $this->db->get();
			$result		= $query->result();
			
			return $result;
		}
		
		public function getPendingValueByCustomerId($customer_id)
		{
			$query			= $this->db->query("
				SELECT COALESCE(SUM((sales_order.quantity - sales_order.sent) * price_list.price_list * (100 - sales_order.discount) / 100), 0) as value
				FROM sales_order
				LEFT JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
				LEFT JOIN code_sales_order_close_request ON code_sales_order.id = code_sales_order_close_request.code_sales_order_id = code_sales_order.id
				JOIN price_list ON sales_order.price_list_id = price_list.id
				WHERE code_sales_order.customer_id = '$customer_id'
				AND COALESCE(code_sales_order_close_request.is_approved, 0) = 0
			");			
			$result		= $query->row();
			
			return $result->value;
		}
		
		public function updateCancelDeliveryOrder($sales_order_array)
		{
			$this->db->update_batch($this->table_sales_order, $sales_order_array, 'id');
		}
		
		public function updateStatusByCodeSalesOrderId($sales_order_id)
		{
			$this->db->set('status', 1);
			$this->db->where('code_sales_order_id', $sales_order_id);
			$this->db->update($this->table_sales_order);
		}

		public function updateByCodeDeliveryOrderIdCancel($deliveryOrderId)
		{
			$query			= $this->db->query("
				SELECT delivery_order.quantity, sales_order.id, sales_order.sent FROM delivery_order 
				JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
				WHERE delivery_order.code_delivery_order_id = '$deliveryOrderId'
			");
			$result			= $query->result();
			$batch			= array();
			foreach($result as $data){
				$batch[] = array(
					"id" => $data->id,
					"sent" => (int)$data->sent - (int)$data->quantity,
					"status" => 0,
				);

				next($result);
			}

			$this->db->update_batch($this->table_sales_order, $batch, 'id');
		}

		public function getByCodeSalesOrderIdArray($idArray)
		{
			$this->db->select('sales_order.*, item.reference, item.name, price_list.price_list');
			$this->db->from('sales_order');
			$this->db->join('price_list', 'sales_order.price_list_id = price_list.id');
			$this->db->join('item', 'price_list.item_id = item.id');
			$this->db->where_in('sales_order.code_sales_order_id', $idArray);
			
			$query			= $this->db->get();
			$result			= $query->result();
			return $result;
		}
}
