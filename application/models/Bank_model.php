<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_model extends CI_Model {
	private $table_bank = 'bank_transaction';
		
		public $id;
		public $date;
		public $value;
		public $transaction;
		public $customer_id;
		public $supplier_id;
		public $other_id;
		public $is_done;
		public $is_delete;
		public $bank_transaction_major;

		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id						= $db_item->id;
			$this->date						= $db_item->date;
			$this->value					= $db_item->value;
			$this->transaction				= $db_item->transaction;
			$this->customer_id				= $db_item->customer_id;
			$this->supplier_id				= $db_item->supplier_id;
			$this->other_id					= $db_item->other_id;
			$this->is_done					= $db_item->is_done;
			$this->is_delete				= $db_item->is_delete;
			$this->bank_transaction_major	= $db_item->bank_transaction_major;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id						= $this->id;
			$db_item->date						= $this->date;
			$db_item->value						= $this->value;
			$db_item->transaction				= $this->transaction;
			$db_item->customer_id				= $this->customer_id;
			$db_item->supplier_id				= $this->supplier_id;
			$db_item->other_id					= $this->other_id;
			$db_item->is_done					= $this->is_done;
			$db_item->is_delete					= $this->is_delete;
			$db_item->bank_transaction_major	= $this->bank_transaction_major;
			
			return $db_item;
		}
		
		public function update_db_from_stub()
		{
			$db_item = new class{};

			$db_item->id						= $this->id;
			$db_item->date						= $this->date;
			$db_item->value						= $this->value;
			$db_item->transaction				= $this->transaction;
			$db_item->customer_id				= $this->customer_id;
			$db_item->supplier_id				= $this->supplier_id;
			$db_item->other_id					= $this->other_id;
			$db_item->is_done					= $this->is_done;
			$db_item->is_delete					= $this->is_delete;
			$db_item->bank_transaction_major	= $this->bank_transaction_major;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Item_model();
			
			$stub->id						= $db_item->id;
			$stub->date						= $db_item->date;
			$stub->value					= $db_item->value;
			$stub->transaction				= $db_item->transaction;
			$stub->customer_id				= $db_item->customer_id;
			$stub->supplier_id				= $db_item->supplier_id;
			$stub->other_id					= $db_item->other_id;
			$stub->is_done					= $db_item->is_done;
			$stub->is_delete				= $db_item->is_delete;
			$stub->bank_transaction_major	= $db_item->bank_transaction_major;
			
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
		
		public function input($date, $value, $transaction, $type, $opponent_id, $account)
		{				
			switch($type){
				case 'customer':
					$db_item	= array(
						'date'=>$date,
						'value' => $value,
						'transaction' => $transaction,
						'customer_id' => $opponent_id,
						'supplier_id' => NULL,
						'other_id' => NULL,
						'account_id' => $account
					);
				break;
				case 'supplier':
					$db_item	= array(
						'date'=>$date,
						'value' => $value,
						'transaction' => $transaction,
						'supplier_id' => $opponent_id,
						'customer_id' => NULL,
						'other_id' => NULL,
						'account_id' => $account
					);
				break;
				case 'other':
					$db_item	= array(
						'date'=>$date,
						'value' => $value,
						'transaction' => $transaction,
						'supplier_id' => NULL,
						'customer_id' => NULL,
						'other_id' => $opponent_id,
						'account_id' => $account
					);
				break;
			}

			$this->db->insert($this->table_bank, $db_item);
			
			return $this->db->insert_id();
		}
		
		public function view_unassigned_data($department, $account, $type, $offset = 0, $limit = 25)
		{
			if($department == 1){
				$this->db->select('bank_transaction.*, COALESCE(`customer`.`name`, `supplier`.`name`) as name');
			} else {
				$this->db->select('bank_transaction.*, COALESCE(`customer`.`name`, `supplier`.`name`, `other_bank_account`.`name`) as name');
			}
			
			$this->db->join('customer', 'bank_transaction.customer_id = customer.id', 'left');
			$this->db->join('supplier', 'bank_transaction.supplier_id = supplier.id', 'left');
			
			if($department != 1){
				$this->db->join('other_bank_account', 'bank_transaction.other_id = other_bank_account.id', 'left');
			} else {
				$this->db->where('bank_transaction.other_id', null);
			}
			
			$this->db->where('bank_transaction.account_id', $account);
			$this->db->where('bank_transaction.transaction', $type);
			$this->db->where('bank_transaction.is_done', 0);
			$this->db->where('bank_transaction.is_delete', 0);
			$this->db->order_by('bank_transaction.date');
			$query	= $this->db->get($this->table_bank, $limit, $offset);
			$result	= $query->result();
			
			return $result;
		}
		
		public function count_unassigned_data($account, $type)
		{
			$this->db->where('account_id', $account);
			$this->db->where('transaction', $type);
			$this->db->where('is_done', 0);
			$this->db->where('is_delete', 0);
			$this->db->order_by('date');
			$query	= $this->db->get($this->table_bank);
			$result	= $query->num_rows();
			
			return $result;
		}
		
		public function show_by_id($id)
		{
			$this->db->select('bank_transaction.*, COALESCE(`customer`.`name`, supplier.name, `other_bank_account`.`name`) as name');
			$this->db->from('bank_transaction');
			$this->db->join('customer', 'bank_transaction.customer_id = customer.id', 'left');
			$this->db->join('supplier', 'bank_transaction.supplier_id = supplier.id', 'left');
			$this->db->join('other_bank_account', 'bank_transaction.other_id = other_bank_account.id', 'left');
			$this->db->where('bank_transaction.id', $id);
			$query = $this->db->get();
			$result	= $query->row();
			
			return $result;
		}
		
		public function assign_receivable($bank_data)
		{
			$bank_value		= $bank_data->value;
			$bank_id		= $bank_data->id;
			$customer_id	= $bank_data->customer_id;
			$date			= $bank_data->date;
			$account_id		= $bank_data->account_id;
			$transaction	= $bank_data->transaction;
			
			$check_box_array		= $this->input->post('check_box');
			$remaining_array		= $this->input->post('remaining');
			$original_array			= $this->input->post('original');
			
			$total_remaining		= array_sum($remaining_array);
			$total_invoice			= array_sum($original_array);
			$assigned				= $total_invoice - $total_remaining;
			if($assigned < $bank_value){
				$not_assigned 	= $bank_value - $assigned;
				$this->db->set('is_delete', 1);
				$this->db->where('id', $bank_id);
				$result = $this->db->update($this->table_bank);
				if($result){
					$db_item	= array(
						'id' => '',
						'date' => $date,
						'customer_id' => $customer_id,
						'transaction' => $transaction,
						'bank_transaction_major' => $bank_id,
						'value' => $not_assigned,
						'is_done' => 0,
						'account_id' => $account_id
					);
					
					$this->db->insert($this->table_bank, $db_item);
					
					$db_item	= array(
						'id' => '',
						'date' => $date,
						'customer_id' => $customer_id,
						'transaction' => $transaction,
						'bank_transaction_major' => $bank_id,
						'value' => $assigned,
						'is_done' => 1,
						'account_id' => $account_id
					);
					
					$this->db->insert($this->table_bank, $db_item);
					$insert_id	= $this->db->insert_id();
					
					foreach($check_box_array as $check_box){
						$invoice_id			= key($check_box_array);
						$original_value		= $original_array[$invoice_id];
						$remaining_value	= $remaining_array[$invoice_id];
						$difference			= $original_value - $remaining_value;
						if($difference > 0){
							$db_item	= array(
								'bank_id' => $insert_id,
								'value' => $difference,
								'date' => $date,
								'invoice_id' => $invoice_id
							);
							
							$this->db->insert('receivable', $db_item);
						
							if($remaining_value == 0){
								$this->db->set('is_done', 1);
								$this->db->where('id', $invoice_id);
								$this->db->update('invoice');
							};
						};
					};
				};
			} else if($assigned == $bank_value){
				foreach($check_box_array as $check_box){
					$invoice_id			= key($check_box_array);
					$original_value		= $original_array[$invoice_id];
					$remaining_value	= $remaining_array[$invoice_id];
					$difference			= $original_value - $remaining_value;
					if($difference > 0){
						$db_item	= array(
							'bank_id' => $bank_id,
							'value' => $difference,
							'date' => $date,
							'invoice_id' => $invoice_id
						);
						
						$this->db->insert('receivable', $db_item);
					
						if($remaining_value == 0){
							$this->db->set('is_done', 1);
							$this->db->where('id', $invoice_id);
							$this->db->update('invoice');
						};
					};
				};
				
				$this->db->set('is_done', 1);
				$this->db->where('id', $bank_id);
				$this->db->update($this->table_bank);
			}
		}
		
		public function assign_payable($bank_data)
		{
			$bank_value		= $bank_data->value;
			$bank_id		= $bank_data->id;
			$supplier_id	= $bank_data->supplier_id;
			$date			= $bank_data->date;
			$account_id		= $bank_data->account_id;
			$transaction	= $bank_data->transaction;
			
			$check_box_array		= $this->input->post('check_box');
			$remaining_array		= $this->input->post('remaining');
			$original_array			= $this->input->post('original');
			
			$total_remaining		= array_sum($remaining_array);
			$total_invoice			= array_sum($original_array);
			$assigned				= $total_invoice - $total_remaining;
			
			
			if($assigned < $bank_value){
				$not_assigned 	= $bank_value - $assigned;
				$this->db->set('is_delete', 1);
				$this->db->where('id', $bank_id);
				$result = $this->db->update($this->table_bank);
				if($result){
					$db_item	= array(
						'id' => '',
						'date' => $date,
						'supplier_id' => $supplier_id,
						'transaction' => $transaction,
						'bank_transaction_major' => $bank_id,
						'value' => $not_assigned,
						'is_done' => 0,
						'account_id' => $account_id
					);
					
					$this->db->insert($this->table_bank, $db_item);
					
					$db_item	= array(
						'id' => '',
						'date' => $date,
						'customer_id' => $customer_id,
						'transaction' => $transaction,
						'bank_transaction_major' => $bank_id,
						'value' => $assigned,
						'is_done' => 1,
						'account_id' => $account_id
					);
					
					$this->db->insert($this->table_bank, $db_item);
					$insert_id	= $this->db->insert_id();
					
					foreach($check_box_array as $check_box){
						$purchase_id		= key($check_box_array);
						$original_value		= $original_array[$purchase_id];
						$remaining_value	= $remaining_array[$purchase_id];
						$difference			= $original_value - $remaining_value;
						
						if($difference > 0){
							$db_item	= array(
								'bank_id' => $insert_id,
								'value' => $difference,
								'date' => $date,
								'purchase_id' => $purchase_id
							);
							
							$this->db->insert('payable', $db_item);
						
							if($remaining_value == 0){
								$this->db->set('is_done', 1);
								$this->db->where('id', $purchase_id);
								$this->db->update('purchase_invoice');
							};
						};
					};
				};
			} else if($assigned == $bank_value){
				foreach($check_box_array as $check_box){
					$purchase_id		= key($check_box_array);
					$original_value		= $original_array[$purchase_id];
					$remaining_value	= $remaining_array[$purchase_id];
					$difference			= $original_value - $remaining_value;
					if($difference > 0){
						$db_item	= array(
							'bank_id' => $bank_id,
							'value' => $difference,
							'date' => $date,
							'purchase_id' => $purchase_id
						);
						
						$this->db->insert('payable', $db_item);
					
						if($remaining_value == 0){
							$this->db->set('is_done', 1);
							$this->db->where('id', $purchase_id);
							$this->db->update('purchase_invoice');
						};
					};
				};
				
				$this->db->set('is_done', 1);
				$this->db->where('id', $bank_id);
				$this->db->update($this->table_bank);
			}
		}
		
		public function view_mutation($account, $start_date, $end_date, $offset = 0, $limit = 25)
		{
			$this->db->select('bank_transaction.*, COALESCE(`customer`.`name`, supplier.name, `other_bank_account`.`name`) as name');
			$this->db->from('bank_transaction');
			$this->db->join('customer', 'bank_transaction.customer_id = customer.id', 'left');
			$this->db->join('supplier', 'bank_transaction.supplier_id = supplier.id', 'left');
			$this->db->join('other_bank_account', 'bank_transaction.other_id = other_bank_account.id', 'left');
			$this->db->where('bank_transaction.account_id', $account);
			$this->db->where('bank_transaction.date >=', $start_date);
			$this->db->where('bank_transaction.date <=', $end_date);
			$this->db->where('bank_transaction_major', null);
			$this->db->limit($limit, $offset);
			$query		= $this->db->get();
			$result		= $query->result();
			
			return $result;
		}
		
		public function count_mutation($account, $start_date, $end_date, $offset = 0, $limit = 25)
		{
			$this->db->select('bank_transaction.id');
			$this->db->where('account_id', $account);
			$this->db->where('date >=', $start_date);
			$this->db->where('date <=', $end_date);
			$this->db->where('bank_transaction_major', null);
			$this->db->limit($limit, $offset);
			$query		= $this->db->get($this->table_bank);
			$result		= $query->num_rows();
			
			return $result;
		}
		
		public function calculate_balance($account, $date)
		{
			$this->db->select_sum('value');
			$this->db->select('transaction');
			$this->db->where('account_id', $account);
			$this->db->where('date <', $date);
			$this->db->group_by('transaction');
			$query	= $this->db->get($this->table_bank);
			$result	= $query->result();
			
			return $result;
		}
		
		public function getPendingValueByOpponentId($type, $id)
		{
			if($type == 'customer'){
				$this->db->select('coalesce(sum(value), 0) as value');
				$this->db->where('customer_id', $id);
				$this->db->where('is_done', 0);
				$this->db->where('is_delete', 0);
				$this->db->where('transaction', 1);
			}
			
			$query		= $this->db->get($this->table_bank);
			$result		= $query->row();
			
			return $result->value;
		}
}