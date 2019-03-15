<?php 
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	// Includes
	include 'classes/db.class.php';
	include 'classes/utils.func.php';
	include 'classes/resources.class.php';
	include 'classes/gantt_chart.class.php';
	include 'classes/gantt_chart_resources.class.php';
	include 'classes/cashflow.class.php';
	include 'classes/projects.class.php';
	include 'classes/bill_of_materials.class.php';
	

	$resources = new Resources();
	$gantt_chart = new Ganttchart();
	$gantt_chart_resources = new GanttChart_Resources();
	$cashflow = new Cashflow();
	$projects = new Projects();
	$bill_of_materials = new BillofMaterials();

	// Convert JSON post to $_POST array.
	//$_POST = json_decode($HTTP_RAW_POST_DATA, true);

	
	$_POST = !isset($HTTP_RAW_POST_DATA) ? $_REQUEST : json_decode($HTTP_RAW_POST_DATA, true);

	$api_command = isset($_POST['api_command']) ? $_POST['api_command'] : false;

	if ($api_command == false || $api_command == '') {
		print json_encode([
			'error' => 'There is no `api_command` parameter set.'
		]);

		// Terminate script.
		die();
	}

	// API Command match list.
	if ($api_command === 'get_resources') {
		$result = $resources->getResources(getPOSTValue('category'));
	} else if ($api_command === 'add_resource') {
		$result = $resources->addResource(getPOSTValue('name'), getPOSTValue('category'), getPOSTValue('gantt_chart_id'), getPOSTValue('resource_id'), getPOSTValue('quantity'), getPOSTValue('duration'), getPOSTValue('rate'));
	} else if ($api_command === 'delete_resource') {
		$result = $resources->deleteResource(getPOSTValue('id'));
	} else if ($api_command === 'update_resource') {
		$result = $resources->updateResource(getPOSTValue('id'), getPOSTValue('name'), getPOSTValue('category'));
	} else if ($api_command === 'get_activity') {
		$result = $gantt_chart->getActivities();		
	} else if ($api_command === 'get_specific_activity') {
		$result = $gantt_chart->getSpecificActivity(getPOSTValue('id'));		
	} else if ($api_command === 'get_project_activities') {
		$result = $gantt_chart->getProjectActivities(getPOSTValue('project_id'));	
	} else if ($api_command === 'add_activity') {
		$result = $gantt_chart->addActivity(getPOSTValue('date_added'), getPOSTValue('activity'), getPOSTValue('date_start'), getPOSTValue('date_end'), getPOSTValue('project_id'));
	} else if ($api_command === 'delete_activity') {
		$result = $gantt_chart->deleteActivity(getPOSTValue('id'));
	} else if ($api_command === 'update_activity') {
		$result = $gantt_chart->updateActivity(getPOSTValue('project_id'), getPOSTValue('id'), getPOSTValue('date_added'), getPOSTValue('activity'), getPOSTValue('date_start'), getPOSTValue('date_end'));
	} else if ($api_command === 'add_activity_resources') {
		$result = $gantt_chart_resources->addActivityResources(getPOSTValue('gantt_chart_id'), getPOSTValue('resource_id'), getPOSTValue('quantity'), getPOSTValue('duration'), getPOSTValue('rate'));
	} else if ($api_command === 'get_cashflow') {
		$result = $cashflow->getCashflow();
	} else if ($api_command === 'get_cashflow_reports') {
		$result = $cashflow->getCashflowReport(getPOSTValue('year'), getPOSTValue('month'), getPOSTValue('project_id'));
	} else if ($api_command === 'transact_cash') {
		$result = $cashflow->TransactCash(getPOSTValue('type'), getPOSTValue('amount'), getPOSTValue('description'), getPOSTValue('date_added'), getPOSTValue('transaction_date'), getPOSTValue('project_id'));
	} else if ($api_command === 'pay_bill') {
		$result = $cashflow->PayBill(getPOSTValue('amount'), getPOSTValue('project_id'), getPOSTValue('billofmaterials_id'), getPOSTValue('description'));
	} else if ($api_command === 'delete_transaction') {
		$result = $cashflow->deleteTransaction(getPOSTValue('id'));
	} else if ($api_command === 'update_transaction') {
		$result = $cashflow->updateTransaction(getPOSTValue('id'), getPOSTValue('type'), getPOSTValue('amount'), getPOSTValue('description'), getPOSTValue('date_added'), getPOSTValue('transaction_date'));
	} else if ($api_command === 'get_project_sum_cashflow') {
		$result = $cashflow->getProjectSumCashflow(getPOSTValue('project_id'));
	} else if ($api_command === 'get_projects') {
		$result = $projects->getProjects();
	} else if ($api_command === 'get_specific_project') {
		$result = $projects->getSpecificProject(getPOSTValue('id'));
	} else if ($api_command === 'add_project') {
		$result = $projects->addProject(getPOSTValue('project_name'), getPOSTValue('date_added'), getPOSTValue('date_start'), getPOSTValue('duration'), getPOSTValue('date_end'));
	} else if ($api_command === 'delete_project') {
		$result = $projects->deleteProject(getPOSTValue('id'));
	} else if ($api_command === 'update_project') {
		$result = $projects->updateProject(getPOSTValue('id'), getPOSTValue('project_name'), getPOSTValue('date_added'), getPOSTValue('date_start'), getPOSTValue('duration'), getPOSTValue('date_end'));
	} else if ($api_command === 'get_bill_of_materials') {
		$result = $bill_of_materials->getBillofMaterials();
	} else if ($api_command === 'add_bill_of_material') {
		$result = $bill_of_materials->addBillofMaterial(getPOSTValue('project_id'), getPOSTValue('item'), getPOSTValue('quantity'), getPOSTValue('amount'), getPOSTValue('total'));
	} else if ($api_command === 'delete_bill_of_material') {
		$result = $bill_of_materials->deleteBillofMaterial(getPOSTValue('id'));
	} else if ($api_command === 'edit_bill_of_material') {
		$result = $bill_of_materials->updateBillofMaterial(getPOSTValue('id'), getPOSTValue('project_id'), getPOSTValue('item'), getPOSTValue('quantity'), getPOSTValue('amount'), getPOSTValue('total'));
	} else if ($api_command === 'get_project_bill_of_materials') {
		$result = $bill_of_materials->getBillofMaterialsActivities(getPOSTValue('project_id'));	
	} else if ($api_command === 'get_specific_bill_of_materials') {
		$result = $bill_of_materials->getSpecificBillofMaterials(getPOSTValue('id'));	
	} else if ($api_command === 'get_specific_resource') {
		$result = $resources->getSpecificResource(getPOSTValue('id'));	
	} else if ($api_command === 'get_specific_cashflow') {
		$result = $cashflow->getSpecificCashflow(getPOSTValue('id'));	
	} else if ($api_command === 'get_project_cashflow') {
		$result = $cashflow->getProjectCashflow(getPOSTValue('project_id'));	
	} else if ($api_command === 'get_activity_resources') {
		$result = $gantt_chart_resources->getActivityResources(getPOSTValue('gantt_chart_id'));
	} else if ($api_command === 'edit_activity_resources') {
		$result = $gantt_chart_resources->editActivityResources(getPOSTValue('id'), getPOSTValue('gantt_chart_id'), getPOSTValue('resource_id'), getPOSTValue('quantity'), getPOSTValue('duration'), getPOSTValue('rate'));
	} else if ($api_command === 'delete_activity_resources') {
		$result = $gantt_chart_resources->deleteActivityResources(getPOSTValue('id'));
	} else if ($api_command === 'get_specific_activity_resources') {
		$result = $gantt_chart_resources->getSpecificActivityResources(getPOSTValue('id'));
	} else if ($api_command === 'get_date_resources') {
		$result = $gantt_chart_resources->getDateResources(getPOSTValue('date'));
	} else if ($api_command === 'get_years') {
		$result = $cashflow->getYears(getPOSTValue('project_id'));
	}		
		// var_dump($result);
		// die;
	// Check for errors.
	if (isset($result['errors'])) {
		print json_encode([
 		  'errors' => $result['errors']
	    ]);
	} else {
		print json_encode([
			'success' => json_encode(utf8ize($result))
		]);
	}

	

?>