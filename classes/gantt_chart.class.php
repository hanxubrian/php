<?php 

	class GanttChart extends Database {

		function __construct()
		{
			// parent::__construct($host, $user, $password, $db);
			parent::__construct('127.0.0.1','root','','thesis');
			
		}
		
		function getActivities()
		{

			$sql = "SELECT projects.`project_name`, gantt_chart.`date_added`, gantt_chart.`id`, gantt_chart.`project_id`, 
						gantt_chart.`activity`, gantt_chart.`date_start`, gantt_chart.`date_end`
					FROM projects
					INNER JOIN gantt_chart ON gantt_chart.project_id = projects.id;";

			// We are now able to access this method because we extended the "Database" class.
			$result = $this->getSQLResult($sql);

			return $result;	
		}

		function getProjectActivities($project_id)
		{
			$sql1 = "SELECT COUNT(id) FROM projects WHERE id='$project_id';";
			$result1 = $this->getSingleSQLStatement($sql1);

			$errors = [];

			if ($project_id == '') {
				array_push($errors, 'The `project_id` field must not be empty.');
			}

			if (in_array(0, $result1)) {
				array_push($errors, 'project_id does not exist on Gantt Chart table');
			}
			
			if (!empty($errors)) {
				return ['errors' => $errors];
			} else {

				$sql2 = "SELECT projects.`project_name`, gantt_chart.`date_added`, 
                        gantt_chart.`activity`, gantt_chart.`date_start`, gantt_chart.`date_end`, gantt_chart.`id`
						FROM projects
						INNER JOIN gantt_chart ON gantt_chart.project_id = projects.id 
						WHERE projects.id = '$project_id'
				;";

				// We are now able to access this method because we extended the "Database" class.
				$result2 = $this->getSQLResult($sql2);
				if (empty($result2))  
				{  
					array_push($errors, 'There are no activties with the selected project');
					return ['errors' => $errors];
				}  
				else  
				{  
					return $result2;
				}  					
			}
		}

		function getSpecificActivity($id)
		{
			$sql1 = "SELECT COUNT(id) FROM gantt_chart WHERE id='$id';";
			$result1 = $this->getSingleSQLStatement($sql1);
			
			$errors = [];
			
			if (in_array(0, $result1)) {
				array_push($errors, 'id does not exist on gantt_chart table');
			} 

			if ($id == '') {
				array_push($errors, 'The `id` field must not be empty.');
			}
			
			if (!empty($errors)) {
				return ['errors' => $errors];
			} else {
				$sql = "SELECT * FROM gantt_chart WHERE id = '$id'";

				// We are now able to access this method because we extended the "Database" class.
				$result = $this->getSingleSQLStatement($sql);

				return $result;	
			}		
		}		

		
		function addActivity($date_added, $activity, $date_start, $date_end, $project_id)
		{
			
			$sql1 = "SELECT COUNT(id) FROM projects WHERE id='$project_id';";
			$result1 = $this->getSingleSQLStatement($sql1);

			
			date_default_timezone_set("Asia/Manila");
			$date1 = new DateTime($date_start);
			$date2 = new DateTime($date_end);

			$interval = $date1->diff($date2);

			$duration = $interval->format("%r%a");
			
			$errors = [];

			if ($duration < 0) {
				array_push($errors, 'The `Date end` must be ahead of `Date start`');
			}

			if (in_array(0, $result1)) {
				array_push($errors, 'project_id does not exist on projects table');
			} 

			if ($activity == '') {
				array_push($errors, 'The `activity` field must not be empty.');
			}

			if ($date_start == '') {
				array_push($errors, 'The `date start` field must not be empty.');
			}

			if ($date_end == '') {
				array_push($errors, 'The `date end` field must not be empty.');
			}

			if (!is_timestamp($date_start)) {
				array_push($errors, 'The `date start` field must be in Timestamp format.');
			}

			if (!is_timestamp($date_end)) {
				array_push($errors, 'The `date end` field must be in Timestamp format.');
			}

			if (!empty($errors)) {
				return ['errors' => $errors];
			} else {

				$sql = "INSERT INTO gantt_chart (date_added, activity, date_start, date_end, project_id) VALUES (now(), '$activity', '$date_start', '$date_end', '$project_id')";

				$this->executeSQL($sql);

				return $sql ;
				
				
			}
		}

		function deleteActivity($id)
		{
			$errors = [];

			$sql1 = "SELECT COUNT(id) FROM gantt_chart WHERE id='$id';";
			$result1 = $this->getSingleSQLStatement($sql1);

			// $sql2 = "SELECT COUNT(id) FROM gantt_chart_resources WHERE gantt_chart_id='$id';";
			// $result2 = $this->getSingleSQLStatement($sql2);

			if (in_array(0, $result1)) {
				array_push($errors, 'id does not exist on gantt_chart table');
			
			} 

			// if (in_array(!0, $result2)) {
			// 	array_push($errors, 'Cannot Delete: Activity has a Resource');
			// }

			if ($id == '') {
				array_push($errors, 'The `id` field must not be empty.');
			}
			
			if (!empty($errors)) {
				return ['errors' => $errors];
			} else {
				$sql = "DELETE FROM gantt_chart WHERE id = '$id'";

				// We are now able to access this method because we extended the "Database" class.
				$this->executeSQL($sql);

				return 'Activity successfully deleted.';
			}	
		}

		function updateActivity($project_id, $id, $date_added, $activity, $date_start, $date_end)
		{
			
			$sql1 = "SELECT COUNT(id) FROM projects WHERE id='$project_id';";
			$result1 = $this->getSingleSQLStatement($sql1);
			
			date_default_timezone_set("Asia/Manila");
			$date1 = new DateTime($date_start);
			$date2 = new DateTime($date_end);

			$interval = $date1->diff($date2);

			$duration = $interval->format("%r%a");
			
			$errors = [];

			if (in_array(0, $result1)) {
				array_push($errors, 'project_id does not exist on projects table');
			} 

			if ($id == '') {
				array_push($errors, 'The `id` field must not be empty.');
			}
	
			if ($duration < 0) {
				array_push($errors, 'The `Date end` must be ahead of `Date start`');
			}

			if ($activity == '') {
				array_push($errors, 'The `activity` field must not be empty.');
			}

			if ($date_start == '') {
				array_push($errors, 'The `date_start` field must not be empty.');
			}
			
			if ($date_end == '') {
				array_push($errors, 'The `date_end` field must not be empty.');
			}

			if (!empty($errors)) {
				return ['errors' => $errors];
			} else {
				$sql = "UPDATE gantt_chart SET date_added=now(), activity='$activity', date_start='$date_start', date_end='$date_end', project_id='$project_id'  WHERE id='$id'";

				// We are now able to access this method because we extended the "Database" class.
				$this->executeSQL($sql);

				return 'Activity successfully edited.';	
			}
		}		
	}
?>