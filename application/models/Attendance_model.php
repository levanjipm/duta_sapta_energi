<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_model extends CI_Model {
	private $table_attendance = 'Attendance_list';
		
		public $id;
		public $user_id;
		public $date;
		public $time;
		public $status;

		public function __construct()
		{
			parent::__construct();
		}
		
		public function get_stub_from_db($db_item)
		{
			$this->id					= $db_item->id;
			$this->user_id				= $db_item->user_id;
			$this->date					= $db_item->date;
			$this->time					= $db_item->time;
			$this->status				= $db_item->status;
			
			return $this;
		}
		
		public function get_db_from_stub()
		{
			$db_item = new class{};
			
			$db_item->id					= $this->id;
			$db_item->user_id				= $this->user_id;
			$db_item->date					= $this->date;
			$db_item->time					= $this->time;
			$db_item->status				= $this->status;
			
			return $db_item;
		}
		
		public function get_new_stub_from_db($db_item)
		{
			$stub = new Area_model();
			
			$stub->id					= $db_item->id;
			$stub->user_id				= $db_item->user_id;
			$stub->date					= $db_item->date;
			$stub->time					= $db_item->time;
			$stub->status				= $db_item->status;
			
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

		public function getUnattendedList($date)
		{
			$this->db->select('users.*');
			$this->db->from('users');
			$this->db->where('is_active', 1);

			$query = $this->db->get();
			$users = $query->result();

			$result = array();
			foreach($users as $user){
				$userArray = (array) $user;

				$resultArray = array();
				$resultArray['user'] = $userArray;

				$this->db->where('date', $date);
				$this->db->where('user_id', $user->id);
				$query = $this->db->get($this->table_attendance);
				$attendance = (array) $query->row();

				$resultArray['attendance'] = $attendance;
				array_push($result, $resultArray);
			}

			return $result;
		}
}