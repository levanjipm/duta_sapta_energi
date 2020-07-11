<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Internal_bank_account_model extends CI_Model {
	private $table_account = 'internal_bank_account';
		
		public $id;
		public $name;
		public $number;
		public $bank;
		public $branch;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function show_all($offset = 0, $term = '', $limit = 25)
		{
			if($term != ''){
				$this->db->like('name', $term, 'both');
				$this->db->or_like('number', $term, 'both');
				$this->db->limit($limit, $offset);
			}
			$this->db->order_by('name', 'asc');
			$query 		= $this->db->get($this->table_account);
			$accounts 	= $query->result();
			
			return $accounts;	
		}
		
		public function create()
		{
			$name		= $this->input->post('name');
			$number		= $this->input->post('number');
			$bank		= $this->input->post('bank');
			$branch		= $this->input->post('branch');
			
			$db_item	= array(
				'name'=>$name,
				'number'=>$number,
				'bank'=>$bank,
				'branch'=>$branch
			);
			
			$this->db->insert($this->table_account, $db_item);
		}
		
		public function delete_by_id($id)
		{
			$this->db->db_debug = FALSE;
			$this->db->where('id', $id);
			$this->db->delete($this->table_account);
			
			return $this->db->affected_rows();
		}
}