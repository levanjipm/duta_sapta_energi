<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plafond_model extends CI_Model {
	private $table_plafond = 'plafond_submission';
		
		public $id;
		public $customer_id;
		public $submitted_plafond;
		public $submitted_by;
		public $submitted_date;
		public $is_confirm;
		public $is_delete;
		public $confirmed_by;
		public $confirmed_date;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->customer_id			= $db_item->customer_id;
			$this->submitted_plafond	= $db_item->submitted_plafond;
			$this->submitted_by			= $db_item->submitted_by;
			$this->submitted_date		= $db_item->submitted_date;
			$this->is_confirm			= $db_item->is_confirm;
			$this->is_delete			= $db_item->is_delete;
			$this->confirmed_by			= $db_item->confirmed_by;
			$this->confirmed_date		= $db_item->confirmed_date;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->customer_id			= $this->customer_id;
			$db_item->submitted_plafond		= $this->submitted_plafond;
			$db_item->submitted_by			= $this->submitted_by;
			$db_item->submitted_date		= $this->submitted_date;
			$db_item->is_confirm			= $this->is_confirm;
			$db_item->is_delete				= $this->is_delete;
			$db_item->confirmed_by			= $this->confirmed_by;
			$db_item->confirmed_date		= $this->confirmed_date;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Area_model();
			
			$stub->id					= $db_item->id;
			$stub->customer_id			= $db_item->customer_id;
			$stub->submitted_plafond	= $db_item->submitted_plafond;
			$stub->submitted_by			= $db_item->submitted_by;
			$stub->submitted_date		= $db_item->submitted_date;
			$stub->is_confirm			= $db_item->is_confirm;
			$stub->is_delete			= $db_item->is_delete;
			$stub->confirmed_by			= $db_item->confirmed_by;
			$stub->confirmed_date		= $db_item->confirmed_date;
			
			return $stub;
		}
		
		public function map_list($customers)
		{
			$result = array();
			foreach ($customers as $customer)
			{
				$result[] = $this->get_new_stub_from_db($customer);
			}
			return $result;
		}
		
		public function check_unconfirmed_submission($customer_id)
		{
			$this->db->where('is_confirm', 0);
			$this->db->where('is_delete', 0);
			$this->db->where('customer_id', $customer_id);
			$query		= $this->db->get($this->table_plafond);
			$item		= $query->num_rows();
			
			if($item	> 0){
				$result	= FALSE;
			} else {
				$result	= TRUE;
			}
			
			return $result;
		}
		
		public function insert_from_post()
		{
			$customer_id		= $this->input->post('id');
			$plafond			= $this->input->post('plafond');
			$result				= $this->Plafond_model->check_unconfirmed_submission($customer_id);
			
			if($result){
				$db_item		= array(
					'id' => '',
					'customer_id' => $customer_id,
					'submitted_plafond' => $plafond,
					'submitted_by' => $this->session->userdata('user_id'),
					'submitted_date' => date('Y-m-d'),
					'is_confirm' => 0,
					'is_delete' => 0,
					'confirmed_by' => null,
					'confirmed_date' => null
				);
				
				$this->db->insert($this->table_plafond, $db_item);
				if($this->db->affected_rows() > 0){
					return ($this->db->insert_id());
				} else {
					return NULL;
				};
			} else {
				return NULL;
			}
		}
		
		public function show_by_id($id)
		{
			$this->db->select('plafond_submission.*, users.name as created_by');
			$this->db->from('plafond_submission');
			$this->db->join('users', 'plafond_submission.submitted_by = users.id');
			$this->db->where('plafond_submission.id', $id);
			
			$query		= $this->db->get();
			$result		= $query->row();
			return $result;
		}
		
		public function update_plafond($id, $is_confirm, $is_delete)
		{
			$this->db->set('is_confirm', $is_confirm);
			$this->db->set('is_delete', $is_delete);
			$this->db->set('confirmed_by', $this->session->userdata('user_id'));
			$this->db->where('id', $id);
			
			$this->db->set('confirmed_date', date('Y-m-d'));
			
			$this->db->update($this->table_plafond);
			
			return ($this->db->affected_rows());
		}
}