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
		
		public function getItems($offset = 0, $term = '', $limit = 10)
		{
			if($term != ''){
				$this->db->like('name', $term, 'both');
				$this->db->or_like('number', $term, 'both');
			}
			$this->db->limit($limit, $offset);
			$this->db->order_by('name', 'asc');
			$query 		= $this->db->get($this->table_account);
			$accounts 	= $query->result();
			
			return $accounts;	
		}

		public function countItems($term)
		{
			if($term != ''){
				$this->db->like('name', $term, 'both');
				$this->db->or_like('number', $term, 'both');
			}
			$query = $this->db->get($this->table_account);
			$result = $query->num_rows();
			return $result;
		}
		
		public function insertItem()
		{
			$this->db->db_debug = false;
			$name		= $this->input->post('name');
			$number		= $this->input->post('number');
			$bank		= $this->input->post('bank');
			$branch		= $this->input->post('branch');
			
			$db_item	= array(
				'id' => "",
				'name'=>$name,
				'number'=>$number,
				'bank'=>$bank,
				'branch'=>$branch
			);
			
			$this->db->insert($this->table_account, $db_item);
			return $this->db->affected_rows();
		}

		public function updateById()
		{
			$this->db->db_debug = false;
			$this->db->set('name', $this->input->post('name'));
			$this->db->set('number', $this->input->post('number'));
			$this->db->set('bank', $this->input->post('bank'));
			$this->db->set('branch', $this->input->post('branch'));
			$this->db->where("id", $this->input->post('id'));

			$this->db->update($this->table_account);
			return $this->db->affected_rows();
		}

		public function getById($id)
		{
			$this->db->where("id", $id);
			$query = $this->db->get($this->table_account);
			$result = $query->row();
			return $result;
		}
		
		public function delete_by_id($id)
		{
			$this->db->db_debug = FALSE;
			$this->db->where('id', $id);
			$this->db->delete($this->table_account);
			
			return $this->db->affected_rows();
		}
}