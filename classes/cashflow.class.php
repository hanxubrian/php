<?php
	

	class Cashflow extends Database {

		function __construct()
		{
			// parent::__construct($host, $user, $password, $db);
			// parent::__construct('127.0.0.1','root','mysql','thesis'); // This is my database local password.
			parent::__construct('127.0.0.1','root','','thesis');
		}	

		function getCashflow()
		{
			$sql = "SELECT * FROM cashflow";

			// We are now able to access this method because we extended the "Database" class.
			$result = $this->getSQLResult($sql);

			return $result;			
		}

		//Check remaining cash
		function getProjectSumCashflow($project_id)
		{
			$sql = "SELECT SUM(amount) AS balance  FROM cashflow WHERE project_id='$project_id'";

			// We are now able to access this method because we extended the "Database" class.
			$result = $this->getSingleSQLStatement($sql);	
			
			return $result;			
		}


		function getYears($project_id)
		{
			$sql = "SELECT 
					  DATE_FORMAT(a.`transaction_date`, '%Y') AS years 
					FROM
					  cashflow a
					WHERE project_id = '$project_id'
					GROUP BY years
					";

			// We are now able to access this method because we extended the "Database" class.
			$result = $this->getSQLResult($sql);	
			
			return $result;			
		}

		function getProjectCashflow($project_id)
		{
			$sql1 = "SELECT COUNT(id) FROM projects WHERE id='$project_id';";
			$result1 = $this->getSingleSQLStatement($sql1);

			$errors = [];

			if ($project_id == '') {
				array_push($errors, 'The `project_id` field must not be empty.');
			}

			if (in_array(0, $result1)) {
				array_push($errors, 'project_id does not exist on Cashflow table');
			}
			
			if (!empty($errors)) {
				return ['errors' => $errors];
			} else {

				$sql2 = "SELECT projects.`project_name`, cashflow.`type`, 
							cashflow.`amount`, cashflow.`date_added`, cashflow.`transaction_date`, cashflow.`id`, cashflow.`description`
						FROM projects
						INNER JOIN cashflow ON cashflow.project_id = projects.id 
						WHERE projects.id = '$project_id'
				;";

				// We are now able to access this method because we extended the "Database" class.
				$result2 = $this->getSQLResult($sql2);
				if (empty($result2))  
				{  
					array_push($errors, 'There is no cashflow with the selected project');
					return ['errors' => $errors];
				}  
				else  
				{  
					return $result2;
				}  					
			}
		}

		function getSpecificCashflow($id)
		{
			$sql1 = "SELECT COUNT(id) FROM cashflow WHERE id='$id';";
			$result1 = $this->getSingleSQLStatement($sql1);
			
			$errors = [];
			
			if (in_array(0, $result1)) {
				array_push($errors, 'id does not exist on cashflow table');
			} 

			if ($id == '') {
				array_push($errors, 'The `id` field must not be empty.');
			}
			
			if (!empty($errors)) {
				return ['errors' => $errors];
			} else {
				$sql = "SELECT * FROM cashflow WHERE id = '$id'";

				// We are now able to access this method because we extended the "Database" class.
				$result = $this->getSingleSQLStatement($sql);

				return $result;	
			}		
		}		
	

		//Cash-in or Cash-out
		function TransactCash($type, $amount, $description, $date_added, $transaction_date, $project_id)
		{
			
			$amount = intval($amount);
			$errors = [];

			if (!in_array($type, ['IN', 'OUT','in','out'])) {
				array_push($errors, 'The Category must either be `IN` or `OUT`.');
			}

			if (!is_int($amount)) {
				array_push($errors, 'The `amount` must be set as an integer');
			}

			if ($transaction_date == '') {
				array_push($errors, 'The `transaction date` field must not be empty.');
			}

			if ($description == '') {
				array_push($errors, 'The `description` field must not be empty.');
			}

			if (!is_timestamp($transaction_date)) {
				array_push($errors, 'The `transactiondate` field must be in Timestamp format.');
			}

			if (!empty($errors)) {
				return ['errors' => $errors];
			} else {

				if ($type == 'in') {
					$amount = abs($amount);
				}

				elseif ($type == 'out') {
					$amount = -1 * abs($amount);
				}
				
					$sql = "
						INSERT INTO `cashflow` (`type`, `amount`, `description`, `date_added`, `transaction_date`, `project_id`) 
						VALUES
						  ('$type', '$amount', '$description', now(), '$transaction_date', '$project_id') ;
					";

					$this->executeSQL($sql);

					return 'Transaction successful.';
			}
		}
		
		function PayBill($amount, $project_id, $billofmaterials_id, $description)
		{
			
			$amount = intval($amount);
			$type = 'OUT';
			$errors = [];

			if (!is_int($amount)) {
				array_push($errors, 'The `amount` must be set as an integer');
			}

			if ($amount <= 0) {
				array_push($errors, 'The `amount` must be greater than 0');
			}

			if (!empty($errors)) {
				return ['errors' => $errors];
			} else {

				if ($type === 'IN') {
					$amount = abs($amount);
				}

				elseif ($type === 'OUT') {
					$amount = -1 * abs($amount);
				}
				
					$sql = "
						INSERT INTO `cashflow` (`type`, `amount`, `date_added`, `transaction_date`, `project_id`, `bill_of_materials_id`, `description`) 
						VALUES
						  ('$type', '$amount', now(),  now(), '$project_id', '$billofmaterials_id', '$description');
					";
					$this->executeSQL($sql);

					$sql = "
						UPDATE `bill_of_materials` SET paid = 1 WHERE id = '$billofmaterials_id';
					";
					$this->executeSQL($sql);

					return 'Payment successful.';
			}
		}
		

		function deleteTransaction($id)
		{
			$errors = [];

			$sql1 = "SELECT COUNT(id) FROM cashflow WHERE id='$id';";
			$result1 = $this->getSingleSQLStatement($sql1);

			$errors = [];

			if (in_array(0, $result1)) {
				array_push($errors, 'id does not exist on cashflow table');
			} 

			if ($id == '') {
				array_push($errors, 'The `id` field must not be empty.');
			}
			
			if (!empty($errors)) {
				return ['errors' => $errors];
			} else {
				$select = "SELECT bill_of_materials_id FROM cashflow WHERE id='$id'";
				$billofmaterials_id = $this->getSingleSQLStatement($select)['bill_of_materials_id'];

				$sql = "DELETE FROM cashflow WHERE id = '$id'";

				// We are now able to access this method because we extended the "Database" class.
				$this->executeSQL($sql);
				$sql = "UPDATE `bill_of_materials` SET paid = 0 WHERE id ='$billofmaterials_id'";

				// We are now able to access this method because we extended the "Database" class.
				$this->executeSQL($sql);

				return 'Transaction successfully deleted.';
			}	
		}
		

		function updateTransaction($id, $type, $amount, $description, $date_added, $transaction_date)
		{
			$amount = intval($amount);
			$errors = [];

			if (!in_array($type, ['IN', 'OUT','in','out'])) {
				array_push($errors, 'The Category must either be `IN` or `OUT`.');
			}

			if (!is_int($amount)) {
				array_push($errors, 'The `amount` must be set as an integer');
			}

			if ($date_added == '') {
				array_push($errors, 'The `date added` field must not be empty.');
			}

			if ($transaction_date == '') {
				array_push($errors, 'The `transactiondate` field must not be empty.');
			}

			if (!empty($errors)) {
				return ['errors' => $errors];
			} else {

				if ($type == 'in') {
					$amount = abs($amount);
				}

				elseif ($type == 'out') {
					$amount = -1 * abs($amount);
				}

				$sql = "UPDATE cashflow SET type='$type', amount='$amount', description='$description', date_added='$date_added', transaction_date='$transaction_date' 
						WHERE id='$id'";

				// We are now able to access this method because we extended the "Database" class.
				$this->executeSQL($sql);

				return 'Transaction successfully edited.';	
			}
		}



		function getCashflowReport($year, $month, $project_id)
		{
			$errors = [];

			$sql1 = "SELECT COUNT(id) AS `count` FROM projects WHERE id='$project_id';";
			$project_id_count = $this->getSingleSQLStatement($sql1)['count'];

			$errors = [];

			if ($project_id == '') {
				array_push($errors, 'The `project_id` field must not be empty.');
			}

			if ($project_id_count == 0) {
				array_push($errors, 'The `project_id` provided is invalid.');
			}

			if ($year == '') {
				array_push($errors, 'The `year` field must not be empty.');
			}

			if ($month == '') {
				array_push($errors, 'The `month` field must not be empty.');
			}

			if (!($year > 1000 && $year < 2200)) {
				array_push($errors, 'Input valid year');
			}

			if (!($month > 0 && $month < 13)) {
				array_push($errors, 'Input valid month');
			}

			if (!empty($errors)) {
				return ['errors' => $errors];
			} else {

				$month = strlen($month) == 1 ? '0' . $month : $month; 
				$initial_balance = [];
				$expenses = [];
				$remaining_balance = [];

				/**
				 * Week (1-8)
				 * Week (9-15)
				 * Week (16-22)
				 * Week (23-31)
				 */

				// Week 1 balance.
				// Week 1 expense
				// Week 1 end balance.
				
				// Week 1 intial balance.
				$week_1 = $year . '-' . $month . '-01';		

				$week_1_balance_sql = "
					SELECT IF(SUM(amount) IS NULL, 0, SUM(amount)) AS initial_balance
					FROM cashflow a 
					WHERE a.project_id = {$project_id}
					AND DATE(a.transaction_date) <= '{$week_1}'
				";

				$week_1_balance = $this->getSingleSQLStatement($week_1_balance_sql)['initial_balance'];
				array_push($initial_balance, $week_1_balance);

				// Week 1 expenses.
				$week_1_expense_range_1 = $year . '-' . $month . '-01';
				$week_1_expense_range_2 = $year . '-' . $month . '-08';

				$week_1_expenses_sql = "
					SELECT ABS(IF(SUM(amount) IS NULL, 0, SUM(amount))) AS expenses
					FROM cashflow a 
					WHERE a.project_id = {$project_id}
					AND DATE(a.transaction_date) BETWEEN '{$week_1_expense_range_1}' AND '{$week_1_expense_range_2}'
					AND a.type = 'out'
				";

				$week_1_expenses = $this->getSingleSQLStatement($week_1_expenses_sql)['expenses'];
				array_push($expenses, $week_1_expenses);

				// die($week_1_expenses); die;

				// Week 1 remaining balance.
				$week_1_remaining_balance_range_1 = $year . '-' . $month . '-01';
				$week_1_remaining_balance_range_2 = $year . '-' . $month . '-08';

				$week_1_remaining_balance_sql = "
					SELECT IF(SUM(amount) IS NULL, 0, SUM(amount)) AS remaining_balance
					FROM cashflow a 
					WHERE a.project_id = {$project_id}
					AND DATE(a.transaction_date) BETWEEN '{$week_1_remaining_balance_range_1}' AND '{$week_1_remaining_balance_range_2}'
				";
				
				$week_1_remaining_balance = $this->getSingleSQLStatement($week_1_remaining_balance_sql)['remaining_balance'];
				array_push($remaining_balance, $week_1_remaining_balance);


				// Week 2 balance.
				// Week 2 expense
				// Week 2 end balance.
				
				// Week 2 intial balance.
				$week_2 = $year . '-' . $month . '-09';

				$week_2_balance = $week_1_remaining_balance;
				array_push($initial_balance, $week_2_balance);

				// Week 2 expenses.
				$week_2_expense_range_1 = $year . '-' . $month . '-09';
				$week_2_expense_range_2 = $year . '-' . $month . '-15';

				$week_2_expenses_sql = "
					SELECT ABS(IF(SUM(amount) IS NULL, 0, SUM(amount))) AS expenses
					FROM cashflow a 
					WHERE a.project_id = {$project_id}
					AND DATE(a.transaction_date) BETWEEN '{$week_2_expense_range_1}' AND '{$week_2_expense_range_2}'
					AND a.type = 'out'
				";

				$week_2_expenses = $this->getSingleSQLStatement($week_2_expenses_sql)['expenses'];
				array_push($expenses, $week_2_expenses);

				// Week 2 remaining balance.
				$week_2_remaining_balance_range_1 = $year . '-' . $month . '-01';
				$week_2_remaining_balance_range_2 = $year . '-' . $month . '-15';

				$week_2_remaining_balance_sql = "
					SELECT IF(SUM(amount) IS NULL, 0, SUM(amount)) AS remaining_balance
					FROM cashflow a 
					WHERE a.project_id = {$project_id}
					AND DATE(a.transaction_date) BETWEEN '{$week_2_remaining_balance_range_1}' AND '{$week_2_remaining_balance_range_2}'
				";
				
				$week_2_remaining_balance = $this->getSingleSQLStatement($week_2_remaining_balance_sql)['remaining_balance'];
				array_push($remaining_balance, $week_2_remaining_balance);
			
				// Week 3 balance.
				// Week 3 expense
				// Week 3 end balance.
				
				// Week 3 intial balance.
				$week_3 = $year . '-' . $month . '-16';

				$week_3_balance = $week_2_remaining_balance;
				array_push($initial_balance, $week_3_balance);

				// Week 3 expenses.
				$week_3_expense_range_1 = $year . '-' . $month . '-16';
				$week_3_expense_range_2 = $year . '-' . $month . '-22';

				$week_3_expenses_sql = "
					SELECT ABS(IF(SUM(amount) IS NULL, 0, SUM(amount))) AS expenses
					FROM cashflow a 
					WHERE a.project_id = {$project_id}
					AND DATE(a.transaction_date) BETWEEN '{$week_3_expense_range_1}' AND '{$week_3_expense_range_2}'
					AND a.type = 'out'
				";

				$week_3_expenses = $this->getSingleSQLStatement($week_3_expenses_sql)['expenses'];
				array_push($expenses, $week_3_expenses);

				// Week 3 remaining balance.
				$week_3_remaining_balance_range_1 = $year . '-' . $month . '-01';
				$week_3_remaining_balance_range_2 = $year . '-' . $month . '-22';

				$week_3_remaining_balance_sql = "
					SELECT IF(SUM(amount) IS NULL, 0, SUM(amount)) AS remaining_balance
					FROM cashflow a 
					WHERE a.project_id = {$project_id}
					AND DATE(a.transaction_date) BETWEEN '{$week_3_remaining_balance_range_1}' AND '{$week_3_remaining_balance_range_2}'
				";
				
				$week_3_remaining_balance = $this->getSingleSQLStatement($week_3_remaining_balance_sql)['remaining_balance'];
				array_push($remaining_balance, $week_3_remaining_balance);

				// Week 4 balance.
				// Week 4 expense
				// Week 4 end balance.
				
				// Week 4 intial balance.
				$week_4 = $year . '-' . $month . '-23';

				$week_4_balance = $week_3_remaining_balance;
				array_push($initial_balance, $week_4_balance);

				// Week 4 expenses.
				$week_4_expense_range_1 = $year . '-' . $month . '-23';
				$week_4_expense_range_2 = $year . '-' . $month . '-31';

				$week_4_expenses_sql = "
					SELECT ABS(IF(SUM(amount) IS NULL, 0, SUM(amount))) AS expenses
					FROM cashflow a 
					WHERE a.project_id = {$project_id}
					AND DATE(a.transaction_date) BETWEEN '{$week_4_expense_range_1}' AND '{$week_4_expense_range_2}'
					AND a.type = 'out'
				";

				$week_4_expenses = $this->getSingleSQLStatement($week_4_expenses_sql)['expenses'];
				array_push($expenses, $week_4_expenses);

				// Week 3 remaining balance.
				$week_4_remaining_balance_range_1 = $year . '-' . $month . '-01';
				$week_4_remaining_balance_range_2 = $year . '-' . $month . '-31';

				$week_4_remaining_balance_sql = "
					SELECT IF(SUM(amount) IS NULL, 0, SUM(amount)) AS remaining_balance
					FROM cashflow a 
					WHERE a.project_id = {$project_id}
					AND DATE(a.transaction_date) BETWEEN '{$week_4_remaining_balance_range_1}' AND '{$week_4_remaining_balance_range_2}'
				";
				
				$week_4_remaining_balance = $this->getSingleSQLStatement($week_4_remaining_balance_sql)['remaining_balance'];
				array_push($remaining_balance, $week_4_remaining_balance);

				$data = [
					'initial_balance' => $initial_balance,
					'expenses' => $expenses,
					'remaining_balance' => $remaining_balance,
				];


				return $data;
			}
		}			
	}
?>