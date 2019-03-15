<?php 

	class Database {

		protected $conn;

		
		function __construct($host, $user, $password, $db)
		{
			// $this->conn =  mysqli_connect($host, $user, 'password', $db);
			$this->conn =  mysqli_connect('localhost', 'root', '', 'thesis');
			//$this->conn =  mysqli_connect('127.0.0.1','root','password','thesis');

			if (!isset($this->conn)) {
				die("Connection failed".mysqli_connect_error());
			}
		}
		
		function getSQLResult ($sql)
		{
			$result = mysqli_query($this->conn, $sql);

			if(!$result)
			{
				$error = [ mysqli_error($this->conn)];
			}

			$container = array();
			while ($row = $result->fetch_assoc()) {
				array_push($container, $row);
				
			
			}
			return $container;	
		}	
	

		function getSingleSQLStatement($sql)
		{
			$result = mysqli_query($this->conn, $sql);
			
			if(!$result)
			{
				$error = [mysqli_error($this->conn)];
			}
			
			$row = $result->fetch_assoc();
			
			return $row;
		}


		function executeSQL($sql)
		{
			$result = mysqli_query($this->conn, $sql);	

			if(!$result)
			{
				$error = [ mysqli_error($this->conn)];
			}	
    	}

		function executeSQLGetId($sql)
		{
			$result = mysqli_query($this->conn, $sql);	

			if(!$result)
			{
				$error = [ mysqli_error($this->conn)];
			}	
			return $this->conn->insert_id;
    	}
	}

 ?>