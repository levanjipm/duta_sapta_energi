<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_return_received_model extends CI_Model {
	private $table_sales_return = 'code_sales_return_received';
		
		public $id;
		public $created_by;
		public $created_date;
		public $date;
		public $name;
		public $is_confirm;	
		public $confirmed_by;
		public $is_done;
		public $is_delete;
		public $bank_id;

		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->created_by			= $db_item->created_by;
			$this->created_date			= $db_item->created_date;
			$this->date					= $db_item->date;
			$this->name					= $db_item->name;
			$this->is_confirm			= $db_item->is_confirm;
			$this->confirmed_by			= $db_item->confirmed_by;
			$this->is_done				= $db_item->is_done;
			$this->is_delete			= $db_item->is_delete;
			$this->bank_id				= $db_item->bank_id;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->created_by			= $this->created_by;
			$db_item->created_date			= $this->created_date;
			$db_item->date					= $this->date;
			$db_item->name					= $this->name;
			$db_item->is_confirm			= $this->is_confirm;
			$db_item->confirmed_by			= $this->confirmed_by;
			$db_item->is_done				= $this->is_done;
			$db_item->is_delete				= $this->is_delete;
			$db_item->bank_id				= $this->bank_id;
			
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Sales_return_received_model();
			
			$stub->id					= $db_item->id;
			$stub->created_by			= $db_item->created_by;
			$stub->created_date			= $db_item->created_date;
			$stub->date					= $db_item->date;
			$stub->name					= $db_item->name;
			$stub->is_confirm			= $db_item->is_confirm;
			$stub->confirmed_by			= $db_item->confirmed_by;
			$stub->is_done				= $db_item->is_done;
			$stub->is_delete			= $db_item->is_delete;
			$stub->bank_id				= $db_item->bank_id;
			
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
		
		public function insertItem($date, $document)
		{
			$this->id					= "";
			$this->created_by			= $this->session->userdata('user_id');
			$this->created_date			= date('Y-m-d');
			$this->date					= $date;
			$this->name					= $document;
			$this->is_confirm			= 0;
			$this->confirmed_by			= null;
			$this->is_done				= 0;
			$this->is_delete			= 0;
			$this->bank_id				= null;

			$db_item 				= $this->get_db_from_stub();
			$db_result 				= $this->db->insert($this->table_sales_return, $db_item);
			
			$insert_id				= $this->db->insert_id();
			return $insert_id;
		}

		public function getUnconfirmedSalesreturn($offset = 0, $limit = 10)
		{
			$query = $this->db->query("
				SELECT code_sales_return_received.name as salesReturnName, customer.*, code_sales_return_received.id
				FROM code_sales_return_received
				JOIN sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
				JOIN sales_return ON sales_return_received.sales_return_id = sales_return.id
				JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
				JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
				JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
				JOIN customer ON code_sales_order.customer_id = customer.id
				WHERE code_sales_return_received.is_confirm = '0' AND code_sales_return_received.is_delete = '0'
			");

			$result		= $query->result();
			return $result;
		}

		public function countUnconfirmedSalesReturn()
		{
			$this->db->where('is_confirm', 0);
			$this->db->where("is_delete", 0);
			$query		= $this->db->get($this->table_sales_return);
			$result		= $query->num_rows();
			return $result;
		}

		public function getById($id)
		{
			$query = $this->db->query("
				SELECT code_sales_return_received.*, code_sales_order.customer_id, code_sales_order.id as salesOrderId, code_delivery_order.id as deliveryOrderId
				FROM code_sales_return_received
				JOIN sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
				JOIN sales_return ON sales_return_received.sales_return_id = sales_return.id
				JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
				JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
				JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
				JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
				WHERE code_sales_return_received.id = '$id'
			");

			$result = $query->row();
			return $result;
		}

		public function updateById($status, $id)
		{
			if($status == 1){
				$this->db->set('is_confirm', 1);
				$this->db->set('confirmed_by', $this->session->userdata('user_id'));
				$this->db->where('is_confirm', 0);
				$this->db->where('is_delete', 0);
			} else if($status == 0){
				$this->db->set('is_delete', 1);
				$this->db->set('confirmed_by', $this->session->userdata('user_id'));
				$this->db->where('is_delete', 0);
				$this->db->where('is_confirm', 0);
			} else if($status == 2){
				$this->db->set('is_delete', 1);
				$this->db->set('is_confirm', 0);
				$this->db->set('confirmed_by', $this->session->userdata('user_id'));
			}

			$this->db->where('id', $id);
			$this->db->update($this->table_sales_return);
			return $this->db->affected_rows();
		}
		
		public function getUnassignedItems($offset = 0, $limit = 10)
		{
			$query = $this->db->query("
				SELECT code_sales_return_received.*, code_sales_order.id as salesOrderId, code_delivery_order.id as deliveryOrderId, customer.name as customerName, customer.address, customer.number, customer.block, customer.rw, customer.rt, customer.postal_code, customer.city
				FROM code_sales_return_received
				JOIN sales_return_received ON sales_return_received.code_sales_return_received_id = code_sales_return_received.id
				JOIN sales_return ON sales_return_received.sales_return_id = sales_return.id
				JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
				JOIN code_delivery_order ON delivery_order.code_delivery_order_id = code_delivery_order.id
				JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
				JOIN code_sales_order ON sales_order.code_sales_order_id = code_sales_order.id
				JOIN customer ON code_sales_order.customer_id = customer.id
				WHERE code_sales_return_received.is_done = '0' AND code_sales_return_received.is_confirm = '1'
				LIMIT $limit OFFSET $offset
			");

			$result = $query->result();
			return $result;
		}

		public function countUnassignedItems()
		{
			$this->db->where('is_done', 0);
			$query = $this->db->get($this->table_sales_return);
			
			$result = $query->num_rows();
			return $result;
		}

		public function updateFinanceStatusById($id, $bankTransactionId)
		{
			$this->db->set('bank_id', $bankTransactionId);
			$this->db->set('is_done', 1);
			$this->db->where('id', $id);
			$this->db->update($this->table_sales_return);
		}

		public function getValueByMonthYear($month, $year)
		{
			if($month != 0){
				$query			= $this->db->query("
					SELECT COALESCE(SUM(price_list.price_list * (100 - sales_order.discount) * sales_return_received.quantity / 100), 0) AS value
					FROM sales_return_received
					JOIN sales_return ON sales_return_received.sales_return_id = sales_return.id
					JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN price_list ON sales_order.price_list_id = price_list.id
					WHERE sales_return_received.code_sales_return_received_id IN (
						SELECT DISTINCT(code_sales_return_received.id) AS id
						FROM code_sales_return_received
						WHERE YEAR(code_sales_return_received.date) = '$year'
						AND code_sales_return_received.is_confirm = '1'
					)
				");
			} else {
				$query			= $this->db->query("
					SELECT COALESCE(SUM(price_list.price_list * (100 - sales_order.discount) * sales_return_received.quantity / 100), 0) AS value
					FROM sales_return_received
					JOIN sales_return ON sales_return_received.sales_return_id = sales_return.id
					JOIN delivery_order ON sales_return.delivery_order_id = delivery_order.id
					JOIN sales_order ON delivery_order.sales_order_id = sales_order.id
					JOIN price_list ON sales_order.price_list_id = price_list.id
					WHERE sales_return_received.code_sales_return_received_id IN (
						SELECT DISTINCT(code_sales_return_received.id) AS id
						FROM code_sales_return_received
						WHERE MONTH(code_sales_return_received.date) = '$month' AND YEAR(code_sales_return_received.date) = '$year'
						AND code_sales_return_received.is_confirm = '1'
					)
				");
			}

			$result			= $query->row();
			return $result->value;
		}

		public function resetByBankId($bankId)
		{
			$this->db->set('is_done', 0);
			$this->db->set('bank_id', NULL);
			$this->db->where('bank_id', $bankId);
			$query			= $this->db->update($this->table_sales_return);
			$result			= $this->db->affected_rows();
			return $result;
		}

		public function getConfirmedItems($offset = 0, $limit = 10)
		{
			$this->db->select('code_sales_return_received.*, customer.name AS customerName');
			$this->db->from('code_sales_return_received');
			$this->db->join('sales_return_received', 'sales_return_received.code_sales_return_received_id = code_sales_return_received.id');
			$this->db->join('sales_return', 'sales_return_received.sales_return_id = sales_return.id');
			$this->db->join('delivery_order', 'sales_return.delivery_order_id = delivery_order.id');
			$this->db->join('sales_order', 'delivery_order.sales_order_id = sales_order.id');
			$this->db->join('code_sales_order', 'sales_order.code_sales_order_id = code_sales_order.id');
			$this->db->join('customer', 'code_sales_order.customer_id = customer.id');
			$this->db->where('code_sales_return_received.is_confirm', 1);
			$this->db->where('code_sales_return_received.is_done', 0);
			$this->db->limit($limit, $offset);
			$query		= $this->db->get();
			$result		= $query->result();
			return $result;
		}

		public function countConfirmedItems()
		{
			$this->db->where('is_confirm', 1);
			$this->db->where('is_done', 0);
			$query			= $this->db->get($this->table_sales_return);
			$result			= $query->num_rows();
			return $result;
		}

		public function cancelById($id)
		{
			$query			= $this->db->query("

			");
		}
	}
?>