<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promotion_model extends CI_Model {
	private $table_promotion = 'promotion';
		
		public $id;
		public $title;
		public $description;
		public $note;
		public $start_date;
		public $end_date;
		public $created_by;
		public $confirmed_by;
		public $is_confirm;
		public $is_delete;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->title				= $db_item->title;
			$this->description			= $db_item->description;
			$this->note					= $db_item->note;
			$this->start_date			= $db_item->start_date;
			$this->end_date				= $db_item->end_date;
			$this->created_by			= $db_item->created_by;
			$this->confirmed_by			= $db_item->confirmed_by;
			$this->is_confirm			= $db_item->is_confirm;
			$this->is_delete			= $db_item->is_delete;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->title					= $this->title;
			$db_item->description			= $this->description;
			$db_item->note					= $this->note;
			$db_item->start_date			= $this->start_date;
			$db_item->end_date				= $this->end_date;
			$db_item->created_by			= $this->created_by;
			$db_item->confirmed_by			= $this->confirmed_by;
			$db_item->is_confirm			= $this->is_confirm;
			$db_item->is_delete				= $this->is_delete;

			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new User_model();
			
			$stub->id					= $db_item->id;
			$stub->title				= $db_item->title;
			$stub->description			= $db_item->description;
			$stub->note					= $db_item->note;
			$stub->start_date			= $db_item->start_date;
			$stub->end_date				= $db_item->end_date;
			$stub->created_by			= $db_item->created_by;
			$stub->confirmed_by			= $db_item->confirmed_by;
			$stub->is_confirm			= $db_item->is_confirm;
			$stub->is_delete			= $db_item->is_delete;
			
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

		public function insertItem()
		{
			$dateStart			= $this->input->post('startDate');
			$dateEnd			= $this->input->post('endDate');
			$title				= $this->input->post('title');
			$description		= $this->input->post('description');
			$note				= $this->input->post('note');
			
			$db_item			= array(
				"start_date" => $dateStart,
				"end_date" => $dateEnd,
				"title" => $this->db->escape_str($title),
				"description" => $this->db->escape_str($description),
				"note" => $this->db->escape_str($note),
				"created_by" => $this->session->userdata('user_id'),
				"confirmed_by" => NULL,
				"is_confirm" => 0,
				"is_delete" => 0
			);

			$this->db->insert($this->table_promotion, $db_item);
			return $this->db->affected_rows();
		}

		public function getUnconfirmedItems($offset = 0, $limit = 10)
		{
			$this->db->where('is_confirm', 0);
			$this->db->where('is_delete', 0);
			$this->db->order_by('start_date', "ASC");
			$this->db->order_by('title', 'ASC');
			$this->db->limit($limit, $offset);
			$query			= $this->db->get($this->table_promotion);
			$result			= $query->result();
			return $result;
		}

		public function countUnconfirmedItems()
		{
			$this->db->where('is_confirm', 0);
			$this->db->where('is_delete', 0);
			$query		= $this->db->get($this->table_promotion);
			$result		= $query->num_rows();
			return $result;
		}

		public function updateById($id, $status)
		{
			if($status == 1){
				$this->db->set('is_confirm', 1);
				$this->db->set('confirmed_by', $this->session->userdata('user_id'));
				$this->db->where('is_delete', 0);
			} else if($status == 2){
				$this->db->set('is_delete', 1);
				$this->db->set('confirmed_by', $this->session->userdata('user_id'));
				$this->db->where('is_confirm', 0);
			}

			$this->db->where('id', $id);
			$this->db->update($this->table_promotion);
			return $this->db->affected_rows();
		}

		public function getItems($offset = 0, $month, $year, $limit = 10)
		{
			$query		= $this->db->query("
				SELECT promotion.*, createdTable.name AS created_by, confirmedTable.name AS confirmed_by
				FROM promotion
				JOIN (
					SELECT users.name, users.id
					FROM users
				) AS createdTable
				ON promotion.created_by = createdTable.id
				LEFT JOIN (
					SELECT users.name, users.id
					FROM users
				) AS confirmedTable
				ON promotion.confirmed_by = confirmedTable.id
				WHERE MONTH(promotion.start_date) = '$month' AND YEAR(promotion.end_date) = '$year' AND promotion.is_delete = '0'
				ORDER BY promotion.start_date ASC, promotion.title ASC
				LIMIT $limit OFFSET $offset
			");

			$result		= $query->result();
			return $result;
		}

		public function countItems($month, $year)
		{
			$this->db->where('MONTH(start_date)', $month);
			$this->db->where('YEAR(start_date)', $year);
			$query		= $this->db->get($this->table_promotion);
			$result		= $query->num_rows();
			return $result;
		}
	}
?>