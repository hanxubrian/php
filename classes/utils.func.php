<?php 
	function getPOSTValue($postIndex) 
	{
		if (isset($_POST[$postIndex])) {
			return $_POST[$postIndex];
		} else {
			return '';
		}
	}

	function is_timestamp($date, $format = 'Y-m-d H:i:s')
	{
		date_default_timezone_set("Asia/Manila");
		$d = DateTime::createFromFormat($format, $date);
   		 return $d && $d->format($format) == $date;
	}
	
	function utf8ize($d) 
	{
	    if (is_array($d)) {
	        foreach ($d as $k => $v) {
	            $d[$k] = utf8ize($v);
	        }
	    } else if (is_string ($d)) {
	        return utf8_encode($d);
	    }
	    return $d;
	}

?>