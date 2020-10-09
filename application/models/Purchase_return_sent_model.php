<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_return_sent_model extends CI_Model {
	private $table_purchase_return = 'code_purchase_return_sent';
		
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
			$db_result 				= $this->db->insert($this->table_purchase_return, $db_item);
			
			$insert_id				= $this->db->insert_id();
			return $insert_id;
		}

		public function getUnconfirmedItems($offset = 0, $term = "", $limit = 10)
		{
			$query		= $this->db->query("
				SELECT code_purchase_return_sent.*, a.supplier_id
				FROM code_purchase_return_sent
				JOIN (
					SELECT DISTINCT (purchase_return_sent.code_purchase_return_sent_id) as id, code_purchase_return.supplier_id
					FROM purchase_return_sent
					JOIN purchase_return ON purchase_return_sent.purchase_return_id = purchase_return.id
					JOIN code_purchase_return ON purchase_return.code_purchase_return_id = code_purchase_return.id
				) AS a
				ON a.id = code_purchase_return_sent.id
				JOIN supplier ON a.supplier_id = supplier.id
				WHERE code_purchase_return_sent.is_confirm = '0' AND code_purchase_return_sent.is_delete = '0' AND (supplier.name LIKE '%$term%' OR supplier.address LIKE '%$term%' OR code_purchase_return_sent.name LIKE '%$term%')
				LIMIT $limit OFFSET $offset
			");

			$result		= $query->result();
			return $result;
		}

		public function countUnconfirmedItems($term = "")
		{
			$query		= $this->db->query("
				SELECT code_purchase_return_sent.*, a.supplier_id
				FROM code_purchase_return_sent
				JOIN (
					SELECT DISTINCT (purchase_return_sent.code_purchase_return_sent_id) as id, code_purchase_return.supplier_id
					FROM purchase_return_sent
					JOIN purchase_return ON purchase_return_sent.purchase_return_id = purchase_return.id
					JOIN code_purchase_return ON purchase_return.code_purchase_return_id = code_purchase_return.id
				) AS a
				ON a.id = code_purchase_return_sent.id
				JOIN supplier ON a.supplier_id = supplier.id
				WHERE code_purchase_return_sent.is_confirm = '0' AND code_purchase_return_sent.is_delete = '0' AND (supplier.name LIKE '%$term%' OR supplier.address LIKE '%$term%' OR code_purchase_return_sent.name LIKE '%$term%')
			");

			$result		= $query->num_rows();
			return $result;
		}

		public function getById($id)
		{
			$query		= $this->db->query("
				SELECT code_purchase_return_sent.*, a.supplier_id, b.value
				FROM code_purchase_return_sent
				JOIN (
					SELECT DISTINCT (purchase_return_sent.code_purchase_return_sent_id) as id, code_purchase_return.supplier_id
					FROM purchase_return_sent
					JOIN purchase_return ON purchase_return_sent.purchase_return_id = purchase_return.id
					JOIN code_purchase_return ON purchase_return.code_purchase_return_id = code_purchase_return.id
				) AS a
				ON a.id = code_purchase_return_sent.id
				JOIN (
					SELECT SUM(purchase_return_sent.quantity * purchase_return.price) AS value, purchase_return_sent.code_purchase_return_sent_id
					FROM purchase_return_sent
					JOIN purchase_return ON purchase_return_sent.purchase_return_id = purchase_return.id
					GROUP BY purchase_return_sent.code_purchase_return_sent_id
				) AS b
				ON b.code_purchase_return_sent_id = code_purchase_return_sent.id
				JOIN supplier ON a.supplier_id = supplier.id
				WHERE code_purchase_return_sent.id = '$id'
			");

			$result			= $query->row();
			return $result;
		}

		public function updateById($status, $id)
		{
			if($status == 1){
				$this->db->set('is_confirm', 1);
				$this->db->set('confirmed_by', $this->session->userdata('user_id'));
				$this->db->where('is_delete', 0);
			} else if($status == 0){
				$this->db->set('is_delete', 1);
				$this->db->set('confirmed_by', $this->session->userdata('user_id'));
				$this->db->where('is_confirm', 0);
			}
			
			$this->db->where("id", $id);
			$this->db->update($this->table_purchase_return);
			return $this->db->affected_rows();
		}

		public function getUnassignedPurchaseReturn($offset = 0, $term = "", $limit = 10)
		{
			$query		= $this->db->query("
				SELECT code_purchase_return_sent.*, a.supplier_id
				FROM code_purchase_return_sent
				JOIN (
					SELECT DISTINCT (purchase_return_sent.code_purchase_return_sent_id) as id, code_purchase_return.supplier_id
					FROM purchase_return_sent
					JOIN purchase_return ON purchase_return_sent.purchase_return_id = purchase_return.id
					JOIN code_purchase_return ON purchase_return.code_purchase_return_id = code_purchase_return.id
				) AS a
				ON a.id = code_purchase_return_sent.id
				JOIN supplier ON a.supplier_id = supplier.id
				WHERE code_purchase_return_sent.is_confirm = '1' AND code_purchase_return_sent.is_done = '0' AND (supplier.name LIKE '%$term%' OR supplier.address LIKE '%$term%' OR code_purchase_return_sent.name LIKE '%$term%')
				LIMIT $limit OFFSET $offset
			");

			$result		= $query->result();
			return $result;
		}

		public function countUnassignedPurchaseReturn($offset = 0, $term = "", $limit = 10)
		{
			$query		= $this->db->query("
				SELECT code_purchase_return_sent.*, a.supplier_id
				FROM code_purchase_return_sent
				JOIN (
					SELECT DISTINCT (purchase_return_sent.code_purchase_return_sent_id) as id, code_purchase_return.supplier_id
					FROM purchase_return_sent
					JOIN purchase_return ON purchase_return_sent.purchase_return_id = purchase_return.id
					JOIN code_purchase_return ON purchase_return.code_purchase_return_id = code_purchase_return.id
				) AS a
				ON a.id = code_purchase_return_sent.id
				JOIN supplier ON a.supplier_id = supplier.id
				WHERE code_purchase_return_sent.is_confirm = '1' AND code_purchase_return_sent.is_done = '0' AND (supplier.name LIKE '%$term%' OR supplier.address LIKE '%$term%' OR code_purchase_return_sent.name LIKE '%$term%')
			");

			$result		= $query->num_rows();
			return $result;
		}

		public function updateBankById($bankId, $id)
		{
			$this->db->set('is_done', 1);
			$this->db->set('bank_id', $bankId);

			$this->db->where('id', $id);
			$this->db->where('bank_id', NULL);

			$this->db->update($this->table_purchase_return);
			return $this->db->affected_rows();
		}
	}
?>
