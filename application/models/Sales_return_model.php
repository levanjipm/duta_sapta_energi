<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_return_model extends CI_Model {
	private $table_sales_return = 'code_sales_return';
		
		public $id;
		public $created_by;
		public $created_date;
		public $is_confirm;
		public $is_delete;
		public $confirmed_by;

		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->created_by			= $db_item->created_by;
			$this->created_date			= $db_item->created_date;
			$this->is_confirm			= $db_item->is_confirm;
			$this->confirmed_by			= $db_item->confirmed_by;
			$this->is_delete			= $db_item->is_delete;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->created_by			= $this->created_by;
			$db_item->created_date			= $this->created_date;
			$db_item->is_confirm			= $this->is_confirm;
			$db_item->is_delete				= $this->is_delete;
			$db_item->confirmed_by			= $this->confirmed_by;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Customer_model();
			
			$stub->id					= $db_item->id;
			$stub->created_by			= $db_item->created_by;
			$stub->created_date			= $db_item->created_date;
			$stub->is_confirm			= $db_item->is_confirm;
			$stub->is_delete			= $db_item->is_delete;
			$stub->confirmed_by			= $db_item->confirmed_by;
			
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
		
		public function insert_from_post()
		{
			$check = true;
			$quantity_array = $this->input->post('return_quantity');
			$this->load->model('Sales_return_detail_model');
			foreach($quantity_array as $return)
			{
				$delivery_order_id = key($quantity_array);
				$quantity_return	= $quantity_array[$delivery_order_id];
				
				//Check for previous return//
				$previous		= $this->Sales_return_detail_model->get_sum_quantity_by_delivery_order_id($delivery_order_id);
				$returned		= $previous->returned;
				$quantity		= $previous->quantity;
				
				if($quantity_return + $returned > $quantity)
				{
					$check = false;
					break;
				}
			}
			
			if($check)
			{
				$this->id			= "";			
				$this->created_by	= $this->session->userdata('user_id');	
				$this->created_date	= date('Y-m-d');
				$this->is_confirm	= 0;
				$this->confirmed_by = null;

				$db_item 		= $this->get_db_from_stub($this);
				$db_result 		= $this->db->insert($this->table_sales_return, $db_item);
				
				$insert_id		= $this->db->insert_id();
				
				if($insert_id != null)
				{
					$quantity_array = $this->input->post('return_quantity');
					foreach($quantity_array as $return)
					{
						$delivery_order_id 	= key($quantity_array);
						$return_quantity	= $quantity_array[$delivery_order_id];
						$this->Sales_return_detail_model->insert_return_data($delivery_order_id, $return_quantity, $insert_id);
					}
				}
			}
		}

		public function getUnconfirmedDocuments($offset, $limit = 10)
		{
			$this->db->where('is_confirm', 0);
			$this->db->where('is_delete', 0);
			$this->db->limit($limit, $offset);
			$query = $this->db->get($this->table_sales_return);
			$result = $query->result();

			return $result;
		}

		public function countUnconfirmedDocuments()
		{
			$this->db->where('is_confirm', 0);
			$this->db->where('is_delete', 0);
			$query = $this->db->get($this->table_sales_return);
			$result = $query->num_rows();

			return $result;
		}  
}