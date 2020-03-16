<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_model extends CI_Model {
	private $table_invoice = 'invoice';
		
		public $id;
		public $name;
		public $value;
		public $customer_id;
		public $date;
		public $information;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->name					= $db_item->name;
			$this->value				= $db_item->value;
			$this->customer_id			= $db_item->customer_id;
			$this->date					= $db_item->date;
			$this->information			= $db_item->information;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->name					= $this->name;
			$db_item->value					= $this->value;
			$db_item->customer_id			= $this->customer_id;
			$db_item->date					= $this->date;
			$db_item->information			= $this->information;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Customer_model();
			
			$stub->id					= $db_item->id;
			$stub->name					= $db_item->name;
			$stub->value				= $db_item->value;
			$stub->customer_id			= $db_item->customer_id;
			$stub->date					= $db_item->date;
			$stub->information			= $db_item->information;
			
			return $stub;
		}
		
		public function map_list($invoices)
		{
			$result = array();
			foreach ($invoices as $invoice)
			{
				$result[] = $this->get_new_stub_from_db($invoice);
			}
			return $result;
		}
		
		public function show_items($offset = 0, $filter = '', $limit = 25)
		{
			if($filter != ''){
				$this->db->like('name', $filter, 'both');
				$this->db->or_like('address', $filter, 'both');
				$this->db->or_like('city', $filter, 'both');
			}
			
			$query 		= $this->db->get($this->table_customer, $limit, $offset);
			$items	 	= $query->result();
			
			$result 	= $this->map_list($items);
			
			return $result;
		}
		
		public function count_items($filter = '')
		{
			if($filter != ''){
				$this->db->like('name', $filter, 'both');
				$this->db->or_like('address', $filter, 'both');
			}
			
			$query		= $this->db->get($this->table_customer);
			$result		= $query->num_rows();
			
			return $result;
		}
		
		public function delivery_order_input($delivery_order_id,$delivery_order_data)
		{
			$value	= $this->Invoice_model->calculate_delivery_order_value($delivery_order_id);
			$name	= $delivery_order_data->do_name;
			$date	= date('Y-m-d', strtotime($delivery_order_data->date));
			$customer_id	= $delivery_order_data->customer_id;
			
			$invoice_name	= 'INV.DSE' . substr($name,7);
			$db_item		= array(
				'id' => '',
				'name' => $invoice_name,
				'value' => $value,
				'customer_id' => $customer_id,
				'information' => $name,
				'date' => $date
			);
			
			$this->db->insert($this->table_invoice, $db_item);
			if($this->db->affected_rows() > 0){
				return ($this->db->insert_id());
			} else {
				return NULL;
			}
		}
		
		public function calculate_delivery_order_value($delivery_order_id)
		{
			$this->db->select('sum(`price_list`.`price_list`*`delivery_order`.`quantity`* (100 - `sales_order`.`discount`) / 100) as value', FAlSE);
			$this->db->from('delivery_order');
			$this->db->join('sales_order', 'delivery_order.sales_order_id = sales_order.id');
			$this->db->join('price_list', 'sales_order.price_list_id = price_list.id');
			$this->db->where('delivery_order.code_delivery_order_id', $delivery_order_id);
			$query	= $this->db->get();
			$result	= $query->row();
			
			return $result->value;
		}
}