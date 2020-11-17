<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Income_model extends CI_Model {
	public function getByMonthYear($month, $year)
	{
		if($month != 0){
			$query			= $this->db->query("
				SELECT bank_assignment.income_id, SUM(bank_transaction.value) AS value, bank_transaction.date
				FROM bank_transaction
				JOIN bank_assignment ON bank_transaction.id = bank_assignment.bank_id
				JOIN income_class ON bank_assignment.income_id = income_class.id 
				WHERE YEAR(bank_transaction.date) = '$year' AND MONTH(date) = '$month'
				GROUP BY bank_assignment.income_id
			");
		} else {
			$query			= $this->db->query("
			SELECT bank_assignment.income_id, SUM(bank_transaction.value) AS value, bank_transaction.date
			FROM bank_transaction
			JOIN bank_assignment ON bank_transaction.id = bank_assignment.bank_id
			JOIN income_class ON bank_assignment.income_id = income_class.id 
			WHERE YEAR(bank_transaction.date) = '$year'
			GROUP BY bank_assignment.income_id
			");
		}

		$result		= $query->result();
		return $result;
	}
}
