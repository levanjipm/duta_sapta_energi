<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Good_receipt_model extends CI_Model {
	private $table_good_receipt = 'code_good_receipt';
		
		public $id;
		public $name;
		public $date;
		public $is_delete;
		public $is_confirm;
		public $invoice_id;
		public $received_date;
		public $created_by;
		public $confirmed_by;
		public $guid;
		
		public function __construct()
		{
			parent::__construct();
			
			$this->id				= '';
			$this->name				= '';
			$this->date				= '';
			$this->is_delete		= '0';
			$this->is_confirm		= '0';
			$this->invoice_id		= null;
			$this->received_date	= '';
			$this->created_by		= '';
			$this->confirmed_by		= null;
			$this->guid				= '';
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id				= $db_item->id;
			$this->name				= $db_item->name;
			$this->date				= $db_item->date;
			$this->is_delete		= $db_item->is_delete;
			$this->is_confirm		= $db_item->is_confirm;
			$this->invoice_id		= $db_item->invoice_id;	
			$this->received_date	= $db_item->received_date;
			$this->created_by		= $db_item->created_by;	
			$this->confirmed_by		= $db_item->confirmed_by;	
			$this->guid				= $db_item->guid;			
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id				= $this->id;
			$db_item->name				= $this->name;
			$db_item->date				= $this->date;
			$db_item->is_delete			= $this->is_delete;
			$db_item->is_confirm		= $this->is_confirm;
			$db_item->invoice_id		= $this->invoice_id;	
			$db_item->received_date		= $this->received_date;
			$db_item->created_by		= $this->created_by;	
			$db_item->confirmed_by		= $this->confirmed_by;	
			$db_item->guid				= $this->guid;			
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Good_receipt_model();
			
			$stub->id				= $db_item->id;
			$stub->name				= $db_item->name;
			$stub->date				= $db_item->date;
			$stub->is_delete		= $db_item->is_delete;
			$stub->is_confirm		= $db_item->is_confirm;
			$stub->invoice_id		= $db_item->invoice_id;	
			$stub->received_date	= $db_item->received_date;
			$stub->created_by		= $db_item->created_by;	
			$stub->confirmed_by		= $db_item->confirmed_by;	
			$stub->guid				= $db_item->guid;		
			
			return $stub;
		}
		
		public function map_list($good_receipts)
		{
			$result = array();
			foreach ($good_receipts as $good_receipt)
			{
				$result[] = $this->get_new_stub_from_db($good_receipt);
			}
			return $result;
		}
		
		public function show_unconfirmed_good_receipt()
		{
			$this->db->where('is_confirm =', 0);
			$this->db->where('is_delete =', 0);
			$query 		= $this->db->get($this->table_good_receipt);
			$items	 	= $query->result();
			
			$result 	= $this->map_list($items);
			
			return $result;
		}
		
		public function input_from_post()
		{
			$guid			= $this->input->post('guid');
			
			$this->load->model('Good_receipt_model');
			$result = $this->Good_receipt_model->check_guid($guid);
			
			if($result == TRUE){
				$this->name				= $this->input->post('document');
				$this->date				= $this->input->post('submit_date');
				$this->guid				= $guid;
				$this->created_by		= $this->session->userdata('user_id');
				$this->received_date	= date('Y-m-d');
				$db_item 				= $this->get_db_from_stub($this);
				$db_result 				= $this->db->insert($this->table_good_receipt, $db_item);
				$insert_id				= $this->db->insert_id();
				
				return $insert_id;
			}
			
		}
		
		public function check_guid($guid)
		{
			$this->db->where('guid =',$guid);
			$query = $this->db->get($this->table_good_receipt);
			$item = $query-> num_rows();
			
			if($item == 0){
				return TRUE;
			} else {
				return FALSE;
			}
		}
		
		public function create_guid()
		{	
			return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
		}
}