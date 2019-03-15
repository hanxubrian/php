<?php 

	class BillofMaterials extends Database {

		function __construct()
		{
			// parent::__construct($host, $user, $password, $db);
			// parent::__construct('127.0.0.1','root','mysql','thesis'); // This is my database local password.
			parent::__construct('127.0.0.1','root','','thesis');
		}	

		function getBillofMaterials()
		{
			$sql = "SELECT projects.`project_name`, bill_of_materials.`item`, bill_of_materials.`quantity`, bill_of_materials.`amount`, bill_of_materials.`total`, bill_of_materials.`id`
			FROM projects
			INNER JOIN bill_of_materials ON bill_of_materials.project_id = projects.id;";

			// We are now able to access this method because we extended the "Database" class.
			$result = $this->getSQLResult($sql);

			return $result;			
		}	

		function getSpecificBillofMaterials($id)
		{
			$sql1 = "SELECT COUNT(id) FROM bill_of_materials WHERE id='$id';";
			$result1 = $this->getSingleSQLStatement($sql1);
			
			$errors = [];
			
			if (in_array(0, $result1)) {
				array_push($errors, 'id does not exist on bill_of_materials table');
			} 

			if ($id == '') {
				array_push($errors, 'The `id` field must not be empty.');
			}
			
			if (!empty($errors)) {
				return ['errors' => $errors];
			} else {
				$sql = "SELECT * FROM bill_of_materials WHERE id = '$id'";

				// We are now able to access this method because we extended the "Database" class.
				$result = $this->getSingleSQLStatement($sql);

				return $result;	
			}		
		}		


		function getBillofMaterialsActivities($project_id)
		{
			$sql1 = "SELECT COUNT(id) FROM projects WHERE id='$project_id';";
			$result1 = $this->getSingleSQLStatement($sql1);

			$errors = [];

			if ($project_id == '') {
				array_push($errors, 'The `project_id` field must not be empty.');
			}

			if (in_array(0, $result1)) {
				array_push($errors, 'project_id does not exist on Bill of Materials table');
			}
			
			if (!empty($errors)) {
				return ['errors' => $errors];
			} else {

				$sql2 = "SELECT projects.`project_name`, bill_of_materials.`item`, 
							bill_of_materials.`quantity`, bill_of_materials.`amount`, bill_of_materials.`total`, bill_of_materials.`id`, bill_of_materials.`paid`
						FROM projects
						INNER JOIN bill_of_materials ON bill_of_materials.project_id = projects.id 
						WHERE projects.id = '$project_id'
				;";

				// We are now able to access this method because we extended the "Database" class.
				$result2 = $this->getSQLResult($sql2);
				if (empty($result2))  
				{  
					array_push($errors, 'Project has no Bill of Materials');
					return ['errors' => $errors];
				}  
				else  
				{  
					return $result2;
				}  						
			}
		}

		function addBillofMaterial($project_id, $item, $quantity, $amount, $total)
		{
			//Check if $gantt_chart_id exist on gantt_chart table
			$sql1 = "SELECT COUNT(id) FROM projects WHERE id='$project_id';";
			$result1 = $this->getSingleSQLStatement($sql1);

			$total = $quantity * $amount;

			$errors = [];

			if (in_array(0, $result1)) {
				array_push($errors, 'project_id does not exist on projects table');
			} 

			if ($item == '') {
				array_push($errors, 'The `item` field must not be empty.');
			}

			if ($quantity == '') {
				array_push($errors, 'The `quantity` field must not be empty.');
			}

			if ($amount == '') {
				array_push($errors, 'The `amount` field must not be empty.');
			}

			if ($total == '') {
				array_push($errors, 'The `total` field must not be empty.');
			}

			if (!empty($errors)) {
				return ['errors' => $errors];
			} else {
				$sql = "
					INSERT INTO bill_of_materials (project_id, item, quantity, amount, total) 
					VALUES
					  ('$project_id', '$item', '$quantity', '$amount', '$total');
				";

				$this->executeSQL($sql);

				return 'Bill of Material successfully added.';
			}
		}
		

		function deleteBillofMaterial($id)
		{
			$sql1 = "SELECT COUNT(id) FROM bill_of_materials WHERE id='$id';";
			$result1 = $this->getSingleSQLStatement($sql1);

			$errors = [];

			if (in_array(0, $result1)) {
				array_push($errors, 'id does not exist on Bill of Materials table');
			} 

			if ($id == '') {
				array_push($errors, 'The `id` field must not be empty.');
			}
			
			if (!empty($errors)) {
				return ['errors' => $errors];
			} else {
				$sql = "DELETE FROM `bill_of_materials` WHERE `id` = '$id'";

				// We are now able to access this method because we extended the "Database" class.
				$this->executeSQL($sql);

				return 'Bill of Material successfully deleted.';
			}	
		}
		

		function updateBillofMaterial($id, $project_id, $item, $quantity, $amount, $total)
		{
			$errors = [];

			$total = $quantity * $amount;

			if ($id == '') {
				array_push($errors, 'The `id` field must not be empty.');
			}
	
			if ($item == '') {
				array_push($errors, 'The `item` field must not be empty.');
			}

			if ($quantity == '') {
				array_push($errors, 'The `quantity` field must not be empty.');
			}

			if ($amount == '') {
				array_push($errors, 'The `amount` field must not be empty.');
			}			

			if (!empty($errors)) {
				return ['errors' => $errors];
			} else {
				$sql = "UPDATE bill_of_materials SET project_id='$project_id', item='$item', quantity='$quantity', amount='$amount', total='$total' WHERE id='$id'";

				// We are now able to access this method because we extended the "Database" class.
				$this->executeSQL($sql);

				return 'Bill of Material successfully edited.';	
			}
		}		
	}
?>