<?php 

	class GanttChart_Resources extends Database {

		function __construct()
		{
			// parent::__construct($host, $user, $password, $db);
			parent::__construct('127.0.0.1','root','','thesis');
			
		}

		function addActivityResources($gantt_chart_id, $resource_id, $quantity, $duration, $rate)
		{

			//Check if $gantt_chart_id exist on gantt_chart table
			$sql1 = "SELECT COUNT(id) FROM gantt_chart WHERE id='$gantt_chart_id';";
			$result1 = $this->getSingleSQLStatement($sql1);

			//Check if $resource_id exist on resources table
			$sql2 = "SELECT COUNT(id) FROM resources WHERE id='$resource_id';";
			$result2 = $this->getSingleSQLStatement($sql2);

			$total = $quantity * $duration * $rate;
			$quantity = intval($quantity);
			$duration = intval($duration);
			$rate = intval($rate);

			$errors = [];

			if (in_array(0, $result1)) {
				array_push($errors, 'gantt_chart_id does not exist on gantt_chart table');
			} 

			if (in_array(0, $result2)) {
				array_push($errors, 'resource_id does not exist on resources table');
			}

			if (!is_int($quantity)) {
				array_push($errors, 'quantity is not set as integer');
			}

			if (!is_int($duration)) {
				array_push($errors, 'duration is not set as integer');
			}

			if (!is_int($rate)) {
				array_push($errors, 'rate is not set as integer');
			}

			if ($quantity == '') {
				array_push($errors, 'The `quantity` field must not be empty.');
			}

			if ($duration == '') {
				array_push($errors, 'The `duration` field must not be empty.');
			}

			if ($rate == '') {
				array_push($errors, 'The `rate` field must not be empty.');
			}

			if ($quantity <= 0) {
				array_push($errors, 'quantity must be greater than 0');
			}
	
	
			if (!empty($errors)) {
				return ['errors' => $errors];
			} else {

				$sql3 = "
					INSERT INTO gantt_chart_resources (gantt_chart_id, resource_id, quantity, duration, rate, total) 
					VALUES
					  ('$gantt_chart_id', '$resource_id', '$quantity', '$duration', '$rate', '$total');
				";

				$this->executeSQL($sql3);

				return 'Resource successfully added.';
			}
		}


		function getActivityResources($gantt_chart_id)
		{
			$sql1 = "SELECT COUNT(id) FROM gantt_chart WHERE id='$gantt_chart_id'";
			$result1 = $this->getSingleSQLStatement($sql1);

			$errors = [];

			if ($gantt_chart_id == '') {
				array_push($errors, 'The `gantt_chart_id` field must not be empty.');
			}

			if (in_array(0, $result1)) {
				array_push($errors, 'gantt_chart_id does not exist on gantt_chart table');
			}
			
			if (!empty($errors)) {
				return ['errors' => $errors];
			} else {

				$sql2 = "SELECT  gantt_chart_resources.`id`, gantt_chart_resources.`quantity`, 
						gantt_chart_resources.`duration`, gantt_chart_resources.`rate`, 
						gantt_chart_resources.`total`, resources.`name`, resources.`category`
						FROM gantt_chart_resources  
						INNER JOIN gantt_chart ON gantt_chart.id = gantt_chart_resources.gantt_chart_id
						INNER JOIN resources ON resources.id = gantt_chart_resources.resource_id 
						WHERE gantt_chart_id = '$gantt_chart_id'";

				// We are now able to access this method because we extended the "Database" class.
				$result2 = $this->getSQLResult($sql2);
				if (empty($result2))  
				{  
					array_push($errors, 'There are no Resources with the Activity');
					return ['errors' => $errors];
				}  
				else  
				{  
					return $result2;
				}  					
			}
		}

		function getDateResources($date)
		{

			$errors = [];

			if ($date == '') {
				array_push($errors, 'The `date` field must not be empty.');
			}
			
			if (!empty($errors)) {
				return ['errors' => $errors];
			} else {

				$sql = "SELECT 
					  d.`project_name`,
					  a.`activity` AS gantt_activity,
					  c.`name` AS resource,
					  b.`total` AS resource_cost 
					FROM
					  gantt_chart a 
					  INNER JOIN gantt_chart_resources b 
					    ON a.`id` = b.`gantt_chart_id` 
					  INNER JOIN resources c 
					    ON b.`resource_id` = c.`id` 
					  INNER JOIN projects d 
					    ON a.`project_id` = d.`id` 
					  WHERE '$date' BETWEEN a.`date_start` 
					  AND a.`date_end` ";

				// We are now able to access this method because we extended the "Database" class.
				$result = $this->getSQLResult($sql);
				if (empty($result))  
				{  
					array_push($errors, 'There are no Resources with the selected Date');
					return ['errors' => $errors];
				}  
				else  
				{  
					return $result;
				}  					
			}
		}
		
		function getSpecificActivityResources($id)
		{
			$sql1 = "SELECT COUNT(id) FROM gantt_chart_resources WHERE id='$id';";
			$result1 = $this->getSingleSQLStatement($sql1);
			
			$errors = [];
			
			if (in_array(0, $result1)) {
				array_push($errors, 'id does not exist on gantt_chart_resources table');
			} 

			if ($id == '') {
				array_push($errors, 'The `id` field must not be empty.');
			}
			
			if (!empty($errors)) {
				return ['errors' => $errors];
			} else {
				$sql = "SELECT * FROM gantt_chart_resources WHERE id = '$id'";

				// We are now able to access this method because we extended the "Database" class.
				$result = $this->getSingleSQLStatement($sql);

				return $result;	
			}		
		}		

		function editActivityResources($id, $gantt_chart_id, $resource_id, $quantity, $duration, $rate)
		{
			//Check if $gantt_chart_id exist on gantt_chart table
			$sql1 = "SELECT COUNT(id) FROM gantt_chart WHERE id='$gantt_chart_id';";
			$result1 = $this->getSingleSQLStatement($sql1);

			//Check if $resource_id exist on resources table
			$sql2 = "SELECT COUNT(id) FROM resources WHERE id='$resource_id';";
			$result2 = $this->getSingleSQLStatement($sql2);

			$total = $quantity * $duration * $rate;
			$quantity = intval($quantity);
			$duration = intval($duration);
			$rate = intval($rate);

			if (in_array(0, $result1)) {
				array_push($errors, 'gantt_chart_id does not exist on gantt_chart table');
			} 

			if (in_array(0, $result2)) {
				array_push($errors, 'resource_id does not exist on resources table');
			}

			if (!is_int($quantity)) {
				array_push($errors, 'quantity is not set as integer');
			}

			if (!is_int($duration)) {
				array_push($errors, 'duration is not set as integer');
			}

			if (!is_int($rate)) {
				array_push($errors, 'rate is not set as integer');
			}

			if ($quantity == '') {
				array_push($errors, 'The `quantity` field must not be empty.');
			}

			if ($duration == '') {
				array_push($errors, 'The `duration` field must not be empty.');
			}

			if ($rate == '') {
				array_push($errors, 'The `rate` field must not be empty.');
			}

			if ($quantity <= 0) {
				array_push($errors, 'quantity must be greater than 0');
			}

			if (!empty($errors)) {
				return ['errors' => $errors];
			} else {

				$sql = "UPDATE gantt_chart_resources SET gantt_chart_id='$gantt_chart_id', resource_id='$resource_id', quantity='$quantity',rate='$rate', duration='$duration', total='$total' 
						WHERE id='$id'";

				// We are now able to access this method because we extended the "Database" class.
				$this->executeSQL($sql);

				return 'Resource successfully edited.';	
			}
		}

		function deleteActivityResources($id)
		{
			$errors = [];

			$sql1 = "SELECT COUNT(id) FROM gantt_chart_resources WHERE id='$id';";
			$result1 = $this->getSingleSQLStatement($sql1);

			$errors = [];

			if (in_array(0, $result1)) {
				array_push($errors, 'id does not exist on gantt_chart_resources table');
			} 

			if ($id == '') {
				array_push($errors, 'The `id` field must not be empty.');
			}
			
			if (!empty($errors)) {
				return ['errors' => $errors];
			} else {
				$sql = "DELETE FROM gantt_chart_resources WHERE id = '$id'";

				// We are now able to access this method because we extended the "Database" class.
				$this->executeSQL($sql);

				return 'Resource successfully deleted.';
			}	
		}	
	}
?>