<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense_model extends CI_Model {
	public function getByMonthYear($month, $year)
	{
		if($month != 0){
			$query			= $this->db->query("
				SELECT SUM(expenseTable.value) AS value, expense_class.id, expense_class.name, expense_class.type
				FROM (
					SELECT bank_assignment.expense_id, bank_transaction.value, bank_transaction.date
					FROM bank_transaction
					JOIN bank_assignment ON bank_transaction.id = bank_assignment.bank_id
					WHERE YEAR(bank_transaction.date) = '$year' AND MONTH(date) = '$month'
					UNION (
						SELECT petty_cash.expense_class AS expense_id, petty_cash.value, petty_cash.date
						FROM petty_cash
						WHERE YEAR(petty_cash.date) = '$year' AND MONTH(petty_cash.date) = '$month'
					)
				) expenseTable
				JOIN expense_class ON expenseTable.expense_id = expense_class.id
				GROUP BY expenseTable.expense_id
			");
		} else {
			$query			= $this->db->query("
				SELECT SUM(expenseTable.value) AS value, expense_class.id, expense_class.name, expense_class.type
				FROM (
					SELECT bank_assignment.expense_id, bank_transaction.value, bank_transaction.date
					FROM bank_transaction
					JOIN bank_assignment ON bank_transaction.id = bank_assignment.bank_id
					WHERE YEAR(bank_transaction.date) = '$year'
					UNION (
						SELECT petty_cash.expense_class AS expense_id, petty_cash.value, petty_cash.date
						FROM petty_cash
						WHERE YEAR(petty_cash.date) = '$year'
					)
				) expenseTable
				JOIN expense_class ON expenseTable.expense_id = expense_class.id
				GROUP BY expenseTable.expense_id
			");
		}

		$result		= $query->result();
		return $result;
	}
}
