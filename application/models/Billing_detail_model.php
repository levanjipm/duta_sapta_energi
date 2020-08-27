<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing_model extends CI_Model {
	private $table_billing = 'billing';
		
		public $id;
		public $invoice_id;
		public $result;
		public $note;
		public $code_billing_id;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->invoice_id			= $db_item->invoice_id;
			$this->result				= $db_item->result;
			$this->note					= $db_item->note;
			$this->code_billing_id		= $db_item->code_billing_id;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->invoice_id			= $this->invoice_id;
			$db_item->result				= $this->result;
			$db_item->note					= $this->note;
			$db_item->code_billing_id		= $this->code_billing_id;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new User_model();
			
			$stub->id					= $db_item->id;
			$stub->invoice_id			= $db_item->invoice_id;
			$stub->result				= $db_item->result;
			$stub->note					= $db_item->note;
			$stub->code_billing_id		= $db_item->code_billing_id;
			
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

		public function insertItem($codeBillingId)
		{
			$invoiceArray = $_REQUEST["invoices"];
			foreach($invoiceArray as $key => $invoiceId)
			{
				
				next($invoiceArray);
			}
		}
}